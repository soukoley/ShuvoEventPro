<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Customers
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-money fa-fw"></i>View Customers
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Mobile Number</th>
								<th>Village</th>
								<th>Post Office</th>
								<th>PIN Number</th>
								<th>Landmark</th>
								<th>Order Summery</th>
							</tr>
						</thead>
						<tbody>
						<?php 

							$i=0;
							$get_c="select * from customers";
							$run_c=mysqli_query($con,$get_c);
							while($row_cust=mysqli_fetch_array($run_c)){
								$customer_id=$row_cust['customer_id'];
								$customer_name=$row_cust['customer_name'];
								$customer_contact=$row_cust['customer_contact'];
								$customer_vill=$row_cust['customer_vill'];
								$customer_po=$row_cust['customer_po'];
								$customer_pin=$row_cust['customer_pin'];
								$customer_lmark=$row_cust['customer_lmark'];
								$i++;
						?>
							<tr>
								<td><?php echo $customer_id; ?></td>
								<td><?php echo $customer_name; ?></td>
								<td><?php echo $customer_contact; ?></td>
								<td><?php echo $customer_vill; ?></td>
								<td><?php echo $customer_po; ?></td>
								<td><?php echo $customer_pin; ?></td>
								<td><?php echo $customer_lmark; ?></td>
								<td>
									<!--a href="index.php?customer_delete=<?php echo  $customer_id; ?>">
										<i class="fa fa-trash-o"></i> Delete
									</a-->
									<a href="index.php?customer_order=<?php echo  $customer_id; ?>" class="btn btn-primary btn-sm">
										<i class="fa fa-shoping-cart"></i> Details
									</a>
								</td>
							</tr>
						<?php } ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>



<?php } ?>