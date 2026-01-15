<?php
include('./includes/db.php');
$id=$_GET['id'];

$b=mysqli_fetch_assoc(mysqli_query($con,"
SELECT bd.*,c.*
FROM booking_details bd
JOIN customer c ON bd.cust_id=c.c_id
WHERE bd.booking_id='$id'
"));

$f=mysqli_query($con,"
SELECT f.fName,bf.*
FROM booking_facilities bf
JOIN facility f ON bf.facility_id=f.id
WHERE bf.booking_id='$id'
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>
<style>
body{font-family:Arial}
table{width:100%;border-collapse:collapse}
td,th{border:1px solid #000;padding:8px}
</style>
</head>
<body onload="window.print()">

<h2>Booking Invoice</h2>
<p><strong>Customer:</strong> <?= $b['c_name'] ?></p>
<p><strong>Event:</strong> <?= $b['e_name'] ?></p>

<table>
<tr><th>Facility</th><th>Qty</th><th>Rate</th><th>Total</th></tr>
<?php $g=0; while($x=mysqli_fetch_assoc($f)){ $g+=$x['tot_amt']; ?>
<tr>
<td><?= $x['fName'] ?></td>
<td><?= $x['qty'] ?></td>
<td><?= $x['rate'] ?></td>
<td><?= $x['tot_amt'] ?></td>
</tr>
<?php } ?>
<tr>
<th colspan="3">Grand Total</th>
<th><?= $g ?></th>
</tr>
</table>

</body>
</html>
