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

    $run_bookings = "SELECT bd.booking_id, bd.booking_date, c.c_name, c.c_mobile, bd.e_name, bd.start_date, bd.end_date, bd.status
                FROM booking_details bd, customer c 
                WHERE bd.cust_id = c.c_id AND bd.booking_date BETWEEN '$sdate' AND '$edate'
                AND bd.status != 'Rejected'";


    $run_bookings .= " ORDER BY bd.booking_date";

    $run_bookings_result = mysqli_query($con, $run_bookings);
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Invoice List</title>
    </head>
    <body>
    <div class="row">
        <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
            <div class="breadcrumb">
                <li class="active">
                    <i class="fa fa-fw fa-calendar-check-o"></i>
                    Invoice / View Invoices / Invoice List
                </li>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
            <div class="panel panel-primary">
                <div class="panel-heading corporate-heading">
				    <h3 class="panel-title"><i class="fa fa-check-circle"></i>
                        Invoice List from <?php echo date("d M Y", strtotime($sdate)); ?> to <?php echo date("d M Y", strtotime($edate)); ?>
                    </h3>
                </div>

                <div class="table-responsive" style="padding-top: 3px;">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Booking ID</th>
                                <th class="text-center">Booking Date</th>
                                <th class="text-left">Customer</th>
                                <th class="text-center">Event</th>
                                <th class="text-center">Event Start Date</th>
                                <th class="text-center">Event End Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        <?php
                            if(mysqli_num_rows($run_bookings_result) > 0){
                                $i = 1;
                                while($bk = mysqli_fetch_assoc($run_bookings_result)){
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo $bk['booking_id']; ?></td>
                                        <td class="text-center"><?php echo date("d M Y", strtotime($bk['booking_date'])); ?></td>
                                        <td class="text-left"><?php echo $bk['c_name'].'<br>'.$bk['c_mobile']; ?></td>
                                        <td class="text-center"><?php echo $bk['e_name']; ?></td>
                                        <td class="text-center"><?php echo date("d M Y", strtotime($bk['start_date'])); ?></td>
                                        <td class="text-center"><?php echo date("d M Y", strtotime($bk['end_date'])); ?></td>
                                        <td class="text-center">
                                            <?php
                                                
                                                if($bk['status'] == 'Pending'){
                                                    echo '<span class="label label-warning">Pending</span>';
                                                } elseif($bk['status'] == 'Approved'){
                                                    echo '<span class="label label-success">Approved</span>';
                                                } elseif($bk['status'] == 'Completed'){
                                                    echo '<span class="label label-primary">Completed</span>';
                                                }
                                            ?>
                                               
                                        </td>
                                        <td class="text-center">
                                            <a href="../includes/invoice_pdf.php?id=<?php echo $bk['booking_id']; ?>" 
                                                class="btn btn-success btn-xs"
                                                style="background-color: #7A1E3A; color: #ffffffff;"
                                                target="_blank">
                                                <i class="fa fa-download"></i> Download Invoice
                                            </a>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='9' class='text-center text-danger' style='font-weight:bold;'>No bookings found for today.</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="margin-top: 15px;">
                <a href="index.php?view_Invoice"
                    class="btn btn-back" 
                    style="background-color: #7A1E3A; color: #ffffffff; font-size: 14px; font-weight: bold;">
                        <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    </body>
    </html>

<?php
}
?>
