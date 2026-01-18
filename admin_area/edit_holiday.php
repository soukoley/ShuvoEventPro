<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{
 ?>
 <?php 
 if(isset($_GET['edit_holiday'])){
 	$e_id=$_GET['edit_holiday'];
 }
	?>
 
<!DOCTYPE html>
<html>
<head>
	<title>Edit Holiday</title>
</head>
<body>
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-cogs"></i> Admin Control / Manage Holidays / View Holidays / Edit Holiday
			</li>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="panel panel-default">
					<div class="panel-heading corporate-heading">
						<h3 class="panel-title"><i class="fa fa-edit"></i>
							Holiday&nbsp;&nbsp;Updation
						</h3>
					</div>
					<?php 
                        $get_holiday = "select * from holidays WHERE id='$e_id'";
                        $run_p = mysqli_query($con, $get_holiday);

                        while($row = mysqli_fetch_array($run_p)){                            
                            $hName = $row['h_name'];
                            $hDate = $row['h_date'];
                        ?>
					<div class="panel-body" style="padding-top: 20px;">
						<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">Holiday Name :</label>
								<div class="col-md-6">
									<input type="text" name="hname" class="form-control" required="" value="<?php echo $hName; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Date of Holiday :</label>
								<div class="col-md-6">
									<input type="date" name="hdate" class="form-control" required="" value="<?php echo $hDate; ?>">
								</div>
							</div>			
							<div class="form-group">
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<input type="submit" name="submit" value="Edit Holiday" class="btn btn-primary form-control" >
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
	$hName = $_POST['hname'];
    $hDate = $_POST['hdate'];
    $hDate = date("Y-m-d", strtotime($hDate));

	$stmt = $con->prepare(
        "UPDATE holidays 
        SET h_name = ?, h_date = ?
        WHERE id = ?"
    );

    $stmt->bind_param("ssi", $hName, $hDate, $e_id);

    if ($stmt->execute()) {
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Holiday updated successfully',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php?view_holidays';
            });
        </script>
        ";
    } else {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Holiday update failed',
                confirmButtonText: 'Try Again'
            }).then(() => {
                window.location.href = 'index.php?edit_holiday=$e_id';
            });
        </script>
        ";
    }


}
	
							}
?>
<?php } ?>