<?php
include ("includes/db.php");
include ("header.php");

if(isset($_GET['event_gallery'])){
 	$event_id1=$_GET['event_gallery'];
	
	$get_event="select * from event where id=$event_id1";
	$run_event=mysqli_query($con,$get_event);
	while($row_event=mysqli_fetch_array($run_event)){
		$event_id=$row_event['id'];
		$event_title=$row_event['e_name'];
	}

}
?>
<?php

/* FUNCTION TO EXTRACT YOUTUBE VIDEO ID */
function getYouTubeId($url) {
    preg_match(
        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
        $url,
        $matches
    );
    return $matches[1] ?? '';
}
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
    <title>Greenland</title>

    <!-- Favicon -->
    <link rel="icon" href="img/core-img/favicon.ico">

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="style.css">
	
	
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
        }

        .video-box {
            background: #fff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            cursor: pointer;
        }

        .video-box img {
            width: 100%;
            border-radius: 8px;
        }

        .video-title {
            text-align: center;
            font-weight: bold;
            margin-top: 8px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-content {
            width: 90%;
            max-width: 700px;
            background: #000;
            border-radius: 10px;
            position: relative;
        }

        .modal iframe {
            width: 100%;
            height: 400px;
            border-radius: 10px;
        }

        .close {
            position: absolute;
            top: -35px;
            right: 0;
            font-size: 30px;
            color: #fff;
            cursor: pointer;
        }

        @media (max-width: 600px) {
            .modal iframe {
                height: 220px;
            }
        }
    </style>
	
	

</head>

<body>
    <!-- Preloader -->
    



    <!-- ##### Breadcumb Area Start ##### -->
    <section class="breadcumb-area bg-img d-flex align-items-center justify-content-center" style="background-image: url(img/bg-img/bg-2.jpg);">
        <div class="bradcumbContent">
            <h2><?php echo "$event_title" ?></h2>
        </div>
    </section>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### About Us Area Start ##### -->
    <section class="about-us-area">
        <div class="container">
		
		 <div class="row justify-content-center">
			<?php  
					$get_event="select * from event_gallery where event_id=$event_id1";
					$run_event=mysqli_query($con,$get_event);
					while($row_event=mysqli_fetch_array($run_event)){
						$event_id=$row_event['id'];
						$event_desc=$row_event['e_desc'];
						$event_img=$row_event['e_img'];
						echo"
						 <div class='col-12 col-md-6 col-lg-4'>
                    <div class='single-rooms-area wow fadeInUp' data-wow-delay='100ms'>
                        <!-- Thumbnail -->
                        <div class='bg-thumbnail bg-img' style='background-image: url(admin_area/event_gallery/$event_img);'></div>
                        <!-- Price -->
                       
                        <!-- Rooms Text -->
                        <div class='rooms-text'>
                            <div class='line'></div>
                            <p>$event_desc</p>
                        </div>
                        <!-- Book Room -->
						
                        
                    </div>
                </div>";
					}
					
					?>

                

            </div>
			<h2>🎬 Video Gallery</h2>

			<div class="gallery">
			<?php
			$sql = "SELECT * FROM event_gallery_video where event_id=$event_id1";
			$result = mysqli_query($con, $sql);

			while ($row = mysqli_fetch_assoc($result)) {

				$videoId = getYouTubeId($row['youtube_url']);
				if (!$videoId) continue;
			?>
				<div class="video-box" onclick="openVideo('<?php echo $videoId; ?>')">
					<img src="https://img.youtube.com/vi/<?php echo $videoId; ?>/hqdefault.jpg">
					<div class="video-title"><?php echo htmlspecialchars($row['title']); ?></div>
				</div>
			<?php } ?>
			</div>

			<!-- MODAL -->
			<div class="modal" id="videoModal">
				<div class="modal-content">
					<span class="close" onclick="closeVideo()">&times;</span>
					<iframe id="videoFrame" src="" allowfullscreen></iframe>
				</div>
			</div>

			<script>
			function openVideo(videoId) {
				document.getElementById("videoFrame").src =
					"https://www.youtube.com/embed/" + videoId + "?autoplay=1";
				document.getElementById("videoModal").style.display = "flex";
			}

			function closeVideo() {
				document.getElementById("videoFrame").src = "";
				document.getElementById("videoModal").style.display = "none";
			}
			</script>

           
        </div>
		
		
		
    </section>
	
	
    <!-- ##### About Us Area End ##### -->


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
    <!-- Popper js -->
    <script src="js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="js/plugins/plugins.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>
</body>

</html>