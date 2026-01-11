<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>View Booking</title>
    </head>
    <body>
        <div class="row">
            <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-fw fa-calendar-check-o"></i>
                        Booking / Search
                    </li>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="panel panel-primary">
                    <div class="panel-heading corporate-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-search"></i> Search by Booking ID
                        </h3>
                    </div>
                    <div class="panel-body" style="padding-top: 20px;">
                        <form method="post" action="index.php?check_booking_status">
                            <div class="form-group">
                                <label>Booking ID</label>
                                <input type="text"
                                    name="booking_id"
                                    class="form-control"
                                    placeholder="Enter Booking ID"
                                    required
                                    autofocus>
                            </div>

                            <button class="btn btn-primary">
                                <i class="fa fa-arrow-right"></i> Check Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

