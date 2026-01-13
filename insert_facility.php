<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>New Facility</title>
	 
  <script>tinymce.init({selector:'textarea'});</script>
</head>
<body>

<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-calendar"></i>
				Facility / New Facility
			</li>
		</div>
	</div>
</div>

		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="panel panel-primary">
					<div class="panel-heading corporate-heading">
						<h3 class="panel-title">
							New&nbsp;&nbsp;Facility
						</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">Facility Name :</label>
								<div class="col-md-6">
									<input type="text" name="facility" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Price :</label>
								<div class="col-md-6">
									<input type="number" name="price" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Select Event :</label>
								<div class="col-md-6">
									<select name="event" class="form-control" required="">
										<option value="ALL">ALL</option>
										<?php 
										$get_com="select * from event order by e_name";
										$run_com=mysqli_query($con,$get_com);
										while ($row=mysqli_fetch_array($run_com)) {
											$id=$row['id'];
											$c_name=$row['e_name'];
											echo "<option value='$c_name'> $c_name </option>";
										}
										?>
										
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Maximum People :</label>
								<div class="col-md-6">
									<select name="max_people" class="form-control" required="">
										<option>---Select Maximum People---</option>
										<?php 
										$get_com1="select * from people order by id";
										$run_com1=mysqli_query($con,$get_com1);
										while ($row1=mysqli_fetch_array($run_com1)) {
											$id=$row1['id'];
											$max_people=$row1['max_people'];
											echo "<option value='$max_people'> $max_people </option>";
										}
										?>
										
									</select>
								</div>
							</div>
							
							
												
							<div class="form-group">
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<input type="submit" name="submit" value="Add Facility" class="btn btn-primary form-control" >
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

    $facility   = $_POST['facility'];
    $price      = $_POST['price'];
    $event      = $_POST['event'];
    $max_people = $_POST['max_people'];

    $insert_product = "INSERT INTO facility (fName, fPrice, eName, max_people) 
                       VALUES ('$facility', '$price', '$event', '$max_people')";

    $run_product = mysqli_query($con, $insert_product);

    if($run_product){
        echo "<script>alert('Facility Added Successfully')</script>";
        echo "<script>window.open('index.php?facility','_self')</script>";
    }
}
?>

<?php } ?>