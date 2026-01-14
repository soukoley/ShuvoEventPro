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
    <title>New Event Gallery Video</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="row">
<div class="col-lg-12">

<div class="breadcrumb">
    <li class="active">
        <i class="fa fa-fw fa-image"></i>
        Gallery / Add Video
    </li>
</div>

<div class="panel panel-primary">
<div class="panel-heading corporate-heading">
	<h3 class="panel-title">
    	<i class="fa fa-plus"></i> Add Video
	</h3>	
</div>
<div class="panel-body">

<form class="form-horizontal" id="videoForm">

    <!-- Select Event -->
    <div class="form-group">
        <label class="col-md-3 control-label">Select Event :</label>
        <div class="col-md-6">
            <select name="event" class="form-control" required>
                <option value="">--- Select Event ---</option>
                <?php
                $run = mysqli_query($con,"SELECT id, e_name FROM event ORDER BY e_name");
                while($row=mysqli_fetch_assoc($run)){
                    echo "<option value='{$row['id']}'>{$row['e_name']}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Video Details -->
    <div class="form-group">
        <label class="col-md-3 control-label">Video Details :</label>
        <div class="col-md-6">
            <input type="text" name="v_details" class="form-control">
        </div>
    </div>

    <!-- Video Link -->
    <div class="form-group">
        <label class="col-md-3 control-label">Video Link :</label>
        <div class="col-md-6">
            <input type="text" name="v_link" class="form-control" required>
        </div>
    </div>

    <!-- Submit -->
    <div class="form-group">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary form-control">
                Add Video
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
$("#videoForm").on("submit", function(e){
    e.preventDefault();

    $.ajax({
        url: "ajax_add_event_video.php",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",

        beforeSend: function(){
            Swal.fire({
                title: "Saving...",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        },

        success: function(res){
            Swal.close();

            if(res.status === "success"){
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: res.message
                }).then(() => {
                    $("#videoForm")[0].reset();
                });
            }else{
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: res.message
                });
            }
        },

        error: function(xhr){
            Swal.fire({
                icon: "error",
                title: "Server Error",
                html: "<pre style='text-align:left'>" + xhr.responseText + "</pre>"
            });
        }
    });
});
</script>

</body>
</html>
