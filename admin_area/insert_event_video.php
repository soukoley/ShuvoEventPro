<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>New Event Gallery</title>
	 
  <script>tinymce.init({selector:'textarea'});</script>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>
				Dashboard / New Event Gallery Video
			</li>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-title">
						<h3>
							<i class="fa a-money fa-w"></i> New Event Gallery Video
						</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">Select Event :</label>
								<div class="col-md-6">
									<select name="event" class="form-control" required="">
										<option>---Select Event---</option>
										<?php 
										$get_com="select * from event order by e_name";
										$run_com=mysqli_query($con,$get_com);
										while ($row=mysqli_fetch_array($run_com)) {
											$id=$row['id'];
											$c_name=$row['e_name'];
											echo "<option value='$id'> $c_name </option>";
										}
										?>
										
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Video Details :</label>
								<div class="col-md-6">
									<input type="text" name="v_details" class="form-control">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-3 control-label">Video Link :</label>
								<div class="col-md-6">
									<input type="text" name="v_link" class="form-control" required="">
								</div>
							</div>
												
							<div class="form-group">
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<input type="submit" name="submit" value="Add Video" class="btn btn-primary form-control" >
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				
			</div>
		</div>
	</div>
</div>



</body>

</html>

<?php 
if(isset($_POST['submit'])){
	$event=$_POST['event'];
	$v_details=$_POST['v_details'];
	$v_link=$_POST['v_link'];
		
	

		$insert_video="insert into event_gallery_video(event_id,title,youtube_url) values('$event','$v_details','$v_link')";
		//echo $insert_product;
		$run_video=mysqli_query($con,$insert_video);
		if($run_video){
			
			echo"<script>alert('Video Added Successfully')</script>";
			echo"<script>window.open('index.php?insert_event_video','_self')</script>";
			
		}
	
}
?>
<?php } ?>