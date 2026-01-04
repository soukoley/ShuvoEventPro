<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Insert Product</title>
	 
  <script>tinymce.init({selector:'textarea'});</script>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>
				Dashboard / Insert Purchase
			</li>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-title">
						<h3>
							<i class="fa a-money fa-w"></i> Insert Purchase
						</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
							
							<div class="form-group">
								<label class="col-md-3 control-label">Product Title :</label>
								<div class="col-md-6">
									<select name="product_id" class="form-control" required="">
										<option>Select a product </option>
										<?php 
										$get_p="select * from products order by product_title";
										$run_p=mysqli_query($con,$get_p);
										while ($row=mysqli_fetch_array($run_p)) {
											$id=$row['product_id'];
											$product_title=$row['product_title'];
											echo "<option value='$id'> $product_title </option>";
										}
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-3 control-label">Qty (Value) :</label>
								<div class="col-md-6">
									<input type="number" name="qty" class="form-control" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">M.R.P :</label>
								<div class="col-md-6">
									<input type="number" name="m_price" step="0.01" class="form-control" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Sale Price :</label>
								<div class="col-md-6">
									<input type="number" name="s_price" step="0.01" class="form-control" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Purchase Price :</label>
								<div class="col-md-6">
									<input type="number" name="p_price" step="0.01" class="form-control" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Purchase Invoice :</label>
								<div class="col-md-6">
									<input type="text" name="p_inv" class="form-control" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Purchase From :</label>
								<div class="col-md-6">
									<input type="text" name="p_from" class="form-control" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Purchase Date :</label>
								<div class="col-md-6">
									<input type="date" name="p_date" class="form-control" required="">
								</div>
							</div>
														
							<div class="form-group">
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<input type="submit" name="submit" value="Purchase Product" class="btn btn-primary form-control" >
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
	$product_id=$_POST['product_id'];
	//$product_title=$_POST['product_title'];
	$qty=$_POST['qty'];
	$m_price=$_POST['m_price'];
	$s_price=$_POST['s_price'];
	$p_price=$_POST['p_price'];
	$p_inv=$_POST['p_inv'];
	$p_from=$_POST['p_from'];
	$p_date=$_POST['p_date'];
	
	$query="select * from stock where product_id='$product_id'";
	$run_query=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($run_query)){
		$product_title=$row['product_title'];
		$o_qty=$row['qty'];
		$o_mrp=$row['m_price'];
		$o_sprice=$row['s_price'];
		$o_pprice=$row['p_price'];
	}
	$FQTY=$o_qty+$qty;
	$fm_price=$m_price;
	$fs_price=$s_price;
	if($o_qty<1){
		$fp_price=$p_price;
		
	}else{
		$fp_price=(($p_price*$qty)+($o_pprice*$o_qty))/($qty+$o_qty);
	}
	
	$insert_purchase="insert into purchase(product_id,m_price,s_price,p_price,qty,p_inv,p_from,p_date) values('$product_id','$m_price','$s_price','$p_price','$FQTY','$p_inv', '$p_from','$p_date')";
	$run_purchase=mysqli_query($con,$insert_purchase);
	if($run_purchase){
		$update_stock="update stock set m_price='$fm_price', s_price='$fs_price', p_price='$fp_price', qty='$FQTY' where product_id='$product_id'";
		$run_update_stock=mysqli_query($con,$update_stock);
		if($run_update_stock){
			echo"<script>alert('Purchase Inserted Successfully')</script>";
			echo"<script>window.open('index.php?purchase_product','_self')</script>";
		}
	}
	
}
?>
<?php } ?>