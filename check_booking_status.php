<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}

if (!isset($_POST['booking_id']) || trim($_POST['booking_id']) == '') {
    echo "<script>alert('Booking ID required'); window.history.back();</script>";
    exit;
}

$booking_id = mysqli_real_escape_string($con, $_POST['booking_id']);

/* STEP 1: Booking exists কিনা */
$qBooking = mysqli_query($con, "
    SELECT status 
    FROM booking_details 
    WHERE booking_id = '$booking_id'
");

if (mysqli_num_rows($qBooking) == 0) {
    echo "<script>alert('Invalid Booking ID'); window.history.back();</script>";
    exit;
} else {
    $bookingRow = mysqli_fetch_assoc($qBooking);
    if ($bookingRow['status'] == 'Pending') {
        // Pending Booking
        echo "<script>window.open('index.php?approve_pending=5&id=$booking_id','_self')</script>";
    } else if( $bookingRow['status'] == 'Approved') {
        // Approved Booking
        echo "<script>window.open('index.php?complete_approval=5&id=$booking_id','_self')</script>";
    } else {
        // Completed Booking
        echo "<script>window.open('index.php?complete_details=5&id=$booking_id','_self')</script>";
    }

}
