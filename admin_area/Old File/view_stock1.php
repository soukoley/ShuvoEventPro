<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Stock
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				<i class="fa fa-money fa-fw"></i>View Stock
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Product Category</th>
								<th>Company</th>
								<th>M.R.P</th>
								<th>Sale Price</th>
								<th>Cost</th>
								<th>Qty</th>
								<th>Stock Price</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$ftotal=0;
							$get_product="select * from stock";
							$run_p=mysqli_query($con,$get_product);
							while($row=mysqli_fetch_array($run_p)){
								$total=0;
								$stock_id=$row['stock_id'];
								$cat_title=$row['cat_title'];
								$company=$row['company'];
								$m_price=$row['m_price'];
								$pro_price=$row['s_price'];
								$p_price=$row['p_price'];
								$qty=$row['qty'];
								$total=$total+($p_price*$qty);
								$ftotal=$ftotal+$total;

							?>
						
							<tr>
								<td><?php echo $stock_id ?></td>
								<td><?php echo $cat_title ?></td>
								<td><?php echo $company ?></td>
								<td><?php echo $m_price ?></td>
								<td><?php echo $pro_price ?></td>
								<td><?php echo $p_price ?></td>
								<td><?php echo $qty ?></td>
								<td><?php echo $total ?></td>
							</tr>
							
							<?php 
							
							} ?>
							<tr>
								<td colspan=7 align="right"><b>Total : </b></td>
								<td><b><?php echo $ftotal ?></b></td>
							</tr>
						</tbody>
						
					</table>
					
				</div>
				
			</div>
			
		</div>
	</div>
</div>







<?php } ?>