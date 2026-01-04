<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>

<style>
    
    /* Brand Text (NO LINK STYLE) */
    .shuvoeventpro-brand {
        font-size: 22px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #E2D9F3;
        padding: 0px 0px;
        display: flex;
        align-items: center;
        height: 70px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.45);
        cursor: default;   /* Not clickable */
        gap: 10px;
    }

    /* Icon styling */
    .shuvoeventpro-brand i {
        margin-right: 8px;
        color: #C8A7FF;
        font-size: 30px;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.35);
    }

    .brand-logo {
		padding-top: 5px;
        width: 100px;      /* Size adjust as needed */
        height: 80px;
        object-fit: contain;
    }

    /* English + Bengali Text Container */
    .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    /* English Name */
    .brand-text .english-name {
        font-size: 22px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* Bengali Name */
    .brand-text .bengali-name {
        font-size: 14px;
        font-weight: 500;
        color: #D8C4F2;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    /* Dropdown hover color */
    .dropdown-menu > li > a:hover {
        background-color: #f0e9ff;   /* light purple shade */
        color: #e9678eff !important;   /* dark purple text */
        font-weight: bold;
    }

    /* Logout hover - red tint */
    .dropdown-menu > li > a.text-danger:hover {
        background-color: #ffe6e6;
        color: #d9534f !important;
    }

    .english-name {
        font-weight: bold;
        font-size: 18px;
    }

    .bengali-name {
        font-size: 14px;
        color: #b99cd9;
    }

</style>

<!-- Top Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top" style="background: #7A1E3A;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <!-- <a href="index.php?dashboard" class="navbar-brand">User Panel</a> -->
        <div class="shuvoeventpro-brand">
            <img src="../img/ShuvoEventPro.png" class="brand-logo" alt="ShuvoEventPro Logo">
            <div class="brand-text">
                <span class="english-name">ShuvoEventPro</span>
                <!-- <span class="bengali-name">হিসাবপ্রো</span> -->
            </div>
        </div>
    </div>

    <!-- Top Right -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" style="color : #E2D9F3; font-weight : bold;" data-toggle="dropdown">
                <!-- PROFILE IMAGE -->
                <img 
                    src="<?php echo $admin_image; ?>" 
                    alt="Profile" 
                    style="width:25px; height:25px; border-radius:50%; object-fit:cover; margin-right:5px;"
                >
                <?php echo $admin_name; ?> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="index.php?user_profile&id=<?php echo $admin_id; ?>">
                        <i class="fa fa-user-circle"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="change_password.php">
                        <i class="fa fa-key"></i> Change Password
                    </a>
                </li>
                <li>
                    <a href="index.php?view_product">
                        <i class="fa fa-fw fa-cube"></i> Products
                    </a>
                </li>
                <li>
                    <a href="index.php?view_customer">
                        <i class="fa fa-fw fa-users"></i> Customers
                    </a>
                </li>
                <li>
                    <a href="index.php?view_cat">
                        <i class="fa fa-fw fa-tags"></i> Categories
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="logout.php" class="text-danger">
                        <i class="fa fa-power-off"></i> Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <!-- Sidebar -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav sidebar-nav">

			<!-- Dashboard Link -->
            <li><a href="index.php?dashboard"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>

			<!-- Event Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#eventMenu"><i class="fa fa-fw fa-calendar"></i> Event <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="eventMenu" class="collapse">
                    <li><a href="index.php?insert_event">New Event</a></li>
                    <li><a href="index.php?view_event">View Event</a></li>
                </ul>
            </li>

			<!-- Gallery Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#galleryMenu"><i class="fa fa-fw fa-image"></i> Gallery <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="galleryMenu" class="collapse">
                    <li><a href="index.php?insert_event_gallery">Add Gallery</a></li>
                    <li><a href="index.php?view_gallery">View Gallery</a></li>
                </ul>
            </li>

			<!-- Booking Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#bookingMenu"><i class="fa fa-fw fa-calendar-check-o"></i> Booking <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="bookingMenu" class="collapse">
                    <li><a href="index.php?new_booking">New Booking</a></li>
                    <li><a href="index.php?view_booking">View Booking</a></li>
                    <li><a href="index.php?confirm_booking">Confirmed Booking</a></li>
                    <li><a href="index.php?older_booking">Older Booking</a></li>
                </ul>
            </li>

			<!-- Payment Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#paymentMenu"><i class="fa fa-fw fa-credit-card"></i> Payment <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="paymentMenu" class="collapse">
					<li><a href="index.php?due_Payments">Due Payments</a></li>
					<li><a href="index.php?view_Payments">View Payments</a></li>
				</ul>
            </li>		
			
		</ul>
	</div>
</nav>

<?php 

}
?>