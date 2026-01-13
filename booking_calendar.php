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
body{background:#f4f6f9;font-family:Segoe UI}
.dark body{background:#121212;color:#eee}

.calendar-card{
    background:#fff;border-radius:12px;
    padding:12px;box-shadow:0 4px 10px rgba(0,0,0,.08)
}
.dark .calendar-card{background:#1e1e1e}

.fc-event{font-size:11px;border-radius:6px;padding:2px}
.fc-toolbar-title{font-size:18px}

.top-bar{
    display:flex;gap:10px;align-items:center;margin-bottom:10px
}
</style>
</head>

<body>

<div class="container-fluid" style="padding:15px">
    <div class="top-bar">
        <h4 class="text-primary">📅 Booking Calendar</h4>

        <select id="hallFilter" class="form-control" style="width:180px">
            <option value="">All Halls</option>
            <?php
            include('./includes/db.php');
            $h=mysqli_query($con,"SELECT DISTINCT e_name FROM booking_details");
            while($x=mysqli_fetch_assoc($h)){
                echo "<option>{$x['e_name']}</option>";
            }
            ?>
        </select>

        <button id="darkToggle" class="btn btn-sm btn-default">
            🌙 Dark
        </button>

        <span class="label label-danger" id="upcomingBadge"></span>
    </div>

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
    if(innerWidth<576) return 'listDay';
    if(innerWidth<992) return 'timeGridWeek';
    return 'dayGridMonth';
}

let calendar=new FullCalendar.Calendar(
document.getElementById('calendar'),{
    initialView:getView(),
    editable:true,   // 🔥 Drag enabled
    selectable:true,
    height:'auto',

    headerToolbar:{
        left:'prev,next today',
        center:'title',
        right:'dayGridMonth,timeGridWeek,listDay'
    },

    events:function(info,success){
        $.get('fetch_bookings.php',{
            hall:$("#hallFilter").val()
        },success);
    },

    eventDrop:function(info){
        Swal.fire({
            title:'Reschedule booking?',
            text:'Do you want to move this booking?',
            showCancelButton:true
        }).then(r=>{
            if(!r.isConfirmed){
                info.revert();
                return;
            }

            $.post("reschedule_drag.php",{
                booking_id:info.event.id,
                start:info.event.start.toISOString(),
                end:info.event.end.toISOString()
            },function(res){
                if(res!=="OK"){
                    Swal.fire("Conflict!",res,"error");
                    info.revert();
                }
            });
        });
    },

    eventClick:function(info){
        Swal.fire({
            title:info.event.title,
            html:`
            Customer: ${info.event.extendedProps.customer}<br>
            Guests: ${info.event.extendedProps.max_guest}<br>
            Status: ${info.event.extendedProps.status}
            `,
            confirmButtonText:'Open Booking'
        }).then(()=>{
            location="confirm_booking.php?id="+info.event.id;
        });
    }
});

calendar.render();

$("#hallFilter").change(()=>calendar.refetchEvents());

// 🔔 Upcoming badge
$.get("upcoming_count.php",c=>{
    if(c>0) $("#upcomingBadge").text("Upcoming: "+c);
});
</script>

</body>
</html>
