<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

 ?>
 <?php 
 if(isset($_GET['delete_video'])){
 	$delete_id=$_GET['delete_video'];
 	$delete_pro="delete from event_gallery_video where id='$delete_id'";
 	$run_delete=mysqli_query($con,$delete_pro);
 	if($run_delete){
 		echo "<script>alert('Your video has been deleted')</script>";
 		echo "<script>window.open('index.php?view_video','_self')</script>";
 	}
 }


 ?>

 <?php } ?>