<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('./includes/db.php'); // or whatever your connection file is

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login.php','_self')</script>";
    exit;
} else {

    if (!isset($_GET['id'])) {
        echo "<script>alert('Invalid Booking'); window.open('index.php?view_booking','_self');</script>";
        exit;
    }

    $booking_id = $_GET['id'];

    /* ================= FETCH BOOKING DETAILS ================= */
    $sql = "
    SELECT 
        bd.booking_id,
        bd.e_name,
        bd.max_guest,
        bd.start_date,
        bd.start_time,
        bd.end_date,
        bd.end_time,
        bd.status,

        c.c_name,
        c.c_addr,
        c.c_mobile

    FROM booking_details bd
    JOIN customer c ON bd.cust_id = c.c_id
    WHERE bd.booking_id = '$booking_id'
    ";

    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('Booking not found'); window.open('index.php?view_booking','_self');</script>";
        exit;
    }

    $data = mysqli_fetch_assoc($result);

    /* ================= FETCH FACILITIES ================= */
    $facilities = [];
    $fac_sql = "
    SELECT 
        f.fName,
        bf.qty,
        bf.rate,
        bf.tot_amt
    FROM booking_facilities bf
    JOIN facility f ON bf.facility_id = f.id
    WHERE bf.booking_id = '$booking_id'
    ";

    $fac_res = mysqli_query($con, $fac_sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Booking</title>
</head>
<body>

<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-calendar-check-o"></i>
				Booking / View Booking / Confirm Booking
			</li>
		</div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="panel panel-primary">
			<div class="panel-heading corporate-heading">
				<h3 class="panel-title">
					Customer&nbsp;&nbsp;Booking&nbsp;&nbsp;Details
				</h3>
			</div>
			<div class="panel-body" style="padding-top: 20px;">

                <!-- Event -->
                <h4><strong>Event Name :</strong> <?php echo $data['e_name']; ?></h4>
                <hr>
                    <!-- Customer -->
                    <h4>Customer Details</h4>
                    <p>
                        <strong>Name :</strong> <?php echo $data['c_name']; ?><br>
                        <strong>Address :</strong> <?php echo $data['c_addr']; ?><br>
                        <strong>Contact :</strong> <?php echo $data['c_mobile']; ?>
                    </p>
                <hr>

                <!-- Guest -->
                <p><strong>Maximum Guest :</strong> <?php echo $data['max_guest']; ?></p>

                <!-- Date Time -->
                <p>
                    <strong>Check-In :</strong>
                    <?php echo date("d M Y", strtotime($data['start_date'])) . " " . $data['start_time']; ?>
                    <br>
                    <strong>Check-Out :</strong>
                    <?php echo date("d M Y", strtotime($data['end_date'])) . " " . $data['end_time']; ?>
                </p>

                <!-- Facilities -->
                <hr>
                <h4><strong>List of Facilities</strong></h4>
                <hr>

                <?php if (mysqli_num_rows($fac_res) > 0) { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-left">Facility Name</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Rate</th>
                                    <th class="text-right">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $grand_total = 0;
                                while ($fac = mysqli_fetch_array($fac_res)) { 
                                    $grand_total += $fac['tot_amt'];
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td class="text-left"><?php echo htmlspecialchars($fac['fName']); ?></td>
                                    <td class="text-center"><?php echo $fac['qty']; ?></td>
                                    <td class="text-right"><?php echo number_format($fac['rate'], 2); ?></td>
                                    <td class="text-right"><?php echo number_format($fac['tot_amt'], 2); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Grand Total</th>
                                    <th class="text-right">
                                        <?php echo number_format($grand_total, 2); ?>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="text-muted">No facilities selected.</p>
                <?php } ?>

                <hr>

                <!-- Action -->
                <?php if ($data['status'] == 'Pending') { ?>
                    <form method="post" action="confirm_booking_action.php">
                        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                        <button type="submit" class="btn btn-success"
                                onclick="return confirm('Confirm this booking?');">
                            <i class="fa fa-check"></i> Confirm Booking
                        </button>
                        <a href="index.php?view_booking" class="btn btn-default">Cancel</a>
                    </form>
                <?php } else { ?>
                    <span class="label label-success">Already Confirmed</span>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
}
?>