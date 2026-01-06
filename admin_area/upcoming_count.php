<?php
include('./includes/db.php');

$q=mysqli_query($con,"
SELECT COUNT(*) c FROM booking_details
WHERE start_date=CURDATE()
AND booking_status!='Cancelled'
");
$r=mysqli_fetch_assoc($q);
echo $r['c'];
