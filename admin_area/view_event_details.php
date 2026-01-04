<?php
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
	exit;
} else {
    if(isset($_GET['view_event_details'], $_GET['type'])){
		$event_name = $_GET['type'];
	} else {
		echo "<script>window.open('index.php?dashboard','_self')</script>";
		exit();
	}
?>

<!-- ================= DASHBOARD HEADER ================= -->
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i> Dashboard / View Event Details
			</li>
		</ol>
	</div>
</div>

<!-- ================= BOOKING HISTORY ================= -->
<?php
$get_bookings = "SELECT bd.booking_id, bd.booking_date, c.c_name, c.c_mobile, bd.e_name, bd.start_date, bd.end_date, bd.status
				FROM booking_details bd, customer c 
				WHERE bd.cust_id = c.c_id and bd.e_name = '$event_name'
				ORDER BY bd.booking_date DESC";
$run_bookings = mysqli_query($con, $get_bookings);
?>

<div class="row">
  <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-calendar"></i> Booking History
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
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>

					<?php
					if(mysqli_num_rows($run_bookings) > 0){
						$i = 1;
						while($bk = mysqli_fetch_assoc($run_bookings)){
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
									<?php if($bk['status'] == "Pending"){ ?>
										<span class="label label-warning">Pending</span>
									<?php } else { ?>
										<span class="label label-success">Confirmed</span>
									<?php } ?>
								</td>
								<td class="text-center">
									<?php if($bk['status'] == "Pending"){ ?>
										<a href="confirm_booking.php?id=<?php echo $bk['id']; ?>"
											class="btn btn-success btn-xs"
											style="background-color: #7A1E3A; color: #ffffffff;"
											onclick="return confirm('Confirm this booking?');">
											<i class="fa fa-edit"></i> Confirm
										</a>
									<?php } else { ?>
										<span class="text-muted">—</span>
									<?php } ?>
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
            <a href="index.php?dashboard" class="btn" style="background-color: #7A1E3A; color: #ffffffff; font-size: 14px; font-weight: bold;">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
	</div>
</div>
<?php } ?>