<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Video
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				<i class="fa fa-money fa-fw"></i>View Video
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Event Name</th>
								<th>Video Details</th>
								<th>Video URL</th>
								<th>Watch Video</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=0;
							$get_product="select * from event_gallery_video ORDER BY event_id";
							$run_p=mysqli_query($con,$get_product);
							while($row=mysqli_fetch_array($run_p)){
								$id=$row['id'];
								$event_id=$row['event_id'];
								$title=$row['title'];
									$get_event="select * from event WHERE id=$event_id";
									$run_e=mysqli_query($con,$get_event);
									while($row1=mysqli_fetch_array($run_e)){
										$e_name=$row1['e_name'];
									}
								
								$youtube_url=$row['youtube_url'];
								$i++							

							?>
						
							<tr>
								<td><?php echo $i ?></td>
								<td><?php echo $e_name ?></td>
								<td><?php echo $title ?></td>
								<td><?php echo $youtube_url ?></td>
								<td>
									<a href="<?php echo htmlspecialchars($youtube_url); ?>"<i class="fa fa-pencil" style="font-size:16px; font-weight: bold; color:green;" target="_blank">
										Watch Video
									</a>
								</td>
								
								<td>
									<a href="index.php?delete_video=<?php echo $id ?>"><i class="fa fa-trash-o" style="font-size:16px; font-weight: bold; color:red;"> Delete</i></a>
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