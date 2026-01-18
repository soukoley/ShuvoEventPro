<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}

$booking_id = $_GET['id'];
$flag = $_GET['approve_pending'];

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
    <script>
        function highlightWeekend(date) {
            var day = date.getDay(); // 0 = Sunday, 6 = Saturday
            if (day === 0 || day === 6) {
                return [true, "weekend-date"];
            }
            return [true, ""];
        }
        $(function(){

            $("#start_date").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                minDate: 0,
                beforeShowDay: highlightWeekend,
                onSelect: function(dateText){
                    // start date select হলে end date minimum সেট হবে
                    $("#end_date").datepicker("option", "minDate", dateText);
                }
            });

            $("#end_date").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                minDate: 0,
                beforeShowDay: highlightWeekend
            });

            // 📅 icon click করলে calendar open হবে
            $("#start_date_icon").click(function(){
                $("#start_date").datepicker("show");
            });

            $("#end_date_icon").click(function(){
                $("#end_date").datepicker("show");
            });

        });

    </script>

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
                    <i class="fa fa-edit"></i> Edit Booking Details
                </h4>

                <div class="row booking-edit-row">

                    <!-- Check-In Date -->
                    <div class="col-md-3">
                        <label class="edit-label">Check-In Date</label>
                        <div class="input-group">
                            <input type="text"
                                id="start_date"
                                name="start_date"
                                class="form-control"
                                placeholder="dd/mm/yyyy"
                                value="<?= date('d/m/Y', strtotime($start_date)) ?>"
                                autocomplete="off">
                            <span class="input-group-addon calendar-icon" id="start_date_icon">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Check-In Time -->
                    <div class="col-md-2">
                        <label class="edit-label">Check-In Time</label>
                        <input type="time"
                            id="start_time"
                            name="start_time"
                            class="form-control"
                            value="<?= $start_time ?>">
                    </div>

                    <!-- Check-Out Date -->
                    <div class="col-md-3">
                        <label class="edit-label">Check-Out Date</label>
                        <div class="input-group">
                            <input type="text"
                                id="end_date"
                                name="end_date"
                                class="form-control"
                                placeholder="dd/mm/yyyy"
                                value="<?= date('d/m/Y', strtotime($end_date)) ?>"
                                autocomplete="off">
                            <span class="input-group-addon calendar-icon" id="end_date_icon">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Check-Out Time -->
                    <div class="col-md-2">
                        <label class="edit-label">Check-Out Time</label>
                        <input type="time"
                            id="end_time"
                            name="end_time"
                            class="form-control"
                            value="<?= $end_time ?>">
                    </div>

                    <!-- Max Guest -->
                    <div class="col-md-2">
                        <label class="edit-label">Max Guest</label>
                        <select class="form-control" id="maxPeople" name="max_guest">
                            <option value="">-- Select Guest --</option>
                            <?php
                            $fqry = "SELECT * FROM guest";
                            $r = mysqli_query($con, $fqry);
                            while ($x = mysqli_fetch_assoc($r)) {
                                $value = $x['max_guest'];
                                $selected = ($value == $old_max_guest) ? 'selected' : '';
                                echo "<option value='$value' $selected>$value</option>";
                            }
                            ?>
                        </select>
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
                    <i class="fa fa-tags"></i> Discount & Payment
                </h4>

                <!-- ================= DISCOUNT ================= -->
                <div class="row billing-row">
                    <div class="col-md-4">
                        <label class="pay-label">Discount Type</label>
                        <select id="discountType" class="form-control">
                            <option value="">-- No Discount --</option>
                            <option value="flat">Flat (₹)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Discount Value</label>
                        <input type="number"
                            id="discountValue"
                            class="form-control"
                            placeholder="Enter discount">
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Payable Amount</label>
                        <input type="text"
                            id="payableAmount"
                            class="form-control"
                            readonly>
                    </div>
                </div>

                <!-- ================= PAYMENT SUMMARY ================= -->
                <div class="row billing-row" style="padding-top: 20px;">
                    <div class="col-md-4">
                        <label class="pay-label">Final Payable</label>
                        <input type="text"
                            id="finalPayable"
                            class="form-control"
                            readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Advance Paid</label>
                        <input type="number"
                            id="advancePaid"
                            class="form-control"
                            placeholder="Enter advance amount">
                    </div>

                    <div class="col-md-4">
                        <label class="pay-label">Due Amount</label>
                        <input type="text"
                            id="dueAmount"
                            class="form-control"
                            readonly>
                    </div>
                </div>

                <!-- ================= PAYMENT TYPE ================= -->
                <div class="row billing-row" style="padding-top: 20px;">
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
                    <?php if($flag==5){ ?>
                        <a href="index.php?search" class="btn btn-back" style="background-color: #7A1E3A; color: #ffffffff; font-size: 14px; font-weight: bold;">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    <?php } else { ?>
                        <button id="backBtn" class="btn btn-back">
                            <i class="fa fa-arrow-left"></i> Back
                        </button>
                    <?php } ?>

                    <button id="approveBooking" class="btn btn-complete">
                        <i class="fa fa-check-circle"></i> Approve Booking
                    </button>

                </div>
                
            </div>
        </div>
    </div>
</div>
<script>

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

    $("#grandTaxable").text(totTaxable.toFixed(2));
    $("#grandGST").text(totTax.toFixed(2));
    $("#grandTotal").text(totNet.toFixed(2));

    // 🔥 always re-apply discount after recalculation
    applyDiscount();
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
                <td class="text-center" style="width:90px;"><input type="number" class="form-control qty text-center" value="${qty}" min="1"></td>
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
   MAX GUEST CHANGE
================================ */
$("#maxPeople").on("change", function(){

    let maxGuest = $(this).val();
    let eventName = "<?= $e_name ?>";
    if(!maxGuest) return;

    $.ajax({
        url: "fetch_facility_by_guest.php",
        type: "POST",
        data: { max_guest: maxGuest, e_name: eventName },
        dataType: "json",
        success: function(facilities){

            // update dropdown
            let options = `<option value="">-- Select Facility --</option>`;
            facilities.forEach(f => {
                options += `
                    <option value="${f.id}" data-rate="${parseFloat(f.fPrice)}">
                        ${f.fName} (₹${parseFloat(f.fPrice).toFixed(2)})
                    </option>`;
            });
            $("#facility_id").html(options);

            // update existing table rates
            $("#facilityTable tbody tr").each(function(){
                let row = $(this);
                let fid = row.data("id");

                let match = facilities.find(f => f.id == fid);
                if(match){
                    let newRate = parseFloat(match.fPrice);
                    let gstRate = parseFloat(match.gst_rate) || 0;
                    let qty = parseInt(row.find(".qty").val()) || 1;

                    let taxableAmt = rate * qty;
                    let gstAmt = taxableAmt * gstRate / 100;
                    let netAmt = taxableAmt + gstAmt;

                    row.attr("data-rate", newRate).data("rate", newRate);

                    // 🔹 update UI
                    row.find(".rate").text(newRate.toFixed(2));
                    row.find(".taxable").text(taxableAmt.toFixed(2));
                    row.find(".taxRate").text(gstRate.toFixed(2));
                    row.find(".gst").text(gstAmt.toFixed(2));
                    row.find(".total").text(netAmt.toFixed(2));

                    // 🔹 VERY IMPORTANT: store in data-attributes
                    row.data("taxable", taxableAmt);
                    row.data("tax-amt", gstAmt);
                    row.data("net-amt", netAmt);

                    /* row.attr("data-rate", newRate);
                    row.data("rate", newRate);

                    row.find(".rate").text(newRate.toFixed(2));
                    row.find(".total").text((qty * newRate).toFixed(2)); */
                }
            });

            recalc();
        }
    });
});

/* ===============================
   DISCOUNT LOGIC (FIXED)
================================ */
function applyDiscount() {
    let txt = $("#grandTotal").text().trim();
    if (txt === "") return;

    let grand = parseFloat(txt) || 0;
    let type  = $("#discountType").val();
    let value = parseFloat($("#discountValue").val()) || 0;

    let discount = 0;

    if (type === "flat") {
        discount = value;
    } else if (type === "percent") {
        discount = (grand * value) / 100;
    }

    if (discount > grand) discount = grand;

    let payable = grand - discount;

    $("#payableAmount").val(payable.toFixed(2));
    $("#finalPayable").val(payable.toFixed(2));

    calculateDue();
}

$("#discountType, #discountValue").on("input change", function(){
    applyDiscount();
});

/* ===============================
   ADVANCE / DUE LOGIC
================================ */
function calculateDue() {
    let payable = parseFloat($("#payableAmount").val()) || 0;
    let advance = parseFloat($("#advancePaid").val()) || 0;

    if (advance > payable) {
        Swal.fire(
            'Invalid Amount',
            'Advance cannot be greater than payable amount',
            'warning'
        );
        $("#advancePaid").val("");
        $("#dueAmount").val(payable.toFixed(2));
        return;
    }

    let due = payable - advance;
    $("#dueAmount").val(due.toFixed(2));
}

$("#advancePaid").on("input", function(){
    calculateDue();
});

/* ===============================
   INITIAL LOAD
================================ */
$(document).ready(function(){
    recalc();
});

/* ===============================
   APPROVE BOOKING
================================ */
function startLoading(){
    $("#approveBooking")
        .addClass("btn-loading")
        .prop("disabled", true)
        .text("Processing...");

    $("#backBtn").prop("disabled", true);
}

function stopLoading(){
    $("#approveBooking")
        .removeClass("btn-loading")
        .prop("disabled", false)
        .html('<i class="fa fa-check-circle"></i> Complete Booking');

    $("#backBtn").prop("disabled", false);
}
$("#approveBooking").click(function(){
    Swal.fire({
        title:'Approve Booking?',
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
                    /*facility_id: $(this).data("id"),
                    qty: $(this).find(".qty").val(),
                    rate: $(this).data("rate"),
                    taxableAmt: $(this).data("taxable"),
                    gstRate: $(this).data("gst-rate"),
                    gstAmt: $(this).data("tax-amt"),
                    netAmt: $(this).data("net-amt")*/
                    facility_id: parseInt($(this).data("id")) || 0,
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

            startLoading();

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
                        stopLoading();
                        Swal.fire('Error', response.error || "Approval failed.", 'error');
                    }
                },
                error: function (xhr) {
                    stopLoading();
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

$("#backBtn").click(function(){
    window.history.back();
});

</script>

</body>
</html>
