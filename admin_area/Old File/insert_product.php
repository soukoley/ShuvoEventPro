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
				Dashboard / Insert Product
			</li>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-title">
						<h3>
							<i class="fa a-money fa-w"></i> Insert Product
						</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">Product Title :</label>
								<div class="col-md-6">
									<input type="text" name="product_title" class="form-control" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Product Category :</label>
								<div class="col-md-6">
									<select name="product_cat" class="form-control" required="">
										<option>Select a product category </option>
										<?php 
										$get_p_cats="select * from product_category order by p_cat_title";
										$run_p_cats=mysqli_query($con,$get_p_cats);
										while ($row=mysqli_fetch_array($run_p_cats)) {
											$id=$row['p_cat_id'];
											$cat_title=$row['p_cat_title'];
											echo "<option value='$id'> $cat_title </option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Categories :</label>
								<div class="col-md-6">
									<select name="cat" class="form-control" required="">
										<option>Select Category</option>
										<?php 
										$get_cats="select * from categories order by cat_title";
										$run_cats=mysqli_query($con,$get_cats);
										while ($row=mysqli_fetch_array($run_cats)) {
											$id=$row['cat_id'];
											$cat_title=$row['cat_title'];
											echo "<option value='$id'> $cat_title </option>";
										}
										?>
										
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Product Image :</label>
								<div class="col-md-6">
									<input type="file" name="product_img1" class="form-control" required="">
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
								<label class="col-md-3 control-label">Value :</label>
								<div class="col-md-6">
									<input type="number" name="val" value="1" class="form-control" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Product Keyword :</label>
								<div class="col-md-6">
									<input type="text" name="product_keywords" class="form-control" required="">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-3 control-label">Company :</label>
								<div class="col-md-6">
									<select name="company" class="form-control" required="">
										<option>Select Company</option>
										<?php 
										$get_com="select * from company order by name";
										$run_com=mysqli_query($con,$get_com);
										while ($row=mysqli_fetch_array($run_com)) {
											$id=$row['id'];
											$c_name=$row['name'];
											echo "<option value='$c_name'> $c_name </option>";
										}
										?>
										
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Status :</label>
								<div class="col-md-6">
									<select name="sta" class="form-control" required="">
										<option value="Active">Active</option>
										<option value="Inactive">Inactive</option>
									</select>
								</div>
							</div>
														
							<div class="form-group">
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<input type="submit" name="submit" value="Insert Product" class="btn btn-primary form-control" >
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
	$product_title=$_POST['product_title'];
	$product_cat=$_POST['product_cat'];
	$cat=$_POST['cat'];
	$m_price=$_POST['m_price'];
	$s_price=$_POST['s_price'];
	$p_price=$_POST['p_price'];
	$val=$_POST['val'];
	$company=$_POST['company'];
	$product_keywords=$_POST['product_keywords'];
	$sta=$_POST['sta'];
	$product_img1=$_FILES['product_img1']['name'];
	$temp_name1=$_FILES['product_img1']['tmp_name'];

	move_uploaded_file($temp_name1, "product_images/$product_img1");
	
	$query="select * from products where product_title='$product_title'";
	$result = mysqli_query($con, $query);
    if(mysqli_num_rows($result) > 0){
		echo"<script>alert('Product Already Exist')</script>";
	
	}else{
		$insert_product="insert into products(p_cat_id,cat_id,product_title,company,product_img1,m_price,s_price,p_price,value,product_keyword,status) values('$product_cat','$cat','$product_title','$company','$product_img1','$m_price','$s_price','$p_price','$val','$product_keywords','$sta')";
		//echo $insert_product;
		$run_product=mysqli_query($con,$insert_product);
		if($run_product){
			
			echo"<script>alert('Product Inserted Successfully')</script>";
			echo"<script>window.open('index.php?view_product')</script>";
			
		}
	}
}
?>
<?php } ?>