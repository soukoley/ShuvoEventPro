<?php
session_start();
include ("includes/db.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login | ShuvoEventPro</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --brand: #7B1E2B;
            --bg: #ffffff;
            --text: #222;
            --card-bg: #ffffff;
        }

        body.dark {
            --bg: #1c1c1c;
            --text: #f2f2f2;
            --card-bg: #2a2a2a;
        }

        body {
            margin: 0;
            background: linear-gradient(135deg, var(--brand), #4e0f1a);
            font-family: "Segoe UI", Tahoma, sans-serif;
            min-height: 100vh;
            padding: 15px;
            color: var(--text);
        }

        /* Mobile-first */
        .login-card {
            width: 100%;
            background: var(--card-bg);
            border-radius: 14px;
            padding: 25px 22px;
            box-shadow: 0 12px 30px rgba(0,0,0,.25);
        }

        /* Desktop center */
        @media (min-width: 768px) {
            body {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-card {
                max-width: 420px;
                padding: 30px 35px;
            }
        }

        .brand-logo {
            max-height: 65px;
            display: block;
            margin: 0 auto 12px;
        }

        .login-title {
            text-align: center;
            color: var(--brand);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
        }

        .form-control {
            height: 42px;
            border-radius: 8px;
            background: transparent;
            color: var(--text);
            border: 1px solid #ccc;
        }

        body.dark .form-control {
            border-color: #555;
        }

        .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 2px rgba(123,30,43,.2);
        }

        .pass-wrapper {
            position: relative;
        }

        .toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--brand);
        }

        .btn-login {
            background: var(--brand);
            border: none;
            height: 44px;
            border-radius: 8px;
            font-weight: bold;
            letter-spacing: .5px;
            transition: .3s;
        }

        .btn-login:hover {
            background: #5e1522;
        }

        /* Dark / Light Toggle */
        .theme-toggle {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #fff;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,.3);
        }

        body.dark .theme-toggle {
            background: #2a2a2a;
        }

        .footer-text {
            margin-top: 15px;
            font-size: 12px;
            text-align: center;
            opacity: .7;
        }
		
    </style>
</head>

<body>

<div class="theme-toggle" onclick="toggleTheme()">
    <i class="fa fa-moon-o" id="themeIcon"></i>
</div>

<div class="login-card">

    <img src="./img/ShuvoEventPro.png" class="brand-logo" alt="Logo">

    <!-- <h4 class="login-title">Login to ShuvoEventPro</h4> -->
	<h4 class="login-title">
		Login to <span style="color:#D4A017;">ShuvoEventPro</span>
	</h4>

    <hr>

    <form method="POST">

        <div class="form-group">
            <label>Email Address</label>
            <input type="text" name="admin_email" class="form-control" placeholder="Enter email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="pass-wrapper">
                <input type="password" name="admin_pass" id="admin_pass" class="form-control" placeholder="Enter password" required>
                <i class="fa fa-eye toggle-eye" onclick="togglePassword()"></i>
            </div>
        </div>

        <button type="submit" name="admin_login" class="btn btn-login btn-block">
            <i class="fa fa-sign-in"></i> Login
        </button>

    </form>

    <div class="footer-text">
        © <?php echo date('Y'); ?> ShuvoEventPro
    </div>

</div>

<script>
/* Password toggle */
function togglePassword() {
    let input = document.getElementById("admin_pass");
    input.type = input.type === "password" ? "text" : "password";
}

/* Theme toggle */
function toggleTheme() {
    document.body.classList.toggle("dark");
    let isDark = document.body.classList.contains("dark");
    localStorage.setItem("theme", isDark ? "dark" : "light");
    document.getElementById("themeIcon").className = isDark ? "fa fa-sun-o" : "fa fa-moon-o";
}

/* Load saved theme */
(function(){
    let saved = localStorage.getItem("theme");
    if(saved === "dark"){
        document.body.classList.add("dark");
        document.getElementById("themeIcon").className = "fa fa-sun-o";
    }
})();
</script>

</body>
</html>

<?php
// LOGIN PROCESS PHP
if (isset($_POST['admin_login'])) {

    $admin_email = mysqli_real_escape_string($con, $_POST['admin_email']);
    $admin_pass = md5($_POST['admin_pass']);  // md5 matching

    $query = "SELECT * FROM admins WHERE admin_email='$admin_email' AND admin_pass='$admin_pass'";
    $run = mysqli_query($con, $query);

    if (mysqli_num_rows($run) == 1) {

        $_SESSION['admin_email'] = $admin_email;
		$row = mysqli_fetch_assoc($run);
		
		$redirect = './admin_area/index.php?dashboard';
        
		echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful!',
                text: 'Welcome back!',
                timer: 1000,
                showConfirmButton: false
            }).then(() => { window.location='$redirect'; });
        </script>";

    } else {

        echo "<script>
            Swal.fire('Error','Invalid Email or Password!'.$query,'error');
        </script>";
    }
}
?>