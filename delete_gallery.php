<?php 
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login.php','_self')</script>";
}else{

 ?>
 <?php 
 if(isset($_GET['delete_gallery'])){
 	$delete_id=$_GET['delete_gallery'];
 	$delete_pro="delete from event_gallery where id='$delete_id'";
 	$run_delete=mysqli_query($con,$delete_pro);
 	if($run_delete){
 		echo "<script>alert('Your picture has been deleted')</script>";
 		echo "<script>window.open('index.php?view_gallery','_self')</script>";
 	}
 }


 ?>

 <?php } ?>