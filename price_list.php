<?php
// ============================
// Initial Setup
// ============================
include("includes/db.php");
include("header.php");

// Utility function for safe output
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Greenland Event Management - Price Chart">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Greenland | Price Chart</title>

    <!-- Favicon -->
    <link rel="icon" href="img/core-img/favicon.ico">

    <!-- Core Stylesheet -->
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- ============================
         Modern Custom Styles
    ============================ -->
    <style>
        :root {
            --primary-color: #1abc9c;
            --primary-dark: #16a085;
            --secondary-color: #7B1E2B;
            --secondary-light: #9a2b3a;
            --accent-color: #E6B84C;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --gray-color: #6c757d;
            --light-gray: #e9ecef;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            font-family: "FuturaLT-Book";
            color: #333;
            background-color: #f9fbfd;
            line-height: 1.6;
        }

        .breadcumb-area {
            position: relative;
            height: 300px;
            overflow: hidden;
        }

        .breadcumb-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26, 188, 156, 0.9), rgba(22, 160, 133, 0.9));
            z-index: 1;
        }

        .breadcumb-area .bradcumbContent {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .breadcumb-area h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        /* Main Content Section */
        .about-us-area {
            padding: 80px 0;
            position: relative;
        }

        .container {
            max-width: 1400px;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .section-header h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        .section-header p {
            color: var(--gray-color);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Price Cards */
        .price-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
            transition: var(--transition);
            border: none;
            height: 100%;
        }

        .price-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .price-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px 25px;
            position: relative;
            overflow: hidden;
        }

        .price-card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .price-card-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            color: var(--gray-color);
        }

        .price-card-header h3 i {
            margin-right: 12px;
            font-size: 1.3rem;
        }

        .price-card-header .subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 5px;
            position: relative;
            z-index: 1;
        }

        /* Common Facilities Card Special Styling */
        .common-facilities .price-card-header {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }

        /* Table Styles */
        .price-table-container {
            padding: 0;
            overflow: hidden;
        }

        .price-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .price-table thead th {
            background-color: var(--light-gray);
            color: var(--dark-color);
            font-weight: 600;
            font-size: 0.95rem;
            padding: 15px;
            border-bottom: 2px solid var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .price-table tbody tr {
            transition: var(--transition);
        }

        .price-table tbody tr:hover {
            background-color: rgba(26, 188, 156, 0.05);
        }

        .price-table tbody td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        .price-table tbody tr:last-child td {
            border-bottom: none;
        }

        .price-table tbody tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        .price-tag {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .max-people {
            color: var(--secondary-color);
            font-weight: 600;
            background-color: rgba(123, 30, 43, 0.08);
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .no-data {
            color: #e74c3c;
            font-weight: 500;
            text-align: center;
            padding: 30px !important;
            font-style: italic;
        }

        /* Scrollable Table Body */
        .table-scroll {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Event Cards Grid */
        .event-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        /* Event Card Specific */
        .event-card .price-card-header {
            background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .event-cards-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .breadcumb-area h2 {
                font-size: 2.2rem;
            }
            
            .section-header h2 {
                font-size: 2rem;
            }
            
            .event-cards-grid {
                grid-template-columns: 1fr;
            }
            
            .price-table thead th, 
            .price-table tbody td {
                padding: 12px 10px;
            }
        }

        @media (max-width: 576px) {
            .breadcumb-area {
                height: 250px;
            }
            
            .about-us-area {
                padding: 50px 0;
            }
            
            .price-card-header h3 {
                font-size: 1.3rem;
            }
        }

        /* Badge for row numbers */
        .row-number {
            display: inline-block;
            width: 28px;
            height: 28px;
            line-height: 28px;
            text-align: center;
            background: var(--light-gray);
            color: var(--dark-color);
            border-radius: 50%;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Footer Styling */
        .footer-area {
            background-color: var(--dark-color);
            color: #ccc;
            padding: 60px 0 30px;
            margin-top: 60px;
        }

        .footer-widget-area img {
            max-height: 60px;
        }

        .copywrite-text {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 30px;
            font-size: 0.95rem;
            color: #aaa;
        }

        /* Animation for cards */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .price-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Stagger animation for event cards */
        .event-card:nth-child(1) { animation-delay: 0.1s; }
        .event-card:nth-child(2) { animation-delay: 0.2s; }
        .event-card:nth-child(3) { animation-delay: 0.3s; }
        .event-card:nth-child(4) { animation-delay: 0.4s; }
        .event-card:nth-child(5) { animation-delay: 0.5s; }
        .event-card:nth-child(n+6) { animation-delay: 0.6s; }
    </style>
</head>

<body>

<!-- ============================
     Breadcumb Area
============================ -->
<section class="breadcumb-area bg-img d-flex align-items-center justify-content-center" style="background-image: url(img/bg-img/bg-2.jpg);">
    <div class="bradcumbContent">
        <h2>Price Chart</h2>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Explore our transparent pricing for all event facilities</p>
    </div>
</section>

<!-- ============================
     Main Content
============================ -->
<section class="about-us-area">
    <div class="container">
        <!-- Common Facilities Section Header -->
        <div class="section-header">
            <h2>Common Facilities</h2>
            <p>Base facilities available for all types of events at Greenland</p>
        </div>

        <!-- ============================
             Common Facilities Panel
        ============================ -->
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="price-card common-facilities">
                    <div class="price-card-header">
                        <h3>
                            <i class="fas fa-list-alt"></i>
                            Common Facilities
                            <span class="subtitle">&nbsp;&nbsp;(Applicable to all events)</span>
                        </h3>
                    </div>
                    
                    <div class="price-table-container">
                        <div class="table-scroll">
                            <table class="table price-table">
                                <thead>
                                    <tr>
                                        <th width="8%">#</th>
                                        <th>Facility Name</th>
                                        <th width="25%">Base Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                $all_q = "SELECT fName, fPrice FROM facility WHERE eName = 'ALL' ORDER BY fName";
                                $all_r = mysqli_query($con, $all_q);

                                if (mysqli_num_rows($all_r) == 0) {
                                    echo "<tr><td colspan='3' class='no-data'>No common facilities available</td></tr>";
                                }

                                while ($fac = mysqli_fetch_assoc($all_r)) {
                                    $i++;
                                ?>
                                <tr>
                                    <td><span class="row-number"><?php echo $i; ?></span></td>
                                    <td><?php echo e($fac['fName']); ?></td>
                                    <td class="price-tag">₹<?php echo number_format($fac['fPrice'], 2); ?></td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Facilities Section Header -->
        <div class="section-header" style="margin-top: 70px;">
            <h2>Event-Specific Facilities</h2>
            <p>Specialized facilities and pricing for each event type</p>
        </div>

        <!-- ============================
             Event Wise Cards Grid
        ============================ -->
        <div class="event-cards-grid">
        <?php
        $event_q = "SELECT e_name FROM event ORDER BY id DESC";
        $event_r = mysqli_query($con, $event_q);

        while ($event = mysqli_fetch_assoc($event_r)) {
            $event_name = $event['e_name'];
            $event_safe = mysqli_real_escape_string($con, $event_name);
        ?>
            <div class="price-card event-card">
                <div class="price-card-header">
                    <h3>
                        <i class="fas fa-calendar-alt"></i>
                        <?php echo e($event_name); ?>
                    </h3>
                </div>
                
                <div class="price-table-container">
                    <div class="table-scroll">
                        <table class="table price-table">
                            <thead>
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Facility Name</th>
                                    <th width="20%">Max People</th>
                                    <th width="25%">Base Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 0;
                            //$facility_q = "SELECT fName, max_people, fPrice FROM facility WHERE eName = '$event_safe' ORDER BY fName";
                            $facility_q = "
                                SELECT 
                                    fName,
                                    CASE
                                        WHEN (
                                            SELECT COUNT(DISTINCT f2.fPrice)
                                            FROM facility f2
                                            WHERE f2.eName = f.eName
                                            AND f2.fName = f.fName
                                        ) = 1
                                        THEN '-' 
                                        ELSE max_people
                                    END AS max_people,
                                    fPrice
                                FROM facility f
                                WHERE eName = '$event_safe'
                                AND (
                                    (SELECT COUNT(DISTINCT f2.fPrice)
                                    FROM facility f2
                                    WHERE f2.eName = f.eName
                                    AND f2.fName = f.fName) > 1
                                    OR
                                    max_people = (
                                        SELECT MIN(f3.max_people)
                                        FROM facility f3
                                        WHERE f3.eName = f.eName
                                        AND f3.fName = f.fName
                                    )
                                )
                                ORDER BY fName, max_people";
                            $facility_r = mysqli_query($con, $facility_q);

                            if (mysqli_num_rows($facility_r) == 0) {
                                echo "<tr><td colspan='4' class='no-data'>No facilities available for this event</td></tr>";
                            }

                            while ($fac = mysqli_fetch_assoc($facility_r)) {
                                $i++;
                            ?>
                            <tr>
                                <td><span class="row-number"><?php echo $i; ?></span></td>
                                <td><?php echo e($fac['fName']); ?></td>
                                <td><span class="max-people"><?php echo e($fac['max_people']); ?></span></td>
                                <td class="price-tag">₹<?php echo number_format($fac['fPrice'], 2); ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>

    </div>
</section>

<!-- ============================
     Footer
============================ -->
<footer class="footer-area">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-5">
                <div class="footer-widget-area mt-50">
                    <a href="#" class="d-block mb-5">
                        <img src="img/core-img/logo.png" alt="Greenland Logo">
                    </a>
                </div>
            </div>

            <div class="col-12">
                <div class="copywrite-text mt-30">
                    <p>
                        <i class="far fa-copyright"></i>
                        Copyright <script>document.write(new Date().getFullYear());</script>
                        All rights reserved | Greenland Event Management
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- ============================
     Scripts
============================ -->
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/popper.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/plugins/plugins.js"></script>
<script src="js/active.js"></script>

<!-- Custom JavaScript for Enhanced Interactions -->
<script>
    $(document).ready(function() {
        // Add hover effect to table rows
        $('.price-table tbody tr').hover(
            function() {
                $(this).css('transform', 'scale(1.005)');
            },
            function() {
                $(this).css('transform', 'scale(1)');
            }
        );

        // Smooth scroll for table overflow
        $('.table-scroll').on('scroll', function() {
            $(this).addClass('scrolling');
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function() {
                $(this).removeClass('scrolling');
            }, 250));
        });

        // Add animation to cards on scroll
        function animateOnScroll() {
            $('.price-card').each(function() {
                var elementTop = $(this).offset().top;
                var elementBottom = elementTop + $(this).outerHeight();
                var viewportTop = $(window).scrollTop();
                var viewportBottom = viewportTop + $(window).height();
                
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('animated');
                }
            });
        }
        
        $(window).on('scroll', animateOnScroll);
        animateOnScroll(); // Initial check
    });
</script>

</body>
</html>