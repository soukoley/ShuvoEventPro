<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit();
}

if (isset($_GET['delete_event'])) {

    $delete_id = intval($_GET['delete_event']); // Security

    $delete_query = "DELETE FROM event WHERE id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "
        <script>
        Swal.fire({
            title: 'Deleted!',
            text: 'The selected event has been deleted successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index.php?view_event';
        });
        </script>";
    } else {
        echo "
        <script>
        Swal.fire({
            title: 'Error!',
            text: 'Something went wrong. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        </script>";
    }

    mysqli_stmt_close($stmt);
}
?>
