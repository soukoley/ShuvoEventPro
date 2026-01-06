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
        $(function(){

            $("#start_date").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                minDate: 0,
                onSelect: function(dateText){
                    // start date select হলে end date minimum সেট হবে
                    $("#end_date").datepicker("option", "minDate", dateText);
                }
            });

            $("#end_date").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                minDate: 0
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
                <hr>
                    <!-- Customer -->
                    <h4>Customer Details</h4>
                    <p>
                        <strong>Name :</strong> <?php echo $c_name; ?><br>
                        <strong>Address :</strong> <?php echo $c_addr; ?><br>
                        <strong>Contact :</strong> <?php echo $c_mobile; ?>
                    </p>
                <hr>

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
                <h4>Edit Booking Details</h4>

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


                <!-- Facilities -->
                <hr>
                <h4><strong>List of Facilities</strong></h4>
                <hr>

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
                <hr>

                <!-- ADD NEW FACILITY -->
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

                <hr>

            <button id="confirmBooking" class="btn btn-success btn-lg">
            Confirm
            </button>

        </div>
    </div>
</div>
<script>

$(function(){

    $("#start_date").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        onSelect: function(dateText){
            // start date select হলে end date minimum সেট হবে
            $("#end_date").datepicker("option", "minDate", dateText);
        }
    });

    $("#end_date").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });

    // 📅 icon click করলে calendar open হবে
    $("#start_date_icon").click(function(){
        $("#start_date").datepicker("show");
    });

    $("#end_date_icon").click(function(){
        $("#end_date").datepicker("show");
    });

});

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
}


// qty change
$(document).on("input",".qty",function(){
    recalc();
});

// remove
$(document).on("click",".removeRow",function(){
    $(this).closest("tr").remove();
    recalc();
});

// ADD NEW FACILITY (FIXED)
$("#addFacility").click(function(){

    let opt = $("#facility_id option:selected");
    let fid = opt.val();
    let fname = opt.text();
    let rate = parseFloat(opt.data("rate")) || 0;
    let qty = parseInt($("#new_qty").val()) || 1;
    
    if(!fid){
        alert("Select facility");
        return;
    }

    let total = rate * qty;
    let cleanName = fname.split('(')[0].trim();

    let row = `
    <tr data-id="${fid}" data-rate="${rate}">
        <td class="fac_name text-left">${cleanName}</td>
        <td>
            <input type="number" class="form-control qty" value="${qty}" min="1">
        </td>
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

$("#maxPeople").on("change", function(){

    let maxGuest = $(this).val();
    let eventName = "<?= $e_name ?>";

    if(!maxGuest) return;

    $.ajax({
        url: "fetch_facility_by_guest.php",
        type: "POST",
        data: {
            max_guest: maxGuest,
            e_name: eventName
        },
        dataType: "json",
        success: function(facilities){

            /* ===============================
               1️⃣ UPDATE FACILITY DROPDOWN
               =============================== */
            let options = `<option value="">-- Select Facility --</option>`;
            facilities.forEach(f => {
                options += `
                    <option value="${f.id}" data-rate="${parseFloat(f.fPrice)}">
                        ${f.fName} (₹${parseFloat(f.fPrice).toFixed(2)})
                    </option>`;
            });
            $("#facility_id").html(options);


            /* =========================================
               2️⃣ UPDATE EXISTING FACILITY TABLE RATES
               (Garden included)
               ========================================= */
            $("#facilityTable tbody tr").each(function(){

                let row = $(this);
                let fid = row.data("id");

                let match = facilities.find(f => f.id == fid);

                if(match){
                    let newRate = parseFloat(match.fPrice);
                    let qty = parseInt(row.find(".qty").val()) || 1;

                    // 🔥 CRITICAL: update BOTH data-rate & text
                    row.attr("data-rate", newRate);
                    row.data("rate", newRate);

                    row.find(".rate").text(newRate.toFixed(2));
                    row.find(".total").text((qty * newRate).toFixed(2));
                }
            });

            /* ===============================
               3️⃣ RECALCULATE GRAND TOTAL
               =============================== */
            recalc();
        }
    });
});


// INITIAL
recalc();

$("#confirmBooking").click(function(){
    Swal.fire({
        title:'Confirm Booking?',
        text:'All changes will be saved permanently',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Yes, Confirm'
    }).then((r)=>{
        if(r.isConfirmed){

            let facilities = [];

            $("#facilityTable tbody tr").each(function(){
                facilities.push({
                    facility_id: $(this).data("id"),
                    qty: $(this).find(".qty").val(),
                    rate: $(this).data("rate")
                });
            });

            let bookingData = {
                booking_id: "<?= $booking_id ?>",
                start_date: $("#start_date").val(),
                start_time: $("#start_time").val(),
                end_date: $("#end_date").val(),
                end_time: $("#end_time").val(),
                max_guest: $("#max_guest").val(),
                facilities: JSON.stringify(facilities)
            };

            /* $.post("confirm_booking_action.php", bookingData, function(res){
                let r = JSON.parse(res);

                Swal.fire("Confirmed","Booking Confirmed","success")
                .then(()=>{
                    window.open(r.invoice,"_blank");
                    window.location="index.php?view_booking";
                });
            }); */

            $.ajax({
                url: 'confirm_booking_action.php',
                type: 'POST',
                contentType: 'application/json',
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

</script>

</body>
</html>
