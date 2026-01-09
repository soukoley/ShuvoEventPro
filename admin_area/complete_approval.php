<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'])){
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

$disc_amt = 0.00;
$adv_amt = 0.00;

if ($resultP && mysqli_num_rows($resultP) > 0) {
    $payRow = mysqli_fetch_assoc($resultP);

    $disc_amt  = $payRow['disc_amt'];
    $adv_amt   = $payRow['adv_amt'];
}

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
    <title>Pending Approval</title>
</head>
<body>

<div class="row">
	<div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
		<div class="breadcrumb">
			<li class="active">
				<i class="fa fa-fw fa-calendar-check-o"></i>
				Booking / Approved Booking / Pending Approval
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
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($f=mysqli_fetch_assoc($fac_res)){ ?>
                            <tr data-id="<?= $f['facility_id'] ?>"
                                data-rate="<?= $f['rate'] ?>"
                                data-gst-rate="<?= $f['gst_rate'] ?>">

                                <td><?= $f['fName'] ?></td>

                                <td class="text-center" style="width:90px;">
                                    <input type="number"
                                        class="form-control qty text-center"
                                        value="<?= $f['qty'] ?>"
                                        min="1">
                                </td>

                                <td class="text-right rate"><?= number_format($f['rate'],2) ?></td>
                                <td class="text-right taxable"><?= number_format($f['taxableAmt'],2) ?></td>
                                <td class="text-right taxRate"><?= $f['gst_rate'] ?>%</td>
                                <td class="text-right gst"><?= number_format($f['gstAmt'],2) ?></td>
                                <td class="text-right total"><?= number_format($f['netAmt'],2) ?></td>

                                <td class="text-center">
                                    <button class="btn btn-danger btn-xs removeRow">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>

                        <tfoot>
                            <tr class="grand-row">
                                <th colspan="3" class="text-right">Grand Total</th>
                                <th class="text-right" id="grandTaxable">0.00</th>
                                <th></th>
                                <th class="text-right" id="grandGST">0.00</th>
                                <th class="text-right" id="grandTotal">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <hr class="soft-divider">

                <!-- ================= ADD FACILITY ================= -->
                <h4 class="section-title">
                    <i class="fa fa-plus-circle"></i> Addition of Facilities
                </h4>

                <div class="row add-facility-row">
                    <div class="col-md-4">
                        <label class="pay-label">Facility</label>
                        <select id="facility_id" class="form-control">
                            <option value="">-- Select Facility --</option>
                            <?php
                            $fqry = "SELECT * FROM facility 
                                    WHERE (max_people=$old_max_guest AND eName='$e_name') 
                                    OR eName='ALL'
                                    ORDER BY fName ASC";
                            $r=mysqli_query($con, $fqry);
                            while($x=mysqli_fetch_assoc($r)){
                                echo "<option value='{$x['id']}'
                                            data-rate='{$x['fPrice']}'
                                            data-gst_rate='{$x['gst_rate']}'>
                                        {$x['fName']} (₹{$x['fPrice']})
                                    </option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="pay-label">Quantity</label>
                        <input type="number" id="new_qty" class="form-control" placeholder="Qty">
                    </div>

                    <div class="col-md-2" style="margin-top:24px;">
                        <button id="addFacility" class="btn btn-primary btn-block">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
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
                        <label class="pay-label">Advance Paid</label>
                        <div class="pay-value advance">
                            ₹ <span id="advancePaid">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="row payment-summary-row" style="padding-top: 20px;">
                    <div class="col-md-4">
                        <label class="pay-label">Payable Amount</label>
                        <div class="pay-value payable">
                            ₹ <span id="finalPayable">0.00</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Received Amount</label>
                        <input type="number"
                            id="receivedAmount"
                            class="form-control received-input"
                            placeholder="Enter received amount">
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
                <button id="confirmBooking" class="btn btn-success btn-lg confirm-btn">
                    <i class="fa fa-check-circle"></i> Complete Booking
                </button>
            </div>
        </div>
    </div>
</div>
<script>

const DB_DISCOUNT = <?= (float)$disc_amt ?>;
const DB_ADVANCE  = <?= (float)$adv_amt ?>;

/* ===============================
   RECALCULATE FACILITY TOTAL
================================ */
function recalc(){
    let totTaxable = 0;
    let totTax = 0;
    let totNet = 0;

    $("#facilityTable tbody tr").each(function(){
        let row = $(this);

        let rate = parseFloat(row.data("rate")) || 0;
        let gstRate = parseFloat(row.data("gst-rate")) || 0;
        let qty = parseInt(row.find(".qty").val()) || 0;

        let taxableAmt = rate * qty;
        let gstAmt = (taxableAmt * gstRate)/100;
        let netAmt = taxableAmt + gstAmt;

        // 🔹 update UI
        row.find(".taxable").text(taxableAmt.toFixed(2));
        row.find(".gst").text(gstAmt.toFixed(2));
        row.find(".total").text(netAmt.toFixed(2));


        // 🔹 VERY IMPORTANT: store in data-attributes
        row.data("taxable", taxableAmt);
        row.data("tax-amt", gstAmt);
        row.data("net-amt", netAmt);

        totTaxable += taxableAmt;
        totTax += gstAmt;
        totNet += netAmt;
    });

    $("#discountAmount").val(DB_DISCOUNT.toFixed(2));
    $("#advancePaid").val(DB_ADVANCE.toFixed(2));

    $("#grandTaxable").text(totTaxable.toFixed(2));
    $("#grandGST").text(totTax.toFixed(2));
    $("#grandTotal").text(totNet.toFixed(2));

    $("#finalAmount").text(totNet.toFixed(2));

    // 🔥 always re-apply discount after recalculation
}

/* ===============================
   QTY CHANGE / REMOVE
================================ */
$(document).on("input", ".qty", function(){
    recalc();
});

$(document).on("click", ".removeRow", function(){
    $(this).closest("tr").remove();
    recalc();
});

function findFacilityRow(fid) {
    let found = null;

    $("#facilityTable tbody tr").each(function () {
        if (parseInt($(this).data("id")) === parseInt(fid)) {
            found = $(this);
            return false; // break loop
        }
    });

    return found;
}


/* ===============================
   ADD NEW FACILITY
================================ */
$("#addFacility").click(function(){

    let opt  = $("#facility_id option:selected");
    let fid  = parseInt(opt.val());
    let fname= opt.text();
    let rate = parseFloat(opt.data("rate")) || 0;
    let gstRate = parseFloat(opt.data("gst-rate")) || 0;
    let qty  = parseInt($("#new_qty").val()) || 1;

    if(!fid){
        alert("Select facility");
        return;
    }

    let cleanName = fname.split('(')[0].trim();

    // 🔍 check duplicate
    let existingRow = findFacilityRow(fid);

    if(existingRow){
        // 🔁 MERGE LOGIC
        let oldQty = parseInt(existingRow.find(".qty").val()) || 0;
        let newQty = oldQty + qty;

        existingRow.find(".qty").val(newQty);

    } else {

        // ➕ NEW ROW
        let taxableAmt = rate * qty;
        let gstAmt = taxableAmt * gstRate / 100;
        let netAmt = taxableAmt + gstAmt;

        let row = `
            <tr data-id="${fid}" data-rate="${rate}" data-gst-rate="${gstRate}"
                data-taxable="${taxableAmt}" data-tax-amt="${gstAmt}" data-net-amt="${netAmt}">
                <td class="text-left">${cleanName}</td>
                <td><input type="number" class="form-control qty" value="${qty}" min="1"></td>
                <td class="rate text-right">${rate.toFixed(2)}</td>
                <td class="taxable text-right">${taxableAmt.toFixed(2)}</td>
                <td class="taxRate text-right">${gstRate.toFixed(2)}</td>
                <td class="gst text-right">${gstAmt.toFixed(2)}</td>
                <td class="total text-right">${netAmt.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-danger btn-xs removeRow">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;

        $("#facilityTable tbody").append(row);
    }

    $("#new_qty").val("");
    $("#facility_id").val("");

    recalc(); // 🔥 always recalc
});

/* ===============================
   INITIAL LOAD
================================ */
$(document).ready(function(){
    recalc();
});

/* ===============================
   CONFIRM BOOKING
================================ */
$("#confirmBooking").click(function(){
    Swal.fire({
        title:'Confirm Booking?',
        text:'All changes will be saved permanently',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Yes, Confirm'
    }).then((r)=>{
        if(r.isConfirmed){

            /* ===============================
               FACILITIES COLLECT
            ================================ */
            let facilities = [];
            $("#facilityTable tbody tr").each(function(){
                facilities.push({
                    facility_id: parseInt($(this).find(".id").val()) || 0,
                    qty: parseInt($(this).find(".qty").val()) || 0,
                    rate: parseFloat($(this).data("rate")) || 0,
                    taxableAmt: parseFloat($(this).data("taxable")) || 0,
                    gstRate: parseFloat($(this).data("gst-rate")) || 0,
                    gstAmt: parseFloat($(this).data("tax-amt")) || 0,
                    netAmt: parseFloat($(this).data("net-amt")) || 0
                });
            });

            /* ===============================
               PAYMENT DETAILS COLLECT
               (AMOUNT FROM ADVANCE)
            ================================ */
            let paymentType = $("#paymentType").val();
            let advanceAmount = $("#advancePaid").val();
            let paymentDetails = {};

            if (!paymentType) {
                Swal.fire('Error', 'Please select payment type', 'warning');
                return;
            }

            if (!advanceAmount || advanceAmount <= 0) {
                Swal.fire('Error', 'Advance amount is required for payment', 'warning');
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
                start_date: $("#start_date").val(),
                start_time: $("#start_time").val(),
                end_date: $("#end_date").val(),
                end_time: $("#end_time").val(),
                max_guest: $("#maxPeople").val(),

                discount_type: $("#discountType").val(),
                discount_value: $("#discountValue").val(),
                payable_amount: $("#payableAmount").val(),
                advance_paid: advanceAmount,
                due_amount: $("#dueAmount").val(),

                payment_type: paymentType,
                payment_details: paymentDetails,

                facilities: facilities
            };

            /* ===============================
               AJAX SUBMIT
            ================================ */
            $.ajax({
                url: 'approve_pending_action.php',
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(bookingData),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved',
                            text: 'Booking Approved',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            window.location.href = "index.php?pending";
                        });
                    } else {
                        Swal.fire('Error', response.error || "Approval failed.", 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', "Error while approving booking: " + xhr.responseText, 'error');
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

</script>


</body>
</html>
