<?php
session_start();
include ("includes/db.php");
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{
?>
<?php 
if(isset($_GET['invoice_no'])){
	$invoice_no=$_GET['invoice_no'];
}
$get_order="select * from customer_order where invoice_no='$invoice_no'";
$run_order=mysqli_query($con,$get_order);
while($row=mysqli_fetch_array($run_order)){
	
	$product_id=$row['product_id'];
	$qty=$row['qty'];
	
	$get_products="select * from products where product_id='$product_id'";
	$run_products=mysqli_query($con,$get_products);
	$row_products=mysqli_fetch_array($run_products);
	$cat_id = $row_products['cat_id'];
	$value=$row_products['value'];
	
	$query="select * from stock where cat_id='$cat_id'";
	$run_query=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($run_query)){
		$stock_id=$row['stock_id'];
		$o_qty=$row['qty'];
	}
	$fqty=$o_qty-($qty*$value);
	$update_stock="update stock set qty='$fqty' where stock_id='$stock_id'";
		$run_update_stock=mysqli_query($con,$update_stock);
		if($run_update_stock){
			
		}
}
$update_order="update customer_order set order_status='Ready for Delivery' where invoice_no='$invoice_no'";
$run_update=mysqli_query($con,$update_order);
if($run_update){
	echo"<script>alert('Updated Status Successfully')</script>";
	echo"<script>window.open('index.php?invoice_no=$invoice_no','_self')</script>";
}
?>















<?php 

}
?>