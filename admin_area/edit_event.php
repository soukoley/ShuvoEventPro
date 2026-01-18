<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{
 ?>
 <?php 
 if(isset($_GET['edit_event'])){
 	$e_id=$_GET['edit_event'];
 }
	?>
 
<!DOCTYPE html>
<html>
<head>
	<title>Edit Event</title>
</head>
<body>
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-calendar"></i> Event / Edit Event
			</li>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="panel panel-default">
					<div class="panel-heading corporate-heading">
						<h3 class="panel-title">
							Event&nbsp;&nbsp;Updation
						</h3>
					</div>
					<?php 
							$i=0;
							$get_product="select * from event WHERE id='$e_id'";
							$run_p=mysqli_query($con,$get_product);
							while($row=mysqli_fetch_array($run_p)){
								
								$name=$row['e_name'];
								$e_desc=$row['e_desc'];
								$e_start_price=$row['e_start_price'];
								$e_cat_img=$row['e_cat_img'];
															

							?>
					<div class="panel-body" style="padding-top: 20px;">
						<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">Event Name :</label>
								<div class="col-md-6">
									<input type="text" name="event" class="form-control" required="" value="<?php echo $name; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Event Details :</label>
								<div class="col-md-6">
									<input type="text" name="event_det" class="form-control" required="" value="<?php echo $e_desc; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Minimum Price :</label>
								<div class="col-md-6">
									<input type="number" name="price" class="form-control" required="" value="<?php echo $e_start_price; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Event Image :</label>
								<div class="col-md-6">
									<input type="file" name="e_image" class="form-control" required="" value="<?php echo $e_cat_img; ?>">
								</div>
							</div>
												
							<div class="form-group">
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<input type="submit" name="submit" value="Edit Event" class="btn btn-primary form-control" >
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
	$price=$_POST['price'];	
	
	$e_image=$_FILES['e_image']['name'];
	$temp_name1=$_FILES['e_image']['tmp_name'];

	move_uploaded_file($temp_name1, "event_category/$e_image");
	
		$update_event="update event set e_name='$event',e_desc='$event_det',e_start_price='$price',e_cat_img='$e_image' WHERE id='$e_id'";
		//echo $insert_product;
		$run_eventt=mysqli_query($con,$update_event);
		if($run_eventt){
			
			echo"<script>alert('Event update Successfully')</script>";
			echo"<script>window.open('index.php?view_event','_self')</script>";
			
		}else{
			echo"<script>alert('Event update Error')</script>";
			echo"<script>window.open('index.php?edit_event=<?php echo $e_id ?>','_self')</script>";
		}
}
	
							}
?>
<?php } ?>