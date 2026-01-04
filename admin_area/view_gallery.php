<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Event
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				<i class="fa fa-money fa-fw"></i>View Event
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
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
								$i++							

							?>
						
							<tr>
								<td><?php echo $i ?></td>
								<td><?php echo $e_name ?></td>
								<td><?php echo $e_desc ?></td>
								<td><img src="event_gallery/<?php echo $e_img ?>" width="30" width="40"></td>
								
								
								<td>
									<a href="index.php?delete_gallery=<?php echo $id ?>"><i class="fa fa-trash-o"></i> Delete</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
						
					</table>
					
				</div>
				
			</div>
			
		</div>
	</div>
</div>







<?php } ?>