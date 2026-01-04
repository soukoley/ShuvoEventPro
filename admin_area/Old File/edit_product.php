<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

 ?>
 <?php 
 if(isset($_GET['edit_product'])){
 	$edit_id=$_GET['edit_product'];
 	$get_p="select * from products where product_id='$edit_id'";
 	$run_edit=mysqli_query($con,$get_p);
 	$row_edit=mysqli_fetch_array($run_edit);
 	$p_id=$row_edit['product_id'];
 	$p_title=$row_edit['product_title'];
 	$p_cat=$row_edit['p_cat_id'];
 	$cat=$row_edit['cat_id'];
 	$p_image1=$row_edit['product_img1'];
 	$m_price=$row_edit['m_price'];
 	$s_price=$row_edit['s_price'];
 	$p_price=$row_edit['p_price'];
	$value=$row_edit['value'];
 	$company=$row_edit['company'];
 	$p_keywords=$row_edit['product_keyword'];
	$status=$row_edit['status'];
 }

 	$get_p_cat = "select * from product_category where p_cat_id='$p_cat'";
 	$run_p_cat=mysqli_query($con,$get_p_cat);
 	$row_p_cat=mysqli_fetch_array($run_p_cat);
 	$p_cat_title=$row_p_cat['p_cat_title'];


 	$get_cat = "select * from categories where cat_id='$cat'";
 	$run_cat=mysqli_query($con,$get_cat);
 	$row_cat=mysqli_fetch_array($run_cat);
 	$cat_title=$row_cat['cat_title'];

 ?>

 <!DOCTYPE html>
<html>
<head>
	<title>Edit Products</title>
	 
  <script>tinymce.init({selector:'textarea'});</script>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>
				Dashboard / Edit Product
			</li>
		</ol>
	</div>
</div>
		<div class="row">
			
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<i class="fa a-money fa-w"></i> Edit Product
						</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-3 control-label">Product Title</label>
								<div class="col-md-6">
									<input type="text" name="product_title" class="form-control" required="" value="<?php echo $p_title; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Product Category</label>
								<div class="col-md-6">
									<select name="product_cat" class="form-control">
										<option value="<?php echo $p_cat; ?>"> <?php echo $p_cat_title; ?></option>
										<?php 
										$get_p_cats="select * from product_category";
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
								<label class="col-md-3 control-label">Category</label>
								<div class="col-md-6">
									<select name="cat" class="form-control">
										<option value="<?php echo $cat; ?>"><?php echo $cat_title; ?></option>
										<?php 
										$get_cats="select * from categories ORDER BY cat_title ";
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
								<label class="col-md-3 control-label">Product Image 1</label>
								<div class="col-md-6">
									<input type="file" name="product_img1" class="form-control" required="">
									<br><img src="product_images/<?php echo $p_image1; ?>" width="70" height="70">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">M.R.P</label>
								<div class="col-md-6">
									<input type="number" name="m_price" step="0.01" class="form-control" required="" value="<?php echo $m_price; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Sale Price</label>
								<div class="col-md-6">
									<input type="number" name="s_price" step="0.01" class="form-control" required="" value="<?php echo $s_price; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Purchase Price</label>
								<div class="col-md-6">
									<input type="number" name="p_price" step="0.01" class="form-control" required="" value="<?php echo $p_price; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Value :</label>
								<div class="col-md-6">
									<input type="number" name="val" class="form-control" required="" value="<?php echo $value; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Product Keyword</label>
								<div class="col-md-6">
									<input type="text" name="product_keywords" class="form-control" required="" value="<?php echo $p_keywords; ?>">
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
											echo "<option value='$id'> $c_name </option>";
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
								<label class="col-md-3 control-label"></label>
								<div class="col-md-6">
									<input type="submit" name="update" value="Update Product" class="btn btn-primary form-control">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	



</body>

</html>

<?php 
if(isset($_POST['update'])){
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

	$update_product="update products set p_cat_id='$product_cat', cat_id='$cat', product_title='$product_title',company='$company', product_img1='$product_img1', m_price='$m_price', s_price='$s_price',p_price='$p_price',value='$val',product_keyword='$product_keywords',status='$sta' where product_id='$p_id'";
	
	$run_product=mysqli_query($con,$update_product);
	if($run_product){
		echo"<script>alert('Product updated Successfully')</script>";
		echo"<script>window.open('index.php?view_product','_self')</script>";
	}
}
?>

 <?php } ?>