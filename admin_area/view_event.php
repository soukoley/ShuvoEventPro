<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-calendar"></i> Event / View Event
			</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="panel panel-primary">
			<div class="panel-heading corporate-heading">
				<h3 class="panel-title">
					All&nbsp;&nbsp;Event&nbsp;&nbsp;Details
				</h3>
			</div>
			<div class="table-responsive" style="padding-top: 3px;">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th class="text-center">ID</th>
							<th class="text-left">Event Name</th>
							<th class="text-left">Event Details</th>
							<th class="text-center">Event Price</th>
							<th class="text-center">Event Image</th>
							<th class="text-center">Edit</th>
							<th class="text-center">Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i=0;
						$get_product="select * from event ORDER BY e_name";
						$run_p=mysqli_query($con,$get_product);
						while($row=mysqli_fetch_array($run_p)){
							$id=$row['id'];
							$name=$row['e_name'];
							$e_desc=$row['e_desc'];
							$e_start_price=$row['e_start_price'];
							$e_cat_img=$row['e_cat_img'];
							$i++							

						?>
					
						<tr>
							<td class="text-center"><?php echo $i ?></td>
							<td class="text-left"><?php echo $name ?></td>
							<td class="text-left"><?php echo $e_desc ?></td>
							<td class="text-center"><?php echo $e_start_price ?></td>
							<td class="text-center"><img src="event_category/<?php echo $e_cat_img ?>" width="30" width="40"></td>
							
							<td>
								<a href="index.php?edit_event=<?php echo $id ?>"><i class="fa fa-pencil" style="font-size:16px; font-weight: bold; color:green;"> Edit</i></a>
							</td>
							<td>
								<a href="index.php?delete_event=<?php echo $id ?>"><i class="fa fa-trash-o" style="font-size:16px; font-weight: bold; color:red;"> Delete</i></a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
					
				</table>
				
			</div>
			
		</div>
	</div>
</div>

<?php } ?>