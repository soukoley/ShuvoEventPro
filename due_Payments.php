<?php
include("includes/db.php");


if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login.php','_self')</script>";
    exit;
} else {
    $sdate = $_SESSION['sdate'];
    $edate = $_SESSION['edate'];

$qry = "
SELECT 
    b.booking_id,
    b.booking_date,
    b.e_name,
    c.c_name,
    p.net_amt,
    p.adv_amt,
    p.due_amt,
    p.payment_date
FROM payment p
JOIN booking_details b ON p.booking_id = b.booking_id
JOIN customer c ON b.cust_id = c.c_id
WHERE p.due_amt > 0 and b.booking_date BETWEEN '$sdate' AND '$edate'
ORDER BY p.payment_date DESC
";

$res = mysqli_query($con, $qry);
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Due Payments</title>
        </head>
        <body>

            <div class="row">
                <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                    <div class="breadcrumb">
                        <li class="active">
                            <i class="fa fa-fw fa-credit-card"></i>
                            Payment / Due Payments
                        </li>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                    <div class="panel panel-primary">
                        <div class="panel-heading corporate-heading">
                            <h3 class="panel-title"><i class="fa fa-exclamation-circle"></i>
                                Due&nbsp;&nbsp;Payments
                            </h3>
                        </div>
                        <div class="table-responsive" style="padding-top: 3px;">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Booking Date</th>
                                        <th>Event</th>
                                        <th>Customer</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Paid</th>
                                        <th class="text-right">Due</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while($row = mysqli_fetch_assoc($res)){ ?>
                                    <tr>
                                        <td><?= $row['booking_id'] ?></td>
                                        <td><?= $row['booking_date'] ?></td>
                                        <td><?= $row['e_name'] ?></td>
                                        <td><?= $row['c_name'] ?></td>
                                        <td class="text-right">₹<?= number_format($row['net_amt'],2) ?></td>
                                        <td class="text-right">₹<?= number_format($row['adv_amt'],2) ?></td>
                                        <td class="text-danger text-right" style="font-weight: bold;">
                                            ₹<?= number_format($row['due_amt'],2) ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?receive_Payment=0 & id=<?php echo $row['booking_id']; ?>"
                                                class="btn btn-success btn-xs"
                                                style="background-color: #7A1E3A; color: #ffffffff;">
                                                <i class="fa fa-money"></i> Receive
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>                    
                    <div style="margin-top: 15px;">
                        <a href="index.php?due_pay_input" class="btn" style="background-color: #7A1E3A; color: #ffffffff; font-size: 14px; font-weight: bold;">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </body>
    </html>
<?php } ?>