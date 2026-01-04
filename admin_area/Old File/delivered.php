<?php

if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{
include ("includes/db.php");
include ("functions/functions.php");
if(isset($_GET['inv_no'])){
	$invoice_no=$_GET['inv_no'];
	$admin=$_SESSION['admin_email'];
	$admin_name="select admin_name from admins where admin_email='$admin'";
	$run_admin=mysqli_query($con,$admin_name);
	$row_admin=mysqli_fetch_array($run_admin);
	$name=$row_admin['admin_name'];

	


	$get_order="select customer_id, SUM(due_amount) AS amount from customer_order where invoice_no='$invoice_no'";
	$run_order=mysqli_query($con,$get_order);
	$row=mysqli_fetch_array($run_order);
	$c_id=$row['customer_id'];
	$amount=$row['amount'];
	if($amount<500){
		$amount=$amount+15;
	}
	$amount=round($amount,0);
}
?>

		
			<div class="box">
				<h1 align="center">Please confirm your payment</h1>
				<form action="" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Invoice Number</label>
						<input type="text" class="form-control" name="invoice_number" value="<?php echo $invoice_no ; ?>" disabled>
					</div>
					<div class="form-group">
						<label>Amount</label>
						<input type="text" class="form-control" name="amount" value="<?php echo $amount ; ?>" disabled>
					</div>
					<div class="form-group">
						<label>Select Payment Mode</label>
						<select class="form-control" name="payment_mode">
							<option value="Cash On Delivery">Cash On Delivery</option>
							<option value="Bank Transfer">Bank Transfer</option>
							<option value="Paypal">Paypal</option>
							<option value="PayuMoney">PayuMoney</option>
							<option value="PayTM">PayTM</option>
							<option value="Google Pay">Google Pay</option>
						</select>
					</div>
					<div class="form-group">
						<label>Transection Number</label>
						<input type="text" class="form-control" name="trfr_number" required="">
					</div>
					<div class="form-group">
						<label>Payment Date</label>
						<input type="date" class="form-control" name="date" required="">
					</div>
					<div class="text-center">
						<button type="submit" name="confirm_payment" class="btn btn-primary btn-lg">
							Confirm Payment
						</button>
					</div>
				</form>
				<?php 
				if(isset($_POST['confirm_payment'])){
					//$update_id=$_GET['update_id'];
					$invoice_number=$_POST['invoice_number'];
					//$amount=$_POST['amount'];
					$payment_mode=$_POST['payment_mode'];
					$trfr_number=$_POST['trfr_number'];
					$date=$_POST['date'];
					$complete="Delivered";
					$insert="insert into payments (customer_id,invoice_id,amount,payment_mode,ref_no,payment_date,admin) values ('$c_id','$invoice_no','$amount','$payment_mode','$trfr_number','$date','$name')";
					$run_insert=mysqli_query($con,$insert);

					$update_q="update customer_order set order_status = '$complete' where invoice_no='$invoice_no'";
					$run_update=mysqli_query($con,$update_q);

					//$update_p="update pending_order set order_status = '$complete' where order_id='$update_id'";
					//$run_update=mysqli_query($con,$update_p);

					echo"<script>alert('Delivered & Payment Done') </script>";
					echo"<script>window.open('index.php?invoice_no=$invoice_no','_self') </script>";
				 
				}
				?>
			</div>
		



<?php } ?>