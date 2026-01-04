<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i>Dashboard / View Feedback
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-comments" aria-hidden="true"></i> View Feedback
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-borderd table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Mobile Number</th>
								<th>Feedback</th>
								<th>Date</th>
								<th>Remarks</th>
								<th>Update</th>
							</tr>
						</thead>
						<tbody>
						<?php 

							$i=0;
							$get_c="select * from feedback order by id desc";
							$run_c=mysqli_query($con,$get_c);
							while($row_cust=mysqli_fetch_array($run_c)){
								$id=$row_cust['id'];
								$customer_name=$row_cust['customer_name'];
								$customer_contact=$row_cust['customer_contact'];
								$msg=$row_cust['msg'];
								$fdate=$row_cust['date'];
								$remarks=$row_cust['remarks'];
								$i++;
						?>
							<tr>
								<td><?php echo $id; ?></td>
								<td><?php echo $customer_name; ?></td>
								<td><?php echo $customer_contact; ?></td>
								<td><?php echo $msg; ?></td>
								<td><?php echo $fdate; ?></td>
								<td><?php echo $remarks; ?></td>
								<td>
									<a href="index.php?feedback_update=<?php echo  $id;  ?>" class='btn btn-primary btn-sm'>
										<i class="fa fa-trash-o"></i> Update
									</a>
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