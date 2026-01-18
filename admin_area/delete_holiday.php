<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit();
}

if (isset($_GET['delete_holiday'])) {

    $delete_id = intval($_GET['delete_holiday']); // Security

    $delete_query = "DELETE FROM holidays WHERE id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "
        <script>
        Swal.fire({
            title: 'Deleted!',
            text: 'The selected holiday has been deleted successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index.php?view_holidays';
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
