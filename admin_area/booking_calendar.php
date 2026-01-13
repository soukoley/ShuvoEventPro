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
        Swal.fire({
            title:info.event.title,
            html:`Status: <b>${info.event.extendedProps.status}</b>`,
			html:`Guest: <b>${info.event.extendedProps.max_guest}</b>`,
            confirmButtonText:'OK'
        });
    }
});

calendar.render();
</script>

</body>
</html>
