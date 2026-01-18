<?php
include ("includes/db.php");
include ("header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>Greenland  Garden</title>

    <!-- Favicon -->
    <link rel="icon" href="img/core-img/favicon.ico">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- jQuery (must come first) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS Bundle (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Animate.css for animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="style.css">

    <!-- For calendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .user-details-heading {
            color: #1a1b1bff; /* Bootstrap primary blue */
            letter-spacing: 1px;

            background-color: #cb8670; /* হালকা ব্যাকগ্রাউন্ড */
            padding: 7px 10px;        /* ভিতরের ফাঁকা জায়গা */
            border-radius: 7px;        /* গোলাকার কোণ */
            border: 1px solid #ddddddff;    /* হালকা বর্ডার */
            display: block;            /* পুরো width নেবে */
            box-shadow: 6px 5px 10px rgba(7, 6, 87, 0.3);
        }

        /* Modal Background Gradient */
        .user-modal-bg {
            background: linear-gradient(to bottom right, #cb8670, #cb8670);
            color: white;
        }

        /* Stylish Heading */
        .stylish-heading {
            font-size: 1.5rem;
            color: #1b1b19ff;
            letter-spacing: 1px;
        }

        /* Label Style */
        .stylish-label {
            font-weight: 500;
            color: #ffdd57;
        }

        /* Input Style */
        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            border-color: #ffdd57;
            box-shadow: 0 0 8px rgba(255, 221, 87, 0.6);
        }

        .datetime-input {
            width: 152px !important;   /* Bootstrap override. Desktop এ fixed width */
            width: 100%;        /* mobile/tablet এ full width */
            font-size: 12px;
            display: inline-block;     /* inline rakhar jonne */
            font-weight: bold;
        }


    </style>


</head>

<body>
   
    <!-- ##### Hero Area Start ##### -->
    <section class="hero-area">
        <div class="hero-slides owl-carousel">

            <!-- Single Hero Slide -->
            <div class="single-hero-slide d-flex align-items-center justify-content-center">
                <!-- Slide Img -->
                <div class="slide-img bg-img" style="background-image: url(img/bg-img/bg-1.jpeg);"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-9">
                            <!-- Slide Content -->
                            <div class="hero-slides-content" data-animation="fadeInUp" data-delay="100ms">
                                <div class="line" data-animation="fadeInUp" data-delay="300ms"></div>
                                <h2 data-animation="fadeInUp" data-delay="500ms">The Vacation Heaven</h2>
                                <p data-animation="fadeInUp" data-delay="700ms">Greenland is a place where love, laughter, and togetherness come alive amidst nature. Whether celebrating a wedding picnic, a child’s first rice ceremony, or a joyful family day, the green surroundings, open skies, and warm moments create memories to cherish forever. A perfect garden escape where every celebration feels peaceful, beautiful, and truly special..</p>
                                <!--a href="#" class="btn palatin-btn mt-50" data-animation="fadeInUp" data-delay="900ms">Read More</a-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Hero Slide -->
            <div class="single-hero-slide d-flex align-items-center justify-content-center">
                <!-- Slide Img -->
                <div class="slide-img bg-img" style="background-image: url(img/bg-img/bg-2.jpeg);"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-9">
                            <!-- Slide Content -->
                            <div class="hero-slides-content" data-animation="fadeInUp" data-delay="100ms">
                                <div class="line" data-animation="fadeInUp" data-delay="300ms"></div>
                                <h2 data-animation="fadeInUp" data-delay="500ms">A place to remember</h2>
                                <p data-animation="fadeInUp" data-delay="700ms">“Surrounded by nature’s beauty, this garden is a perfect place for every celebration — be it a wedding, reception, Annaprasan, family gathering, or a peaceful picnic. With open green spaces, elegant décor, and a warm, joyful atmosphere, it creates unforgettable moments where laughter, love, and memories come together. A place to remember, where every special event begins beautifully.”</p>
                                 <!--a href="#" class="btn palatin-btn mt-50" data-animation="fadeInUp" data-delay="900ms">Read More</a-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Hero Slide -->
            <div class="single-hero-slide d-flex align-items-center justify-content-center">
                <!-- Slide Img -->
                <div class="slide-img bg-img" style="background-image: url(img/bg-img/bg-3.jpeg);"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-9">
                            <!-- Slide Content -->
                            <div class="hero-slides-content" data-animation="fadeInUp" data-delay="100ms">
                                <div class="line" data-animation="fadeInUp" data-delay="300ms"></div>
                                <h2 data-animation="fadeInUp" data-delay="500ms">Enjoy your life</h2>
                                <p data-animation="fadeInUp" data-delay="700ms">Enjoy your life in this garden, where nature wraps you in calm and every moment feels special. Surrounded by lush greenery, fresh air, and open spaces, it’s the perfect place to relax, celebrate, and create beautiful memories.</p>
                                <!--a href="#" class="btn palatin-btn mt-50" data-animation="fadeInUp" data-delay="900ms">Read More</a-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- ##### Hero Area End ##### -->

    <!-- ##### Book Now Area Start ##### -->
    <div class="book-now-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="book-now-form">
                        <form class="form-horizontal" id="bookingForm" method="post" action="" enctype="multipart/form-data">
                            <!-- Form Group -->
                            <div class="form-group">
                                <label for="fbdate">Booking From</label>
                                <input type="text" name="fbdate" id="fbdate" class="form-control datetime-input" required readonly>
                            </div>

                            <!-- Form Group -->
                            <div class="form-group">
                                <label for="tbdate">Booking To</label>
                                <input type="text" name="tbdate" id="tbdate" class="form-control datetime-input" required readonly>
                            </div>

                            <!-- Form Group -->
                            <div class="form-group">
                                <label for="select3">Event</label>
                                <select name="event" id="event" class="form-control" required>
									<?php 
                                    $get_test="select * from event order by e_name";
                                    $run_test=mysqli_query($con,$get_test);
                                    while ($row=mysqli_fetch_array($run_test)) {
                                        $id=$row['id'];
                                        $name=$row['e_name'];                                        
                                        echo "<option value='$name'> $name </option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Form Group -->
                            <div class="form-group">
                                <label for="select4">Maximum People</label>
                                <select name="max_people" id="maxPeople" class="form-control">
									<option value="">-- Select --</option>
									<?php
									$run = mysqli_query($con,"SELECT * FROM guest ORDER BY max_guest");
									while($row=mysqli_fetch_assoc($run)){
										echo "<option value='{$row['max_guest']}'>{$row['max_guest']}</option>";
									}
									?>
								</select>
                            </div>

                            <!-- Button -->
                            <!-- <button type="submit" name="book">  Book Now</button> -->
                            <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModal" id="openModalBtn"> -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#facilityModal" id="openModalBtn">
                                Book Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="finalBookingForm">
                    <div class="modal-header ">
                        <h5 class="modal-title">Confirm Booking Details</h5>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                    
                        <!-- Summary preview -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong><i class="fa fa-calendar-check text-success"></i> Booking From:</strong> <span id="modal_fbdate"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fa fa-calendar-xmark text-danger"></i> Booking To:</strong> <span id="modal_tbdate"></span></p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong><i class="fa fa-star text-warning"></i> Event:</strong> <span id="modal_event"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fa fa-users text-primary"></i> Maximum People:</strong> <span id="modal_maxPeople"></span></p>
                            </div>
                        </div>

                        <!-- User Details Inputs -->
                        <h5 class="modal-title user-details-heading"><i class="fa fa-user-circle"></i> Your Details</h5><br/>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="userName" class="form-label"><i class="fa fa-user"></i> Name</label>
                                <input type="text" id="userName" name="userName" class="form-control" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="userEmail" class="form-label"><i class="fa fa-envelope"></i> Email</label>
                                <input type="email" id="userEmail" name="userEmail" class="form-control" placeholder="example@mail.com">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="userPhone" class="form-label"><i class="fa fa-phone"></i> Phone</label>
                                <input type="tel" id="userPhone" name="userPhone" class="form-control" placeholder="Mobile number" maxlength="10" required>
                                <div class="form-text">Enter 10-digit mobile number</div>
                            </div>
                            <div class="col-md-6">
                                <label for="userId" class="form-label"><i class="fa fa-id-card"></i> Aadhaar / PAN</label>
                                <input type="text" id="userId" name="userId" class="form-control" placeholder="Aadhaar or PAN number" maxlength="12" required>
                                <div class="form-text">Enter Aadhaar (12 digits) or PAN (10 characters)</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="userAddress" class="form-label"><i class="fa fa-location-dot"></i> Address</label>
                                <textarea id="userAddress" name="userAddress" class="form-control" rows="3" placeholder="Enter your address" required></textarea>
                            </div>
                        </div>

                         <!-- Facilities Table -->
                        <div class="modal-content user-modal-bg">
                            <div class="modal-header">
                                <h5 class="modal-title stylish-heading">Available Facilities</h5>
                            </div>
                            <table class="table table-bordered table-hover align-middle table-bordered" id="itemTable">
                                <thead>
                                <tr>
                                    <th style="vertical-align: middle;">
                                        <label for="selectAllFacilities" style="cursor: pointer; user-select: none; display: flex; align-items: center; gap: 5px; margin: 0;">
                                            Select All <input type="checkbox" id="selectAllFacilities">
                                        </label>
                                    </th>
                                    <th>Facilities</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-right">Net Amount</th>
                                </tr>
                                </thead>
                                <tbody id="facilityTableBody">
                                <!-- Filled via JS if needed -->
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-3">
                                <h5>
                                    Grand Total: 
                                    <span id="grandTotal" class="badge fs-5">₹ 0.00</span>
                                </h5>
                            </div>

                            <!-- Hidden fields to submit all -->
                            <input type="hidden" name="fbdate" id="hidden_fbdate">
                            <input type="hidden" name="tbdate" id="hidden_tbdate">
                            <input type="hidden" name="event" id="hidden_event">
                            <!-- <input type="hidden" name="eventTime" id="hidden_eventTime"> -->
                            <input type="hidden" name="maxPeople" id="hidden_maxPeople">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="confirmBookingBtn">
                            Confirm Booking
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ##### About Us Area Start ##### -->
    <section class="about-us-area">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-12 col-lg-6">
                    <div class="about-text text-center mb-100">
                        <div class="section-heading text-center">
                            <div class="line-"></div>
                            <h2>A place to remember</h2>
                        </div>
                        <p>"Surrounded by nature’s beauty, this garden is a perfect place for every celebration — be it a wedding, reception, Annaprasan, family gathering, or a peaceful picnic. With open green spaces, elegant décor, and a warm, joyful atmosphere, it creates unforgettable moments where laughter, love, and memories come together. A place to remember, where every special event begins beautifully.”</p>
                        <div class="about-key-text">
                            <h6><span class="fa fa-check"></span> The garden is beautifully managed, and the friendly behavior of the owner makes every visit comfortable and memorable.</h6>
                            <h6><span class="fa fa-check"></span> Everything you need is available, making the place very convenient.</h6>
                        </div>
                        <!--a href="#" class="btn palatin-btn mt-50">Read More</a-->
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="about-thumbnail homepage mb-100">
                        <!-- First Img -->
                        <div class="first-img wow fadeInUp" data-wow-delay="100ms">
                            <img src="img/bg-img/5.jpeg" alt="">
                        </div>
                        <!-- Second Img -->
                        <div class="second-img wow fadeInUp" data-wow-delay="600ms">
                            <img src="img/bg-img/6.jpeg" alt="">
                        </div>
                        <!-- Third Img-->
                        <div class="third-img wow fadeInUp" data-wow-delay="1100ms">
                            <img src="img/bg-img/7.jpeg" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### About Us Area End ##### -->

    <!-- ##### Pool Area Start ##### -->
    <section class="pool-area section-padding-100 bg-img bg-fixed" style="background-image: url(img/bg-img/4.jpeg);">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-12 col-lg-7">
                    <div class="pool-content text-center wow fadeInUp" data-wow-delay="300ms">
                        <div class="section-heading text-center white">
                            <div class="line-"></div>
                            <h2>Colorful Flowers</h2>
                            <p>A beautiful garden filled with colorful flowers creates a calm and refreshing atmosphere. The vibrant blooms add natural charm, making it a perfect place to relax, celebrate special moments, or enjoy time with family and friends.</p>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-4">
                               <div class="pool-feature">
									<i class="fa fa-anchor"></i>
									<p>Dolna (Swing)</p>
								</div>

                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="pool-feature">
                                    <div class="pool-feature">
										<i class="fa fa-couch"></i>
										<p>Sleeper Seating</p>
									</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="pool-feature">
										<i class="fa fa-child-reaching"></i>
										<p>Kids Playground</p>
								</div>
                            </div>
                        </div>
                        <!-- Button -->
                        <!--a href="#" class="btn palatin-btn mt-50">Read More</a-->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Pool Area End ##### -->

    <!-- ##### Rooms Area Start ##### -->
    <section class="rooms-area section-padding-100-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="section-heading text-center">
                        <div class="line-"></div>
                        <h2>Choose an event</h2>
                        <p>The gentle breeze and warm sunlight create an inviting environment where memories are made and joy blossoms. A garden picnic truly brings people closer to nature and to each other.</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">

                <!-- Single Rooms Area -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="single-rooms-area wow fadeInUp" data-wow-delay="100ms">
                        <!-- Thumbnail -->
                        <div class="bg-thumbnail bg-img" style="background-image: url(img/bg-img/1.png);"></div>
                        <!-- Price -->
                        <p class="price-from">From ₹15,000/night</p>
                        <!-- Rooms Text -->
                        <div class="rooms-text">
                            <div class="line"></div>
                            <h4>Marriage Reception</h4>
                            <p>The venue is beautifully decorated with flowers, elegant drapes, and twinkling lights that create a warm and festive atmosphere.</p>
                        </div>
                        <!-- Book Room -->
                        <a href="#bookingForm" class="book-room-btn btn palatin-btn">Book Now</a>
                    </div>
                </div>

                <!-- Single Rooms Area -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="single-rooms-area wow fadeInUp" data-wow-delay="300ms">
                        <!-- Thumbnail -->
                        <div class="bg-thumbnail bg-img" style="background-image: url(img/bg-img/2.png);"></div>
                        <!-- Price -->
                        <p class="price-from">From ₹10,000/day</p>
                        <!-- Rooms Text -->
                        <div class="rooms-text">
                            <div class="line"></div>
                            <h4>Rice Ceremony</h4>
                            <p>The Rice Ceremony, or Annaprasan, is a special and sacred ritual that marks a baby’s first intake of solid food, usually rice.</p>
                        </div>
                        <!-- Book Room -->
                        <a href="#bookingForm" class="book-room-btn btn palatin-btn">Book Now</a>
                    </div>
                </div>

                <!-- Single Rooms Area -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="single-rooms-area wow fadeInUp" data-wow-delay="500ms">
                        <!-- Thumbnail -->
                        <div class="bg-thumbnail bg-img" style="background-image: url(img/bg-img/3.jpeg);"></div>
                        <!-- Price -->
                        <p class="price-from">From ₹5000/day</p>
                        <!-- Rooms Text -->
                        <div class="rooms-text">
                            <div class="line"></div>
                            <h4>Picnic</h4>
                            <p>It’s a perfect way to take a break from daily routines, create happy memories, and enjoy quality time with loved ones. Greenland is the best for all Events.</p>
                        </div>
                        <!-- Book Room -->
                        <a href="#bookingForm" class="book-room-btn btn palatin-btn">Book Now</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ##### Rooms Area End ##### -->

    <!-- ##### Contact Area Start ##### -->
    <section class="contact-area d-flex flex-wrap align-items-center">
        <div class="home-map-area">
          
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2911.060320825519!2d88.10311797406071!3d22.60950233161914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f87f002834a0f1%3A0xb40ab1734b9d2868!2sGreenland%20Garden!5e1!3m2!1sen!2sin!4v1750176863474!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			
			
        </div>
        <!-- Contact Info -->
        <div class="contact-info">
            <div class="section-heading wow fadeInUp" data-wow-delay="100ms">
                <div class="line-"></div>
                <h2>Contact Info</h2>
                <p></p>
            </div>
            <h4 class="wow fadeInUp" data-wow-delay="300ms">Mr. Monibesh Maity</h4>
            <h5 class="wow fadeInUp" data-wow-delay="400ms">9088152702</h5>
            <h5 class="wow fadeInUp" data-wow-delay="500ms">greenland@gmail.com</h5>
            <!-- Social Info -->
            <!--div class="social-info mt-50 wow fadeInUp" data-wow-delay="600ms">
                <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a>
                <a href="#"><i class="fa fa-behance" aria-hidden="true"></i></a>
                <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
            </div-->
        </div>
    </section>
    <!-- ##### Contact Area End ##### -->

    <!-- ##### Footer Area Start ##### -->
    <footer class="footer-area">
        <div class="container">
            <div class="row">

                <!-- Footer Widget Area -->
                <div class="col-12 col-lg-5">
                    <div class="footer-widget-area mt-50">
                        <a href="#" class="d-block mb-5"><img src="img/core-img/logo.png" alt=""></a>
                        <p></p>
                    </div>
                </div>

                <!-- Footer Widget Area -->
                <!--div class="col-12 col-md-6 col-lg-4">
                    <div class="footer-widget-area mt-50">
                        <h6 class="widget-title mb-5">Find us on the map</h6>
                        <img src="img/bg-img/footer-map.png" alt="">
                    </div>
                </div>

                <!-- Footer Widget Area -->
                <!--div class="col-12 col-md-6 col-lg-3">
                    <div class="footer-widget-area mt-50">
                        <h6 class="widget-title mb-5">Subscribe to our newsletter</h6>
                        <form action="#" method="post" class="subscribe-form">
                            <input type="email" name="subscribe-email" id="subscribeemail" placeholder="Your E-mail">
                            <button type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>

                <!-- Copywrite Text -->
                <div class="col-12">
                    <div class="copywrite-text mt-30">
                        <p><a href="#"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Greenland
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- ##### Footer Area End ##### -->

    <!-- ##### All Javascript Script ##### -->
    <!-- jQuery-2.2.4 js -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- All Plugins js -->
    <script src="js/plugins/plugins.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>
</body>

</html>

<script>

    // 🔥 Global flag for Special Day Pricing
    let useSpecialGlobal = false;

    const phoneInput = document.getElementById('userPhone');

    phoneInput.addEventListener('input', function () {
        // শুধুমাত্র সংখ্যা রাখবে
        this.value = this.value.replace(/\D/g, '');
        
        // 10 digit এর বেশি হলে auto truncate
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });

    // Booking From (Date + Time)
    let fromDate = flatpickr("#fbdate", {
        enableTime: true,          // ✅ Time enable
        time_24hr: false,           // ২৪ ঘন্টার ফরম্যাট (optional)
        dateFormat: "d-m-Y h:i K",  // ✅ AM/PM format
        minDate: "today",          // আজকের আগের allow হবে না
        onChange: function(selectedDates, dateStr, instance) {
            // যখন From Date select করবে, To Date তার পরে হতে হবে
            toDate.set("minDate", dateStr);
        }
    });

    // Booking To (Date + Time)
    let toDate = flatpickr("#tbdate", {
        enableTime: true,
        time_24hr: false,
        dateFormat: "d-m-Y h:i K",  // ✅ AM/PM format
        minDate: "today"
    });

    document.addEventListener("DOMContentLoaded", function () {
        let myModalEl = document.getElementById('facilityModal'); // modal id

        myModalEl.addEventListener('show.bs.modal', function (event) {

            const fbdate = document.getElementById("fbdate").value;
            const tbdate = document.getElementById("tbdate").value;
            const eventName  = document.getElementById("event").value;
            //const eventTime = document.getElementById("eventTime").value;
            const maxPeople = document.getElementById("maxPeople").value;

            if (!fbdate || !tbdate) {
                event.preventDefault();
                alert("Please select Booking From and To date first!");
                return;
            }
            
            // Customer details
            const userName = document.getElementById("userName").value;
            const userEmail = document.getElementById("userEmail").value;
            const userPhone = document.getElementById("userPhone").value;
            const userId = document.getElementById("userId").value;
            const userAddress = document.getElementById("userAddress").value;

            // Show in modal
            document.getElementById("modal_fbdate").innerText = fbdate;
            document.getElementById("modal_tbdate").innerText = tbdate;
            document.getElementById("modal_event").innerText = eventName ;
            //document.getElementById("modal_eventTime").innerText = eventTime;
            document.getElementById("modal_maxPeople").innerText = maxPeople;

            // Set hidden input values for final submission
            document.getElementById("hidden_fbdate").value = fbdate;
            document.getElementById("hidden_tbdate").value = tbdate;
            document.getElementById("hidden_event").value = eventName ;
            //document.getElementById("hidden_eventTime").value = eventTime;
            document.getElementById("hidden_maxPeople").value = maxPeople;

            $.ajax({
                url: "get_facilities.php",
                type: "GET",
                dataType: "json",
                data: { 
                    eventName: eventName,
                    noOfPeople: maxPeople,
                    fbdate: fbdate,
                    tbdate: tbdate
                },
                success: function (response) {
                    const useSpecial = response.useSpecial;
                    const facilities = response.facilities;

                    useSpecialGlobal = useSpecial; // 🔥 store globally for final submit
                    
                    console.log("Facilities received:", facilities);
                    
                    let tableBody = $("#facilityTableBody");
                    tableBody.empty(); // Clear old rows

                    if (useSpecial) {
                        $("#specialBadge").remove(); 
                        $("#itemTable").before(
                            `<div id="specialBadge" class="alert alert-warning text-center">
                                <i class="fa fa-star"></i>
                                Special Day Pricing Applied (Sunday / Holiday)
                            </div>`
                        );
                    }

                    facilities.forEach(function (facility) {

                        // Check if compulsory
                        let isCompulsory = facility.compulsory == 1;

                        let row = `
                        <tr class="${isCompulsory ? 'table-warning' : ''}">
                            <td>
                                <input type="checkbox" 
                                    class="facility-checkbox"
                                    value="${facility.id}"
                                    ${isCompulsory ? 'checked' : ''}>
                            </td>
                            <td>
                                ${facility.fName}
                                ${isCompulsory ? '<span class="badge bg-danger ms-2">Compulsory</span>' : ''}
                                <input type="hidden" class="facility-name" value="${facility.fName}">
                                <input type="hidden" class="facility-gst" value="${facility.gst_rate}">
                            </td>
                            <td>
                                ${facility.fPrice}
                                <input type="hidden" class="facility-price" value="${facility.fPrice}">
                            </td>
                            <td>
                                <input type="number" 
                                    class="form-control facility-qty"
                                    min="1"
                                    value="${isCompulsory ? 1 : ''}"
                                    ${isCompulsory ? 'readonly' : ''}>
                            </td>
                            <td>
                                <input type="text"
                                    class="form-control facility-net text-right"
                                    value="0.00"
                                    readonly>
                            </td>
                        </tr>
                        `;

                        tableBody.append(row);
                    });

                    // Select All functionality (won't affect compulsory because disabled)
                    $("#selectAllFacilities").off("change").on("change", function () {

                        const isChecked = this.checked;

                        $(".facility-checkbox").each(function () {
                            let row = $(this).closest("tr");
                            let qtyInput = row.find(".facility-qty");

                            // If compulsory row → always checked
                            if (row.hasClass("table-warning")) {
                                this.checked = true;
                            } else {
                                this.checked = isChecked;
                            }

                            // If checked & qty empty → set 1
                            if (this.checked) {
                                if (!qtyInput.val() || qtyInput.val() <= 0) {
                                    qtyInput.val(1);
                                }
                            }
                        });

                        // 🔥 Force recalculation
                        calculateTotals();
                    });

                    // Live update when checkbox or qty changes
                    // FORCE compulsory rows to stay checked + live total update
                    $("#facilityTableBody")
                    .off()
                    .on("change", ".facility-checkbox", function () {

                        let row = $(this).closest("tr");
                        let qtyInput = row.find(".facility-qty");

                        // If compulsory → force checked
                        if (row.hasClass("table-warning")) {
                            this.checked = true;
                        }

                        // If checked & qty empty → set 1
                        if (this.checked) {
                            if (!qtyInput.val() || qtyInput.val() <= 0) {
                                qtyInput.val(1);
                            }
                        }

                        calculateTotals();
                    })
                    .on("input", ".facility-qty", function () {
                        calculateTotals();
                    });

                    // Initial calculation
                    calculateTotals();
                }
            });
        });
    });

    document.getElementById('finalBookingForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent normal form submission
        const confirmBtn = document.getElementById("confirmBookingBtn");

        // Collect main booking info
        let fbdate = $("input[name='fbdate']").val();
        let tbdate = $("input[name='tbdate']").val();
        let event = $("#hidden_event").val();
        let maxPeople = $("#hidden_maxPeople").val();
        let userName = $("#userName").val();
        let userEmail = $("#userEmail").val();
        let userPhone = $("#userPhone").val();
        let userId = $("#userId").val();
        let userAddress = $("#userAddress").val(); 
        let gTotal = $("#grandTotal").text().replace('₹', '').trim();

        // Collect selected facilities
        let selectedFacilities = [];

        $(".facility-checkbox:checked").each(function () {
            let row = $(this).closest("tr");

            let id = $(this).val();
            let name = row.find(".facility-name").val();
            let price = row.find(".facility-price").val();
            let gstRate = row.find(".facility-gst").val();   // ✅ GST
            let qty = row.find(".facility-qty").val();

            selectedFacilities.push({
                id: id,
                name: name,
                price: price,
                gst_rate: gstRate,
                quantity: qty
            });
        });


        //console.log("Sending facilities:", selectedFacilities);
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

        $.ajax({
            url: 'save_booking.php',  // Your backend PHP script that will save to DB
            type: 'POST',
            contentType: "application/json",   // sending as JSON
            dataType: "json",                  // receiving as JSON
            data: JSON.stringify({
                fbdate: fbdate,
                tbdate: tbdate,
                event: event,
                maxPeople: maxPeople,
                userName: userName,
                userEmail: userEmail,
                userPhone: userPhone,
                userId: userId,
                userAddress: userAddress,
                grandTotal: gTotal,
                useSpecial: useSpecialGlobal,   // ⭐ ADD THIS
                facilities: selectedFacilities
            }),
            success: function(response) {
                // Assuming your PHP returns JSON like { success: true, message: "Saved" }
                console.log("Raw response from PHP:", response); 
                // 🔓 button enable again
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = 'Confirm Booking';

                try {
                    
                    if (response.success) {
                        alert(response.message);

                        let modalEl = document.getElementById('facilityModal');
                        let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.hide();

                        $('#finalBookingForm')[0].reset();
                        $("#facilityTableBody").empty();

                        // PDF auto download (যেমন আছে তেমনই থাকবে)
                        if (response.pdf_base64) {
                            const byteCharacters = atob(response.pdf_base64);
                            const byteNumbers = new Array(byteCharacters.length);
                            for (let i = 0; i < byteCharacters.length; i++) {
                                byteNumbers[i] = byteCharacters.charCodeAt(i);
                            }
                            const byteArray = new Uint8Array(byteNumbers);
                            const blob = new Blob([byteArray], { type: "application/pdf" });

                            const link = document.createElement("a");
                            link.href = URL.createObjectURL(blob);
                            link.download = "Booking.pdf";
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            URL.revokeObjectURL(link.href);
                        }

                    } else {
                        alert("Error: " + response.message);
                    }
                } catch (err) {
                    console.error("❌ JSON Parse Error:", err);
                    console.error("🔎 Server actually returned:", response);
                    alert("Unexpected server response.");
                }
            },
            error: function(xhr, status, error) {

                confirmBtn.disabled = false;
                confirmBtn.innerHTML = 'Confirm Booking';

                console.error("AJAX Error:", status, error);
                alert("AJAX error while saving booking.");
            }

        });
    });

    document.addEventListener("DOMContentLoaded", function () {

        // ===== 1. Set Today's Date & Time =====
        function formatDateTime(dt) {
            let yyyy = dt.getFullYear();
            let mm = String(dt.getMonth() + 1).padStart(2, '0');
            let dd = String(dt.getDate()).padStart(2, '0');

            let hh = String(dt.getHours()).padStart(2, '0');
            let min = String(dt.getMinutes()).padStart(2, '0');

            return `${yyyy}-${mm}-${dd} ${hh}:${min}`;
        }

        let now = new Date();
        let formatted = formatDateTime(now);

        document.getElementById("fbdate").value = formatted;
        document.getElementById("tbdate").value = formatted;

        // ===== 2. Set Middle Value for Maximum People =====
        let maxPeopleSelect = document.querySelector("select[name='max_people']");
        
        if (maxPeopleSelect && maxPeopleSelect.options.length > 1) {
            // ignore first option (-- Select --)
            let totalOptions = maxPeopleSelect.options.length - 1;
            let middleIndex = Math.floor(totalOptions / 2) + 1;
            maxPeopleSelect.selectedIndex = middleIndex;
        }

    });

    function calculateTotals() {
        let grandTotal = 0;

        $("#facilityTableBody tr").each(function () {
            let row = $(this);

            let checkbox = row.find(".facility-checkbox")[0];
            let qtyInput = row.find(".facility-qty");
            let netInput = row.find(".facility-net");

            let price = parseFloat(row.find(".facility-price").val());
            let gstRate = parseFloat(row.find(".facility-gst").val());
            let qty = parseInt(qtyInput.val());

            // Fallbacks
            if (isNaN(price)) price = 0;
            if (isNaN(gstRate)) gstRate = 0;
            if (isNaN(qty) || qty <= 0) qty = 0;

            // Net = price × qty
            let netAmount = price * qty;
            netInput.val(netAmount.toFixed(2));

            // Only count checked rows
            if (checkbox && checkbox.checked) {
                let gstAmount = (netAmount * gstRate) / 100;
                grandTotal += netAmount + gstAmount;
            }
        });

        console.log("💰 Grand Total:", grandTotal); // DEBUG
        $("#grandTotal").text("₹ " + grandTotal.toFixed(2));
    }




</script>