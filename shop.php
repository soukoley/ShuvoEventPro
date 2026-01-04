<?php
include("header.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>





<div id="content">
	<div class="container"><!--- Container Start----->
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="index.php">Home</a></li>
				<li>Shop</li>
				
			</ul>
		</div>
		<div class="col-md-3 col-sm-3">
			<?php include ("includes/sidebar.php"); ?>
			
		</div>
		<div class="col-md-9">
			<?php 
				if(!isset($_GET['p_cat'])){
					if(!isset($_GET['cat_id'])){
						echo "<div class='box1'>
						<h3>Shop</h3>
						</div>";
					}
				}
			?>
			<div class="row"><!--- Row Start----->
				<?php 
					if(!isset($_GET['p_cat'])){
						if(!isset($_GET['cat_id'])){
							$per_page=9;
							if(isset($_GET['page'])){
								$page=$_GET['page'];
							} else{
								$page=1;
							}
							$start_from=($page-1) * $per_page;
							$ip_add=getUserIP();
							$get_product="select * from products where status='Active' order by 1 DESC LIMIT $start_from,$per_page";
							$run_pro=mysqli_query($con,$get_product);
							while($row=mysqli_fetch_array($run_pro)) {
								$pro_id=$row['product_id'];
								$pro_title=$row['product_title'];
								$m_price=$row['m_price'];
								$pro_price=$row['s_price'];
								$pro_img1=$row['product_img1'];
								$save=$m_price-$pro_price;
								$off1=($save/$m_price)*100;
								$off=round($off1,1);


								$get_cart="select * from cart where p_id='$pro_id' AND ip_add='$ip_add'";
								$run_cart=mysqli_query($con,$get_cart);
								$count=mysqli_num_rows($run_cart);
								if($count>0){
									$row2=mysqli_fetch_array($run_cart);
									$qty=$row2['qty'];

									echo"<div class='col-md-4 col-sm-4 center-responsive'>
									<div class='product'>
									<a href='shop.php?pro_id=$pro_id'>
										<span class='dis_section'> <span>$off<span class='per_txt'>%</span></span> <br/> off </span> 
										<span class='cat-img'> 
											<img src='admin_area/product_images/$pro_img1' class='img-responsive'>
										</span>
									</a>
									<div class='text'>
									<div class='ptitle'><a href='shop.php?pro_id=$pro_id'><b>$pro_title</b></a></div>
									<p class='price'> Price <strike>$m_price </strike> &nbsp; &nbsp; <b><i class='fa fa-rupee'></i> $pro_price</b></p>
									<span class='save_price'><b>You Save &nbsp;<i class='fa fa-rupee'></i> $save</b> </span> 
									<form action='shop.php' method='post' enctype='multipart-form-data'>
										
										<p class='buttons' style='height:44px;'>
										<input type='hidden' name='p_id' value=$pro_id/>
											<button class='btn1' type='submit' name='sub' value=$pro_id ><i class='fa fa-minus-circle fa-2x'></i></button>
											 &nbsp;&nbsp; $qty &nbsp;&nbsp;
											<button class='btn1' type='submit' name='add' value=$pro_id ><i class='fa fa-plus-circle fa-2x'></i></button>
										
										</p>
									</form>
									</div>

									</div>
									</div>
									";


								} else{
									echo"<div class='col-md-4 col-sm-4 center-responsive'>
									<div class='product'>
									<a href='shop.php?pro_id=$pro_id'>
										<span class='dis_section'> <span>$off<span class='per_txt'>%</span></span> <br/> off </span> 
										<span class='cat-img'> 
											<img src='admin_area/product_images/$pro_img1' class='img-responsive'>
										</span>
									</a>
									<div class='text'>
									<div class='ptitle'><a href='shop.php?pro_id=$pro_id'><b>$pro_title</b></a></div>
									<p class='price'> Price <strike>$m_price </strike> &nbsp; &nbsp; <b><i class='fa fa-rupee'></i> $pro_price</b></p>
									<span class='save_price'><b>You Save &nbsp;<i class='fa fa-rupee'></i> $save</b> </span>
									<p class='buttons'>
									<a href='shop.php?pro_id=$pro_id' class='btn btn-primary'><i class='fa fa-shoping-cart'></i> Add to Cart</a>
									</div>

									</div>
									</div>
									";
								}

							}

				?>
			</div><!--- Row End----->
			<center>
				<ul class="pagination">
					<?php 
					$query="select * from products where status='Active'";
					$result=mysqli_query($con,$query);
					$total_record=mysqli_num_rows($result);
					$total_pages=ceil($total_record / $per_page);
					echo"
					<li><a href='shop.php?page=1'>".'First Page'."</a></li>";
					for($i=1; $i<=$total_pages; $i++){
						echo"<li><a href='shop.php?page=".$i."'>".$i."</a></li>";
					};
					echo "
						<li><a href='shop.php?page=$total_pages'>".'Last Page'."</a></li>";
					

						}
					}
					?>
					
				</ul>
			</center>
			</div>
			
				<?php  
				getPcatPro();

				getCatPro();
				?>
		<?php
		function sub_shop(){
			global $con;
			$ip_add=getUserIP();
			if(isset($_POST['sub'])){
				$p_id=$_POST['sub'];
				$p_search="SELECT qty FROM cart WHERE p_id='$p_id' AND ip_add='$ip_add'";
				$run_s_cat=mysqli_query($con,$p_search);
				$row_s_cat=mysqli_fetch_array($run_s_cat);
				$pro_qty1=$row_s_cat['qty'];
				if($pro_qty1>1){
					$qty=$pro_qty1-1;
					$sub_product="update cart set qty='$qty' where p_id='$p_id' AND ip_add='$ip_add'";
					$run_sub=mysqli_query($con,$sub_product);
					if($run_sub) {
						echo "<script> window.open('shop.php','_self')</script>";
					}
				}elseif($pro_qty1=1){
					$del_product="delete from cart where p_id='$p_id' AND ip_add='$ip_add'";
					$del_sub=mysqli_query($con,$del_product);
					if($del_sub) {
						echo "<script> window.open('shop.php','_self')</script>";
					}
				}
				
			}
		}



		echo @$sub_shop=sub_shop();



		function add_shop(){
			global $con;
			$ip_add=getUserIP();
			if(isset($_POST['add'])){
				$p_id=$_POST['add'];
				$p_search="SELECT qty FROM cart WHERE p_id='$p_id' AND ip_add='$ip_add'";
				$run_s_cat=mysqli_query($con,$p_search);
				$row_s_cat=mysqli_fetch_array($run_s_cat);
				$pro_qty1=$row_s_cat['qty'];
				$qty=$pro_qty1+1;
				$add_product="update cart set qty='$qty' where p_id='$p_id' AND ip_add='$ip_add'";
				$run_add=mysqli_query($con,$add_product);
				if($run_add) {
					echo "<script> window.open('shop.php','_self')</script>";
				}
				
			}
		}

		echo @$add_shop=add_shop();
		?>
		
	</div><!--- Container End----->
</div>





<!-------Footer--------->
	<div id="copyright">
		<div class="container">
			<div class="col-md-6">
				<p class="pull-left">
					
				</p>
			</div>
			<div class="col-md-6">
				<p class="pull-right">
					Valo Mudikhana @ 2021 | All Right Reserved 
				</p>
			</div>
		</div>
	</div>


  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</body>

</html>