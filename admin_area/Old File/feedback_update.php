<?php 
//session_start();
include ("includes/db.php");
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

 ?>
 <?php 
	 if(isset($_GET['feedback_update'])){
	 	$feedback_update_id=$_GET['feedback_update'];
	 	$get_feedback="select * from feedback where id='$feedback_update_id'";
	 	$run_edit=mysqli_query($con,$get_feedback);
	 	$row_edit=mysqli_fetch_array($run_edit);
	 	$customer_name=$row_edit['customer_name'];
	 	$customer_contact=$row_edit['customer_contact'];
	 	$msg=$row_edit['msg'];
	 	$date=$row_edit['date'];
	 	$remarks=$row_edit['remarks'];
	 }

 


 ?>

  <!DOCTYPE html>
<html>
<head>
	<title>Modify Remarks on Feedback</title>
	 
  <script>tinymce.init({selector:'textarea'});</script>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>
				Dashboard / Edit Feedback
			</li>
		</ol>
	</div>
</div>
		<div class="row">
			
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<i class="fa a-money fa-w"></i> Edit Feedback
						</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">Customer Name</label>
								<div class="col-md-6">
									<input type="text" name="c_name" class="form-control" value="<?php echo $customer_name; ?>" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Customar Contact</label>
								<div class="col-md-6">
									<input type="number" name="c_number" class="form-control" value="<?php echo $customer_contact; ?>" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Message</label>
								<div class="col-md-6">
									<input type="text" name="msg" class="form-control" value="<?php echo $msg; ?>" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Date</label>
								<div class="col-md-6">
									<input type="text" name="date" class="form-control"  value="<?php echo $date; ?>" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Remarks</label>
								<div class="col-md-6">
									<input type="text" name="remarks" class="form-control" required="" value="<?php echo $remarks; ?>">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-3 control-label"></label>
								<div class="col-md-6">
									<input type="submit" name="update" value="Update Feedback" class="btn btn-primary form-control">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	



</body>

</html>

<?php 
if(isset($_POST['update'])){
	$new_remarks=$_POST['remarks'];

	$update_feedback="update feedback set remarks='$new_remarks' where id='$feedback_update_id'";
	
	$run_update=mysqli_query($con,$update_feedback);
	if($run_update){
		echo"<script>alert('Updated Remarks Successfully')</script>";
		echo"<script>window.open('index.php?view_feedback','_self')</script>";
	}
}
?>


 <?php } ?>