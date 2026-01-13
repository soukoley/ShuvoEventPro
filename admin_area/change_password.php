<?php
session_start();
include('./includes/db.php');

if (isset($_SESSION['admin_email'])) {
    $admin_email = $_SESSION['admin_email'];
} else {
    echo "<script>window.location='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password | ShuvoEventPro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root{
            --brand:#7B1E2B;
            --brand-dark:#4e0f1a;
            --gold:#D4A017;
            --card:#ffffff;
            --text:#222;
        }

        body.dark{
            --card:#2b2b2b;
            --text:#f2f2f2;
        }

        body{
            margin:0;
            min-height:100vh;
            background:linear-gradient(135deg,var(--brand),var(--brand-dark));
            font-family:"Segoe UI",Tahoma,sans-serif;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:15px;
            color:var(--text);
        }

        /* Card */
        .password-card{
            width:100%;
            max-width:420px;
            background:var(--card);
            border-radius:16px;
            padding:30px 32px;
            box-shadow:0 18px 40px rgba(0,0,0,.35);
            position:relative;
            transition:.4s;
        }

        /* Theme toggle */
        .theme-toggle{
            position:absolute;
            top:15px;
            right:15px;
            width:38px;
            height:38px;
            border-radius:50%;
            background:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            box-shadow:0 4px 12px rgba(0,0,0,.35);
        }
        body.dark .theme-toggle{background:#333;color:#fff}

        /* Logo */
        .brand-logo{
            max-height:65px;
            display:block;
            margin:0 auto 12px;
        }

        /* Title */
        .page-title{
            text-align:center;
            font-weight:700;
            margin-bottom:8px;
            color:var(--brand);
        }

        .brand-highlight{
            background:linear-gradient(135deg,var(--gold),#ffe28a);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            font-weight:800;
            text-shadow:0 0 6px rgba(212,160,23,.45);
        }

        .brand-underline{
            position:relative;
            display:inline-block;
        }
        .brand-underline:after{
            content:'';
            position:absolute;
            left:0;
            bottom:-6px;
            width:100%;
            height:3px;
            background:linear-gradient(90deg,var(--gold),#ffe28a);
            border-radius:3px;
            animation:underline .9s ease;
        }
        @keyframes underline{from{width:0}to{width:100%}}

        /* Inputs */
        .form-group label{
            font-size:13px;
            font-weight:600;
        }
        .form-control{
            height:42px;
            border-radius:8px;
            border:1px solid #ccc;
            background:transparent;
            color:var(--text);
        }
        body.dark .form-control{border-color:#555}
        .form-control:focus{
            border-color:var(--brand);
            box-shadow:0 0 0 2px rgba(123,30,43,.2);
        }

        .pass-wrapper{position:relative}
        .toggle-eye{
            position:absolute;
            right:12px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            color:var(--brand);
        }

        /* Strength bar */
        .strength{
            height:6px;
            margin-top:6px;
            border-radius:4px;
            background:#ddd;
            transition:.3s;
        }

        /* Button */
        .btn-purple{
            background:var(--brand);
            border:none;
            height:44px;
            border-radius:8px;
            font-weight:bold;
            letter-spacing:.4px;
            transition:.3s;
        }
        .btn-purple:hover{background:#5e1522}

        /* Footer */
        .footer-text{
            margin-top:14px;
            font-size:12px;
            text-align:center;
            opacity:.7;
        }
    </style>
</head>

<body>

<div class="password-card">

    <div class="theme-toggle" onclick="toggleTheme()">
        <i class="fa fa-moon-o" id="themeIcon"></i>
    </div>

    <img src="./img/ShuvoEventPro.png" class="brand-logo" alt="Logo">

    <h4 class="page-title">
        Change <span class="brand-highlight brand-underline">Password</span>
    </h4>
    <hr>

    <form method="POST">

        <div class="form-group">
            <label>Current Password</label>
            <div class="pass-wrapper">
                <input type="password" name="current_password" id="current_password"
                       class="form-control" required>
                <i class="fa fa-eye toggle-eye"
                   onclick="togglePassword('current_password', this)"></i>
            </div>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <div class="pass-wrapper">
                <input type="password" name="new_password" id="new_password"
                       class="form-control" required onkeyup="checkStrength()">
                <i class="fa fa-eye toggle-eye"
                   onclick="togglePassword('new_password', this)"></i>
            </div>
            <div id="strength-bar" class="strength"></div>
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <div class="pass-wrapper">
                <input type="password" name="confirm_password" id="confirm_password"
                       class="form-control" required>
                <i class="fa fa-eye toggle-eye"
                   onclick="togglePassword('confirm_password', this)"></i>
            </div>
        </div>

        <button type="submit" name="change_btn"
                class="btn btn-purple btn-block">
            <i class="fa fa-check-circle"></i> Update Password
        </button>

    </form>

    <div class="footer-text">
        © <?php echo date('Y'); ?> ShuvoEventPro
    </div>

</div>

<script>
function togglePassword(fieldId, icon){
    const input=document.getElementById(fieldId);
    if(input.type==="password"){
        input.type="text";
        icon.classList.replace("fa-eye","fa-eye-slash");
    }else{
        input.type="password";
        icon.classList.replace("fa-eye-slash","fa-eye");
    }
}

function checkStrength(){
    const pass=document.getElementById("new_password").value;
    const bar=document.getElementById("strength-bar");
    let s=0;
    if(pass.length>=6)s++;
    if(/[A-Z]/.test(pass))s++;
    if(/[0-9]/.test(pass))s++;
    if(/[^A-Za-z0-9]/.test(pass))s++;

    if(s===0){bar.style.width="0%";}
    else if(s===1){bar.style.width="25%";bar.style.background="red";}
    else if(s===2){bar.style.width="50%";bar.style.background="orange";}
    else if(s===3){bar.style.width="75%";bar.style.background="#3498db";}
    else{bar.style.width="100%";bar.style.background="green";}
}

function toggleTheme(){
    document.body.classList.toggle("dark");
    let d=document.body.classList.contains("dark");
    localStorage.setItem("theme",d?"dark":"light");
    document.getElementById("themeIcon").className=d?"fa fa-sun-o":"fa fa-moon-o";
}
(function(){
    if(localStorage.getItem("theme")==="dark"){
        document.body.classList.add("dark");
        document.getElementById("themeIcon").className="fa fa-sun-o";
    }
})();
</script>

</body>
</html>

<?php
/* ===== PHP PROCESS ===== */
if(isset($_POST['change_btn'])){

    $current = md5($_POST['current_password']);
    $newpass = md5($_POST['new_password']);
    $confirm = md5($_POST['confirm_password']);

    $sql="SELECT admin_pass FROM admins WHERE admin_email='$admin_email'";
    $res=mysqli_query($con,$sql);
    $row=mysqli_fetch_assoc($res);

    if($current!==$row['admin_pass']){
        echo "<script>Swal.fire('Error','Current password is incorrect!','error');</script>";
    }
    elseif($newpass!==$confirm){
        echo "<script>Swal.fire('Error','Passwords do not match!','error');</script>";
    }
    else{
        $upd="UPDATE admins SET admin_pass='$newpass' WHERE admin_email='$admin_email'";
        if(mysqli_query($con,$upd)){
            echo "<script>
                Swal.fire({
                    icon:'success',
                    title:'Password Updated!',
                    timer:1500,
                    showConfirmButton:false
                }).then(()=>{
                    window.location='../login.php';
                });
            </script>";
        }else{
            echo "<script>Swal.fire('Error','Update failed!','error');</script>";
        }
    }
}
?>
