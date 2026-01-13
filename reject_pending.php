<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}

if (!isset($_GET['id']) || trim($_GET['id']) == '') {
    echo "<script>
            alert('Invalid request');
            window.history.back();
          </script>";
    exit;
}

$booking_id = mysqli_real_escape_string($con, $_GET['id']);

/* STEP 1: Check booking exists & pending */
$chk = mysqli_query($con, "
    SELECT status 
    FROM booking_details 
    WHERE booking_id = '$booking_id'
");

if (mysqli_num_rows($chk) == 0) {
    echo "<script>
            alert('Booking not found');
            window.history.back();
          </script>";
    exit;
}

$row = mysqli_fetch_assoc($chk);

if ($row['status'] != 'Pending') {
    echo "<script>
            alert('This booking is not pending');
            window.history.back();
          </script>";
    exit;
}

/* STEP 2: Update booking status to Rejected */
$upd = mysqli_query($con, "
    UPDATE booking_details 
    SET status = 'Rejected'
    WHERE booking_id = '$booking_id'
");

/* STEP 3: Redirect with message */
if ($upd) {
    echo "
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Rejected!',
            text: 'Booking rejected successfully.',
            confirmButtonColor: '#C82333'
        }).then(() => {
            window.location.href = document.referrer;
        });
    </script>
    ";
} else {
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again.',
            confirmButtonColor: '#6c757d'
        }).then(() => {
            window.history.back();
        });
    </script>
    ";
}
