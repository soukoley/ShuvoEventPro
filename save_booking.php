<?php
ob_start();
header('Content-Type: application/json');
include("includes/db.php"); // your DB connection setup

require 'fpdf186/fpdf.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Asia/Kolkata');

// Read JSON body
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['fbdate'], $data['event'], $data['tbdate'], $data['maxPeople'], $data['userName'], $data['userEmail'], $data['userPhone'], $data['userId'], $data['userAddress'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request or missing fields']);
    exit;
}

// Collect main booking info
$fbdate     = $data['fbdate'];
$tbdate     = $data['tbdate'];
$event      = $data['event'];
//$eventTime  = $data['eventTime'];
$maxPeople  = intval($data['maxPeople']);
$userName   = $data['userName'];
$userEmail  = $data['userEmail'];
$userPhone  = $data['userPhone'];
$userId     = $data['userId'];
$userAddress = $data['userAddress'];
$facilities = isset($data['facilities']) ? $data['facilities'] : [];
$booking_date = date('Y-m-d H:i:s');

try {

    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) throw new Exception("Invalid input");

    // Start transaction
    $con->begin_transaction();

    // Get last booking ID to ensure unique booking
    $sql = "SELECT * FROM booking_counter WHERE fyear = '$cur_finance_year'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = intval($row['current_no']);
    } else {
        $last_id = 0; // Default if no records found
    }
    $new_id = $last_id + 1;
    $fyear = str_replace("", "", $cur_finance_year); // Remove dashes if any
    // Create new booking ID
    //$booking_id = "GL-". $fyear. str_pad($new_id, 6, '0', STR_PAD_LEFT);
    $booking_id = $companyShortName. $fyear. str_pad($new_id, 6, '0', STR_PAD_LEFT);

    $sql = "UPDATE booking_counter SET current_no = current_no + 1 where fyear = '$cur_finance_year'";
    $con->query($sql);

    // Insert into customer
    $sql = "INSERT INTO customer (c_name, c_addr, c_mobile, c_email, aadhaar_pan) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $userName, $userAddress, $userPhone, $userEmail, $userId);
    $stmt->execute();

    $newCustomerId = $con->insert_id;

    $fromDateTime     = DateTime::createFromFormat("d-m-Y h:i A", $fbdate);
    $toDateTime       = DateTime::createFromFormat("d-m-Y h:i A", $tbdate);

    // Date (YYYY-MM-DD)
    $start_date   = $fromDateTime->format("Y-m-d");
    $end_date     = $toDateTime->format("Y-m-d");

    // Time (HH:MM:SS 24hr format)
    $start_time   = $fromDateTime->format("H:i:s");
    $end_time     = $toDateTime->format("H:i:s");

    $status = 'Pending';

    // Insert into booking_details table

    $sql1 = "INSERT INTO booking_details (booking_id, booking_date, e_name, max_guest, cust_id, start_date, start_time, end_date, end_time, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql1);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("sssiisssss", $booking_id, $booking_date, $event, $maxPeople, $newCustomerId, $start_date, $start_time, $end_date, $end_time, $status);
    $stmt->execute();

    // Insert facilities
    $sql2 = "INSERT INTO booking_facilities (booking_id, facility_id, qty, rate, taxableAmt, gstAmt, netAmt) 
             VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt2 = $con->prepare($sql2);

    foreach ($facilities as $fac) {
        $fid        = intval($fac['id']);
        $fprice     = floatval($fac['price']);
        $qty        = intval($fac['quantity']);
        $gst_rate   = intval($fac['gst_rate']);
        $taxable    = $fprice * $qty;
        $gstAmt     = ($taxable * $gst_rate) / 100;
        $net_amt    = $taxable + $gstAmt;

        // Insert each facility booking
        $stmt2->bind_param("siidddd", $booking_id, $fid, $qty, $fprice, $taxable, $gstAmt, $net_amt);
        $stmt2->execute();
    }

    // If everything is fine, commit
    $con->commit();

    // ✅ FPDF generate (in memory)

    // Customer & Booking arrays
    $customer = [
        'name' => $userName,
        'address' => $userAddress,
        'phone' => $userPhone,
        'email' => $userEmail,
        'aadhaarPan' => $userId
    ];

    $startTimeAMPM = date("h:i A", strtotime($start_time));
    $endTimeAMPM   = date("h:i A", strtotime($end_time));

    $booking = [
        'booking_id' => $booking_id,
        'date' => $booking_date,
        'startDateTime' => date("d-m-Y", strtotime($start_date)) . ' ' . $startTimeAMPM,
        'endDateTime'   => date("d-m-Y", strtotime($end_date)) . ' ' . $endTimeAMPM,
        'max_people' => $maxPeople,
        'event' => $event,
        'status' => $status
    ];

    // ------------------- Fetch Facilities -------------------
    $sql2 = "SELECT f.fName, bf.qty, bf.rate, bf.taxableAmt, bf.gstAmt, bf.netAmt
            FROM booking_facilities bf
            JOIN facility f ON bf.facility_id = f.id
            WHERE bf.booking_id = ?";
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param("s", $booking_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $facilities = [];
    while($row = $result2->fetch_assoc()){
        $facilities[] = [
            'name'      => $row['fName'],
            'quantity'  => $row['qty'],
            'rate'      => $row['rate'],
            'taxableAmt'=> $row['taxableAmt'],
            'gstAmt'    => $row['gstAmt'],
            'netAmt'    => $row['netAmt']
        ];
    }

    // -------------------- Company Details ------------------------------------
    $cmp_qry = mysqli_query($con,"SELECT * FROM company LIMIT 1");
    $cmp = mysqli_fetch_assoc($cmp_qry);

    // ------------------- Generate Professional Invoice PDF -------------------
    class PDF extends FPDF{
        function Footer()
        {
            $this->SetY(-10);
            $this->SetFont('Arial','I',8);
            $this->SetTextColor(120);
            $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
        }

        function SectionTitle($title)
        {
            $this->SetTextColor(123,30,43); // #7B1E2B
            $this->SetTextColor(255);
            $this->SetFont('Arial','B',11);
            $this->Cell(0,8,$title,0,1,'L',true);
            $this->Ln(2);
        }
    }

    $pdf = new FPDF();
    $pdf->AddPage();

    // ===== HEADER =====
    $pdf->SetFont('Arial','B',20);
    $pdf->SetTextColor(123,30,43);
    $pdf->Cell(210,12,$cmp['c_name'],0,1,'C');
    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0);
    $pdf->Cell(210,6,'Event & Booking Management',0,1,'C');
    $pdf->Cell(210,6,$cmp['c_address'],0,1,'C');
    $pdf->Cell(210,6,'Mobile: '.$cmp['c_contact'].' | Website: '.$cmp['c_website'],0,1,'C');

    $pdf->Ln(5);
    //$pdf->Line(10, 35, 200, 35);
    $pdf->SetDrawColor(212,160,23);
    $pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());

    // ===== INVOICE TITLE =====
    $pdf->Ln(4);
    $pdf->SetFont('Arial','B',14);
    $pdf->SetTextColor(123,30,43); // #7B1E2B
    $pdf->Cell(120,8,'BOOKING INVOICE',0,0,'L');

    // ===== INVOICE META =====
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(0);
    $pdf->Cell(190,6,'Booking Date : '.date("d-m-Y", strtotime($booking_date)),0,1,'R');

    $pdf->Ln(5);

    // ===== CUSTOMER DETAILS =====
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(123,30,43); // #7B1E2B
    $pdf->Cell(0,8,'Customer Details',0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0);
    $pdf->Cell(35,6,'Name',0,0);      $pdf->Cell(0,6,": ".$customer['name'],0,1);
    $pdf->Cell(35,6,'Address',0,0);   $pdf->MultiCell(0,6,": ".$customer['address']);
    $pdf->Cell(35,6,'Phone',0,0);     $pdf->Cell(0,6,": ".$customer['phone'],0,1);
    $pdf->Cell(35,6,'Email',0,0);     $pdf->Cell(0,6,": ".$customer['email'],0,1);

    $pdf->Ln(4);

    // ===== BOOKING DETAILS =====
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(123,30,43); // #7B1E2B
    $pdf->Cell(0,8,'Booking Details',0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0);
    $pdf->Cell(50,6,'Event Name',0,0); $pdf->Cell(0,6,": ".$booking['event'],0,1);
    $pdf->Cell(50,6,'Event Duration',0,0);
    $pdf->Cell(0,6,": ".$booking['startDateTime']."  to  ".$booking['endDateTime'],0,1);
    $pdf->Cell(50,6,'Max Guests',0,0); $pdf->Cell(0,6,": ".$booking['max_people'],0,1);
    $pdf->Cell(50,6,'Status',0,0);     $pdf->Cell(0,6,": ".$booking['status'],0,1);

    $pdf->Ln(6);

    // ===== FACILITY TABLE HEADER =====
    $pdf->SetFillColor(123,30,43); // #7B1E2B
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(70,8,'Facility',1,0,'L',true);
    $pdf->Cell(10,8,'Qty',1,0,'C',true);
    $pdf->Cell(25,8,'Rate',1,0,'R',true);
    $pdf->Cell(30,8,'Taxable',1,0,'R',true);
    $pdf->Cell(25,8,'GST',1,0,'R',true);
    $pdf->Cell(30,8,'Total',1,1,'R',true);

    // ===== FACILITY ROWS =====
    $pdf->SetFont('Arial','',11);
    $totalTaxable = 0;
    $totalGST = 0;
    $totalNet = 0;

    foreach($facilities as $fac){
        $totalTaxable += $fac['taxableAmt'];
        $totalGST     += $fac['gstAmt'];
        $totalNet     += $fac['netAmt'];

        $pdf->Cell(70,7,$fac['name'],1);
        $pdf->Cell(10,7,$fac['quantity'],1,0,'C');
        $pdf->Cell(25,7,number_format($fac['rate'],2),1,0,'R');
        $pdf->Cell(30,7,number_format($fac['taxableAmt'],2),1,0,'R');
        $pdf->Cell(25,7,number_format($fac['gstAmt'],2),1,0,'R');
        $pdf->Cell(30,7,number_format($fac['netAmt'],2),1,1,'R');
    }

    // ===== TOTAL SUMMARY =====
    $pdf->Ln(4);
    $pdf->SetFont('Arial','B',11);

    $pdf->SetFillColor(123,30,43); // #7B1E2B
    $pdf->Cell(130,8,'Total Taxable Amount',1,0,'R');
    $pdf->SetTextColor(0);
    $pdf->Cell(60,8,number_format($totalTaxable,2),1,1,'R');

    $pdf->SetFillColor(123,30,43); // #7B1E2B
    $pdf->Cell(130,8,'Total GST Amount',1,0,'R');
    $pdf->SetTextColor(0);
    $pdf->Cell(60,8,number_format($totalGST,2),1,1,'R');

    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(123,30,43); // #7B1E2B
    $pdf->Cell(130,9,'Grand Total',1,0,'R');
    $pdf->SetTextColor(0);
    $pdf->Cell(60,9,number_format($totalNet,2),1,1,'R');

    // ===== FOOTER =====
    $pdf->Ln(10);
    $pdf->SetFont('Arial','I',10);
    $pdf->Cell(0,6,'This is a system generated invoice. No signature required.',0,1,'C');
    $pdf->Cell(0,6,'Thank you for choosing '.$cmp['c_name'].'.',0,1,'C');

    // ===== OUTPUT =====
    $pdfData = $pdf->Output('S');
    ob_end_flush();

    // ✅ Sending Mail with PDF attachment
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host         = "smtp.gmail.com";
        $mail->SMTPAuth     = true;
        $mail->Username     = "greenlandgarden.howrah@gmail.com"; // তোমার email
        $mail->Password     = "gcotyxhtkhewgpvr"; // App password use করবে
        $mail->SMTPSecure   = "tls";
        $mail->Port         = 587;

        $mail->setFrom("greenlandgarden.howrah@gmail.com", "Booking System");
        $mail->addAddress($userEmail, $userName);

        $mail->isHTML(true);
        $mail->Subject = "Booking Registered - $booking_id";
        $mail->Body    = "Dear $userName,<br><br>Your booking is registered. Please find the attached booking PDF.<br><br>Thank you.";

        // PDF attach (memory থেকে)
        $mail->addStringAttachment($pdfData, "Booking_$booking_id.pdf");

        $mail->send();
        $mailStatus = "Mail sent successfully.";
    } catch (Exception $e) {
        $mailStatus = "Mailer Error: {$mail->ErrorInfo}";
    }

    $response = [
        "success" => true,
        "message" => "Booking saved successfully! $mailStatus",
        "pdf_base64" => base64_encode($pdfData)
    ];
    echo json_encode($response);
} catch (Exception $e) {
    // Rollback if error
    $con->rollback();
    $response = [
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ];
    echo json_encode($response);
}
exit;


?>

// Start DB transaction or your insert logic here
/* try {
    // Example: insert booking master record
    $stmt = $pdo->prepare("INSERT INTO bookings (fbdate, event, eventTime, maxPeople) VALUES (?, ?, ?, ?)");
    $stmt->execute([$fbdate, $event, $eventTime, $maxPeople]);
    $booking_id = $pdo->lastInsertId();

    // Insert selected facilities
    foreach ($facility_ids as $fid) {
        $qty = isset($quantities[$fid]) ? intval($quantities[$fid]) : 1;
        $stmt2 = $pdo->prepare("INSERT INTO booking_facilities (booking_id, facility_id, quantity) VALUES (?, ?, ?)");
        $stmt2->execute([$booking_id, $fid, $qty]);
    }

    echo json_encode(['success' => true, 'message' => 'Booking saved']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} */
