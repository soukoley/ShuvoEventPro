<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Purchase
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				<i class="fa fa-money fa-fw"></i>View Purchase
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Product</th>
								<th>Qty</th>
								<th>M.R.P</th>
								<th>SP</th>
								<th>Cost</th>
								<th>Invoice</th>
								<th>From</th>
								<th>Date</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=0;
							$get_product="select * from purchase";
							$run_p=mysqli_query($con,$get_product);
							while($row=mysqli_fetch_array($run_p)){
								$purchase_id=$row['purchase_id'];
								$cat_title=$row['cat_id'];
								$qty=$row['qty'];
								$m_price=$row['m_price'];
								$pro_price=$row['s_price'];
								$p_price=$row['p_price'];
								$p_inv=$row['p_inv'];
								$p_from=$row['p_from'];
								$p_date=$row['p_date'];

							?>
						
							<tr>
								<td><?php echo $purchase_id ?></td>
								<td><?php echo $cat_title ?></td>
								<td><?php echo $qty ?></td>
								<td><?php echo $m_price ?></td>
								<td><?php echo $pro_price ?></td>								
								<td><?php echo $p_price ?></td>
								<td><?php echo $p_inv ?></td>
								<td><?php echo $p_from ?></td>
								
								<td><?php echo $p_date ?></td>
								<!--td>
									<a href="index.php?edit_purchase=<?php echo $purchase_id ?>"><i class="fa fa-pencil"></i> Edit</a>
								</td>
								<td>
									<a href="index.php?delete_purchase=<?php echo $purchase_id ?>"><i class="fa fa-trash-o"></i> Delete</a>
								</td-->
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