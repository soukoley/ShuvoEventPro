<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ob_start();

require('../fpdf186/fpdf.php');
include('./db.php');

if(!isset($_GET['id'])){
    die('Booking ID missing');
}

$booking_id = mysqli_real_escape_string($con, $_GET['id']);

/* FETCH DATA */
$qry = mysqli_query($con,"
    SELECT 
        p.gross_amt, p.net_amt, p.adv_amt, p.due_amt, p.payment_date,
        b.booking_id, b.e_name, b.booking_date,
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

/* ================= PDF CLASS ================= */
class PDF extends FPDF
{
    function Header()
    {
        // Title
        $this->SetFont('Arial','B',18);
        $this->SetTextColor(123,30,43); // #7B1E2B
        $this->Cell(0,12,'INVOICE',0,1,'C');

        // Company name
        $this->SetFont('Arial','',10);
        $this->SetTextColor(212,160,23); // #D4A017
        $this->Cell(0,6,'Your Company Name',0,1,'C');

        $this->Ln(6);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(120);
        $this->Cell(0,10,'System Generated Invoice | Page '.$this->PageNo(),0,0,'C');
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
/* ================= PDF CREATE ================= */

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0);

/* INVOICE INFO */
$pdf->Cell(95,6,'Invoice No : '.$row['booking_id'],0,0);
$pdf->Cell(95,6,'Invoice Date : '.date('d M Y',strtotime($row['payment_date'])),0,1);
$pdf->Ln(3);

/* CUSTOMER DETAILS */
$pdf->SectionTitle('Customer Details');
$pdf->Cell(0,6,'Name : '.$row['c_name'],0,1);
$pdf->Cell(0,6,'Mobile : '.$row['c_mobile'],0,1);
$pdf->Cell(0,6,'Address : '.$row['c_addr'],0,1);
$pdf->Ln(3);

/* EVENT DETAILS */
$pdf->SectionTitle('Event Details');
$pdf->Cell(95,6,'Event Name : '.$row['e_name'],0,0);
$pdf->Cell(95,6,'Booking Date : '.date('d M Y',strtotime($row['booking_date'])),0,1);
$pdf->Ln(3);

/* PAYMENT SUMMARY */
$pdf->SectionTitle('Payment Summary');

$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(212,160,23); // gold
$pdf->SetTextColor(0);

$pdf->Cell(80,8,'Description',1,0,'C',true);
$pdf->Cell(40,8,'Amount (Rs.)',1,1,'C',true);

$pdf->SetFont('Arial','',10);
$pdf->Cell(80,8,'Total Amount',1);
$pdf->Cell(40,8,number_format($row['net_amt'],2),1,1,'R');

$pdf->Cell(80,8,'Paid Amount',1);
$pdf->Cell(40,8,number_format($row['adv_amt'],2),1,1,'R');

$pdf->Cell(80,8,'Due Amount',1);
$pdf->Cell(40,8,number_format($row['due_amt'],2),1,1,'R');

/* STATUS TEXT (no stamp) */
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);

if($row['due_amt'] <= 0){
    $pdf->SetTextColor(40,167,69); // green
    $status = 'PAID';
} else {
    $pdf->SetTextColor(212,160,23); // gold
    $status = 'DUE';
}

$pdf->Cell(0,10,'Payment Status : '.$status,0,1,'C');
$pdf->SetTextColor(0);

/* FOOT NOTE */
$pdf->Ln(6);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(0,8,'Thank you for your business!',0,1,'C');

/* OUTPUT */
$pdf->Output('I','Invoice_'.$booking_id.'.pdf');
