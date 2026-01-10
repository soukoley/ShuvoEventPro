<?php
include("includes/db.php");

$booking_id = $_GET['payment_Details'];

$qBooking = "
SELECT 
    b.booking_id, b.e_name, b.booking_date,
    c.c_name, c.c_mobile,
    p.net_amt, p.adv_amt, p.due_amt
FROM booking_details b
JOIN customer c ON b.cust_id = c.c_id
JOIN payment p ON b.booking_id = p.booking_id
WHERE b.booking_id = '$booking_id'
";
$booking = mysqli_fetch_assoc(mysqli_query($con, $qBooking));

$qFacilities = "
SELECT f.fName, bf.qty, bf.rate, bf.taxableAmt, bf.gstAmt, bf.netAmt
FROM booking_facilities bf
JOIN facility f ON bf.facility_id = f.id
WHERE bf.booking_id = '$booking_id'
";
$facs = mysqli_query($con, $qFacilities);
?>

<h3><i class="fa fa-file-text-o"></i> Payment Details</h3>

<div class="panel panel-default">
<div class="panel-body">

<p><strong>Booking ID:</strong> <?= $booking['booking_id'] ?></p>
<p><strong>Customer:</strong> <?= $booking['c_name'] ?> (<?= $booking['c_mobile'] ?>)</p>
<p><strong>Event:</strong> <?= $booking['e_name'] ?></p>

<hr>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Facility</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Taxable</th>
            <th>GST</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php while($f = mysqli_fetch_assoc($facs)){ ?>
        <tr>
            <td><?= $f['fName'] ?></td>
            <td><?= $f['qty'] ?></td>
            <td>₹<?= $f['rate'] ?></td>
            <td>₹<?= $f['taxableAmt'] ?></td>
            <td>₹<?= $f['gstAmt'] ?></td>
            <td>₹<?= $f['netAmt'] ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<hr>

<div class="row">
    <div class="col-md-4"><strong>Total:</strong> ₹<?= $booking['net_amt'] ?></div>
    <div class="col-md-4"><strong>Paid:</strong> ₹<?= $booking['adv_amt'] ?></div>
    <div class="col-md-4 text-danger"><strong>Due:</strong> ₹<?= $booking['due_amt'] ?></div>
</div>

</div>
</div>
