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
    <title>Insert Holiday</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="row">
    <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
        <div class="breadcrumb">
            <li class="active">
                <i class="fa fa-fw fa-cogs"></i>
                Admin control / Manage Holidays / Add Holiday
            </li>
        </div>
    </div>
</div>

<div class="row">
<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
<div class="panel panel-primary">

<div class="panel-heading corporate-heading">
    <h3 class="panel-title">
		<i class="fa fa-plus"></i> New Holiday
	</h3>
</div>

<div class="panel-body">

<form class="form-horizontal" id="eventForm" enctype="multipart/form-data">

    <div class="form-group">
        <label class="col-md-3 control-label">Holiday Name :</label>
        <div class="col-md-6">
            <input type="text" name="hname" class="form-control" required>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label">Date of Holiday :</label>
        <div class="col-md-6">
            <input type="date" name="hdate"  class="form-control" required="">
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary form-control">
                Add Holiday
            </button>
        </div>
    </div>

</form>

</div>
</div>
</div>
</div>

<!-- ================= AJAX SCRIPT ================= -->
<script>
$("#eventForm").on("submit", function(e){
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: "ajax_add_holiday.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",

        beforeSend: function () {
            Swal.fire({
                title: "Uploading...",
                text: "Please wait",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        },

        success: function (res) {
            Swal.close();

            if (res.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: res.message
                }).then(() => {
                    window.location.href = "index.php?holidays";
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: res.message
                });
            }
        },

        error: function () {
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
