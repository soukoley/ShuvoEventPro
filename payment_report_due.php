<?php
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}
?>

<h4 class="section-title">
    <i class="fa fa-exclamation-triangle"></i> Due Payment Report
</h4>

<?php
$sql = "
    SELECT 
        p.booking_id,
        b.e_name,
        c.c_name,
        c.c_mobile,
        p.net_amt,
        p.adv_amt,
        p.due_amt,
        p.payment_date
    FROM payment p
    JOIN booking_details b ON p.booking_id = b.booking_id
    JOIN customer c ON b.cust_id = c.c_id
    WHERE p.due_amt > 0
    ORDER BY p.payment_date ASC
";

$res = mysqli_query($con, $sql);

$totalDue = 0;
?>

<div class="panel panel-default">
    <div class="panel-heading corporate-heading">
        PENDING DUES
    </div>

    <div class="table-responsive">
        <table class="table table-bordered facility-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Event</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th class="text-right">Bill (₹)</th>
                    <th class="text-right">Paid (₹)</th>
                    <th class="text-right">Due (₹)</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            $count = 0;
            while ($row = mysqli_fetch_assoc($res)):
                $count++;
                $totalDue += $row['due_amt'];
            ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['e_name']; ?></td>
                    <td><?php echo $row['c_name']; ?></td>
                    <td><?php echo $row['c_mobile']; ?></td>
                    <td class="text-right">₹ <?php echo number_format($row['net_amt'],2); ?></td>
                    <td class="text-right">₹ <?php echo number_format($row['adv_amt'],2); ?></td>
                    <td class="text-right text-danger">
                        ₹ <?php echo number_format($row['due_amt'],2); ?>
                    </td>
                </tr>
            <?php endwhile; ?>

            </tbody>

            <tfoot>
                <tr class="grand-row">
                    <th colspan="7" class="text-right">TOTAL DUE</th>
                    <th class="text-right text-danger">
                        ₹ <?php echo number_format($totalDue,2); ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
