<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / Lottery
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-money fa-fw"></i>Lottery for January 2021
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped"> 
					
						<thead>
							<tr >
								<th>Customer Name</th>
								<th>Address</th>
								<th>Landmark</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=0;
							$get_cats="SELECT * FROM `lottery` ORDER BY RAND() LIMIT 1";
							//$get_cats="SELECT * FROM `lottery` WHERE customer_id='10'";
							$run_cats=mysqli_query($con,$get_cats);
							while ($row_cats=mysqli_fetch_array($run_cats)) {
								$customer_name=$row_cats['customer_name'];
								$customer_vill=$row_cats['customer_vill'];
								$customer_po=$row_cats['customer_po'];
								

								?>
								<tr style="font-size:28px; color:red; bold;">
									<td><?php echo $customer_name; ?></td>
									<td><?php echo $customer_vill; ?></td>
									<td><?php echo $customer_po; ?></td>
									
								</tr>
								
								
								
						<?php 	} ?>

						</tbody>
						<div >
						<br><br> <h2 style="text-align:center; color:red;"> Congratulations!!! </h2><h1 style="text-align:center; color:green;" >"<?php echo $customer_name;?> "</h1><h2 style="text-align:center; color:blue;"> You Won 5kg Aashirbad Atta.</h2>
						</div>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="text-align:center; color:purple ">
<h2>Valo Mudikhana</h2>
</div>

<?php } ?>