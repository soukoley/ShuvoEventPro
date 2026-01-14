<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("includes/db.php");

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Facility</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
	<div class="row">
		<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
			<div class="breadcrumb">
				<li class="active">
					<i class="fa fa-fw fa-hotel"></i>
					Facility / New Facility
				</li>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
			<div class="panel panel-primary">
				<div class="panel-heading corporate-heading">
					<h3 class="panel-title">
						<i class="fa fa-plus"></i> Add New Facility
					</h3>
				</div>

				<div class="panel-body">

					<form class="form-horizontal" id="facilityForm">

						<!-- Facility Name -->
						<div class="form-group">
							<label class="col-md-3 control-label">Facility Name :</label>
							<div class="col-md-6">
								<input type="text" name="facility" class="form-control" required>
							</div>
						</div>

						<!-- Price -->
						<div class="form-group">
							<label class="col-md-3 control-label">Price :</label>
							<div class="col-md-6">
								<input type="number" name="price" class="form-control" min="0" required>
							</div>
						</div>

						<!-- GST -->
						<div class="form-group">
							<label class="col-md-3 control-label">GST Rate :</label>
							<div class="col-md-6">
								<input type="number" name="gst_rate" class="form-control" min="0" required>
							</div>
						</div>

						<!-- Event -->
						<div class="form-group">
							<label class="col-md-3 control-label">Select Event :</label>
							<div class="col-md-6">
								<select name="event" id="eventSelect" class="form-control" required>
									<option value="">-- Select --</option>
									<option value="ALL">ALL</option>
									<?php
									$run = mysqli_query($con,"SELECT * FROM event ORDER BY e_name");
									while($row=mysqli_fetch_assoc($run)){
										echo "<option value='{$row['e_name']}'>{$row['e_name']}</option>";
									}
									?>
								</select>
							</div>
						</div>

						<!-- Max People -->
						<div class="form-group" id="maxPeopleGroup">
							<label class="col-md-3 control-label">Maximum People :</label>
							<div class="col-md-6">
								<select name="max_people" class="form-control">
									<option value="">-- Select --</option>
									<?php
									$run = mysqli_query($con,"SELECT * FROM guest ORDER BY max_guest");
									while($row=mysqli_fetch_assoc($run)){
										echo "<option value='{$row['max_guest']}'>{$row['max_guest']}</option>";
									}
									?>
								</select>
							</div>
						</div>

						<!-- Submit_toggle -->
						<div class="form-group">
							<div class="col-md-3"></div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-primary form-control">
									Add Facility
								</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- ================= JS ================= -->
	<script>
	$("#eventSelect").change(function(){
		if($(this).val()==="ALL"){
			$("#maxPeopleGroup").hide();
			$("select[name='max_people']").val("0");
		}else{
			$("#maxPeopleGroup").show();
		}
	});


	$("#facilityForm").on("submit", function(e){
		e.preventDefault();

		$.ajax({
			url: "ajax_add_facility.php",
			type: "POST",
			data: $(this).serialize(),
			dataType: "json",
			beforeSend:function(){
				Swal.fire({
					title: "Processing...",
					allowOutsideClick: false,
					didOpen: () => Swal.showLoading()
				});
			},
			success:function(res){
				Swal.close();

				if(res.status==="success"){
					Swal.fire({
						icon: "success",
						title: "Success",
						text: res.message
					});
					$("#facilityForm")[0].reset();
					$("#maxPeopleGroup").show();
				}else{
					Swal.fire({
						icon: "error",
						title: "Error",
						text: res.message
					});
				}
			},
			error:function(){
				Swal.fire({
					icon: "error",
					title: "Server Error",
					text: "Something went wrong"
				});
			}
		});
	});
	</script>

</body>
</html>
