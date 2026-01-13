<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}

include("./includes/db.php");

$admin_id = $_GET['id'];

$get_admin = "SELECT * FROM admins WHERE admin_id='$admin_id'";
$run_admin = mysqli_query($con, $get_admin);
$row_admin = mysqli_fetch_assoc($run_admin);

if (!$row_admin) {
    die('Admin not found');
}

$admin_name     = $row_admin['admin_name'];
$admin_email    = $row_admin['admin_email']; 
$admin_phone    = $row_admin['admin_contact'];
$admin_address  = $row_admin['admin_address'];
$admin_aadhar   = $row_admin['admin_aadhar'];
$admin_image    = $row_admin['admin_image'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>

    <!-- Font Awesome + SweetAlert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body{
            background:#f1eef8;
        }

        /* ===== MODERN PROFILE HEADER ===== */
        .profile-header-modern{
            background:linear-gradient(135deg, #f1b419, #7B1E2B);
            padding:30px;
            border-radius:16px 16px 0 0;
        }

        .profile-header-inner{
            display:flex;
            align-items:center;
            gap:20px;
        }

        .avatar-box{
            width:110px;
            height:110px;
            border-radius:50%;
            background:#fff;
            padding:4px;
            box-shadow:0 6px 18px rgba(0,0,0,0.25);
            flex-shrink:0;
        }

        .avatar-box img{
            width:100%;
            height:100%;
            border-radius:50%;
            object-fit:cover;
        }

        .profile-info h3{
            margin:0;
            color:#fff;
            font-weight:600;
        }

        .profile-info p{
            margin:4px 0 0;
            color:#f1e9ff;
            font-size:14px;
        }

        /* Remove old overlap effect if exists */
        .profile-img{
            display:none;
        }


        /* Body */
        .profile-body{
            padding:30px;
        }

        .form-group label{
            font-weight:600;
            color:#3b1c63;
        }

        .form-control{
            border-radius:8px;
            border:1px solid #ccc;
            box-shadow:none;
        }

        .form-control:focus{
            border-color:rgba(53,15,97,1);
            box-shadow:0 0 0 2px rgba(53,15,97,0.15);
        }

        label i{
            margin-right:6px;
            color: #7B1E2B;
        }

        .btn-purple{
            background: #7B1E2B;
            color:#fff;
            font-weight:600;
            border-radius:10px;
            padding:10px;
            border:none;
        }

        .btn-purple:hover{
            background: #D4A017;
            color:#fff;
        }
    </style>
</head>

<body>

<div class="profile-wrapper">
<div class="profile-card">

    <!-- HEADER -->
    <!-- MODERN PROFILE HEADER -->
<div class="profile-header-modern">
    <div class="profile-header-inner">

        <!-- Avatar -->
        <div class="avatar-box">
            <?php if (!empty($admin_image)) { ?>
                <img src="<?php echo $admin_image; ?>" alt="Profile">
            <?php } else { ?>
                <img src="../images/default.png" alt="Profile">
            <?php } ?>
        </div>

        <!-- Info -->
        <div class="profile-info">
            <h3><?php echo $admin_name; ?></h3>
            <p><?php echo $admin_email; ?></p>
        </div>

    </div>
</div>


    <!-- BODY -->
    <div class="profile-body">

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label><i class="fa fa-user"></i> Full Name</label>
                <input type="text" class="form-control"
                       value="<?php echo $admin_name; ?>" readonly>
            </div>

            <div class="form-group">
                <label><i class="fa fa-envelope"></i> User ID</label>
                <input type="email" class="form-control"
                       value="<?php echo $admin_email; ?>" readonly>
            </div>

            <div class="form-group">
                <label><i class="fa fa-phone"></i> Phone</label>
                <input type="text" name="admin_phone" class="form-control"
                       value="<?php echo $admin_phone; ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fa fa-home"></i> Address</label>
                <textarea name="admin_address" class="form-control"
                          rows="3" required><?php echo $admin_address; ?></textarea>
            </div>

            <div class="form-group">
                <label><i class="fa fa-id-card"></i> Aadhar Number</label>
                <input type="text" name="admin_aadhar" class="form-control"
                       value="<?php echo $admin_aadhar; ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fa fa-image"></i> Update Profile Photo</label>
                <input type="file" name="admin_image" class="form-control">
            </div>

            <button type="submit" name="update_profile"
                    class="btn btn-purple btn-block">
                <i class="fa fa-save"></i> Update Profile
            </button>

        </form>

    </div>
</div>
</div>

</body>
</html>

<?php
/* ================= UPDATE PROFILE ================= */
if (isset($_POST['update_profile'])) {

    $new_phone   = $_POST['admin_phone'];
    $new_address = $_POST['admin_address'];
    $new_aadhar  = $_POST['admin_aadhar'];

    $new_image   = $_FILES['admin_image']['name'];
    $temp_image  = $_FILES['admin_image']['tmp_name'];

    $upload_dir = __DIR__ . "/admin_images/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($new_image)) {

        if (!empty($admin_image) && file_exists($admin_image)) {
            unlink($admin_image);
        }

        move_uploaded_file($temp_image, $upload_dir . $new_image);
        $image_path = "admin_images/" . $new_image;

    } else {
        $image_path = $admin_image;
    }

    $update_query = "
        UPDATE admins SET
        admin_contact='$new_phone',
        admin_address='$new_address',
        admin_aadhar='$new_aadhar',
        admin_image='$image_path'
        WHERE admin_id='$admin_id'
    ";

    if (mysqli_query($con, $update_query)) {
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Profile Updated!',
                text: 'Your profile has been updated successfully.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location='index.php?user_profile&id=$admin_id';
            });
        </script>";
    } else {
        echo "<script>Swal.fire('Error','Something went wrong!','error');</script>";
    }
}
?>
