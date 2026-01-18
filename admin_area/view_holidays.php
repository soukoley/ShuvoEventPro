<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>

<!DOCTYPE html>
<html>
    <head>
        <title>View Holidadys</title>
    </head>
    <body>
		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="breadcrumb">
					<li class="active">
						<i class="fa fa-fw fa-cogs"></i> Admin Control / Manage Holidays / View Holidays
					</li>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="panel panel-primary">
					<div class="panel-heading corporate-heading">
						<h3 class="panel-title"><i class="fa fa-eye"></i>
							All&nbsp;&nbsp;Holiday&nbsp;&nbsp;Details
						</h3>
					</div>
					<div class="table-responsive" style="padding-top: 3px;">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Holiday Name</th>
									<th class="text-center">Holiday Date</th>
									<th class="text-center">Created At</th>
									<th class="text-center">Edit</th>
									<th class="text-center">Remove</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 0;
								$get_holidays = " SELECT * FROM holidays ORDER BY h_date ASC";
								$run_p = mysqli_query($con,$get_holidays);
								while($row = mysqli_fetch_array($run_p)){
									$id			= $row['id'];
									$hName		= $row['h_name'];
									$hDate		= $row['h_date'];
									$createdAt  = $row['created_at'];
									$i++;						

								?>
							
								<tr>
									<td><?php echo $i ?></td>
									<td class="text-center"><?php echo $hName ?></td>
									<td class="text-center"><?php echo $hDate ?></td>
									<td class="text-center"><?php echo $createdAt ?></td>
									<td class="text-center">
										<a href="index.php?edit_holiday=<?php echo $id; ?>">
											<i class="fa fa-pencil" style="font-size:16px; font-weight: bold; color:green;"> Edit</i>
										</a>
									</td>
									<td class="text-center">
										<a href="#" onclick="deleteHoliday(<?php echo $id; ?>)">
											<i class="fa fa-trash-o" style="color:red; font-size:15px; font-weight: bold;"> Delete</i>
										</a>
									</td>
								</tr>
								<?php } ?>
							</tbody>								
						</table>					
					</div>					
				</div>
			</div>
		</div>
	</body>
</html>

<script>
function deleteHoliday(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This holiday will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php?delete_holiday=" + id;
        }
    });
}
</script>

<?php } ?>