<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Orders
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				<i class="fa fa-money fa-fw"></i>View Ready Orders
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>Sl No</th>
								<th>Customer</th>
								<th>Address</th>
								<th>Invoice</th>
								<th>Order Date</th>
								<th>Amount</th>
								<th>Shipping</th>
								<th>Order Status</th>
								<th>Order Details</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=0;
							//$get_order="select order_id, customer_id, invoice_no, order_date, SUM(due_amount) AS due_amount1, order_status from customer_order where order_status='Ready for Delivery'  group by invoice_no order by order_date ";
							$get_order="select order_id, customer_id, invoice_no, order_date, due_amount, order_status from customer_order where order_status='Ready for Delivery' order by order_date ";
							$run_order=mysqli_query($con,$get_order);
							while($row=mysqli_fetch_array($run_order)){
								$order_id=$row['order_id'];
								$c_id=$row['customer_id'];
								$invoice_no=$row['invoice_no'];
								$order_date=$row['order_date'];
								$due_amount=$row['due_amount'];
								$order_status=$row['order_status'];
								$i++				

							?>
							
							<tr>
								<td><?php echo $i ?></td>
								<td>
									<?php 
									if($due_amount<500){
										$Shipping=15;
									}else{
										$Shipping=0;
									}	
									$get_customer="select * from customers where customer_id='$c_id'";
									$run_customer=mysqli_query($con,$get_customer);
									$row_customer=mysqli_fetch_array($run_customer);
									$customer_contact=$row_customer['customer_contact'];
									$customer_vill=$row_customer['customer_vill'];
									echo $customer_contact;
									 ?>
								</td>
								<td><?php echo $customer_vill; ?></td>
								<td ><?php echo $invoice_no; ?></td>
								<td><?php echo $order_date; ?></td>
								<td><i class="fa fa-rupee"> </i> <?php echo $due_amount; ?></td>
								<td><i class="fa fa-rupee"> </i> <?php echo $Shipping; ?></td>
								<td><?php echo $order_status; ?></td>
								<td><a href="index.php?invoice_no=<?php echo $invoice_no ?>" target="_blank" class="btn btn-primary btn-sm">View Details</a></td>
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