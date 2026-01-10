<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login.php','_self')</script>";
    exit;
} else {
    $sdate = $_SESSION['sdate'];
    $edate = $_SESSION['edate'];

    $qry = "
    SELECT 
        p.id AS payment_id,
        p.booking_id,
        p.gross_amt,
        p.net_amt,
        p.adv_amt,
        p.due_amt,
        p.payment_date,

        b.e_name,
        c.c_name,
        c.c_mobile

    FROM payment p
    JOIN booking_details b ON p.booking_id = b.booking_id
    JOIN customer c ON b.cust_id = c.c_id
    WHERE p.payment_date BETWEEN '$sdate' AND '$edate'
    ORDER BY p.payment_date DESC
    ";

    $res = mysqli_query($con, $qry);
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <title>View Payments</title>
        </head>
        <body>
            <div class="row">
                <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                    <div class="breadcrumb">
                        <li class="active">
                            <i class="fa fa-fw fa-credit-card"></i>
                            Payment / View Payments / Payment List
                        </li>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                    <div class="panel panel-primary">
                        <div class="panel-heading corporate-heading">
                            <h3 class="panel-title"><i class="fa fa-list-alt"></i>
                                Payment&nbsp;&nbsp;List&nbsp;&nbsp;from&nbsp;&nbsp;<?php echo date("d M Y", strtotime($sdate)); ?> &nbsp;&nbsp;to&nbsp;&nbsp; <?php echo date("d M Y", strtotime($edate)); ?>
                            </h3>
                        </div>
                        <div class="table-responsive" style="padding-top: 3px;">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking ID</th>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Event</th>
                                        <th>Total Amount</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                        <th>Payment Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php 
                                $i = 1;
                                while($row = mysqli_fetch_assoc($res)){ 

                                    $status = ($row['due_amt'] <= 0)
                                        ? '<span class="label label-success">Paid</span>'
                                        : '<span class="label label-warning">Due</span>';
                                ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $row['booking_id'] ?></td>
                                        <td><?= htmlspecialchars($row['c_name']) ?></td>
                                        <td><?= $row['c_mobile'] ?></td>
                                        <td><?= htmlspecialchars($row['e_name']) ?></td>

                                        <td>₹<?= number_format($row['net_amt'],2) ?></td>
                                        <td class="text-success">
                                            ₹<?= number_format($row['adv_amt'],2) ?>
                                        </td>
                                        <td class="text-danger">
                                            ₹<?= number_format($row['due_amt'],2) ?>
                                        </td>

                                        <td><?= $status ?></td>
                                        <td><?= date("d M Y", strtotime($row['payment_date'])) ?></td>

                                        <td>
                                            <a href="index.php?receive_Payment=0&id=<?= $row['booking_id'] ?>"
                                            class="btn btn-xs btn-success">
                                                <i class="fa fa-money"></i>
                                            </a>

                                            <a href="index.php?complete_details=0&id=<?= $row['booking_id'] ?>"
                                            class="btn btn-xs btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>  
                        </div>
                    </div>
                    <div style="margin-top: 15px;">
                        <a href="index.php?view_Payments" class="btn" style="background-color: #7A1E3A; color: #ffffffff; font-size: 14px; font-weight: bold;">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </body>
    </html>
<?php } ?>
