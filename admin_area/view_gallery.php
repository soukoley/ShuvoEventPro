<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<!DOCTYPE html>
<html>
    <head>
        <title>View Gallery</title>
    </head>
    <body>
		<div class="row page-top-fix">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="breadcrumb">
					<li class="active">
						<i class="fa fa-fw fa-image"></i> Gallery / View Gallery
					</li>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="panel panel-primary">
					<div class="panel-heading corporate-heading">
						<h3 class="panel-title">
							<i class="fa fa-money fa-fw"></i> View Gallery
						</h3>
					</div>
					
					<div class="table-responsive" style="padding-top: 3px;">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Event Name</th>
									<th>Event Details</th>
									<th>Event Image</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i=0;
								$get_product="select * from event_gallery ORDER BY event_id";
								$run_p=mysqli_query($con,$get_product);
								while($row=mysqli_fetch_array($run_p)){
									$id=$row['id'];
									$event_id=$row['event_id'];
									$e_desc=$row['e_desc'];
										$get_event="select * from event WHERE id=$event_id";
										$run_e=mysqli_query($con,$get_event);
										while($row1=mysqli_fetch_array($run_e)){
											$e_name=$row1['e_name'];
										}
									
									$e_img=$row['e_img'];
									$i++;
								?>
								<tr>
									<td><?php echo $i ?></td>
									<td><?php echo $e_name ?></td>
									<td><?php echo $e_desc ?></td>
									<td><img src="event_gallery/<?php echo $e_img ?>" width="30" width="40"></td>
									<td>
										<a href="#" onclick="deleteGallery(<?php echo $id; ?>)">
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
	function deleteGallery(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: "This picture will be permanently deleted!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'Cancel'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = "index.php?delete_gallery=" + id;
			}
		});
	}
</script>

<?php } ?>