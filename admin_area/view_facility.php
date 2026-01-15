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
				<ol class="breadcrumb">
					<li class="active">
						<i class="fa fa-fw fa-hotel"></i> Facility / View Facility
					</li>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
				<div class="panel panel-primary">
					<div class="panel-heading corporate-heading">
						<h3 class="panel-title"><i class="fa fa-eye"></i>
							All&nbsp;&nbsp;Facility&nbsp;&nbsp;Details
						</h3>
					</div>
					<div class="table-responsive" style="padding-top: 3px;">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Facility Name</th>
									<th>Price</th>
									<th>Event Name</th>
									<th class="text-right">Maximum Guest</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i=0;
								$get_product=" SELECT * FROM facility ORDER BY fName ASC, eName ASC, max_people ASC";
								$run_p=mysqli_query($con,$get_product);
								while($row=mysqli_fetch_array($run_p)){
									$id=$row['id'];
									$fName=$row['fName'];
									$fPrice=$row['fPrice'];
									$eName=$row['eName'];
									$max_people=$row['max_people'];
									
									$i++							

								?>
							
								<tr>
									<td><?php echo $i ?></td>
									<td><?php echo $fName ?></td>
									<td><?php echo $fPrice ?></td>
									<td><?php echo $eName ?></td>
									<td class="text-right"><?php echo $max_people ?></td>
									<td class="text-center">
										<a href="index.php?delete_facility=<?php echo $id ?>"><i class="fa fa-trash-o" style="font-size:15px; font-weight: bold; color:red;"> Delete</i></a>
									</td>
								</tr>
								<?php } ?>
							</tbody>								
						</table>					
					</div>					
				</div>
			</div>
		</div>
	</body>
</html>







<?php } ?>