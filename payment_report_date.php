<?php
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}
?>

<div class="row">
    <div class="col-lg-12">
        <h4 class="section-title">
            <i class="fa fa-calendar"></i> Date-wise Payment Report
        </h4>
    </div>
</div>

<!-- FILTER -->
<div class="panel panel-default">
    <div class="panel-body">

        <form method="GET" class="row">
            <input type="hidden" name="payment_report_date">

            <div class="col-md-3">
                <label class="edit-label">From Date</label>
                <input type="date" name="from_date" class="form-control"
                       value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>">

            </div>

            <div class="col-md-3">
                <label class="edit-label">To Date</label>
                <input type="date" name="to_date" class="form-control"
                       value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>">
            </div>

            <div class="col-md-3" style="margin-top:20px;">
                <button class="btn btn-primary">
                    <i class="fa fa-search"></i> Generate
                </button>
            </div>

        </form>

    </div>
</div>

<?php
$from = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to   = isset($_GET['to_date'])   ? $_GET['to_date']   : '';


if ($from && $to):

    $sql = "
        SELECT 
            p.booking_id,
            b.e_name,
            c.c_name,
            p.payment_date,
            p.net_amt,
            p.adv_amt,
            p.due_amt
        FROM payment p
        JOIN booking_details b ON p.booking_id = b.booking_id
        JOIN customer c ON b.cust_id = c.c_id
        WHERE p.payment_date BETWEEN '$from' AND '$to'
        ORDER BY p.payment_date ASC
    ";

    $res = mysqli_query($con, $sql);

    $totalReceived = 0;
    $totalDue = 0;
?>

<!-- REPORT TABLE -->
<div class="panel panel-default">
    <div class="panel-heading corporate-heading">
        PAYMENT SUMMARY
    </div>

    <div class="table-responsive">
        <table class="table table-bordered facility-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Event</th>
                    <th>Customer</th>
                    <th>Payment Date</th>
                    <th class="text-right">Received</th>
                    <th class="text-right">Due</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            $count = 0;
            while ($row = mysqli_fetch_assoc($res)):
                $totalReceived += $row['adv_amt'];
                $totalDue      += $row['due_amt'];
                $count++;
            ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $row['booking_id'] ?></td>
                    <td><?= $row['e_name'] ?></td>
                    <td><?= $row['c_name'] ?></td>
                    <td><?= date('d-m-Y', strtotime($row['payment_date'])) ?></td>
                    <td class="text-right">₹ <?= number_format($row['adv_amt'],2) ?></td>
                    <td class="text-right">₹ <?= number_format($row['due_amt'],2) ?></td>
                </tr>
            <?php endwhile; ?>

            </tbody>

            <tfoot>
                <tr class="grand-row">
                    <th colspan="5" class="text-right">TOTAL</th>
                    <th class="text-right">₹ <?= number_format($totalReceived,2) ?></th>
                    <th class="text-right">₹ <?= number_format($totalDue,2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php endif; ?>
