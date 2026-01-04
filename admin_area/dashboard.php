<?php
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
	exit;
}
?>

<!-- ================= DASHBOARD HEADER ================= -->
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<!-- <h1 class="page-header">Dashboard</h1> -->
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i> Dashboard
			</li>
		</ol>
	</div>
</div>

<!-- ================= EVENT TYPE COUNTS ================= -->
<?php

$get_event_types = "
SELECT et.id, et.e_name, COUNT(bd.id) AS total
FROM event et
LEFT JOIN booking_details bd ON bd.e_name = et.e_name
GROUP BY et.e_name
";
$run_event_types = mysqli_query($con, $get_event_types);
?>

<div class="row">
<?php while($et = mysqli_fetch_assoc($run_event_types)){ ?>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 text-right">
						<div class="huge"><?php echo $et['total']; ?></div>
						<div><?php echo $et['e_name']; ?></div>
					</div>
				</div>
			</div>
			<a href="index.php?view_event_details&type=<?php echo $et['e_name']; ?>">
				<div class="panel-footer" style="background-color: #D4A017; color: white;">
					<span class="pull-left" style="font-size: 14px; font-weight: bold;">View Details</span>
					<span class="pull-right" style="font-size: 16px; font-weight: bold;">
						<i class="fa fa-arrow-circle-right"></i>
					</span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
<?php } ?>
</div>

<!-- ================= BOOKING HISTORY ================= -->
<?php

$get_bookings = "SELECT bd.booking_id, bd.booking_date, c.c_name, c.c_mobile, bd.e_name, bd.start_date, bd.end_date, bd.status
				FROM booking_details bd, customer c 
				WHERE bd.cust_id = c.c_id AND bd.booking_date = CURDATE() 
				ORDER BY bd.booking_date DESC";
$run_bookings = mysqli_query($con, $get_bookings);
?>

<div class="row">
  <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-calendar"></i> Today's Booking History
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
	</div>
</div>
