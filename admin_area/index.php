<?php
session_start();
include ("includes/db.php");
//include('../functions/indianCurrencyFormat.php');
global $cur_finance_year;

if(!isset($_SESSION['admin_email'])){
	$cur_date = date('Y-m-d');
	echo "<script>window.open('login.php','_self')</script>";
	exit;
}
	
$admin_session = $_SESSION['admin_email'];
$get_admin = "SELECT * FROM admins WHERE admin_email='$admin_session'";
$run_admin = mysqli_query($con, $get_admin);
$row_admin = mysqli_fetch_array($run_admin);

$admin_id = $row_admin['admin_id'];
$admin_name = $row_admin['admin_name'];
$admin_email = $row_admin['admin_email'];
$admin_image = $row_admin['admin_image'];
$admin_contact = $row_admin['admin_contact'];
$admin_job = $row_admin['admin_job'];
$admin_about = $row_admin['admin_about'];
$admin_country = $row_admin['admin_country'];

// ===================== CURRENT FINANCIAL YEAR =====================
$c_fyear_qry = "SELECT f_year FROM finance_year WHERE status=1";
$c_fyear_res = mysqli_query($con, $c_fyear_qry);
$c_fyear_row = mysqli_fetch_array($c_fyear_res);
$cur_finance_year = $c_fyear_row['f_year'];

?>

<!DOCTYPE html>
<html>
<head>
	<title> User Panel </title>
	<link rel="shortcut icon" type="image/png" href="../images/tlogo.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	
	<!-- Favicon Logo -->
	<link rel="apple-touch-icon" sizes="180x180" href="../images/favicon_io/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon_io/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon_io/favicon-16x16.png">
	<link rel="manifest" href="../images/favicon_io/site.webmanifest">

	<style>
		body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
		thead th {
			vertical-align: middle !important;
		}
        .sidebar {
            background-color: #f8f9fa;
            padding: 15px;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

			/* Content full width */
			.content-area {
				width: 100% !important;
				margin-left: 0 !important;
				padding-left: 10px;
				padding-right: 10px;
			}
			
			.col-sm-8,
			.col-md-10 {
				width: 100% !important;
			}

			#page-wrapper {
				padding-left: 0 !important;
				padding-right: 0 !important;
			}
        }

        .content-area {
            padding: 15px;
        }

        #page-wrapper {
            margin-top: 60px;
			padding-left: 35px;
			padding-top: 20px;
        }

	</style>
</head>
<body>

<div class="container-fluid">
	<div class="row">

		<!-- Sidebar -->
        <div class="col-md-2 col-sm-4 sidebar">
            <?php include 'includes/sidebar.php'; ?>
        </div>

		<!-- Main Content Area -->
        <div class="col-md-10 col-sm-8 content-area">
            <div id="page-wrapper" class="container-fluid">
                <?php

					$pages = [
						'dashboard'          	=> 'dashboard.php',
						'view_event_details'    => 'view_event_details.php',
						'insert_event'          => 'insert_event.php',
						'view_event'            => 'view_event.php',
						'edit_event'            => 'edit_event.php',

						'user_profile'			=>	'user_profile.php',

						'insert_event_gallery'  => 'insert_event_gallery.php',
						'view_gallery'          => 'view_gallery.php',
						'edit_gallery'          => 'edit_gallery.php',
						'delete_gallery'        => 'delete_gallery.php',
						
						'view_booking'        	=> 'view_booking.php',
						'booking_result'        => 'view_booking_result.php',
						'confirm_booking'   	=> 'confirm_booking.php',
					];

					foreach ($pages as $key => $file) {
						if (isset($_GET[$key])) {
							if (file_exists($file)) {
								include $file;
							} else {
								echo "<h3 style='color:red'>Page not found: $file</h3>";
							}
						}
					}

				?>

			</div>
		</div>
	</div>
</div>
</body>
</html>