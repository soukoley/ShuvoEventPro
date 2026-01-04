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
				Dashboard / New Event Gallery
			</li>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-title">
						<h3>
							<i class="fa a-money fa-w"></i> New Event Gallery
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
								<label class="col-md-3 control-label">Image Details :</label>
								<div class="col-md-6">
									<input type="text" name="event_det" class="form-control">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-3 control-label">Event Image :</label>
								<div class="col-md-6">
									<input type="file" name="e_image" class="form-control" required="">
								</div>
							</div>
												
							<div class="form-group">
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<input type="submit" name="submit" value="Add Image" class="btn btn-primary form-control" >
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
	$event_det=$_POST['event_det'];
		
	$e_image=$_FILES['e_image']['name'];
	$temp_name1=$_FILES['e_image']['tmp_name'];

	move_uploaded_file($temp_name1, "event_gallery/$e_image");

		$insert_product="insert into event_gallery(event_id,e_desc,e_img) values('$event','$event_det','$e_image')";
		//echo $insert_product;
		$run_product=mysqli_query($con,$insert_product);
		if($run_product){
			
			echo"<script>alert('Image Added Successfully')</script>";
			echo"<script>window.open('index.php?insert_event_gallery','_self')</script>";
			
		}
	
}
?>
<?php } ?>