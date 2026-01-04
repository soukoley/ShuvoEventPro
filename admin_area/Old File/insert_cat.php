<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-dashboard"></i>Dashboard / Insert Category
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-money fa-fw"></i>Insert Category
				</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" action="" method="post">
					<div class="form-group">
						<label class="col-md-3 control-label">Category Title</label>
						<div class="col-md-6">
							<input type="text" name="cat_title" class="form-control">
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
							<textarea type="text" name="cat_desc" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"></label>
						<div class="col-md-6">
							<input type="submit" name="submit" value="Insert Category" class="btn btn-primary form-control">
						</div>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>

<?php 
if(isset($_POST['submit'])){
	$cat_title=$_POST['cat_title'];
	$product_cat=$_POST['product_cat'];
	$company=$_POST['company'];
	$cat_desc=$_POST['cat_desc'];
	$query="select * from categories where cat_title='$cat_title'";
	$result = mysqli_query($con, $query);
    if(mysqli_num_rows($result) > 0){
		echo"<script>alert('categories Already Exist')</script>";
	
	}else{
	
		$insert_cat="insert into categories (cat_title,p_cat_id,company,cat_desc) values ('$cat_title','$product_cat','$company','$cat_desc')";
		$run_cat=mysqli_query($con,$insert_cat);
		if($run_cat){
			$query1="select cat_id from categories where cat_title='$cat_title'";
			$result1 = mysqli_query($con, $query1);
			while($row=mysqli_fetch_array($result1)){
				$cat_id=$row['cat_id'];
			}
			$qty=0;
			$m_price=0;
			$s_price=0;
			$p_price=0;
			
			$insert_stock="insert into stock(p_cat_id,cat_id,cat_title,company,m_price,s_price,p_price,qty) values('$product_cat','$cat_id','$cat_title','$company','$m_price','$s_price','$p_price','$qty')";
			$run_stock=mysqli_query($con,$insert_stock);
				if($run_stock){
					echo "<script>alert('New Category has been Inserted')</script>";
					echo "<script>window.open('index.php?view_categories','_self')</script>";
				}
			
		}
	}
}

?>

<?php } ?>