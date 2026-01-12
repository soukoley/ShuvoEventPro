<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ob_start();

require('../fpdf186/fpdf.php');
include('./db.php');
date_default_timezone_set('Asia/Kolkata');

if(!isset($_GET['id'])){
    die('Booking ID missing');
}

$booking_id = mysqli_real_escape_string($con, $_GET['id']);

/* FETCH DATA */
$qry = mysqli_query($con,"
    SELECT 
        p.sgst_amt, p.cgst_amt, p.disc_amt, p.gross_amt, p.net_amt, p.adv_amt, p.due_amt, p.payment_date,
        b.booking_id, b.e_name, b.booking_date, b.status, b.start_date, b.start_time, b.end_date, b.end_time, b.max_guest,
        c.c_name, c.c_mobile, c.c_addr
    FROM payment p
    JOIN booking_details b ON p.booking_id = b.booking_id
    JOIN customer c ON b.cust_id = c.c_id
    WHERE p.booking_id = '$booking_id'
    ORDER BY p.id DESC
    LIMIT 1
");

if(mysqli_num_rows($qry)==0){
    die('No payment record found');
}

$row = mysqli_fetch_assoc($qry);
$taxAmt = $row['sgst_amt'] + $row['cgst_amt'];

/* ================= PDF CLASS ================= */
class PDF extends FPDF
{
    public $company;
    public $invoice;
    public $watermarkText = '';   // PAID / DUE

    // ---------- ROTATION HELPERS ----------
    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0) {
            $this->_out('Q');
        }
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf(
                'q %.5F %.5F %.5F %.5F %.5F %.5F cm 1 0 0 1 %.5F %.5F cm',
                $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy
            ));
        }
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    // ---------- WATERMARK ----------
    function DrawWatermark()
    {
        if ($this->watermarkText == '') return;

        // Color based on watermark
        if ($this->watermarkText === 'PAID') {
            $this->SetTextColor(210,210,210);   // soft grey
        } else { // DUE
            $this->SetTextColor(230,190,190);   // soft red
        }

        $this->SetFont('Arial','B',60);
        $this->RotatedText(10, 272, $this->watermarkText, 0);
        $this->SetTextColor(0);
    }

    function Header()
    {
        // 🔑 Watermark FIRST (behind everything)
        $this->DrawWatermark();

        /* ===== existing header code ===== */
        $cmp = $this->company;

        $this->SetFont('Arial','B',16);
        $this->SetTextColor(123,30,43);
        $this->Cell(0,8,$cmp['c_name'],0,1,'C');

        $this->SetFont('Arial','',9);
        $this->SetTextColor(0);
        $this->Cell(0,5,$cmp['c_address'],0,1,'C');
        $this->Cell(0,5,'Mobile: '.$cmp['c_contact'].' | Website: '.$cmp['c_website'],0,1,'C');

        $this->Ln(3);
        $this->SetDrawColor(212,160,23);
        $this->Line(10,$this->GetY(),200,$this->GetY());
        $this->Ln(2);
    }

    function FacilityTableHeader()
    {
        $this->SetFillColor(212,160,23); // #D4A017
        $this->SetTextColor(0);
        $this->SetFont('Arial','B',9);

        $this->Cell(10,8,'#',1,0,'C',true);
        $this->Cell(55,8,'Facility',1,0,'C',true);
        $this->Cell(15,8,'Qty',1,0,'C',true);
        $this->Cell(25,8,'Rate',1,0,'C',true);
        $this->Cell(30,8,'Taxable',1,0,'C',true);
        $this->Cell(25,8,'GST Amt',1,0,'C',true);
        $this->Cell(30,8,'Net Amt',1,1,'C',true);
    }

    function Footer()
    {
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(120);
        $this->Cell(0,10,'This is a system generated invoice. | Page '.$this->PageNo(),0,0,'C');
    }

    function SectionTitle($title)
    {
        $this->SetFillColor(123,30,43); // maroon
        $this->SetTextColor(255);
        $this->SetFont('Arial','B',11);
        $this->Cell(0,8,$title,0,1,'L',true);
        $this->Ln(2);
    }
}

$cmp_qry = mysqli_query($con,"SELECT * FROM company LIMIT 1");
$company = mysqli_fetch_assoc($cmp_qry);

$fac_sql = "
    SELECT 
        f.fName AS facility_name,
        bf.qty,
        bf.rate,
        bf.taxableAmt,
        bf.gstAmt,
        bf.netAmt
    FROM booking_facilities bf
    INNER JOIN facility f ON bf.facility_id = f.id
    WHERE bf.booking_id = '$booking_id'
";

$fac_qry = mysqli_query($con, $fac_sql);

if (!$fac_qry) {
    die('Facility Query Error : ' . mysqli_error($con));
}


/* ================= PDF CREATE ================= */

$pdf = new PDF();
$pdf->company = $company;   // 🔑 IMPORTANT
$pdf->invoice = $row;       // 🔑 IMPORTANT

// 🔑 Watermark condition
if ($row['due_amt'] == 0) {
    $pdf->watermarkText = 'PAID';
} elseif ($row['due_amt'] > 0) {
    $pdf->watermarkText = 'DUE';
}

$pdf->AddPage();

$pdf->SetFont('Arial','B',11);
$pdf->SetTextColor(0);

/* ================= BOOKING INVOICE HEADER ================= */

// LEFT : BOOKING INVOICE
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(123,30,43); // #7B1E2B
$pdf->Cell(120,8,'BOOKING INVOICE',0,0,'L');

/* // RIGHT : [ STATUS ]
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor($statusColor[0], $statusColor[1], $statusColor[2]);
$pdf->Cell(70,8,'[ '.$statusText.' ]',0,1,'R'); */

// RIGHT : Booking info (below status)
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0);

//$pdf->Cell(120,6,'',0,0);
$pdf->Cell(70,6,'Booking ID : '.$row['booking_id'],0,1,'R');

//$pdf->Cell(120,6,'',0,0);
$pdf->Cell(190,6,'Invoice Date : '.date('d M Y, h:i A'),0,1,'R');

// Divider line
$pdf->Ln(2);
/* $pdf->SetDrawColor(212,160,23); // #D4A017
$pdf->SetLineWidth(0.4);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); */

$pdf->Ln(3);


/* CUSTOMER DETAILS */
$pdf->SectionTitle('Customer Details');
$pdf->SetTextColor(0);

/* Row 1 */
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,6,'Name :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(70,6,utf8_decode($row['c_name']),0,0);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,6,'Mobile :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(70,6,$row['c_mobile'],0,1);

/* Row 2 */
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,6,'Address :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(165,6,utf8_decode($row['c_addr']),0,'L');

$pdf->Ln(3);

/* EVENT DETAILS */
$pdf->SectionTitle('Event Details');
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0);

// Container line (top)
$yStart = $pdf->GetY();

// Column widths
$labelW = 45;
$valueW = 50;

// Row 1
$pdf->SetFont('Arial','B',10);
$pdf->Cell($labelW,6,'Event Name',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell($valueW,6,': '.$row['e_name'],0,0,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell($labelW,6,'Booking Date',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell($valueW,6,': '.date('d M Y',strtotime($row['booking_date'])),0,1,'L');

// Row 2
$pdf->SetFont('Arial','B',10);
$pdf->Cell($labelW,6,'Check-In',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(
    $valueW,
    6,
    ': '.date('d M Y',strtotime($row['start_date'])).
    ' '.date('h:i A',strtotime($row['start_time'])),
    0,
    0,
    'L'
);

$pdf->SetFont('Arial','B',10);
$pdf->Cell($labelW,6,'Check-Out',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(
    $valueW,
    6,
    ': '.date('d M Y',strtotime($row['end_date'])).
    ' '.date('h:i A',strtotime($row['end_time'])),
    0,
    1,
    'L'
);

// Row 3
$pdf->SetFont('Arial','B',10);
$pdf->Cell($labelW,6,'Maximum Guests',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell($valueW,6,': '.$row['max_guest'],0,1,'L');

// Bottom spacing
$pdf->Ln(4);

/* -------- Facility Table -------- */
$pdf->FacilityTableHeader();

$pdf->SetFont('Arial','',9);
$i = 1;
$fill = false;

while ($f = mysqli_fetch_assoc($fac_qry)) {

    // 🔁 Page break handling
    if ($pdf->GetY() > 180) {
        $pdf->AddPage();
        $pdf->FacilityTableHeader();
    }

    // Zebra row style
    $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);

    $pdf->Cell(10,8,$i++,1,0,'C',true);
    $pdf->Cell(55,8,$f['facility_name'],1,0,'L',true);
    $pdf->Cell(15,8,$f['qty'],1,0,'C',true);
    $pdf->Cell(25,8,number_format($f['rate'],2),1,0,'R',true);
    $pdf->Cell(30,8,number_format($f['taxableAmt'],2),1,0,'R',true);
    $pdf->Cell(25,8,number_format($f['gstAmt'],2),1,0,'R',true);
    $pdf->Cell(30,8,number_format($f['netAmt'],2),1,1,'R',true);

    $fill = !$fill;
}

// Reserve space for Payment Summary + Signature + E.&O.E.
$requiredSpace = 70; // mm

if ($pdf->GetY() > (230 - $requiredSpace)) {
    $pdf->AddPage();
}

/* ================= PAYMENT SUMMARY ================= */

$pdf->Ln(4);
$pdf->SectionTitle('Payment Summary');

$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0);

$labelW = 110;
$amtW   = 40;

/* Header */
$pdf->SetFillColor(212,160,23); // #D4A017
$pdf->SetFont('Arial','B',10);
$pdf->Cell($labelW,9,'Description',1,0,'L',true);
$pdf->Cell($amtW,9,'Amount (Rs.)',1,1,'R',true);

$pdf->SetFont('Arial','',10);
$zebra = false;

/* Helper function */
function summaryRow($pdf, $label, $amount, $bold = false, $zebra = false)
{
    $pdf->SetFont('Arial', $bold ? 'B' : '', $bold ? 11 : 10);
    $pdf->SetFillColor($zebra ? 245 : 255, $zebra ? 245 : 255, $zebra ? 245 : 255);
    $pdf->Cell(110,8,$label,1,0,'L',true);
    $pdf->Cell(40,8,number_format($amount,2),1,1,'R',true);
}

/* ALWAYS visible */
summaryRow($pdf,'Gross Amount', $row['gross_amt'], false, $zebra); $zebra = !$zebra;
summaryRow($pdf,'Tax Amount (GST)', $taxAmt, false, $zebra); $zebra = !$zebra;

/* Status based visibility */
if ($row['status'] != 'Pending') {
    summaryRow($pdf,'Discount Amount', $row['disc_amt'], false, $zebra); $zebra = !$zebra;
    summaryRow($pdf,'Advance Amount',  $row['adv_amt'], false, $zebra); $zebra = !$zebra;
}

/* ALWAYS visible */
summaryRow($pdf,'Due Amount', $row['due_amt'], false, $zebra); $zebra = !$zebra;

/* NET AMOUNT (Highlighted) */
$pdf->SetFillColor(123,30,43); // #7B1E2B
$pdf->SetTextColor(255);
$pdf->SetFont('Arial','B',11);
$pdf->Cell($labelW,10,'Net Payable Amount',1,0,'L',true);
$pdf->Cell($amtW,10,number_format($row['net_amt'],2),1,1,'R',true);

/* Reset */
$pdf->SetTextColor(0);

/* ================= DECLARATION / SIGNATURE ================= */

// Ensure enough space, else new page
/* $requiredSpace = 40;
if ($pdf->GetY() > (270 - $requiredSpace)) {
    $pdf->AddPage();
} */
$pdf->SetY(-47);
// "For, Company Name"
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,6,'For, '.$company['c_name'],0,1,'R');

$pdf->SetY(270);
$y = $pdf->GetY();
// "E. & O.E."
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,6,'E. & O.E.',0,1,'R');

/* OUTPUT */
$pdf->Output('I','Invoice_'.$booking_id.'.pdf');

ob_end_flush();
