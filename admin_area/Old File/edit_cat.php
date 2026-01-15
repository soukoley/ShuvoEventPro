<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>

<?php  
if(isset($_GET['edit_cat'])){
	$edit_id=$_GET['edit_cat'];
	$edit_cat="select * from categories where cat_id='$edit_id'";
	$run_edit=mysqli_query($con,$edit_cat);
	$row_edit=mysqli_fetch_array($run_edit);
	$c_id=$row_edit['cat_id'];
	$cat_title=$row_edit['cat_title'];
	$p_cat_id=$row_edit['p_cat_id'];
	$company=$row_edit['company'];
	$cat_desc=$row_edit['cat_desc'];

}

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-dashboard"></i>Dashboard / Edit Category
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-money fa-fw"></i>Edit Category
				</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" action="" method="post">
					<div class="form-group">
						<label class="col-md-3 control-label">Category Title</label>
						<div class="col-md-6">
							<input type="text" name="cat_title" class="form-control" value="<?php echo $cat_title; ?>">
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
						<label class="col-md-3 control-label">Category Description</label>
						<div class="col-md-6">
							<textarea type="text" name="cat_desc" class="form-control"><?php echo $cat_desc; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"></label>
						<div class="col-md-6">
							<input type="submit" name="update" value="Update Category" class="btn btn-primary form-control">
						</div>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>

<?php 
if(isset($_POST['update'])){
	$cat_title=$_POST['cat_title'];
	$cat_desc=$_POST['cat_desc'];
	$p_cat_id=$_POST['product_cat'];
	$company=$_POST['company'];
	$update_cat="update categories set cat_title='$cat_title', p_cat_id='$p_cat_id', company='$company', cat_desc='$cat_desc' where cat_id='$edit_id'";
	$run_cat=mysqli_query($con,$update_cat);
	if($run_cat){
		$update_stock="update stock set cat_title='$cat_title', p_cat_id='$p_cat_id', company='$company' where cat_id='$edit_id'";
		$run_stock=mysqli_query($con,$update_stock);
		if($run_stock){
			echo "<script>alert('Category has been Updated')</script>";
			echo "<script>window.open('index.php?view_categories','_self')</script>";
		}
		
		
	}
}

?>

<?php } ?>