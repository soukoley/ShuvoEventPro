<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<?php 
if(isset($_GET['invoice_no'])){
	$invoice_no=$_GET['invoice_no'];
}
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
<?php 
	
	$get_order="select * from customer_order where invoice_no='$invoice_no'";
	$run_order=mysqli_query($con,$get_order);
	while($row=mysqli_fetch_array($run_order)){
		$order_date=$row['order_date'];
		$received_date=$row['received_date'];
		$c_id=$row['customer_id'];
	}
	?>
	<?php 
		$get_customer="select * from customers where customer_id='$c_id'";
		$run_customer=mysqli_query($con,$get_customer);
		$row_customer=mysqli_fetch_array($run_customer);
		$customer_contact=$row_customer['customer_contact'];
		$customer_vill=$row_customer['customer_vill'];
		$customer_po=$row_customer['customer_po'];
		$customer_name=$row_customer['customer_name'];
	?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				<i class="fa fa-money fa-fw"></i>View Delivered Orders                  
				</h3>
				<hr>
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;"><b> Invoice : </b><?php echo $invoice_no; ?> || <b>Order Date : </b><?php echo $order_date; ?> || <b>Received Date : </b><?php echo $received_date; ?> </h4>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>Sl.</th>
								<th>Product</th>
								<th>Product Title</th>
								<th>M.R.P</th>
								<th>Price</th>
								<th>Qty</th>
								<th>Sub Total</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$i=0;
								$total=0;
								$get_order="select * from customer_order where invoice_no='$invoice_no'";
								$run_order=mysqli_query($con,$get_order);
								while($row=mysqli_fetch_array($run_order)){
									$order_id=$row['order_id'];
									$c_id=$row['customer_id'];
									$product_id=$row['product_id'];
									$qty=$row['qty'];
									$due_amount=$row['due_amount'];
									$mrp=$row['mrp'];
									$order_status=$row['order_status'];
									$price=$due_amount/$qty;
									$get_products="select * from products where product_id='$product_id'";
									$run_products=mysqli_query($con,$get_products);
									$row_products=mysqli_fetch_array($run_products);
									$product_title = $row_products['product_title'];
									$product_img1=$row_products['product_img1'];

									$total += $due_amount;
									$i++							

								?>
							<tr>
								<td><?php echo $i ?></td>
								 <td><img src="../admin_area/product_images/<?php echo $product_img1 ?>"></td>
								<td><?php echo $product_title; ?></td>
								<td><?php echo $mrp; ?></td>
								<td><?php echo $price; ?></td>
								<td><?php echo $qty; ?></td>
								<td><?php echo $due_amount; ?></td>
								<td><?php echo $order_status; ?>
								</td>
								
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<?php 
								$shipping=0;
								if($total<500){
									$shipping=15;
									$total=$total+$shipping;
								}
								$f_total=round($total,0);
								$rd=$f_total-$total;
								$total=$f_total;
							?>
							<tr>
								<th colspan="4"></th>
								<th colspan="2" align="right">Shipping Charge :  </th>
								
								<th colspan="1"><i class='fa fa-rupee'></i> <?php echo $shipping; ?></th>
								<th colspan="2"></th>
							</tr>
							<tr>
								<th colspan="3"></th>
								<th colspan="1" align="right">Round off : </th>
								
								<th colspan="1" align="left"><i class='fa fa-rupee'></i> <?php echo $rd; ?>&nbsp;,</th>
								<th colspan="1" align="right">Total : </th>
								
								<th colspan="1"><i class='fa fa-rupee'></i> <?php echo $total; ?></th>
								<th colspan="2"></th>
							</tr>
							<tr>
								<th colspan="2"><?php echo $customer_contact; ?></th>
								<th colspan="1"><?php echo $customer_vill; ?></th>
								<th colspan="1"><?php echo $customer_po; ?></th>
								<th colspan="2"></th>
								<th> 
									<?php 
									if($order_status=="Ready for Delivery"){
										echo "<a href='index.php?inv_no=$invoice_no' class='btn btn-success btn-sm'><i class='fa fa-shopping-cart'></i> Delivered</a>";
									
									}elseif($order_status=="pending"){
									
										echo "<a href='readyfordelivery.php?invoice_no=$invoice_no' class='btn btn-success btn-sm'><i class='fa fa-shopping-cart'></i> Ready for Delivery</a>";
									 }else{

									 } ?>

									

								</th>
								<th> 
									<a href='printinvoice.php?invoice_no=<?php echo $invoice_no ?>' class='btn btn-primary btn-sm'><i class='fa fa-shopping-cart'></i> Print Invoice</a>
								</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>