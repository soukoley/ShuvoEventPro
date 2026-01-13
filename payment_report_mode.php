<?php
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}

$from = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to   = isset($_GET['to_date']) ? $_GET['to_date'] : '';
?>

<h4 class="section-title">
    <i class="fa fa-credit-card"></i> Mode-wise Payment Report
</h4>

<!-- FILTER -->
<div class="panel panel-default">
    <div class="panel-body">
        <form method="GET" class="row">
            <input type="hidden" name="payment_report_mode">

            <div class="col-md-3">
                <label class="edit-label">From Date</label>
                <input type="date" name="from_date" class="form-control"
                       value="<?php echo $from; ?>">
            </div>

            <div class="col-md-3">
                <label class="edit-label">To Date</label>
                <input type="date" name="to_date" class="form-control"
                       value="<?php echo $to; ?>">
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
if ($from && $to) {

    $sql = "
        SELECT 
            pd.p_type,
            SUM(pd.pdAmt) AS total_amount,
            COUNT(*) AS total_count
        FROM payment_details pd
        WHERE pd.payment_date BETWEEN '$from' AND '$to'
        GROUP BY pd.p_type
    ";

    $res = mysqli_query($con, $sql);
?>

<div class="panel panel-default">
    <div class="panel-heading corporate-heading">
        MODE SUMMARY
    </div>

    <div class="table-responsive">
        <table class="table table-bordered facility-table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Payment Mode</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $grandTotal = 0;
            $count = 0;
            while ($row = mysqli_fetch_assoc($res)) {
                $grandTotal += $row['total_amount'];
                $count++;
            ?>
                <tr>
                    <td class="text-center"><?php echo $count; ?></td>
                    <td><?php echo $row['p_type']; ?></td>
                    <td class="text-center"><?php echo $row['total_count']; ?></td>
                    <td class="text-right">
                        ₹ <?php echo number_format($row['total_amount'], 2); ?>
                    </td>
                </tr>
            <?php } ?>

            </tbody>

            <tfoot>
                <tr class="grand-row">
                    <th colspan="3" class="text-right">TOTAL</th>
                    <th class="text-right">
                        ₹ <?php echo number_format($grandTotal, 2); ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php } ?>
