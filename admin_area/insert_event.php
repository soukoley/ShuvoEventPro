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
	<title>New Event</title>
</head>
<body>
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-calendar"></i>
				Event / New Event
			</li>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="panel panel-primary">
			<div class="panel-heading corporate-heading">
				<h3 class="panel-title">
					New&nbsp;&nbsp;Event
				</h3>
			</div>
			<div class="panel-body" style="padding-top: 20px;">
				<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
					<div class="form-group">
						<label class="col-md-3 control-label">Event Name :</label>
						<div class="col-md-6">
							<input type="text" name="event" class="form-control" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Event Details :</label>
						<div class="col-md-6">
							<input type="text" name="event_det" class="form-control" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Minimum Price :</label>
						<div class="col-md-6">
							<input type="number" name="price" class="form-control" required="">
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
							<input type="submit" name="submit" value="Add New Event" class="btn btn-primary form-control" >
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-3">
		
	</div>
</div>



</body>

</html>

<?php 
if(isset($_POST['submit'])){
	$event=$_POST['event'];
	$event_det=$_POST['event_det'];
	$price=$_POST['price'];	
	
	$e_image=$_FILES['e_image']['name'];
	$temp_name1=$_FILES['e_image']['tmp_name'];

	move_uploaded_file($temp_name1, "event_category/$e_image");
	$query="select * from event where e_name='$event'";
	$result = mysqli_query($con, $query);
    if(mysqli_num_rows($result) > 0){
		echo"<script>alert('Event Already Exist')</script>";
		echo"<script>window.open('index.php?insert_event','_self')</script>";
	}else{
		$insert_product="insert into event(e_name,e_desc,e_start_price,e_cat_img) values('$event','$event_det','$price','$e_image')";
		//echo $insert_product;
		$run_product=mysqli_query($con,$insert_product);
		if($run_product){
			
			echo"<script>alert('Event Added Successfully')</script>";
			echo"<script>window.open('index.php?view_event','_self')</script>";
			
		}
	}
}
?>
<?php } ?>