<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}

$booking_id = $_GET['id'];
$flag = $_GET['complete_details'];

$q = mysqli_query($con,"
SELECT bd.*,c.c_name,c.c_addr,c.c_mobile
FROM booking_details bd
JOIN customer c ON bd.cust_id=c.c_id
WHERE bd.booking_id='$booking_id'
");
$data = mysqli_fetch_assoc($q);

$booking_date = $data['booking_date'];
$e_name = $data['e_name'];
$c_name = $data['c_name'];
$c_addr = $data['c_addr'];
$c_mobile = $data['c_mobile'];
$old_max_guest = $data['max_guest'];
$start_date = $data['start_date'];
$end_date = $data['end_date'];
$start_time = $data['start_time']; 
$end_time = $data['end_time'];

$paymentQry = mysqli_query($con,"
SELECT *
FROM payment
WHERE booking_id='$booking_id'
");
$pData = mysqli_fetch_assoc($paymentQry);

$disc_amt   = $pData['disc_amt'];
$gross_amt  = $pData['gross_amt'];
$net_amt    = $pData['net_amt'];
$due_amt    = $pData['due_amt'];
$payment_amt= $pData['adv_amt'];

$fac_res = mysqli_query($con,"
SELECT bf.*, f.fName, f.gst_rate
FROM booking_facilities bf
JOIN facility f ON bf.facility_id=f.id
WHERE bf.booking_id='$booking_id'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complete Details</title>
</head>
<body>

<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-calendar-check-o"></i>
				Booking / Complete Booking / Complete Booking Details
			</li>
		</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="panel panel-primary">
			<div class="panel-heading corporate-heading">
				<h3 class="panel-title">
					Customer&nbsp;&nbsp;Booking&nbsp;&nbsp;Details
				</h3>
			</div>
			<div class="panel-body" style="padding-top: 20px;">

                <!-- Event Details -->
                <h4 class="section-title">
                    <i class="fa fa-fw fa-calendar"></i> Event Details
                </h4>

                <div class="row booking-view-box">

                    <!-- Event Name -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <label class="view-label">Event Name</label>
                        <div class="view-value">
                            <i class="fa fa-star"></i>
                            <?= $e_name ?>
                        </div>
                    </div>

                    <!-- Booking ID -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <label class="view-label">Booking ID</label>
                        <div class="view-value">
                            <i class="fa fa-hashtag"></i>
                            <?= $booking_id ?>
                        </div>
                    </div>

                    <!-- Booking Date -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <label class="view-label">Booking Date</label>
                        <div class="view-value">
                            <i class="fa fa-calendar-check-o"></i>
                            <?= date('d M Y, h:i A', strtotime($booking_date)) ?>
                        </div>
                    </div>

                </div>

                <hr class="soft-divider">

                <!-- Customer -->
                <h4 class="section-title">
                    <i class="fa fa-user"></i> Customer Details
                </h4>

                <div class="customer-row">
                    <span class="label">
                        <i class="fa fa-id-card"></i> Name
                    </span>
                    <span class="value"><?= htmlspecialchars($c_name) ?></span>
                </div>

                <div class="customer-row">
                    <span class="label">
                        <i class="fa fa-map-marker"></i> Address
                    </span>
                    <span class="value"><?= nl2br(htmlspecialchars($c_addr)) ?></span>
                </div>

                <div class="customer-row">
                    <span class="label">
                        <i class="fa fa-phone"></i> Contact
                    </span>
                    <span class="value"><?= htmlspecialchars($c_mobile) ?></span>
                </div>


                <hr class="soft-divider">

                <h4 class="section-title">
                    <i class="fa fa-calendar-check-o"></i> Booking Details
                </h4>

                <div class="row booking-view-box">

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="view-label">Check-In Date</label>
                        <div class="view-value">
                            <i class="fa fa-calendar"></i>
                            <?= date('d M Y', strtotime($start_date)) ?>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label class="view-label">Check-In Time</label>
                        <div class="view-value">
                            <i class="fa fa-clock-o"></i>
                            <?= date('h:i A', strtotime($start_time)) ?>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="view-label">Check-Out Date</label>
                        <div class="view-value">
                            <i class="fa fa-calendar"></i>
                            <?= date('d M Y', strtotime($end_date)) ?>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label class="view-label">Check-Out Time</label>
                        <div class="view-value">
                            <i class="fa fa-clock-o"></i>
                            <?= date('h:i A', strtotime($end_time)) ?>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-12 col-xs-12">
                        <label class="view-label">Max Guest</label>
                        <div class="view-value">
                            <i class="fa fa-users"></i>
                            <?= $old_max_guest ?>
                        </div>
                    </div>

                </div>

                <hr class="soft-divider">

                <!-- Facilities -->
                
                <h4 class="section-title">
                    <i class="fa fa-list"></i> List of Facilities
                </h4>

                <div class="table-responsive facility-table-wrap">
                    <table class="table table-bordered facility-table" id="facilityTable">
                        <thead>
                            <tr>
                                <th>Facility</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Rate</th>
                                <th class="text-right">Taxable</th>
                                <th class="text-right">GST %</th>
                                <th class="text-right">GST Amt</th>
                                <th class="text-right">Net Amt</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($f=mysqli_fetch_assoc($fac_res)){ ?>
                            <tr>
                                <td><?= $f['fName'] ?></td>
                                <td><?= $f['qty'] ?></td>
                                <td class="text-right rate"><?= number_format($f['rate'],2) ?></td>
                                <td class="text-right taxable"><?= number_format($f['taxableAmt'],2) ?></td>
                                <td class="text-right taxRate"><?= $f['gst_rate'] ?>%</td>
                                <td class="text-right gst"><?= number_format($f['gstAmt'],2) ?></td>
                                <td class="text-right total"><?= number_format($f['netAmt'],2) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <hr class="soft-divider">
                
                <h4 class="section-title">
                    <i class="fa fa-credit-card"></i> Payment Section
                </h4>

                <!-- ================= PAYMENT SUMMARY ================= -->
                 <div class="row payment-summary-row">
                    <div class="col-md-4">
                        <label class="pay-label">Total Amount</label>
                        <div class="pay-value final">
                            ₹ <span><?= $gross_amt ?></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Discount Given</label>
                        <div class="pay-value discount">
                            ₹ <span><?= $disc_amt ?></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Final Net Amount</label>
                        <div class="pay-value payable">
                            ₹ <span><?= $net_amt ?></span>
                        </div>
                    </div>
                </div>

                <div class="row payment-summary-row">
                    <div class="col-md-4">
                        <label class="pay-label">Payment Amount</label>
                        <div class="pay-value payment">
                            ₹ <span ><?= $payment_amt ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="pay-label">Due Amount</label>
                        <div class="pay-value due">
                            ₹ <span ><?= $due_amt ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top: 15px;">
            <?php if($flag==5){ ?>
                <a href="index.php?search" class="btn btn-back" style="background-color: #7A1E3A; color: #ffffffff; font-size: 14px; font-weight: bold;">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            <?php } else { ?>
                <button id="backBtn" class="btn btn-back">
                    <i class="fa fa-arrow-left"></i> Back
                </button>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>

<script>
    
$("#backBtn").click(function(){
    window.history.back();
});

</script>