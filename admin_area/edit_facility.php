<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("includes/db.php"); // তোমার DB কানেকশন ফাইল

// Session Check
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit();
}

// Get Facility ID
if (!isset($_GET['edit_facility'])) {
    echo "<script>window.open('index.php?view_facility','_self')</script>";
    exit();
}

$e_id = intval($_GET['edit_facility']);

// Fetch Facility Data (Secure)
$stmt = mysqli_prepare($con, "SELECT * FROM facility WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $e_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    echo "<script>alert('Facility not found'); window.open('index.php?view_facility','_self');</script>";
    exit();
}

$fname      = $row['fName'];
$price      = $row['fPrice'];
$splPrice   = $row['splPrice'];
$event_name = $row['eName'];
$max_guest  = $row['max_people'];
$gst_rate   = $row['gst_rate'];

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Facility</title>
</head>

<body>

<div class="row">
    <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">

        <div class="breadcrumb">
            <li class="active">
                <i class="fa fa-fw fa-hotel"></i> Facility / Edit Facility
            </li>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading corporate-heading">
                <h3 class="panel-title">
                    <i class="fa fa-edit"></i> Facility Updation
                </h3>
            </div>

            <div class="panel-body" style="padding-top: 20px;">
                <form class="form-horizontal" method="post">

                    <!-- Facility Name -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Facility Name :</label>
                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($fname); ?>"
                                   readonly>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Price :</label>
                        <div class="col-md-6">
                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   class="form-control"
                                   required
                                   value="<?php echo htmlspecialchars($price); ?>">
                        </div>
                    </div>

                    <!-- Special Price -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Special Price :</label>
                        <div class="col-md-6">
                            <input type="number"
                                   step="0.01"
                                   name="specialPrice"
                                   class="form-control"
                                   required
                                   value="<?php echo htmlspecialchars($splPrice); ?>">
                        </div>
                    </div>

                    <!-- GST -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">GST Rate (%) :</label>
                        <div class="col-md-6">
                            <input type="number"
                                   step="0.01"
                                   name="gst_rate"
                                   class="form-control"
                                   required
                                   value="<?php echo htmlspecialchars($gst_rate); ?>">
                        </div>
                    </div>

                    <!-- Event Name -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Event Name :</label>
                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($event_name); ?>"
                                   readonly>
                        </div>
                    </div>

                    <!-- Max People (Only if Event != ALL) -->
                    <?php if ($event_name != "ALL") { ?>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Maximum People :</label>
                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($max_guest); ?>"
                                   readonly>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Submit -->
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-6">
                            <button type="submit" name="submit" class="btn btn-primary form-control">
                                <i class="fa fa-save"></i> Update Facility
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>

<?php
// Handle Update
if (isset($_POST['submit'])) {

    $ePrice         = floatval($_POST['price']);
    $specialPrice   = floatval($_POST['specialPrice']);
    $gst_rate       = floatval($_POST['gst_rate']);

    $stmt = mysqli_prepare($con,
        "UPDATE facility SET fPrice = ?, splPrice = ?, gst_rate = ? WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, "dddd", $ePrice, $specialPrice, $gst_rate, $e_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "
        <script>
        Swal.fire({
            title: 'Success!',
            text: 'Facility updated successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index.php?view_facility';
        });
        </script>";
    } else {
        echo "
        <script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to update facility.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        </script>";
    }

    mysqli_stmt_close($stmt);
}
?>
