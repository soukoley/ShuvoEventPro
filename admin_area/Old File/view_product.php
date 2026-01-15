<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Product
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				<i class="fa fa-money fa-fw"></i>View Products
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Product Title</th>
								<th>Image</th>
								<th>M.R.P</th>
								<th>Sale Price</th>
								<th>Save Rs.</th>
								<th>Offers</th>
								<th>Purchase</th>
								<th>Value</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=0;
							$get_product="select * from products ORDER BY product_title";
							$run_p=mysqli_query($con,$get_product);
							while($row=mysqli_fetch_array($run_p)){
								$product_id=$row['product_id'];
								$product_title=$row['product_title'];
								$product_img1=$row['product_img1'];
								$m_price=$row['m_price'];
								$pro_price=$row['s_price'];
								$p_price=$row['p_price'];
								$val=$row['value'];
								$save=$m_price-$pro_price;
								$off1=($save/$m_price)*100;
								$off=round($off1,1);
								//$product_keywords=$row['product_keyword'];
								$i++							

							?>
						
							<tr>
								<td><?php echo $i ?></td>
								<td><?php echo $product_title ?></td>
								<td><img src="product_images/<?php echo $product_img1 ?>" width="30" width="40"></td>
								<td><?php echo $m_price ?></td>
								<td><?php echo $pro_price ?></td>
								<td><?php echo $save ?></td>
								<td><?php echo $off ?></td>
								<td><?php echo $p_price ?></td>
								<td><?php echo $val ?></td>
								<td>
									<a href="index.php?edit_product=<?php echo $product_id ?>"><i class="fa fa-pencil"></i> Edit</a>
								</td>
								<td>
									<a href="index.php?delete_product=<?php echo $product_id ?>"><i class="fa fa-trash-o"></i> Delete</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
						
					</table>
					
				</div>
				
			</div>
			
		</div>
	</div>
</div>







<?php } ?>