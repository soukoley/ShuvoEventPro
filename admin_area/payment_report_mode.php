<?php
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}

$from = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to   = isset($_GET['to_date']) ? $_GET['to_date'] : '';
?>

<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-credit-card"></i>
				Payment / Payment Reports / Mode-wise Report
			</li>
		</div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
        <div class="panel-heading corporate-heading">
            <h3 class="panel-title">
                <i class="fa fa-credit-card"></i> Mode-wise Payment Report
            </h3>
        </div>
        <div class="panel-body" style="padding-top: 20px;">

            <form class="form-horizontal" method="GET" class="row">
                <input type="hidden" name="payment_report_mode">
                <div class="form-group">
                    <label class="col-md-2 control-label" style="text-align : right;">Start Date :</label>
                    <div class="col-md-3">
                        <input type="date" name="from_date" class="form-control"
                        value="<?php echo $from; ?>">
                    </div>

                    <label class="col-md-2 control-label" style="text-align : right;">End Date :</label>
                    <div class="col-md-3">
                        <input type="date" name="to_date" class="form-control"
                        value="<?php echo $to; ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <button class="btn btn-primary">
                            <i class="fa fa-search"></i> Generate
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
