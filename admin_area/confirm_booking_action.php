<?php
session_start();
include('./includes/db.php');

$booking_id = $_POST['booking_id'];

$start_date = $_POST['start_date'];
$start_time = $_POST['start_time'];
$end_date   = $_POST['end_date'];
$end_time   = $_POST['end_time'];
$max_guest  = $_POST['max_guest'];

$facilities = json_decode($_POST['facilities'], true);

mysqli_begin_transaction($con);

try{

    // update booking
    mysqli_query($con,"
        UPDATE booking_details SET
            start_date='$start_date',
            start_time='$start_time',
            end_date='$end_date',
            end_time='$end_time',
            max_guest='$max_guest',
            status='Confirmed'
        WHERE booking_id='$booking_id'
    ");

    // reset facilities
    mysqli_query($con,"
        DELETE FROM booking_facilities
        WHERE booking_id='$booking_id'
    ");

    // insert facilities
    foreach($facilities as $f){
        $facility_name = $f['facility_name'];
        $qty = $f['qty'];
        $rate = $f['rate'];
        //$total = $f['total'];
        $total = $qty * $rate;

        mysqli_query($con,"
            INSERT INTO booking_facilities
            (booking_id, facility_id, qty, rate, tot_amt)
            VALUES
            ('$booking_id','{$f['facility_id']}','$qty','$rate','$total')
        ");
    }

    mysqli_commit($con);

    echo json_encode([
        "status"=>"OK",
        //"invoice"=>"invoice_pdf.php?id=$booking_id"
        'success'   => true,
        'booking_id'   => $booking_id,
    ]);

}catch(Exception $e){
    mysqli_rollback($con);
    //http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Transaction failed: ' . $e->getMessage()
    ]);
}
