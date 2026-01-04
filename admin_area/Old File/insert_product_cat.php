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
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-dashboard"></i>Dashboard / Insert Products Category
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-money fa-fw"></i>Insert Product Category
				</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
					<div class="form-group">
						<label class="col-md-3 control-label">Product Category Title</label>
						<div class="col-md-6">
							<input type="text" name="p_cat_title" class="form-control" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Product Category Description</label>
						<div class="col-md-6">
							<textarea type="text" name="p_cat_desc" class="form-control" required=""></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Product Category Image</label>
						<div class="col-md-6">
							<input type="file" name="product_img1" class="form-control" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"></label>
						<div class="col-md-6">
							<input type="submit" name="submit" value="Submit Now" class="btn btn-primary form-control">
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
if(isset($_POST['submit'])){
	$p_cat_title=$_POST['p_cat_title'];
	$p_cat_desc=$_POST['p_cat_desc'];
	$product_img1=$_FILES['product_img1']['name'];
	$temp_name1=$_FILES['product_img1']['tmp_name'];

	move_uploaded_file($temp_name1, "product_images/$product_img1");

	$insert_p_cat="insert into product_category (p_cat_title,p_cat_desc,product_img1) values ('$p_cat_title','$p_cat_desc','$product_img1')";
	echo $insert_p_cat;
	$run_p_cat=mysqli_query($con,$insert_p_cat);
	if($run_p_cat){
		echo "<script>alert('New Product Category has been Inserted ' $insert_p_cat;)</script>";
		echo "<script>window.open('index.php?view_product_cat','_self')</script>";
	}
}

?>

<?php } ?>