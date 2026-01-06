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
            --text: #222;
            --card-bg: #ffffff;
        }

        body.dark {
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
            transition: all .4s ease;
        }

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

        /* Golden brand text */
        .brand-highlight {
            background: linear-gradient(135deg, #D4A017, #FFD966);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            text-shadow: 0 0 6px rgba(212,160,23,0.45);
            transition: all .3s ease;
        }

        body.dark .brand-highlight {
            text-shadow:
                0 0 10px rgba(212,160,23,0.9),
                0 0 22px rgba(212,160,23,0.6);
        }

        /* Animated underline */
        .brand-underline {
            position: relative;
            display: inline-block;
        }
        .brand-underline::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #D4A017, #FFD966);
            border-radius: 3px;
            animation: underlineGrow 1.1s ease forwards;
        }
        @keyframes underlineGrow {
            from { width: 0; }
            to { width: 100%; }
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

        /* Theme toggle */
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

        /* Login success animation */
        .login-success {
            animation: successPop .6s ease forwards;
        }
        @keyframes successPop {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.04); }
            100% { transform: scale(.98); opacity: 0; }
        }

        .success-overlay {
            position: fixed;
            inset: 0;
            background: rgba(123,30,43,.85);
            z-index: 9999;
            animation: flashOut .8s ease forwards;
        }
        @keyframes flashOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        /* ❌ Login failure shake */
        .login-error {
            animation: shake .45s ease;
            box-shadow: 0 0 0 3px rgba(220,53,69,.35);
        }
        @keyframes shake {
            0% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-6px); }
            80% { transform: translateX(6px); }
            100% { transform: translateX(0); }
        }
    </style>
</head>

<body>

<div class="theme-toggle" onclick="toggleTheme()">
    <i class="fa fa-moon-o" id="themeIcon"></i>
</div>

<div class="login-card">

    <img src="./img/ShuvoEventPro.png" class="brand-logo" alt="Logo">

    <h4 class="login-title">
        Login to 
        <span class="brand-highlight brand-underline">ShuvoEventPro</span>
    </h4>
    <hr>

    <form method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="text" name="admin_email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="pass-wrapper">
                <input type="password" name="admin_pass" id="admin_pass" class="form-control" required>
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
function togglePassword() {
    let i = document.getElementById("admin_pass");
    i.type = i.type === "password" ? "text" : "password";
}

function toggleTheme() {
    document.body.classList.toggle("dark");
    let d = document.body.classList.contains("dark");
    localStorage.setItem("theme", d ? "dark" : "light");
    document.getElementById("themeIcon").className = d ? "fa fa-sun-o" : "fa fa-moon-o";
}

(function(){
    let t = localStorage.getItem("theme");
    if(t === "dark"){
        document.body.classList.add("dark");
        document.getElementById("themeIcon").className = "fa fa-sun-o";
    }
})();
</script>

</body>
</html>

<?php
/* LOGIN PROCESS */
if (isset($_POST['admin_login'])) {

    $email = mysqli_real_escape_string($con, $_POST['admin_email']);
    $pass  = md5($_POST['admin_pass']); // keep as-is

    $q = "SELECT * FROM admins WHERE admin_email='$email' AND admin_pass='$pass'";
    $r = mysqli_query($con, $q);

    if (mysqli_num_rows($r) == 1) {

        $_SESSION['admin_email'] = $email;

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: 'Welcome back!',
                timer: 900,
                showConfirmButton: false
            }).then(() => {
                document.querySelector('.login-card').classList.add('login-success');
                let o = document.createElement('div');
                o.className = 'success-overlay';
                document.body.appendChild(o);
                setTimeout(() => {
                    window.location='./admin_area/index.php?dashboard';
                }, 700);
            });
        </script>";

    } else {

        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: 'Invalid Email or Password',
                timer: 900,
                showConfirmButton: false
            }).then(() => {
                let card = document.querySelector('.login-card');
                card.classList.add('login-error');
                document.getElementById('admin_pass').focus();
                setTimeout(() => {
                    card.classList.remove('login-error');
                }, 500);
            });
        </script>";
    }
}
?>
