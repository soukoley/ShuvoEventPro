<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'], $_GET['receive_Payment'])){
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}

$booking_id = $_GET['id'];

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

$qryP = "SELECT * FROM payment WHERE booking_id='$booking_id'";
$resultP = mysqli_query($con, $qryP);

$fac_res = mysqli_query($con,"
SELECT bf.*, f.fName, f.gst_rate
FROM booking_facilities bf
JOIN facility f ON bf.facility_id=f.id
WHERE bf.booking_id='$booking_id'
");

$disc_amt = 0.00;
$gross_amt = 0.00;
$net_amt = 0.00;
$adv_amt = 0.00;
$due_amt = 0.00;


if ($resultP && mysqli_num_rows($resultP) > 0) {
    $payRow = mysqli_fetch_assoc($resultP);

    $disc_amt  = $payRow['disc_amt'];
    $adv_amt   = $payRow['adv_amt'];
    $gross_amt = $payRow['gross_amt'];
    $net_amt   = $payRow['net_amt'];
    $due_amt   = $payRow['due_amt'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Receive Payment</title>
</head>
<body>

<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-credit-card"></i>
				Payment / View Payments / Receive Payment
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
                
                <!-- ================= PAYMENT SECTION ================= -->

                <h4 class="section-title">
                    <i class="fa fa-credit-card"></i> Payment Section
                </h4>

                <!-- ================= PAYMENT SUMMARY ================= -->
                 <div class="row payment-summary-row">
                    <div class="col-md-4">
                        <label class="pay-label">Total Amount</label>
                        <div class="pay-value final">
                            ₹ <span id="finalAmount">0.00</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Discount Given</label>
                        <div class="pay-value discount">
                            ₹ <span id="discountAmount">0.00</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Advance Payment</label>
                        <div class="pay-value advance">
                            ₹ <span id="advanceAmount">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="row payment-summary-row" style="padding-top: 20px;">
                    <div class="col-md-4">
                        <label class="pay-label">Final Payable Amount</label>
                        <div class="pay-value payable">
                            ₹ <span id="remainingAmount">0.00</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Received Amount</label>
                        <input type="number"
                            id="receivedAmount"
                            class="form-control received-input"
                            placeholder="Enter received amount">
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Due Amount</label>
                        <div class="pay-value due">
                            ₹ <span id="duePayment">0.00</span>
                        </div>
                    </div>
                </div>

                <!-- ================= PAYMENT TYPE ================= -->
                <div class="row" style="padding-top: 20px;">
                    <div class="col-md-4">
                        <label class="pay-label">Payment Type</label>
                        <select id="paymentType" class="form-control">
                            <option value="">-- Select Payment Type --</option>
                            <option value="CASH">Cash</option>
                            <option value="UPI">UPI</option>
                            <option value="NEFT">NEFT</option>
                            <option value="CHEQUE">Cheque</option>
                        </select>
                    </div>
                </div>

                <!-- ================= CASH ================= -->
                <div class="row payment-box" id="cashBox" style="display:none;">
                    <div class="col-md-12">
                        <p class="cash-note">
                            ✅ Cash payment selected. No extra details needed.
                        </p>
                    </div>
                </div>

                <!-- ================= UPI ================= -->
                <div class="row payment-box" id="upiBox" style="display:none;">
                    <div class="col-md-4">
                        <label class="pay-label">Transaction ID / Reference</label>
                        <input type="text" id="upiRef" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="pay-label">Transaction Date</label>
                        <input type="date" id="upiDate" class="form-control">
                    </div>
                </div>

                <!-- ================= NEFT ================= -->
                <div class="row payment-box" id="neftBox" style="display:none;">
                    <div class="col-md-4">
                        <label class="pay-label">Bank Name</label>
                        <input type="text" id="neftBank" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="pay-label">IFSC Code</label>
                        <input type="text" id="neftIfsc" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="pay-label">Account Name</label>
                        <input type="text" id="neftAccountName" class="form-control">
                    </div>

                    <div class="col-md-4" style="margin-top:10px;">
                        <label class="pay-label">Account Number</label>
                        <input type="text" id="neftAccountNo" class="form-control">
                    </div>
                    <div class="col-md-4" style="margin-top:10px;">
                        <label class="pay-label">Transaction Date</label>
                        <input type="date" id="neftDate" class="form-control">
                    </div>
                    <div class="col-md-4" style="margin-top:10px;">
                        <label class="pay-label">Reference Number</label>
                        <input type="text" id="neftTxn" class="form-control">
                    </div>
                </div>

                <!-- ================= CHEQUE ================= -->
                <div class="row payment-box" id="chequeBox" style="display:none;">
                    <div class="col-md-4">
                        <label class="pay-label">Bank Name</label>
                        <input type="text" id="chequeBank" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="pay-label">IFSC Code</label>
                        <input type="text" id="chequeIfsc" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="pay-label">Account Name</label>
                        <input type="text" id="chequeAccountName" class="form-control">
                    </div>

                    <div class="col-md-4" style="margin-top:10px;">
                        <label class="pay-label">Account Number</label>
                        <input type="text" id="chequeAccountNo" class="form-control">
                    </div>
                    <div class="col-md-4" style="margin-top:10px;">
                        <label class="pay-label">Cheque Number</label>
                        <input type="text" id="chequeNo" class="form-control">
                    </div>
                    <div class="col-md-4" style="margin-top:10px;">
                        <label class="pay-label">Cheque Date</label>
                        <input type="date" id="chequeDate" class="form-control">
                    </div>

                    <div class="col-md-4" style="margin-top:10px;">
                        <label class="pay-label">NEFT Ref No (if any)</label>
                        <input type="text" id="chequeNeftRef" class="form-control">
                    </div>
                </div>

                <hr class="soft-divider">
                    
                <!-- CONFIRM BUTTON SECTION -->
                <div class="action-btn-group">

                    <button id="backBtn" class="btn btn-back">
                        <i class="fa fa-arrow-left"></i> Back
                    </button>

                    <button id="paymentReceived" class="btn btn-complete">
                        <i class="fa fa-check-circle"></i> Payment Received
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>
<script>

const DB_DISCOUNT = <?= (float)$disc_amt ?>;
const DB_ADVANCE  = <?= (float)$adv_amt ?>;
const DB_GROSS    = <?= (float)$gross_amt ?>;
const DB_NET      = <?= (float)$net_amt ?>;
const DB_DUE      = <?= (float)$due_amt ?>;

/* ===============================
   RECALCULATE FACILITY TOTAL
================================ */
function recalc(){

    let remainingAmt = DB_NET - DB_ADVANCE;

    // ✅ SPANS → use .text()
    $("#finalAmount").text(DB_GROSS.toFixed(2));
    $("#discountAmount").text(DB_DISCOUNT.toFixed(2));
    $("#advanceAmount").text(DB_ADVANCE.toFixed(2));
    $("#remainingAmount").text(remainingAmt.toFixed(2));
    $("#duePayment").text(DB_DUE.toFixed(2));
}

/* ===============================
   INITIAL LOAD
================================ */
$(document).ready(function(){
    recalc();
});

/* ===============================
   DUE LOGIC
================================ */
function calculateDue() {
    let payable = parseFloat($("#remainingAmount").text()) || 0;
    let received = parseFloat($("#receivedAmount").val()) || 0;

    // Validation
    if (received > payable) {
        Swal.fire(
            'Invalid Amount',
            'Received amount cannot be greater than due amount',
            'warning'
        );
        $("#receivedAmount").val("");
        $("#duePayment").text(payable.toFixed(2));
        return;
    }

    let due = payable - received;
    $("#duePayment").text(due.toFixed(2));
}

$(document).on("input", "#receivedAmount", function () {
    calculateDue();
});


/* ===============================
   COMPLETE BOOKING
================================ */
function startLoading(){
    $("#paymentReceived")
        .addClass("btn-loading")
        .prop("disabled", true)
        .text("Processing...");

    $("#backBtn").prop("disabled", true);
}

function stopLoading(){
    $("#paymentReceived")
        .removeClass("btn-loading")
        .prop("disabled", false)
        .html('<i class="fa fa-check-circle"></i> Complete Booking');

    $("#backBtn").prop("disabled", false);
}

$("#paymentReceived").click(function(){
    Swal.fire({
        title:'Confirm Payment?',
        text:'All changes will be saved permanently',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Yes, Confirm'
    }).then((r)=>{
        if(r.isConfirmed){

            /* ===============================
               PAYMENT DETAILS COLLECT
               (AMOUNT FROM ADVANCE)
            ================================ */

            let paymentType = $("#paymentType").val();
            let received_amount = $("#receivedAmount").val();
            let paymentDetails = {};

            if (!paymentType) {
                Swal.fire('Error', 'Please select payment type', 'warning');
                return;
            }

            if (!received_amount || received_amount <= 0) {
                Swal.fire('Error', 'Received amount is required for payment', 'warning');
                return;
            }

            /* -------- CASH -------- */
            if (paymentType === "CASH") {
                paymentDetails = {
                    note: "Cash payment selected. No extra details needed."
                };
            }

            /* -------- CHEQUE -------- */
            else if (paymentType === "CHEQUE") {
                paymentDetails = {
                    bank_name: $("#chequeBank").val(),
                    ifsc_code: $("#chequeIfsc").val(),
                    account_name: $("#chequeAccountName").val(),
                    account_number: $("#chequeAccountNo").val(),
                    cheque_number: $("#chequeNo").val(),
                    cheque_date: $("#chequeDate").val(),
                    neft_ref_no: $("#chequeNeftRef").val()
                };

                if (!paymentDetails.cheque_number || !paymentDetails.cheque_date) {
                    Swal.fire('Error', 'Cheque number and date are required', 'warning');
                    return;
                }
            }

            /* -------- UPI -------- */
            else if (paymentType === "UPI") {
                paymentDetails = {
                    transaction_ref: $("#upiRef").val(),
                    transaction_date: $("#upiDate").val()
                };

                if (!paymentDetails.transaction_ref) {
                    Swal.fire('Error', 'UPI transaction reference is required', 'warning');
                    return;
                }
            }

            /* -------- NEFT -------- */
            else if (paymentType === "NEFT") {
                paymentDetails = {
                    bank_name: $("#neftBank").val(),
                    ifsc_code: $("#neftIfsc").val(),
                    account_name: $("#neftAccountName").val(),
                    account_number: $("#neftAccountNo").val(),
                    transaction_date: $("#neftDate").val(),
                    reference_number: $("#neftTxn").val()
                };

                if (!paymentDetails.reference_number) {
                    Swal.fire('Error', 'NEFT reference number is required', 'warning');
                    return;
                }
            }

            /* ===============================
               BOOKING DATA (FINAL)
            ================================ */
            let bookingData = {
                booking_id: "<?= $booking_id ?>",
                final_amount: $("#finalAmount").text(),
                discount_amount: $("#discountAmount").text(),
                advance_amount: $("#advanceAmount").text(),
                received_amount: received_amount,
                due_amount: $("#duePayment").text(),
                payment_type: paymentType,
                payment_details: paymentDetails
            };

            startLoading();

            /* ===============================
               AJAX SUBMIT
            ================================ */
            $.ajax({
                url: 'confirm_payment_action.php',
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(bookingData),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Confirmed',
                            text: 'Payment Confirmed',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            window.history.back();
                        });
                    } else {
                        stopLoading();
                        Swal.fire('Error', response.error || "Payment failed.", 'error');
                    }
                },
                error: function () {
                    stopLoading();
                    Swal.fire('Error', 'Server error while confirming payment', 'error');
                }

            });
        }
    });
});

$("#paymentType").on("change", function () {

    let type = $(this).val();

    // hide all first
    $(".payment-box").hide();

    if (type === "CASH") {
        $("#cashBox").slideDown();
    }
    else if (type === "UPI") {
        $("#upiBox").slideDown();
    }
    else if (type === "NEFT") {
        $("#neftBox").slideDown();
    }
    else if (type === "CHEQUE") {
        $("#chequeBox").slideDown();
    }
});

$("#backBtn").click(function(){
    window.history.back();
});

</script>

</body>
</html>
