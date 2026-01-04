<?php 
//$db=mysqli_connect("localhost:3306","wbcarepl","prapti@2022","prapti");
$db=mysqli_connect("localhost","root","","greenland");


/* For getting User IP Address Start*/
function getUserIP(){
	switch (true) {
		case (!empty($_SERVER['HTTP_X_REAL_IP'])): return $_SERVER['HTTP_X_REAL_IP'];
		case (!empty($_SERVER['HTTP_CLIENT_IP'])): return $_SERVER['HTTP_CLIENT_IP'];
		case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])): return $_SERVER['HTTP_X_FORWARDED_FOR'];
			
		
		default: return $_SERVER['REMOTE_ADDR'];
			
	}
}

/* For getting User IP Address End*/

function addCart(){

	global $db;
	if(isset($_GET['add_cart'])){
		$ip_add=getUserIP();
		$p_id=$_GET['add_cart'];
		$product_qty=$_POST['product_qty'];
		$product_size=$_POST['product_size'];
		$check_product="select * from cart where ip_add='$ip_add' AND p_id='$p_id'";
		$run_check=mysqli_query($db,$check_product);
		if(mysqli_num_rows($run_check)>0) {

			echo"<script>alert('This product is already in cart')</script>";
			echo"<script>window.open('details.php?pro_id=$p_id','_self')</script>";
		}else{

			$query="insert into cart(p_id,ip_add,qty,size) values('$p_id','$ip_add','$product_qty','$product_size')";
			$run_query=mysqli_query($db,$query);
			echo "<script>window.open('details.php?pro_id=$p_id','_self')</script>";
		}
	}
}

/*Item Count Stsrt*/
function item(){
	global $db;
	$ip_add=getUserIP();
	$get_items="select * from cart where ip_add='$ip_add'";
	$run_item=mysqli_query($db,$get_items);
	$count=mysqli_num_rows($run_item);
	echo $count;
}

/*Item Count End*/

/*Total Price*/
function totalPrice(){
	global $db;
	$ip_add=getUserIP();
	$total=0;
	$select_cart="select * from cart where ip_add='$ip_add'";
	$run_cart=mysqli_query($db,$select_cart);
	while($record=mysqli_fetch_array($run_cart)){
		$pro_id=$record['p_id'];
		$pro_qty=$record['qty'];
		$get_price="select * from products where product_id='$pro_id'";
		$run_price=mysqli_query($db,$get_price);
		while($row=mysqli_fetch_array($run_price)){
			$sub_total=$row['s_price'] * $pro_qty;
			$total += $sub_total;
		}
	}
	echo "$total";

}


function getPro(){
	global $db;
	$ip_add=getUserIP();
	$get_product="select * from products order by 1 DESC LIMIT 0,8";
	$run_products=mysqli_query($db,$get_product);
	while ($row_product=mysqli_fetch_array($run_products)) {
		$pro_id=$row_product['product_id'];
		$pro_title=$row_product['product_title'];
		$m_price=$row_product['m_price'];
		$pro_price=$row_product['s_price'];
		$pro_img1=$row_product['product_img1'];
		$save=$m_price-$pro_price;
		$off1=($save/$m_price)*100;
		$off=round($off1,1);

		echo "<div class='col-md-3 col-sm-4 center-responsive'>
			<div class='product'>
			<a href='details.php?pro_id=$pro_id'>
			
			<span class='dis_section'> <span>$off<span class='per_txt'>%</span></span> <br/> off </span> 
			<span class='cat-img'> 
			<img src='admin_area/product_images/$pro_img1' class='img-responsive'>
			</span>
			</a>
			<div class='text'>
				<div class='ptitle'>
					<a href='detals.php?pro_id=$pro_id'><b>$pro_title</b></a>
				</div>
			<p class='price'>Price <strike>$m_price </strike> &nbsp; &nbsp; <b><i class='fa fa-rupee'></i> $pro_price </b></p>
			<span class='save_price'><b>You Save &nbsp;<i class='fa fa-rupee'></i> $save</b> </span>
			
			<p class='buttons'>
			<a href='index.php?pro_id=$pro_id' class='btn btn-primary'><i class='fa fa-shopping-cart'></i>Add to Cart</a>
			</p>

			</div>

			</div>

		</div>

		";
	}
}

/*Product Categories*/
function getPCats(){
	global $db;
	$get_p_cats="select * from product_category order by p_cat_title";
	$run_p_cats=mysqli_query($db,$get_p_cats);
	while ($row_p_cats=mysqli_fetch_array($run_p_cats)) {
		$p_cat_id=$row_p_cats['p_cat_id'];
		$p_cat_title=$row_p_cats['p_cat_title'];
		echo "<li><a href='shop.php?p_cat=$p_cat_id'>$p_cat_title</a></li>";
	}
}


/* Categories */
function getCat(){
	global $db;
	$get_cat="select * from categories";
	$run_cat=mysqli_query($db,$get_cat);
	while ($row_cat=mysqli_fetch_array($run_cat)) {
		$cat_id=$row_cat['cat_id'];
		$cat_title=$row_cat['cat_title'];
		echo "<li><a href='shop.php?cat_id=$cat_id'>$cat_title</a></li>";
	}
}


/*Get Product Categories*/
function getPcatPro(){
	global $db;
	if(isset($_GET['p_cat'])){
		$p_cat_id=$_GET['p_cat'];
		$get_p_cat="select * from product_category where p_cat_id='$p_cat_id'";
		$run_p_cat=mysqli_query($db,$get_p_cat);
		$row_p_cat=mysqli_fetch_array($run_p_cat);
		$p_cat_title=$row_p_cat['p_cat_title'];
		$p_cat_desc=$row_p_cat['p_cat_desc'];

		$get_products="select * from products where p_cat_id='$p_cat_id' and status='Active'";
		$run_products=mysqli_query($db,$get_products);
		$count=mysqli_num_rows($run_products);
		if($count==0){
			echo"
			<div class='box1'>
			<h3>No Product Found in this product category</h3>
			</div>

			";
		}else{
			echo"<div class='box1'>
			<h3>$p_cat_title</h3>
			</div>
			";
		}
		echo "<div class='row'>";
		while ($row_products=mysqli_fetch_array($run_products)) {
			$pro_id=$row_products['product_id'];
			$pro_title=$row_products['product_title'];
			$m_price=$row_products['m_price'];
			$pro_price=$row_products['s_price'];
			$pro_img1=$row_products['product_img1'];
			$save=$m_price-$pro_price;
			$off1=($save/$m_price)*100;
			$off=round($off1,1);

			$ip_add=getUserIP();
			$get_cart="select * from cart where p_id='$pro_id' AND ip_add='$ip_add'";
			$run_cart=mysqli_query($db,$get_cart);
			$count=mysqli_num_rows($run_cart);
			if($count>0){
				$row2=mysqli_fetch_array($run_cart);
				$qty=$row2['qty'];

				echo"<div class='col-md-4 col-sm-4 center-responsive'>
				<div class='product'>
				<a href='shop.php?p_cat=$p_cat_id'>
					<span class='dis_section'> <span>$off<span class='per_txt'>%</span></span> <br/> off </span> 
					<span class='cat-img'> 
						<img src='admin_area/product_images/$pro_img1' class='img-responsive'>
					</span>
				</a>
				<div class='text'>
				<div class='ptitle'><a href='shop.php?p_cat=$p_cat_id'><b>$pro_title</b></a></div>
				<p class='price'> Price <strike>$m_price </strike> &nbsp; &nbsp; <b><i class='fa fa-rupee'></i> $pro_price</b></p>
				<span class='save_price'><b>You Save &nbsp;<i class='fa fa-rupee'></i> $save</b> </span> 
				<form action='shop.php' method='post' enctype='multipart-form-data'>
					
				<p class='buttons' style='height:44px;'>
					<input type='hidden' name='p_id' value=$pro_id/>
						<button class='btn1' type='submit' name='sub_p' value=$pro_id ><i class='fa fa-minus-circle fa-2x'></i></button>
						 &nbsp;&nbsp; $qty &nbsp;&nbsp;
						<button class='btn1' type='submit' name='add_p' value=$pro_id ><i class='fa fa-plus-circle fa-2x'></i></button>
				</p>
					
				</form>
				</div>

				</div>
				</div>
				";


			} else{
				echo"<div class='col-md-4 col-sm-4 center-responsive'>
				<div class='product'>
				<a href='shop.php?p_cat=$p_cat_id'>
					<span class='dis_section'> <span>$off<span class='per_txt'>%</span></span> <br/> off </span> 
					<span class='cat-img'> 
						<img src='admin_area/product_images/$pro_img1' class='img-responsive'>
					</span>
				</a>
				<div class='text'>
				<div class='ptitle'><a href='shop.php?p_cat=$p_cat_id'><b>$pro_title</b></a></div>
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
		echo "</div>";
	}

	
	function sub_shop_p(){
		global $con;
		$ip_add=getUserIP();
		if(isset($_POST['sub_p'])){
			$p_id=$_POST['sub_p'];

			$get_cat="select * from products where product_id='$p_id'";
			$run_cat=mysqli_query($con,$get_cat);
			$row_cat=mysqli_fetch_array($run_cat);
			$p_cat_id=$row_cat['p_cat_id'];

			$p_search="SELECT qty FROM cart WHERE p_id='$p_id' AND ip_add='$ip_add'";
			$run_s_cat=mysqli_query($con,$p_search);
			$row_s_cat=mysqli_fetch_array($run_s_cat);
			$pro_qty1=$row_s_cat['qty'];
			if($pro_qty1>1){
				$qty=$pro_qty1-1;
				$sub_product="update cart set qty='$qty' where p_id='$p_id' AND ip_add='$ip_add'";
				$run_sub=mysqli_query($con,$sub_product);
				if($run_sub) {
					echo "<script> window.open('shop.php?p_cat=$p_cat_id','_self')</script>";
				}
			}elseif($pro_qty1=1){
				$del_product="delete from cart where p_id='$p_id' AND ip_add='$ip_add'";
				$del_sub=mysqli_query($con,$del_product);
				if($del_sub) {
					echo "<script> window.open('shop.php?p_cat=$p_cat_id','_self')</script>";
				}
			}
			
		}
	}



	echo @$sub_shop_p=sub_shop_p();



	function add_shop_p(){
		global $con;
		$ip_add=getUserIP();
		if(isset($_POST['add_p'])){
			$p_id=$_POST['add_p'];

			$get_cat1="select * from products where product_id='$p_id'";
			$run_cat1=mysqli_query($con,$get_cat1);
			$row_cat1=mysqli_fetch_array($run_cat1);
			$p_cat_id=$row_cat1['p_cat_id'];

			$p_search="SELECT qty FROM cart WHERE p_id='$p_id' AND ip_add='$ip_add'";
			$run_s_cat=mysqli_query($con,$p_search);
			$row_s_cat=mysqli_fetch_array($run_s_cat);
			$pro_qty1=$row_s_cat['qty'];
			$qty=$pro_qty1+1;
			$add_product="update cart set qty='$qty' where p_id='$p_id' AND ip_add='$ip_add'";
			$run_add=mysqli_query($con,$add_product);
			if($run_add) {
				echo "<script> window.open('shop.php?p_cat=$p_cat_id','_self')</script>";
			}
			
		}
	}

	echo @$add_shop_p=add_shop_p();
		
		
}


/*Get Product Categories*/
function getCatPro(){
	global $db;
	if(isset($_GET['cat_id'])){
		$cat_id=$_GET['cat_id'];
		$get_cat="select * from categories where cat_id='$cat_id'";
		$run_cat=mysqli_query($db,$get_cat);
		$row_cat=mysqli_fetch_array($run_cat);
		$cat_title=$row_cat['cat_title'];
		$cat_desc=$row_cat['cat_desc'];

		$get_products="select * from products where cat_id='$cat_id'";
		$run_products=mysqli_query($db,$get_products);
		$count=mysqli_num_rows($run_products);
		if($count==0){
			echo"
			<div class='box1'>
			<h3>No Product Found in this category</h3>
			</div>

			";
		}else{
			echo"<div class='box1'>
			<h3>$cat_title</h3>
			</div>
			";
		}
		echo "<div class='row'>";
		while ($row_products=mysqli_fetch_array($run_products)) {
			$pro_id=$row_products['product_id'];
			$pro_title=$row_products['product_title'];
			$pro_price=$row_products['product_price'];
			$pro_img1=$row_products['product_img1'];

			echo"
			
			<div class='col-md-3 col-sm-6 center-responsive'>
			<div class='product'>
			<a href='details.php?pro_id=$pro_id'>
			<span class='dis_section'> <span>$off<span class='per_txt'>%</span></span> <br/> off </span> 
			<span class='cat-img'> 
				<img src='admin_area/product_images/$pro_img1' class='img-responsive'>
			</span>
			</a>
			<div class='text'>
			<div class='ptitle'><a href='details.php?pro_id=$pro_id'><b>$pro_title</b></a></div>
			<p class='price'> Price <strike>$pro_price </strike> &nbsp; &nbsp; <b><i class='fa fa-rupee'></i>$pro_price</b></p>
			<span class='save_price'><b>You Save &nbsp;<i class='fa fa-rupee'></i> $pro_price</b> </span>
			<p class='buttons'>
			<a href='details.php?pro_id=$pro_id' class='btn btn-primary'><i class='fa fa-shopping-cart'></i>Add to Cart</a>
			</div>
			</div>
			</div>
			";

		}
		echo "</div>";
	}
		
}
?>