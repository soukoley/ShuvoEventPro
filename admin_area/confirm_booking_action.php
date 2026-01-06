<?php
session_start();
include('./includes/db.php');

$booking_id=$_POST['booking_id'];
$items=json_decode($_POST['items'],true);

mysqli_begin_transaction($con);

try{
    mysqli_query($con,"DELETE FROM booking_facilities WHERE booking_id='$booking_id'");

    foreach($items as $it){
        $qty=$it['qty'];
        $rate=$it['rate'];
        $total=$qty*$rate;

        mysqli_query($con,"
        INSERT INTO booking_facilities
        (booking_id,facility_id,qty,rate,tot_amt)
        VALUES
        ('$booking_id','{$it['facility_id']}','$qty','$rate','$total')
        ");
    }

    mysqli_query($con,"
    UPDATE booking_details
    SET status='Confirmed'
    WHERE booking_id='$booking_id'
    ");

    mysqli_commit($con);
    echo "OK";

}catch(Exception $e){
    mysqli_rollback($con);
    http_response_code(500);
}
