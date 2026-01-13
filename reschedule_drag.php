<?php
include('./includes/db.php');

$id=$_POST['booking_id'];
$start=$_POST['start'];
$end=$_POST['end'];

$sd=date('Y-m-d H:i:s',strtotime($start));
$ed=date('Y-m-d H:i:s',strtotime($end));

/* 🔴 Conflict check */
$c=mysqli_query($con,"
SELECT 1 FROM booking_details
WHERE booking_id!='$id'
AND booking_status!='Cancelled'
AND (
    ('$sd' BETWEEN CONCAT(start_date,' ',start_time)
             AND CONCAT(end_date,' ',end_time))
 OR ('$ed' BETWEEN CONCAT(start_date,' ',start_time)
             AND CONCAT(end_date,' ',end_time))
)
");

if(mysqli_num_rows($c)>0){
    echo "Another booking exists in this time slot";
    exit;
}

mysqli_query($con,"
UPDATE booking_details
SET start_date=DATE('$sd'),
    start_time=TIME('$sd'),
    end_date=DATE('$ed'),
    end_time=TIME('$ed'),
    booking_status='Rescheduled'
WHERE booking_id='$id'
");

echo "OK";
