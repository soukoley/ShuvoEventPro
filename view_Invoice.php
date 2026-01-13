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
	<title>View Invoices</title>
</head>
<body>
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-file-text-o"></i>
				Invoice / View Invoices
			</li>
		</div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="panel panel-primary">
			<div class="panel-heading corporate-heading">
				<h3 class="panel-title"><i class="fa fa-eye"></i>
					Date Range for Invoices
				</h3>
			</div>
			<div class="panel-body" style="padding-top: 20px;">
				<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
					<div class="form-group">
						<label class="col-md-3 control-label">Start Date :</label>
						<div class="col-md-6">
							<input type="date" name="sdate"  class="form-control" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">End Date :</label>
						<div class="col-md-6">
							<input type="date" name="edate" value="<?php $currentDate = date('Y-m-d'); echo $currentDate; ?>" class="form-control" required="">
						</div>
					</div>
													
					<div class="form-group">
						<div class="col-md-3">
						</div>
						<div class="col-md-6">
							<input type="submit" name="submit" value="Submit" class="btn btn-primary form-control" >
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

	if(isset($_POST['submit'])){
		$sdate 			= $_POST['sdate'];
		$edate 			= $_POST['edate'];
		$_SESSION['sdate']			= $sdate;
		$_SESSION['edate']			= $edate;
		
		echo"<script>window.open('index.php?invoice_list','_self')</script>";
		
	}

} ?>