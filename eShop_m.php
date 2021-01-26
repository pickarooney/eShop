<!DOCTYPE html>

 <?php  
 
	include 'shopFunctions.php';

	if(!isset($_SESSION)) {
		session_start();
	}
		
	$logFile = 'c:\temp\phplog.txt';
	
	//$serverName = '(local)';
	$serverName = 'tcp:pickarooney.sytes.net,33433';
	//$serverName="tcp:170.253.55.50,1433";   //carlos

	$_SESSION['serverName'] = $serverName;	

	$productList = createProductList($serverName);
	$brandList = createBrandList($serverName);
	$categoryList = createCategoryList($serverName);

	if (isset($_SESSION['ccode'])){
		$ccode = $_SESSION['ccode'];
	}
	else {
		$ccode = 'Guest Account';
	}
	
	
?>

<html>
<head>
	<!-- Meta Tag -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name='copyright' content=''>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Title Tag  -->
    <title>Eshop mobile page</title>
	<!-- Favicon -->
	<link rel="icon" type="image/png" href="images/favicon.png">
	<!-- Web Font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
	
	<!-- StyleSheet -->
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Magnific Popup -->
    <link rel="stylesheet" href="css/magnific-popup.min.css">
	<!-- Fancybox -->
	<link rel="stylesheet" href="css/jquery.fancybox.min.css">
	<!-- Themify Icons -->
    <link rel="stylesheet" href="css/themify-icons.css">
	<!-- Jquery Ui -->
    <link rel="stylesheet" href="css/jquery-ui.css">
	<!-- Nice Select CSS -->
    <link rel="stylesheet" href="css/niceselect.css">
	<!-- Animate CSS -->
    <link rel="stylesheet" href="css/animate.css">
	<!-- Flex Slider CSS -->
    <link rel="stylesheet" href="css/flex-slider.min.css">
	<!-- Owl Carousel -->
    <link rel="stylesheet" href="css/owl-carousel.css">
	<!-- Slicknav -->
    <link rel="stylesheet" href="css/slicknav.min.css">
	<!-- Spinners -->
	<link rel="stylesheet" href="css/jquery.nice-number.css">

	
	<!-- Eshop StyleSheet -->
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="style_m.css">
    <link rel="stylesheet" href="css/responsive.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/ad0d2759d9.js" crossorigin="anonymous"></script>

	
	<script>
		window.addEventListener("beforeunload", function(event) {
		  sessionStorage.setItem('cartStore', JSON.stringify(cartList));
		  sessionStorage.setItem('cartTotal',cartTotal);
		});
	</script>

	<script>
		$(document).ready(function() { 

			//retrieve cart on reload
			if(sessionStorage.getItem('cartStore') == null){
				cartList =[];
				$(".total-count").html(0);
			}else{
				cartList =  JSON.parse(sessionStorage.getItem('cartStore'));
				$(".total-count").html(cartList.length);
				//loop to change add to remove on all products
				for (i=0;i<cartList.length;i++){
					$('#scart'+cartList[i][0]).hide();
					$('#suncart'+cartList[i][0]).show();
				}
				
			}


		});
		
		$(function(){
			$('input[type="number"]').niceNumber({
			  autoSize:true,
			  autoSizeBuffer: 1,
			  buttonDecrement:'-',
			  buttonIncrement:"+",
			  buttonPosition:'around'
			});
		});
		
	</script>
	
	
</head>
<body class="js">
	
	<!-- Preloader -->
	<div class="preloader">
		<div class="preloader-inner">
			<div class="preloader-icon">
				<span></span>
				<span></span>
			</div>
		</div>
	</div>
	<!-- End Preloader -->
		
		<!-- Header -->
		<header class="header shop">
			<!-- Topbar -->
			<div class="topbar">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-4 col-12">

							<!-- Logo -->
							<div class="logo">
								<a href="eShop_m.php"><img src="images/logo.png" alt="logo"></a>
							</div>
							<!--/ End Logo -->
												
						
							<!-- Top Right -->
							<div class="right-content shopping">
								<ul class="list-main">
									<li><i class="fas fa-user" style = "padding-left:20px"></i><span><?php echo $ccode ?></span></li>
								</ul>
								<div class="shopping-item">
											<input name="login" id="loginfield" placeholder="Enter your account number" required="" type="text" style="width:100%">
								</div>

							</div>
							

							
							<!-- End Top Right -->
						</div>
					</div>
				</div>
			</div>
			<!-- End Topbar -->
			
		
			<div class="middle-inner">
				<div class="container">
					<div class="row">
						<div style = "padding:none">

							<!-- Search Form -->
								<select id = "catsearch" style="width:33%">
									<option selected="selected">All Items</option>
										<?php
										 foreach ($categoryList as $item) {
											echo "<option>".$item[1]."</option>";
										 }	
										?>							
								</select>

								<input name="searchproduct" id = "searchproduct" placeholder="Search..." type="search" style="width:33%">
							<!--/ End Search Form -->

						</div>

						<div class="col-lg-2 col-md-3 col-12">
							<div class="right-bar">
								<!-- Search Form -->
								<div class="sinlge-bar">
									<a href="#" class="single-icon"><i class="fa fa-heart-o" aria-hidden="true" id ="username"></i></a>
								</div>
								<div class="sinlge-bar shopping">
									<a href="#" class="single-icon"><i class="fa fa-user-circle-o" aria-hidden="true" id="login"></i></a>

								</div>

								<div class="sinlge-bar">
									<a href="#" class="single-icon"><i class="fas fa-shopping-cart fa-2x"></i> <span class="total-count">0</span></a>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
		</header>
		<!--/ End Header -->
		
		<!-- Product Style -->
		<section class="product-area shop-sidebar shop section" style = "padding : 0px;">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-4 col-12">
						<div class="shop-sidebar">
								<!-- Single Widget -->
								<div class="single-widget category">
									<h3 class="title">Categories</h3>
										<ul class="category-list">
											<li class = "cat" id="cat0">ALL</a></li>
											<?php
												foreach ($categoryList as $item) {
												   $text = '<li class = "cat" id="cat'.$item[0].'">'.$item[1].'</a></li>';
												   echo("$text\n");
												}
											?>
										</ul>
								</div>
								<!--/ End Single Widget -->
								<!-- Single Widget -->
								<div class="single-widget category">
									<h3 class="title">Brands</h3>
										<ul class="brand-list">
											<li class = "brand" id="brand0">ALL</a></li>
											<?php
												foreach ($brandList as $item) {
												   //$text = '<li><a href="#">'.$item[1].'</a></li>';
												   $text = '<li class = "brand" id="brand'.$item[0].'">'.$item[1].'</a></li>';
												   echo("$text\n");
												}
											?>
										</ul>
								</div>
								<!--/ End Single Widget -->
						</div>
					</div>
					<div class="col-lg-9 col-md-8 col-12">
						<div class="row">
							<div class="col-12">
								<!-- Shop Top -->
								<div class="shop-top">
									<div class="shop-shorter">
										<div class="single-shorter">
										    <label id = "sortby">Sort By :</label>
											<select name="sortorder" id="sortorder" style="display: none;">
												<option selected="selected">Name</option>
												<option>Price (Low to High)</option>
												<option>Price (High to Low)</option>
											</select>

										</div>
									</div>

								</div>
								<!--/ End Shop Top -->
							</div>
						</div>
						<div id="product-grid" class="row">
				
							<!-- add from list -->
							<?php
							
							$displayHTML = generateProductDisplay($serverName,$productList,'');	
							echo $displayHTML;							

							?>
							<!-- add from list -->

						</div>
					</div>
				</div>
			</div>
		</section>
		<!--/ End Product Style 1  -->	

		<!-- Start Shop Newsletter  -->
		<section class="shop-newsletter section">
			<div class="container">
				<div class="inner-top">
					<div class="row">
						<div class="col-lg-8 offset-lg-2 col-12">
							<!-- Start Newsletter Inner -->

						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Shop Newsletter -->
		
		
		
		<!-- Product Modal -->
			<div class="modal fade" id="productmodal" tabindex="-1" role="dialog"> </div>
		
		<!-- Cart Modal -->
			<div class="modal fade" id ="cartmodal" tabindex="-1" role="dialog"> 
			 <form action="checkOut.php" method="POST">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
						</div>
						<div class="container" id="cartheader">
						  <div class="row" style="font-weight:bold;align-items-center;height:50px; padding-top:20px">
							<div class="col-md-2"></div>
							<div class="col-md-5">Product Description</div>
							<div class="col-md-1">U.P.</div>
							<div class="col-md-1">Qty</div>
							<div class="col-md-1">Price</div>
							<div class="col-md-1">Remove</div>
						  </div>
						</div>
						<div class="container" id="cartdetails">
						</div>
					</div>
				</div>			
			 </form>
			</div>
			
			
		
		<!-- Start Footer Area -->
		<footer class="footer">
			<!-- Footer Top -->
			<div class="footer-top section">
				<div class="container">
					<div class="row">
						<div class="col-lg-5 col-md-6 col-12">
							<!-- Single Widget -->
							<div class="single-footer about">
								<div class="logo">
									<a href="index.html"><img src="images/logo2.png" alt="#"></a>
								</div>
								<p class="text">Praesent dapibus, neque id cursus ucibus, tortor neque egestas augue,  magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>
								<p class="call">Got Question? Call us 24/7<span><a href="tel:123456789">+0123 456 789</a></span></p>
							</div>
							<!-- End Single Widget -->
						</div>
						<div class="col-lg-2 col-md-6 col-12">
							<!-- Single Widget -->
							<div class="single-footer links">
								<h4>Information</h4>
								<ul>
									<li><a href="#">About Us</a></li>
									<li><a href="#">Faq</a></li>
									<li><a href="#">Terms & Conditions</a></li>
									<li><a href="#">Contact Us</a></li>
									<li><a href="#">Help</a></li>
								</ul>
							</div>
							<!-- End Single Widget -->
						</div>
						<div class="col-lg-2 col-md-6 col-12">
							<!-- Single Widget -->
							<div class="single-footer links">
								<h4>Customer Service</h4>
								<ul>
									<li><a href="#">Payment Methods</a></li>
									<li><a href="#">Returns</a></li>
									<li><a href="#">Shipping</a></li>
								</ul>
							</div>
							<!-- End Single Widget -->
						</div>
						<div class="col-lg-3 col-md-6 col-12">
							<!-- Single Widget -->
							<div class="single-footer social">
								<h4>Get In Touch</h4>
								<!-- Single Widget -->
								<div class="contact">
									<ul>
										<li>NO. 342 - London Oxford Street.</li>
										<li>012 United Kingdom.</li>
										<li>info@eshop.com</li>
										<li>+032 3456 7890</li>
									</ul>
								</div>
								<!-- End Single Widget -->
								<ul>
									<li><a href="#"><i class="ti-facebook"></i></a></li>
									<li><a href="#"><i class="ti-twitter"></i></a></li>
									<li><a href="#"><i class="ti-flickr"></i></a></li>
									<li><a href="#"><i class="ti-instagram"></i></a></li>
								</ul>
							</div>
							<!-- End Single Widget -->
						</div>
					</div>
				</div>
			</div>
			<!-- End Footer Top -->
			<div class="copyright">
				<div class="container">
					<div class="inner">
						<div class="row">
							<div class="col-lg-6 col-12">
								<div class="left">
									<p>Copyright © 2020 <a href="http://www.wpthemesgrid.com" target="_blank">Wpthemesgrid</a>  -  All Rights Reserved.</p>
								</div>
							</div>
							<div class="col-lg-6 col-12">
								<div class="right">
									<img src="images/payments.png" alt="#">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!-- /End Footer Area -->
	
	
    <!-- Jquery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.0.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<!-- Popper JS -->
	<script src="js/popper.min.js"></script>
	<!-- Bootstrap JS -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Slicknav JS -->
	<script src="js/slicknav.min.js"></script>
	<!-- Owl Carousel JS -->
	<script src="js/owl-carousel.js"></script>
	<!-- Magnific Popup JS -->
	<script src="js/magnific-popup.js"></script>
	<!-- Fancybox JS -->
	<script src="js/facnybox.min.js"></script>
	<!-- Waypoints JS -->
	<script src="js/waypoints.min.js"></script>
	<!-- Countdown JS -->
	<script src="js/finalcountdown.min.js"></script>
	<!-- Nice Select JS -->
	<script src="js/nicesellect.js"></script>
	<!-- Ytplayer JS -->
	<script src="js/ytplayer.min.js"></script>
	<!-- Flex Slider JS -->
	<script src="js/flex-slider.js"></script>
	<!-- ScrollUp JS -->
	<script src="js/scrollup.js"></script>
	<!-- Onepage Nav JS -->
	<script src="js/onepage-nav.min.js"></script>
	<!-- Easing JS -->
	<script src="js/easing.js"></script>
	<!-- Active JS -->
	<script src="js/active.js"></script>
	<!-- Spinners -->
	<script src="js/jquery.nice-number.js"></script>
	<!-- Notify -->
	<script src="js/notify.min.js"></script>

	<script src="js/rich-shop.js"></script>
	
</body>
</html>