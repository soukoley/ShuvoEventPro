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
SELECT bf.*,f.fName 
FROM booking_facilities bf
JOIN facility f ON bf.facility_id=f.id
WHERE bf.booking_id='$booking_id'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Booking</title>
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
				Booking / View Booking / Confirm Booking
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

                <!-- Event -->
                <h4><strong>Event Name :</strong> <?php echo $e_name; ?></h4>
                <h4><strong>Booking Id :</strong> <?php echo $booking_id; ?></h4>
                <hr style="border: none; height: 1px; background: #D4A017;">

                <!-- Customer -->
                <h4><strong>Customer Details</strong></h4>
                <p>
                    <strong>Name :</strong> <?php echo $c_name; ?><br>
                    <strong>Address :</strong> <?php echo $c_addr; ?><br>
                    <strong>Contact :</strong> <?php echo $c_mobile; ?>
                </p>
                <hr style="border: none; height: 1px; background: #D4A017;">

                <!-- Guest -->
                <!-- <p><strong>Maximum Guest :</strong> <?php echo $old_max_guest; ?></p> -->

                <!-- Date Time -->
                <!-- <p>
                    <strong>Check-In :</strong>
                    <?php echo date("d M Y", strtotime($start_date)) . " " . $start_time; ?>
                    <br>
                    <strong>Check-Out :</strong>
                    <?php echo date("d M Y", strtotime($end_date)) . " " . $end_time; ?>
                </p> -->
                <h4><strong>Edit Booking Details</strong></h4>
                <div class="row">
                    <!-- Check-In -->
                    <div class="col-md-3">
                        <label>Check-In Date</label>
                        <div class="input-group">
                            <input type="text"
                                id="start_date"
                                name="start_date"
                                class="form-control"
                                placeholder="dd/mm/yyyy"
                                value="<?= date('d/m/Y', strtotime($start_date)) ?>"
                                autocomplete="off">
                            <span class="input-group-addon" id="start_date_icon" style="cursor:pointer">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label>Check-In Time</label>
                        <input type="time"
                            id="start_time"
                            name="start_time"
                            class="form-control"
                            value="<?= $start_time ?>">
                    </div>

                    <!-- Check-Out -->
                    <div class="col-md-3">
                        <label>Check-Out Date</label>
                        <div class="input-group">
                            <input type="text"
                                id="end_date"
                                name="end_date"
                                class="form-control"
                                placeholder="dd/mm/yyyy"
                                value="<?= date('d/m/Y', strtotime($end_date)) ?>"
                                autocomplete="off">
                            <span class="input-group-addon" id="end_date_icon" style="cursor:pointer">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label>Check-Out Time</label>
                        <input type="time"
                            id="end_time"
                            name="end_time"
                            class="form-control"
                            value="<?= $end_time ?>">
                    </div>


                    <div class="col-md-2">
                        <label>Max Guest</label>
                        <select class="form-control" id="maxPeople" name="max_guest">
                            <option value="">-- Select Guest --</option>

                            <?php
                            $fqry = "SELECT * FROM guest";
                            $r = mysqli_query($con, $fqry);

                            while ($x = mysqli_fetch_assoc($r)) {

                                $value = $x['max_guest']; // cast
                                $selected = ($value == $old_max_guest) ? 'selected' : '';

                                echo "<option value='$value' $selected>$value</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <hr style="border: none; height: 1px; background: #D4A017;">

                <!-- Facilities -->
                
                <h4><strong>List of Facilities</strong></h4>

                <!-- RESPONSIVE TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="facilityTable">
                        <thead>
                            <tr>
                                <!-- <th class="text-center">#</th> -->
                                <th class="text-left">Facility</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Rate</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                        //$i=1;
                        while($f=mysqli_fetch_assoc($fac_res)){
                    ?>
                        <tr data-id="<?= $f['facility_id'] ?>" data-rate="<?= $f['rate'] ?>">
                            <!-- <td class="text-center"><?= $i++ ?></td> -->
                            <td class="text-left"><?= $f['fName'] ?></td>
                            <td class="text-center"><input type="number" class="form-control qty" value="<?= $f['qty'] ?>" min="1"></td>
                            <td class="rate text-right"><?= $f['rate'] ?></td>
                            <td class="total text-right"><?= $f['tot_amt'] ?></td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-xs removeRow">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php 
                        }
                    ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Grand Total</th>
                            <th class="text-right" id="grandTotal"></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <hr style="border: none; height: 1px; background: #D4A017;">

                <!-- ADD NEW FACILITY -->
                <h4><strong>Addition of Facilities</strong></h4>
                <div class="row">
                    <div class="col-sm-4">
                        <select id="facility_id" class="form-control">
                        <option value="">-- Select Facility --</option>
                        <?php
                        $fqry = "SELECT * FROM facility WHERE (max_people=$old_max_guest and eName='$e_name') or eName='ALL' ORDER BY fName ASC";
                        $r=mysqli_query($con, $fqry);
                        while($x=mysqli_fetch_assoc($r)){
                            echo "<option value='{$x['id']}' data-rate='{$x['fPrice']}'>
                                    {$x['fName']} (₹{$x['fPrice']})
                                </option>";
                        }
                        ?>
                        </select>
                    </div>

                    <div class="col-sm-2">
                    <input type="number" id="new_qty" class="form-control" placeholder="Qty">
                    </div>

                    <div class="col-sm-2">
                    <button id="addFacility" class="btn btn-primary">Add</button>
                    </div>
                </div>
                <hr style="border: none; height: 1px; background: #D4A017;">

                <!-- DISCOUNT SECTION -->
                <h4><strong>Discount Section</strong></h4>
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-4">
                        <label>Discount Type</label>
                        <select id="discountType" class="form-control">
                            <option value="">-- No Discount --</option>
                            <option value="flat">Flat (₹)</option>
                            <option value="percent">Percentage (%)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Discount Value</label>
                        <input type="number" id="discountValue" class="form-control" placeholder="Enter discount">
                    </div>

                    <div class="col-md-4">
                        <label>Payable Amount</label>
                        <input type="text" id="payableAmount" class="form-control" readonly>
                    </div>
                </div>
                <hr style="border: none; height: 1px; background: #D4A017;">

                <!-- ADVANCE / DUE PAYMENT -->
                <h4><strong>Payment Section</strong></h4>
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-4">
                        <label>Payable Amount</label>
                        <input type="text" id="finalPayable" class="form-control" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Advance Paid</label>
                        <input type="number" id="advancePaid" class="form-control" placeholder="Enter advance amount">
                    </div>

                    <div class="col-md-4">
                        <label>Due Amount</label>
                        <input type="text" id="dueAmount" class="form-control" readonly>
                    </div>
                </div>
                <!-- <hr style="border: none; height: 1px; background: #D4A017;"> -->

                <!-- PAYMENT DETAILS -->
                <!-- <h4><strong>Payment Details</strong></h4> -->
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-4">
                        <label>Payment Type</label>
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
                        <p style="margin-top:10px;color:#2e7d32;font-weight:600;">
                            ✅ Note : Cash payment selected. No extra details needed.
                        </p>
                    </div>
                </div>

                <!-- ================= UPI ================= -->
                <div class="row payment-box" id="upiBox" style="display:none;">
                    <div class="col-md-4">
                        <label>Transaction ID / Reference</label>
                        <input type="text" id="upiRef" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Transaction Date</label>
                        <input type="date" id="upiDate" class="form-control">
                    </div>
                </div>

                <!-- ================= NEFT ================= -->
                <div class="row payment-box" id="neftBox" style="display:none;">
                    <div class="col-md-4">
                        <label>Bank Name</label>
                        <input type="text" id="neftBank" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>IFSC Code</label>
                        <input type="text" id="neftIfsc" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Account Name</label>
                        <input type="text" id="neftAccountName" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Account Number</label>
                        <input type="text" id="neftAccountNo" class="form-control">
                    </div>
                    <div class="col-md-4" style="margin-top:10px;">
                        <label>Transaction Date</label>
                        <input type="date" id="neftDate" class="form-control">
                    </div>
                    <div class="col-md-4" style="margin-top:10px;">
                        <label>Reference Number</label>
                        <input type="text" id="neftTxn" class="form-control">
                    </div>
                </div>

                <!-- ================= CHEQUE ================= -->
                <div class="row payment-box" id="chequeBox" style="display:none;">
                    <div class="col-md-4">
                        <label>Bank Name</label>
                        <input type="text" id="chequeBank" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>IFSC Code</label>
                        <input type="text" id="chequeIfsc" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Account Name</label>
                        <input type="text" id="chequeAccountName" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Account Number</label>
                        <input type="text" id="chequeAccountNo" class="form-control">
                    </div>
                    <div class="col-md-4" style="margin-top:10px;">
                        <label>Cheque Number</label>
                        <input type="text" id="chequeNo" class="form-control">
                    </div>
                    <div class="col-md-4" style="margin-top:10px;">
                        <label>Cheque Date</label>
                        <input type="date" id="chequeDate" class="form-control">
                    </div>

                    <div class="col-md-4" style="margin-top:10px;">
                        <label>NEFT Ref No (if any)</label>
                        <input type="text" id="chequeNeftRef" class="form-control">
                    </div>
                </div>
                <hr style="border: none; height: 1px; background: #D4A017;">
                    
                <!-- CONFIRM BUTTON SECTION -->
                <button id="confirmBooking" class="btn btn-success btn-lg confirm-btn">
                    <i class="fa fa-check-circle"></i> Confirm Booking
                </button>
            </div>
        </div>
    </div>
</div>
<script>

/* ===============================
   RECALCULATE FACILITY TOTAL
================================ */
function recalc(){
    let grand = 0;

    $("#facilityTable tbody tr").each(function(){
        let rate = parseFloat($(this).data("rate")) || 0;
        let qty  = parseInt($(this).find(".qty").val()) || 1;

        let total = rate * qty;
        $(this).find(".total").text(total.toFixed(2));
        grand += total;
    });

    $("#grandTotal").text(grand.toFixed(2));

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

/* ===============================
   ADD NEW FACILITY
================================ */
$("#addFacility").click(function(){

    let opt  = $("#facility_id option:selected");
    let fid  = opt.val();
    let fname= opt.text();
    let rate = parseFloat(opt.data("rate")) || 0;
    let qty  = parseInt($("#new_qty").val()) || 1;

    if(!fid){
        alert("Select facility");
        return;
    }

    let total = rate * qty;
    let cleanName = fname.split('(')[0].trim();

    let row = `
        <tr data-id="${fid}" data-rate="${rate}">
            <td class="text-left">${cleanName}</td>
            <td><input type="number" class="form-control qty" value="${qty}" min="1"></td>
            <td class="rate text-right">${rate.toFixed(2)}</td>
            <td class="total text-right">${total.toFixed(2)}</td>
            <td class="text-center">
                <button class="btn btn-danger btn-xs removeRow">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>`;

    $("#facilityTable tbody").append(row);

    $("#new_qty").val("");
    $("#facility_id").val("");

    recalc();
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
                    let qty = parseInt(row.find(".qty").val()) || 1;

                    row.attr("data-rate", newRate);
                    row.data("rate", newRate);

                    row.find(".rate").text(newRate.toFixed(2));
                    row.find(".total").text((qty * newRate).toFixed(2));
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
    let grand = parseFloat($("#grandTotal").text()) || 0;
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
                    facility_id: $(this).data("id"),
                    qty: $(this).find(".qty").val(),
                    rate: $(this).data("rate")
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

                facilities: JSON.stringify(facilities)
            };

            /* ===============================
               AJAX SUBMIT
            ================================ */
            $.ajax({
                url: 'confirm_booking_action.php',
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(bookingData),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Confirmed',
                            text: 'Booking Confirmed',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            window.location.href = "index.php?view_booking";
                        });
                    } else {
                        Swal.fire('Error', response.error || "Confirmation failed.", 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', "Error while confirming booking: " + xhr.responseText, 'error');
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
