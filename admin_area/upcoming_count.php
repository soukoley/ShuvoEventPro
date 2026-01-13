<?php
include("includes/db.php");
header("Content-Type:text/plain");

$today = date("Y-m-d");

$q = $con->prepare("
    SELECT COUNT(*) 
    FROM booking_details 
    WHERE event_date>=? AND status='Confirmed'
");
$q->bind_param("s",$today);
$q->execute();
$q->bind_result($count);
$q->fetch();

echo $count;
