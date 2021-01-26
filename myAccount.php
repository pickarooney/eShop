<!DOCTYPE html>

 <?php  
 
	include 'shopFunctions.php';
	
	if(!isset($_SESSION)) {
		session_start();
	}
	
	$serverName = $_SESSION['serverName'];	
	
	$ccode = ''; $fname = '';$lname='';$email='';$mobile='';$add1='';$add2='';$postcode='';$town='';$state='';$country='';$countrycode='';
	//$serverName = '(local)';
	//$serverName = 'tcp:pickarooney.sytes.net,33433';
	//$serverName="tcp:170.253.55.50,1433";   //carlos

	$logFile = 'c:\temp\phplog.txt';

	if(isset($_GET['data']) && !empty($_GET['data'])){
		$_SESSION['ccode'] = $_GET['data'];
		$ccode = $_GET['data'];
	}
	else if (isset($_SESSION['ccode']) && !empty($_SESSION['ccode'])){
		$ccode = $_SESSION['ccode'];
	}

	if (!isset($_POST['login']) && !isset($_SESSION['ccode'])){
		file_put_contents($logFile, 'Please enter an account number.'.  PHP_EOL, FILE_APPEND);	
				file_put_contents($logFile, 'Line 22.'.$ccode.  PHP_EOL, FILE_APPEND);
		echo 'Please enter an account number.';

	}

	if(isset($_POST['login']) && !empty($_POST['login'])){
		$ccode = $_POST['login'];
		file_put_contents($logFile, 'Line 28.'.  PHP_EOL, FILE_APPEND);
	}

	if ($ccode == '') {
		file_put_contents($logFile, 'Please enter a valid account number.'.  PHP_EOL, FILE_APPEND);
		file_put_contents($logFile, 'Line 34.'.$ccode.  PHP_EOL, FILE_APPEND);
		echo 'Please enter a valid account number format.';
		header("Location: eShop.php");
		die();
	}

	$conn = createConn($serverName);

	$clientDetails = lookupClient($serverName,$ccode);

	if (empty($clientDetails)){ //
		file_put_contents($logFile, 'Please enter a valid client account number.'.  PHP_EOL, FILE_APPEND);
		echo 'Please enter a valid client account number.';
		header("Location: eShop.php");
		die();
	}
	else {
		$cid = $clientDetails[0];$fname = $clientDetails[1];$lname = $clientDetails[2];$mobile = $clientDetails[3];$email = $clientDetails[4];$address=$clientDetails[5];$postcode=$clientDetails[6];$town=$clientDetails[7];$countrycode=$clientDetails[8];

		if(strpos($address, ',') !== false){
			$addresses = explode(",", $address);
			$add1 = trim($addresses[0]); // piece1
			$add2 = trim($addresses[1]); // piece2
		}
		else {
			$add1 = $address;
			$add2 = '';
		}

		$_SESSION['ccode'] = $ccode;

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
    <title>Eshop - Checkout</title>
	<!-- Favicon -->
	<link rel="icon" type="image/png" href="images/favicon.png">
	<!-- Web Font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
	
	<!-- StyleSheet -->
	
	<!-- Bootstrap -->
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
	<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">-->
	<link rel="stylesheet" href="css/bootstrap_eshop.css">
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
	<!-- Number input -->



	
	<!-- Eshop StyleSheet -->
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/ad0d2759d9.js" crossorigin="anonymous"></script>
	<script src="js/jquery.payform.min.js" charset="utf-8"></script>
	
	<script>
		window.addEventListener("beforeunload", function(event) {
		  sessionStorage.setItem('cartStore', JSON.stringify(cartList));
		  sessionStorage.setItem('cartTotal',$("#grandtotal").text());
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
				cartTotal =  sessionStorage.getItem('cartTotal');
				cartTotal = parseFloat(cartTotal).toFixed(2);
				$(".total-count").html(cartList.length);
				$("#stotal").text(cartTotal);
				$("#gtotal").text(cartTotal);
			}
		
			


		});

	</script>
	
	
	<style>
		input[type="radio"] {  
		  margin-right: 10px;
		  margin-left: 10px;
		}
	</style>
	
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
						<div class="col-lg-8 col-md-12 col-12">
							<!-- Top Left
							<div class="top-left">
								<ul class="list-main">
									<li><i class="ti-headphone-alt"></i> +33 415 4845</li>
									<li><i class="ti-email"></i> support@onlineshampoo.com</li>
								</ul>
							</div>
							End Top Left -->
						</div>
						<div class="col-lg-8 col-md-12 col-12">
							<!-- Top Right -->
							<div class="right-content">
								<ul class="list-main">
									<li><i class="ti-user"></i><span id ="loggedIn" > <?php echo $ccode ?></span></li>
								</ul>
							</div>
							<!-- End Top Right -->
						</div>
					</div>
				</div>
			</div>
			<!-- End Topbar -->
			
			<!-- Header Inner -->
			<div class="header-inner">
				<div class="container">
					<div class="cat-nav-head">
						<div class="row">
							<div class="col-12">
								<div class="menu-area">
									<!-- Main Menu -->
									<nav class="navbar navbar-expand-lg">
										<div class="navbar-collapse">	
											<div class="nav-inner">	
												<ul class="nav main-menu menu navbar-nav">
													<li class="active"><a href="eShop.php">Continue Shopping</a></li>												
													<li><a href="contact.html">Contact Us</a></li>
												</ul>
											</div>
										</div>
									</nav>
									<!--/ End Main Menu -->	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/ End Header Inner -->
			
			<div id="notification" style = "text-align:center; padding: 10px; display: none; color:white; background-color:orange;">Empty Messsage.</div>
			
			
			<div class="middle-inner">
				<div class="container">
					<div class="row">
						<div class="col-lg-2 col-md-2 col-12">
							<!-- Logo -->
							<div class="logo">
								<a href="index.html"><img src="images/logo.png" alt="logo"></a>
							</div>
							<!--/ End Logo -->
							<!-- Search Form -->

							<!--/ End Search Form -->
							<div class="mobile-nav"></div>
						</div>
						<div class="col-lg-8 col-md-7 col-12">
							<div class="search-bar-top">
								<div>

								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-3 col-12">
							<div class="right-bar">
								<!-- Search Form -->
								<div class="sinlge-bar">

								</div>
								<div class="sinlge-bar">

								</div>
								<div class="sinlge-bar shopping">
									<a href="#" class="single-icon"><i class="fas fa-shopping-cart fa-2x" id = "cocart"></i> <span class="total-count">0</span></a>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</header>
		<!--/ End Header -->
	


				
		<!-- Start Checkout -->
		<section class="shop checkout section">
			<div class="container" id ="checkoutForm">


				<!-- Nav pills -->
				<ul class="nav nav-pills">
				  <li class="nav-item">
					<a class="nav-link active" data-toggle="pill" href="#Account" id = "accounttab">Account</a>
				  </li>
				  <li class="nav-item">
					<a class="nav-link" data-toggle="pill" href="#Orders" id = "ordertab">Orders</a>
				  </li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane container active" id="Account">
						<div class="row"> 
							<div class="col-lg-8 col-12">
								<div class="checkout-form">

									<!-- Form -->
									<form class="form" id="clientform">
										<div class="col-lg-6 col-md-6 col-12">

										</div>
										<div class="row">
											<div class="col-lg-6 col-md-6 col-12">
												<div class="form-group">
													<label>First Name<span>*</span></label>
													<input type="text" id="firstName" placeholder="" required="required" value= "<?php echo $fname ?>">
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-12">
												<div class="form-group">
													<label>Last Name<span>*</span></label>
													<input type="text" id="lastName" placeholder="" required="required" value= "<?php echo $lname ?>">
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-12">
												<div class="form-group">
													<label>Email Address<span>*</span></label>
													<input type="email" id="eMail" placeholder="" required="required" value= "<?php echo $email ?>">
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-12">
												<div class="form-group">
													<label>Phone Number<span>*</span></label>
													<input type="tel" id="mobile" pattern="^\+(?:[0-9] ?){6,25}[0-9]$" placeholder="(intl. format)" required="required" value= "<?php echo $mobile ?>">
												</div>
											</div>
											
											<div class="col-lg-6 col-md-6 col-12" id ="add1">
												<div class="form-group">
													<label>Address Line 1<span></span></label>
													<input type="text" id="address1" placeholder="" value= "<?php echo $add1 ?>">
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-12" id ="add2">
												<div class="form-group">
													<label>Address Line 2<span></span></label>
													<input type="text" id="address2" placeholder="" value= "<?php echo $add2 ?>">
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-12" id ="zip">
												<div class="form-group">
													<label>Post/Zip Code<span></span></label>
													<input type="text" id="postcode" placeholder="" value= "<?php echo $postcode ?>">
												</div>

											</div>
											<div class="col-lg-6 col-md-6 col-12" id ="city">
												<div class="form-group">
													<label>City<span></span></label>
													<input type="text" id="town" placeholder="" value= "<?php echo $town ?>">
												</div>

											</div>
											<div class="col-lg-6 col-md-6 col-12" id ="province">
												<div class="form-group">
													<label>State/Province<span></span></label>
													<input type="text" id="state" placeholder="">
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-12" id ="country">
												<div class="form-group">
													<label>Country<span>*</span></label>
													<select name="country_name" id="countries">
														<option value="32" <?php if ($countrycode == '32') echo ' selected="selected"'; ?>>Belgium</option>
														<option value="33" <?php if ($countrycode == '33') echo ' selected="selected"'; ?>>France</option>
														<option value="49" <?php if ($countrycode == '49') echo ' selected="selected"'; ?>>Germany</option>
														<option value="353" <?php if ($countrycode == '353') echo ' selected="selected"'; ?>>Ireland</option>
														<option value="39" <?php if ($countrycode == '39') echo ' selected="selected"'; ?>>Italy</option>
														<option value="31" <?php if ($countrycode == '31') echo ' selected="selected"'; ?>>Netherlands</option>
														<option value="34" <?php if ($countrycode == '34') echo ' selected="selected"'; ?>>Spain</option>
														<option value="41" <?php if ($countrycode == '41') echo ' selected="selected"'; ?>>Switzerland</option>
														<option value="44" <?php if ($countrycode == '44') echo ' selected="selected"'; ?>>United Kingdom</option>
													</select>
												</div>
											</div>
											<button type="submit" class="btn" id="updateClient">Update details</button>
											<div class="col-lg-6 col-md-6 col-12">

											</div>
											<div class="col-lg-6 col-md-6 col-12">

											</div>
											<div class="col-lg-6 col-md-6 col-12">
											</div>
											<div class="col-lg-6 col-md-6 col-12">
											</div>
											<div class="col-lg-6 col-md-6 col-12">
											</div>
											<div class="col-lg-6 col-md-6 col-12">
											</div>

										</div>
									</form>
									<!--/ End Form -->
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane container fade" id="Orders">
						<div class="row"> 

								

									<div class="container" id="cartheader">
									  <div class="row" style="font-weight:bold;align-items-center;height:50px; padding-top:20px">
										<div class="col-md-1"></div>
										<div class="col-md-3">Product Description</div>
										<div class="col-md-1">U.P.</div>
										<div class="col-md-1">Qty</div>
										<div class="col-md-1">Price</div>
										<div class="col-md-2">Order</div>
										<div class="col-md-2">Date</div>
										<div class="col-md-1"></div>
									  </div>
									</div>
									<div class="container" id="historydetails">
									</div>
								

						</div>
					</div>
				</div>
			</div>
		</section>
		<!--/ End Checkout -->
		
		<!-- Start Shop Services Area  -->
		<section class="shop-services section home">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Service -->
						<div class="single-service">
							<i class="ti-rocket"></i>
							<h4>Free shiping</h4>
							<p>Orders over $100</p>
						</div>
						<!-- End Single Service -->
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Service -->
						<div class="single-service">
							<i class="ti-reload"></i>
							<h4>Free Return</h4>
							<p>Within 30 days returns</p>
						</div>
						<!-- End Single Service -->
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Service -->
						<div class="single-service">
							<i class="ti-lock"></i>
							<h4>SECURE Payment</h4>
							<p>100% secure payment</p>
						</div>
						<!-- End Single Service -->
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Service -->
						<div class="single-service">
							<i class="ti-tag"></i>
							<h4>Best PRICE</h4>
							<p>Guaranteed price</p>
						</div>
						<!-- End Single Service -->
					</div>
				</div>
			</div>
		</section>
		<!-- End Shop Services -->
		
		<!-- Start Shop Newsletter  -->
		<section class="shop-newsletter section">
			<div class="container">
				<div class="inner-top">
					<div class="row">
						<div class="col-lg-8 offset-lg-2 col-12">
							<!-- Start Newsletter Inner -->

							<!-- End Newsletter Inner -->
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Shop Newsletter -->
		
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
		</div
			
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
									<li><a href="#">Money-back</a></li>
									<li><a href="#">Returns</a></li>
									<li><a href="#">Shipping</a></li>
									<li><a href="#">Privacy Policy</a></li>
								</ul>
							</div>
							<!-- End Single Widget -->
						</div>
						<div class="col-lg-3 col-md-6 col-12">
							<!-- Single Widget -->
							<div class="single-footer social">
								<h4>Get In Tuch</h4>
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
	<!-- Color JS -->
	<script src="js/colors.js"></script>
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
	<!-- Notify -->
	<script src="js/notify.min.js"></script>
	<!-- Rich shop function -->
	<script src="js/rich-shop.js"></script>
	<!-- card check function -->
	<script src="js/card-check.js"></script>


	
</body>
</html>