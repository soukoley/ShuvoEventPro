<?php 
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
		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="breadcrumb">
					<li class="active">
						<i class="fa fa-fw fa-image"></i> Gallery / View Video
					</li>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="panel panel-primary">
					<div class="panel-heading corporate-heading">
						<h3 class="panel-title">
							<i class="fa fa-play-circle"></i> View Video
						</h3>
					</div>
					
					<div class="table-responsive" style="padding-top: 3px;">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Event Name</th>
									<th>Video Details</th>
									<!-- <th>Video URL</th> -->
									<th class="text-center">Watch Video</th>
									<th class="text-center">Remove</th>
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
									<!-- <td><?php echo $youtube_url ?></td> -->
									<td class="text-center">
										<a href="<?php echo htmlspecialchars($youtube_url); ?>"<i class="fa fa-play-circle" style="font-size:16px; font-weight: bold; color:green;" target="_blank">
											Watch
										</a>
									</td>

									<td class="text-center">
										<a href="index.php?delete_video=<?php echo $id ?>"><i class="fa fa-trash-o" style="font-size:16px; font-weight: bold; color:red;"> Remove</i></a>
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
</div>
</body>
</html>

<?php } ?>