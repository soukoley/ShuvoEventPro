<?php

header('Content-Type: application/json');
include("includes/db.php"); // your DB connection setup

require 'fpdf186/fpdf.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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

    /* $start_date = date("d-m-Y", strtotime($fbdate));
    $start_time = date("h:i A", strtotime($fbdate));
    $end_date = date("d-m-Y", strtotime($tbdate));
    $end_time = date("h:i A", strtotime($tbdate)); */
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
    $sql2 = "INSERT INTO booking_facilities (booking_id, facility_id, qty, rate, tot_amt) 
             VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $con->prepare($sql2);

    foreach ($facilities as $fac) {
        $fid        = intval($fac['id']);
        $fprice     = floatval($fac['price']);
        $qty        = intval($fac['quantity']);
        $tot_amt    = $fprice * $qty;

        // Insert each facility booking
        $stmt2->bind_param("siidd", $booking_id, $fid, $qty, $fprice, $tot_amt);
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

    $booking = [
        'booking_id' => $booking_id,
        'date' => $booking_date,
        'startDateTime' => $start_date . ' ' . $start_time,
        'endDateTime' => $end_date . ' ' . $end_time,
        'max_people' => $maxPeople,
        'event' => $event,
        'status' => $status
    ];

    // ------------------- Fetch Facilities -------------------
    $sql2 = "SELECT f.fName, bf.qty, bf.rate, bf.tot_amt
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
            'totalAmt'  => $row['tot_amt']
        ];
    }

    // ------------------- Generate PDF -------------------
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(203, 134, 112); // #cb8670
    $pdf->Cell(0,10,'Greenland',0,1,'C');  // Top Center

    $pdf->SetFont('Arial','B',16);
    // Reset color for rest of the text
    $pdf->SetTextColor(0,0,0); // black
    $pdf->Cell(0,10,'Booking Confirmation',0,1,'C');

    // Customer Details
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,8,'Customer Details',0,1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,6,'Name :'); $pdf->Cell(0,6,$customer['name'],0,1);
    $pdf->Cell(50,6,'Address :'); $pdf->Cell(0,6,$customer['address'],0,1);
    $pdf->Cell(50,6,'Contact Number :'); $pdf->Cell(0,6,$customer['phone'],0,1);
    $pdf->Cell(50,6,'Email :'); $pdf->Cell(0,6,$customer['email'],0,1);
    $pdf->Cell(50,6,'Aadhaar/PAN :'); $pdf->Cell(0,6,$customer['aadhaarPan'],0,1);

    // Booking Details
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,8,'Booking Details',0,1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,6,'Event Name :'); $pdf->Cell(0,6,$booking['event'],0,1);
    $pdf->Cell(50,6,'Booking ID :'); $pdf->Cell(0,6,$booking['booking_id'],0,1);
    $pdf->Cell(50,6,'Booking Date :'); $pdf->Cell(0,6,$booking['date'],0,1);
    $pdf->Cell(50,6,'Event Start Date :'); $pdf->Cell(0,6,$booking['startDateTime'],0,1);
    $pdf->Cell(50,6,'Event End Date :'); $pdf->Cell(0,6,$booking['endDateTime'],0,1);
    $pdf->Cell(50,6,'Max People :'); $pdf->Cell(0,6,$booking['max_people'],0,1);
    $pdf->Cell(50,6,'Status :'); $pdf->Cell(0,6,$booking['status'],0,1);

    // Facilities Table
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,8,'Facilities Details',0,1);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(80,7,'Facility',1,0,'L');
    $pdf->Cell(30,7,'Quantity',1,0,'C');
    $pdf->Cell(40,7,'Rate',1,0,'R');
    $pdf->Cell(40,7,'Total',1,0,'R');
    $pdf->Ln();

    $pdf->SetFont('Arial','',11);
    $totalAmount = 0;
    foreach($facilities as $fac){
        $totalAmount += $fac['totalAmt'];
        $pdf->Cell(80,6,$fac['name'],1,0,'L');
        $pdf->Cell(30,6,$fac['quantity'],1,0,'C');
        $pdf->Cell(40,6,number_format($fac['rate'],2),1,0,'R');
        $pdf->Cell(40,6,number_format($fac['totalAmt'],2),1,0,'R');
        $pdf->Ln();
    }

    // Total Amount
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(150,7,'Total Amount',1);
    $pdf->Cell(40,7,number_format($totalAmount,2),1,0,'R');

    // PDF string এ নিলাম (save করলাম না)
    $pdfData = $pdf->Output('S');  // S = return as string

    // ✅ Mail পাঠানো with PDF attachment
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host         = "smtp.gmail.com";
        $mail->SMTPAuth     = true;
        $mail->Username     = "smnkmrkl80@gmail.com"; // তোমার email
        $mail->Password     = "hghrhljlvsypbppw"; // App password use করবে
        $mail->SMTPSecure   = "tls";
        $mail->Port         = 587;

        $mail->setFrom("smnkmrkl80@gmail.com", "Booking System");
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
