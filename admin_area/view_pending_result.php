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
    $booking_status = "Pending"; // Default to pending if not set"

    $run_bookings = "SELECT bd.booking_id, bd.booking_date, c.c_name, c.c_mobile, bd.e_name, bd.start_date, bd.end_date, bd.status
                FROM booking_details bd, customer c 
                WHERE bd.cust_id = c.c_id AND bd.booking_date BETWEEN '$sdate' AND '$edate' AND bd.status = 'Pending'";

    /* if ($booking_status == 'pending') {
        $run_bookings .= " AND status = Pending";
    } elseif ($booking_status == 'approved') {
        $run_bookings .= " AND status = Approved";
    } */

    $run_bookings .= " ORDER BY bd.booking_date";

    $run_bookings_result = mysqli_query($con, $run_bookings);
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <title>Booking Results</title>
        </head>
        <body>
            <div class="row">
                <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                    <div class="breadcrumb">
                        <li class="active">
                            <i class="fa fa-fw fa-calendar-check-o"></i>
                            Booking / Pending Booking / View Booking Results
                        </li>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                    <div class="panel panel-primary">
                        <div class="panel-heading corporate-heading">
                            <h3 class="panel-title"><i class="fa fa-clock-o"></i>
                                Pending Booking from <?php echo date("d M Y", strtotime($sdate)); ?>  to  <?php echo date("d M Y", strtotime($edate)); ?>
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
                                        <th class="text-center">Approve</th>
                                        <th class="text-center">Reject</th>
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
                                                    <span class="label"
                                                        style="background-color:#FFC107;color:#000;">
                                                        Pending
                                                    </span>

                                                </td>
                                                <!-- Approve TD -->
                                                <td class="text-center">
                                                    <a href="index.php?approve_pending=0&id=<?php echo $bk['booking_id']; ?>"
                                                    class="btn btn-xs"
                                                    style="background-color: #1E7E34;color: #ffffff;"
                                                    data-toggle="tooltip"
                                                    title="Approve Booking">
                                                        <i class="fa fa-check"></i> Approve
                                                    </a>
                                                </td>

                                                <!-- Reject TD -->
                                                <td class="text-center">
                                                    <a href="index.php?reject_pending=0&id=<?php echo $bk['booking_id']; ?>"
                                                    class="btn btn-xs reject-btn"
                                                    style="background-color:#C82333;color:#ffffff;"
                                                    data-id="<?php echo $bk['booking_id']; ?>"
                                                    data-toggle="tooltip"
                                                    title="Reject Booking">
                                                        <i class="fa fa-times"></i> Reject
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
                        <a href="index.php?pending" class="btn" style="background-color: #7A1E3A; color: #ffffffff; font-size: 14px; font-weight: bold;">
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

<script>
$(document).on("click", ".reject-btn", function(e){
    e.preventDefault();

    let bookingId = $(this).data("id");
    let url = "index.php?reject_pending=0&id=" + bookingId;

    Swal.fire({
        title: 'Are you sure?',
        text: "This booking will be rejected!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C82333',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
});
</script>
