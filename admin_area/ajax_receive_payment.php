<?php
include("includes/db.php");

$booking_id = $_POST['booking_id'];
$receiveAmt = (float) $_POST['amount'];
$mode       = $_POST['mode'];

$q = mysqli_query($con,"SELECT adv_amt, due_amt FROM payment WHERE booking_id='$booking_id'");
$row = mysqli_fetch_assoc($q);

$newPaid = $row['adv_amt'] + $receiveAmt;
$newDue  = $row['due_amt'] - $receiveAmt;

$upd = "
UPDATE payment SET 
    adv_amt = '$newPaid',
    due_amt = '$newDue',
    payment_date = CURDATE()
WHERE booking_id = '$booking_id'
";

if(mysqli_query($con,$upd)){
    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false]);
}
