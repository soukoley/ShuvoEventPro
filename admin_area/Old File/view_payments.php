<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Payments
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-money fa-fw"></i>View Payments
				</h3>
				<hr>
				<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">

					<div class="form-group">
						<label>From Date : </label>
						<input type="date" class="form-control" name="fdate" required="">
					</div>
					<div class="form-group">
						<label>To Date : </label>
						<input type="date" class="form-control" name="tdate" required="">
					</div>
					<div class="form-group">
						<label>Delivered By : </label>
						<select name="admin" class="form-control" required="">
							<option value='all'> All Admin </option>
							
							<?php 
							$get_admin="select * from admins order by admin_name";
							$run_admin=mysqli_query($con,$get_admin);
							while ($row=mysqli_fetch_array($run_admin)) {
								$name=$row['admin_name'];
								echo "<option value='$name'> $name </option>";
							}
							?>
						</select>
					</div>
					
					<div class="text-center">
						<input type="submit" name="search" value="Search"  class="btn btn-primary form-control">
					</div>
				</form>
				<?php 
					if(isset($_POST['search'])){
						$F_DATE=$_POST['fdate'];
						$T_DATE=$_POST['tdate'];
						$admin=$_POST['admin'];

						$fdate = date("Y-m-d", strtotime($F_DATE));
						$tdate = date("Y-m-d", strtotime($T_DATE));

						if($admin=='all'){
							$get_payments="select * from payments where payment_date between '$fdate' and '$tdate' order by payment_id desc";
						}else{
							$get_payments="select * from payments where admin='$admin' and payment_date between '$fdate' and '$tdate' order by payment_id desc";
						}
						
						
					}else{
						$get_payments="select * from payments order by payment_id desc";
					}
				?>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>P_ID</th>
								<th>Invoice</th>
								<th>Amount</th>
								<th>Payment By</th>
								<th>Ref. No</th>
								<th>Date</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Delivered By</th>
							</tr>
						</thead>
						<tbody>
						<?php 

							$i=0;
							$tamount=0;
							//$get_payments="select * from payments order by payment_id desc";
							$run_payments=mysqli_query($con,$get_payments);
							while($row_payment=mysqli_fetch_array($run_payments)){
								$payment_id=$row_payment['payment_id'];
								$customer_id=$row_payment['customer_id'];
								$invoice_no=$row_payment['invoice_id'];
								$amount=$row_payment['amount'];
								$payment_mode=$row_payment['payment_mode'];
								$ref_no=$row_payment['ref_no'];
								$payment_date=$row_payment['payment_date'];
								$admin=$row_payment['admin'];

								$c_name="select * from customers where customer_id='$customer_id'";
								$run_c=mysqli_query($con,$c_name);
								$row_c=mysqli_fetch_array($run_c);
								$name=$row_c['customer_name'];
								$mobile=$row_c['customer_contact'];
								
								$tamount=$tamount+$amount;

								$i++;
						?>
							<tr>
								<td><?php echo $payment_id; ?></td>
								<td><?php echo $invoice_no; ?></td>
								<td><?php echo $amount; ?></td>
								<td><?php echo $payment_mode; ?></td>
								<td><?php echo $ref_no; ?></td>
								<td><?php echo $payment_date; ?></td>
								<td><?php echo $name; ?></td>
								<td><?php echo $mobile; ?></td>
								<td><?php echo $admin; ?></td>
								
							</tr>
						<?php } ?>

						</tbody>
						<tfoot>
							<tr>
								<th colspan="2">Total Amount: </th>
								<th><?php echo $tamount; ?></th>
								<th colspan="6"></th>

							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>



<?php } ?>