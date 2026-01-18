<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Booking Calendar</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    background:#f4f6f9;
    font-family:Segoe UI
}
.dark body{
    background:#121212;
    color:#eee
}

.calendar-card{
    background:#fff;
    border-radius:12px;
    padding:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.08)
}
.dark .calendar-card{
    background:#1e1e1e
}

/* ===== FORCE FULLCALENDAR v6 STATUS COLORS ===== */

.fc .fc-daygrid-event.fc-event.status-approved,
.fc .fc-timegrid-event.fc-event.status-approved,
.fc .fc-list-event.fc-event.status-approved {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: #ffffff !important;
}

.fc .fc-daygrid-event.fc-event.status-pending,
.fc .fc-timegrid-event.fc-event.status-pending,
.fc .fc-list-event.fc-event.status-pending {
    background-color: #9d36f7 !important;
    border-color: #9d36f7 !important;
    color: #000000 !important;
}

.fc .fc-daygrid-event.fc-event.status-completed,
.fc .fc-timegrid-event.fc-event.status-completed,
.fc .fc-list-event.fc-event.status-completed {
    background-color: #06c0c9 !important;
    border-color: #06c0c9 !important;
    color: #ffffff !important;
}

.fc .fc-daygrid-event.fc-event.status-rejected,
.fc .fc-timegrid-event.fc-event.status-rejected,
.fc .fc-list-event.fc-event.status-rejected {
    background-color: #f7366a !important;
    border-color: #f7366a !important;
    color: #ffffff !important;
}


.fc-tooltip {
    position: absolute;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.85);
    color: #fff;
    padding: 8px 10px;
    border-radius: 6px;
    font-size: 13px;
    pointer-events: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    max-width: 220px;
}

.calendar-header {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    background: #f2f2f2;
}

/* Normal mode text */
.calendar-header h4 {
    margin: 0;
    color: #222;
    font-weight: 600;
}

/* Button */
#darkToggle {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    background: #333;
    color: #fff;
    font-size: 14px;
}

/* 🔥 DARK MODE */
.calendar-header.dark {
    background: #111;          /* Dark background */
    border: 1px solid #444;
}

.calendar-header.dark h4 {
    color: #ffffff;            /* TEXT VISIBLE */
}

.calendar-header.dark #darkToggle {
    background: #ffffff;       /* BUTTON VISIBLE */
    color: #000;
}



}
</style>
</head>

<body>

<div style="padding:15px">

	<div class="calendar-header">
		<h4>📅 Booking Calendar</h4>
		<button id="darkToggle">🌙 Dark</button>
	</div>

    <!--div style="display:flex;gap:10px;align-items:center;margin-bottom:10px">
        <h4>📅 Booking Calendar</h4>

        <button id="darkToggle">🌙 Dark</button>
    </div-->

    <!-- LEGEND >
    <div style="margin-bottom:10px">
        <span class="fc-event status-confirmed">Confirmed</span>
        <span class="fc-event status-pending">Pending</span>
        <span class="fc-event status-cancelled">Cancelled</span>
    </div-->

    <div class="calendar-card">
        <div id="calendar"></div>
    </div>
</div>

<script>
let dark=false;
$("#darkToggle").click(()=>{
    dark=!dark;
    $("html").toggleClass("dark");
});

function getView(){
    if(window.innerWidth<576) return 'listDay';
    if(window.innerWidth<992) return 'timeGridWeek';
    return 'dayGridMonth';
}

let calendar = new FullCalendar.Calendar(
document.getElementById('calendar'),{
    initialView:getView(),
    height:'auto',

    headerToolbar:{
        left:'prev,next today',
        center:'title',
        right:'dayGridMonth,timeGridWeek,listDay'
    },

    events:'fetch_bookings.php',

    eventClick:function(info){

        let p = info.event.extendedProps;

        // Fallback values
        let cname   = p.customer_name   || "N/A";
        let mobile  = p.customer_mobile || "";
        let bdate   = p.booking_date   || "N/A";
        let status  = (p.status || "N/A").toLowerCase();
        let guests  = p.max_guest || "N/A";
        let bid     = p.booking_id || "N/A";

        // Status color mapping
        let statusColor = "#6c757d"; // default gray
        if(status === "approved")  statusColor = "#28a745";
        if(status === "pending")   statusColor = "#9d36f7";
        if(status === "completed")statusColor = "#06c0c9";
        if(status === "rejected" || status === "cancelled") statusColor = "#f7366a";

        // Format links
        let phoneLink = mobile ? `tel:${mobile}` : "#";
        let waLink = mobile 
            ? `https://wa.me/${mobile}?text=${encodeURIComponent(
                "Hello " + cname + ", regarding your booking (ID: " + bid + ")."
            )}`
            : "#";

        Swal.fire({
            title: "",
            html: `
            <div style="
                background: #d39da3;
                border-radius:12px;
                box-shadow:0 8px 20px #7B1E2B;
                padding:16px;
                font-family:Segoe UI, Arial, sans-serif;
            ">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <h3 style="margin:0;font-size:18px">
                        <i class="fa fa-calendar"></i> Booking Details
                    </h3>
                    <span style="
                        background:${statusColor};
                        color:#fff;
                        padding:4px 10px;
                        border-radius:20px;
                        font-size:12px;
                        text-transform:capitalize;
                    ">
                        ${status}
                    </span>
                </div>

                <div style="font-size:14px;line-height:1.7">
                    <p><i class="fa fa-hashtag"></i> <b>Booking ID:</b> ${bid}</p>
                    <p><i class="fa fa-calendar-check-o"></i> <b>Booking Date:</b> ${bdate}</p>
                    <p><i class="fa fa-user"></i> <b>Name:</b> ${cname}</p>
                    <p><i class="fa fa-phone"></i> <b>Mobile:</b> ${mobile || "N/A"}</p>
                    <p><i class="fa fa-users"></i> <b>Guests:</b> ${guests}</p>
                </div>

                <hr style="margin:12px 0">

                <div style="display:flex;gap:10px;justify-content:center">
                    <a href="${phoneLink}" 
                    class="swal2-confirm swal2-styled" 
                    style="text-decoration:none; color: #7B1E2B">
                        <i class="fa fa-phone"></i> Call
                    </a>

                    <a href="${waLink}" 
                    target="_blank"
                    class="swal2-cancel swal2-styled" 
                    style="text-decoration:none; color: #068522">
                        <i class="fa fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
            `,
            showConfirmButton: false,
            showCloseButton: true
        });
    }

});

calendar.render();
</script>

</body>
</html>
