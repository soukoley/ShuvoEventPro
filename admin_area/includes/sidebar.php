<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

?>

<style>
    
    /* ================= MODERN NAVBAR ================= */

.navbar{
    border:none;
    box-shadow:0 2px 8px rgba(0,0,0,0.25);
}

/* -------- NAVBAR HEADER -------- */
.navbar-header{
    display:flex;
    align-items:center;
    height:70px;
    padding-left:15px;
}

/* -------- TOP RIGHT NAV -------- */
.top-nav > li > a{
    display:flex;
    align-items:center;
    gap:8px;
    height:70px;
    padding:0 18px;
    color: #f5b30e !important;
    font-weight:600;
}


.top-nav img{
    width:30px;
    height:30px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #D4A017;
}

/* Dropdown */
.dropdown-menu{
    border-radius:10px;
    box-shadow:0 8px 20px rgba(0,0,0,0.2);
    border:none;
    margin-top:8px;
}

.dropdown-menu > li > a{
    padding:10px 18px;
    font-weight:600;
}

.dropdown-menu > li > a i{
    margin-right:8px;
    color: #7A1E3A;
}

/* Hover */
.dropdown-menu > li > a:hover{
    color: #7A1E3A !important;
}

/* Logout hover */
.dropdown-menu > li > a.text-danger:hover{
    color: #d9534f !important;
}

/* ===== LOGO : CIRCLE (DEFAULT) ===== */
/* ===== BRAND CONTAINER (BADGE) ===== */
.shuvoeventpro-brand{
    display:flex;
    align-items:center;
    gap:12px;

    padding:6px 14px;
    height:58px;

    transition:all 0.3s ease;
}

/* ===== BADGE LOGO IMAGE ===== */
.brand-logo{
    width:92px;              /* 🔥 clear & readable */
    height:auto;

    object-fit:contain;
    padding:4px 6px;

    background:#000;
    border-radius:10px;
    border:2px solid #D4A017;

    transition:all 0.3s ease;
}

/* ===== TEXT ===== */
.brand-text .english-name{
    font-size:20px;
    font-weight:700;
    letter-spacing:1px;
    color: #F2E9FF;
    white-space:nowrap;
}

/* ===== SCROLL STATE ===== */
.navbar.scrolled .shuvoeventpro-brand{
    height:48px;
    padding:4px 10px;
}

.navbar.scrolled .brand-logo{
    width:76px;              /* still readable */
}

.navbar.scrolled .brand-text .english-name{
    font-size:17px;
}


</style>

<!-- Top Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top" style="background: #7A1E3A;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <div class="shuvoeventpro-brand">
            <img src="../img/ShuvoEventPro.png" class="brand-logo" alt="ShuvoEventPro Logo">
            <div class="brand-text">
                <span class="english-name">ShuvoEventPro</span>
            </div>
        </div>
    </div>


    <!-- Top Right -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo $admin_image; ?>" alt="Profile">
                <?php echo $admin_name; ?>
                <b class="caret"></b>
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
            <li>
                <a href="index.php?dashboard">
                    <i class="fa fa-fw fa-dashboard"></i> Dashboard
                </a>
            </li>


            <!-- Event Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#eventMenu">
                    <i class="fa fa-fw fa-calendar"></i> Event
                    <i class="fa fa-fw fa-caret-down pull-right"></i>
                </a>
                <ul id="eventMenu" class="collapse">
                    <li><a href="index.php?insert_event"><i class="fa fa-plus"></i> New Event</a></li>
                    <li><a href="index.php?view_event"><i class="fa fa-eye"></i> View Event</a></li>
                    <li><a href="index.php?booking_calendar"><i class="fa fa-calendar-o"></i> Event Calendar</a></li>
                </ul>
            </li>


            <!-- Facility Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#facilityMenu">
                    <i class="fa fa-fw fa-hotel"></i> Facility
                    <i class="fa fa-fw fa-caret-down pull-right"></i>
                </a>
                <ul id="facilityMenu" class="collapse">
                    <li><a href="index.php?insert_facility"><i class="fa fa-plus"></i> New Facility</a></li>
                    <li><a href="index.php?view_facility"><i class="fa fa-eye"></i> View Facility</a></li>
                </ul>
            </li>


            <!-- Gallery Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#galleryMenu">
                    <i class="fa fa-fw fa-image"></i> Gallery
                    <i class="fa fa-fw fa-caret-down pull-right"></i>
                </a>
                <ul id="galleryMenu" class="collapse">
                    <li><a href="index.php?insert_event_gallery"><i class="fa fa-plus"></i> Add Gallery</a></li>
                    <li><a href="index.php?view_gallery"><i class="fa fa-eye"></i> View Gallery</a></li>
					<li><a href="index.php?insert_event_video"><i class="fa fa-plus"></i> Add Video</a></li>
                    <li><a href="index.php?view_video"><i class="fa fa-eye"></i> View Video</a></li>
                </ul>
            </li>


            <!-- Booking Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#bookingMenu">
                    <i class="fa fa-fw fa-calendar-check-o"></i> Booking
                    <i class="fa fa-fw fa-caret-down pull-right"></i>
                </a>
                <ul id="bookingMenu" class="collapse">
                    <li><a href="index.php?pending"><i class="fa fa-clock-o"></i> Pending</a></li>
                    <li><a href="index.php?approved"><i class="fa fa-check-circle"></i> Approved</a></li>
                    <li><a href="index.php?complete"><i class="fa fa-flag-checkered"></i> Completed</a></li>
                    <li><a href="index.php?search"><i class="fa fa-search"></i> Search</a></li>
                </ul>
            </li>


            
            <!-- Invoice Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#invoiceMenu">
                    <i class="fa fa-fw fa-file-text-o"></i> Invoice
                    <i class="fa fa-fw fa-caret-down pull-right"></i>
                </a>
                <ul id="invoiceMenu" class="collapse">
                    <li><a href="index.php?get_Invoice"><i class="fa fa-plus-circle"></i> Get Invoice</a></li>
                    <li><a href="index.php?view_Invoice"><i class="fa fa-eye"></i> View Invoices</a></li>
                </ul>
            </li>


            <!-- Payment Menu-->
            <li>
                <a href="#" data-toggle="collapse" data-target="#paymentMenu">
                    <i class="fa fa-fw fa-credit-card"></i> Payment
                    <i class="fa fa-fw fa-caret-down pull-right"></i>
                </a>
                <ul id="paymentMenu" class="collapse">

                    <li>
                        <a href="index.php?due_pay_input">
                            <i class="fa fa-fw fa-exclamation-circle text-danger"></i> Due Payments
                        </a>
                    </li>

                    <!-- <li>
                        <a href="index.php?receive_Payment">
                            <i class="fa fa-fw fa-money text-success"></i> Receive Payment
                        </a>
                    </li>

                    <li>
                        <a href="index.php?partial_Payments">
                            <i class="fa fa-fw fa-adjust"></i> Partial Payments
                        </a>
                    </li> -->

                    <!-- <li class="divider"></li> -->

                    <li>
                        <a href="index.php?view_Payments">
                            <i class="fa fa-fw fa-list-alt"></i> View Payments
                        </a>
                    </li>

                    <li>
                        <a href="#" data-toggle="collapse" data-target="#paymentReportMenu">
                            <i class="fa fa-fw fa-bar-chart"></i> Payment Reports
                            <i class="fa fa-fw fa-caret-down pull-right"></i>
                        </a>

                        <ul id="paymentReportMenu" class="collapse sidebar-submenu">

                            <li>
                                <a href="index.php?payment_report_date">
                                    <i class="fa fa-fw fa-calendar"></i> Date-wise Report
                                </a>
                            </li>

                            <li>
                                <a href="index.php?payment_report_mode">
                                    <i class="fa fa-fw fa-credit-card"></i> Mode-wise Report
                                </a>
                            </li>

                            <!-- <li>
                                <a href="index.php?payment_report_booking">
                                    <i class="fa fa-fw fa-list"></i> Booking-wise Report
                                </a>
                            </li> -->

                            <li>
                                <a href="index.php?payment_report_due">
                                    <i class="fa fa-fw fa-exclamation-triangle"></i> Due Report
                                </a>
                            </li>

                            <li>
                                <a href="index.php?payment_charts">
                                    <i class="fa fa-fw fa-bar-chart"></i> Payment Charts
                                </a>
                            </li>


                            <!-- <li>
                                <a href="index.php?payment_report_discount">
                                    <i class="fa fa-fw fa-percent"></i> Discount Report
                                </a>
                            </li> -->

                        </ul>
                    </li>

                </ul>
            </li>
            
            <!-- Admin Control Menu -->
            <li>
                <a href="#" data-toggle="collapse" data-target="#adminControlMenu">
                    <i class="fa fa-fw fa-cogs"></i> Admin Control
                    <i class="fa fa-fw fa-caret-down pull-right"></i>
                </a>

                <ul id="adminControlMenu" class="collapse">

                    <!-- Financial Year -->
                    <li>
                        <a href="index.php?change_Finance_Year">
                            <i class="fa fa-fw fa-calendar-o"></i> Financial Year
                        </a>
                    </li>
                    <li>
                        <a href="#" data-toggle="collapse" data-target="#holidaysMenu">
                            <i class="fa fa-gift"></i> Manage Holidays
                            <i class="fa fa-fw fa-caret-down pull-right"></i>
                        </a>

                        <ul id="holidaysMenu" class="collapse sidebar-submenu">
                            <li>
                                <a href="index.php?holidays">
                                    <i class="fa fa-fw fa-plus"></i> Add Holiday
                                </a>
                            </li>
                            <li>
                                <a href="index.php?view_holidays">
                                    <i class="fa fa-fw fa-eye"></i> View Holidays
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- (Future Ready – optional, comment for now) -->
                    <!--
                    <li>
                        <a href="index.php?company_settings">
                            <i class="fa fa-fw fa-building"></i> Company Settings
                        </a>
                    </li>

                    <li>
                        <a href="index.php?gst_settings">
                            <i class="fa fa-fw fa-percent"></i> GST Settings
                        </a>
                    </li>

                    <li>
                        <a href="index.php?user_roles">
                            <i class="fa fa-fw fa-users"></i> User Roles
                        </a>
                    </li>
                    -->

                </ul>
            </li>


        </ul>
    </div>
</nav>

<?php 

}
?>