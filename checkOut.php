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

	if(isset($_SESSION['ccode']) && !empty($_SESSION['ccode'])){
		$ccode = $_SESSION['ccode'];
		$gcode = $ccode;
	}
	else{
		$ccode = 'Guest Account';
		$gcode = '';
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
				<div class="row"> 
					<div class="col-lg-8 col-12">
						<div class="checkout-form">
							<h2>New customer</h2>
							<p>Enter your contact details here</p>
							<!-- Form -->
							<form class="form" id="clientform">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-12">
										<div class="form-group">
											<label>First Name<span>*</span></label>
											<input type="text" id="firstName" placeholder="">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-12">
										<div class="form-group">
											<label>Last Name<span>*</span></label>
											<input type="text" id="lastName" placeholder="">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-12">
										<div class="form-group">
											<label>Email Address<span>*</span></label>
											<input type="email" id="eMail" placeholder="">
										</div>
										<br><hr/><br>
									</div>
									<div class="col-lg-6 col-md-6 col-12">
										<div class="form-group">
											<label>Phone Number<span>*</span></label>
											<input type="tel" id="mobile" pattern="^\+(?:[0-9] ?){6,25}[0-9]$" placeholder="(intl. format)">
										</div>
										<br><hr/><br>
									</div>
									
									<div class="col-lg-6 col-md-6 col-12" id ="add1" style = "display:none">
										<div class="form-group">
											<label>Address Line 1<span>*</span></label>
											<input type="text" id="address1" placeholder="" required="required">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-12" id ="add2" style = "display:none">
										<div class="form-group">
											<label>Address Line 2<span></span></label>
											<input type="text" id="address2" placeholder="">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-12" id ="zip" style = "display:none">
										<div class="form-group">
											<label>Post/Zip Code<span>*</span></label>
											<input type="text" id="postcode" placeholder="" required="required">
										</div>

									</div>
									<div class="col-lg-6 col-md-6 col-12" id ="city" style = "display:none">
										<div class="form-group">
											<label>City<span>*</span></label>
											<input type="text" id="town" placeholder="" required="required">
										</div>

									</div>
									<div class="col-lg-6 col-md-6 col-12" id ="province" style = "display:none">
										<div class="form-group">
											<label>State/Province<span></span></label>
											<input type="text" id="state" placeholder="">
										</div>
										<hr/><br>
									</div>
									<div class="col-lg-6 col-md-6 col-12" id ="country" style = "display:none">
										<div class="form-group">
											<label>Country<span>*</span></label>
											<select name="country_name" id="countries">
												<option value="32">Belgium</option>
												<option value="33">France</option>
												<option value="49">Germany</option>
												<option value="353">Ireland</option>
												<option value="39">Italy</option>
												<option value="31">Netherlands</option>
												<option value="34">Spain</option>
												<option value="41">Switzerland</option>
												<option value="44" selected="selected">United Kingdom</option>
											</select>
										</div>
										<br><hr/><br>
									</div>

									
									<div class="col-lg-6 col-md-6 col-12">

										<div class="form-group">
											<label><p> - OR - enter your customer Number</p></label>
										</div>
									</div>
									
									<div class="col-lg-6 col-md-6 col-12">
										<div class="form-group">
											<input type="number" id="custnum" placeholder="Customer number" value="<?php echo $gcode ?>">
										</div>
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
									<div class="col-lg-6 col-md-6 col-12">
									</div>

								</div>
							</form>
							<!--/ End Form -->
						</div>
					</div>
					<div class="col-lg-4 col-12">
						<div class="order-details">
							<!-- Order Widget -->
							<div class="single-widget">
								<h2>CART  TOTALS</h2>
								<div class="content">
									<ul>
										<li>Sub Total<span id="stotal">0</span></li>
										<li>(+) Shipping<span id = "shipping">0.00</span></li>
										<li class="last">Total €<span id="gtotal">0</span></li>
									</ul>
								</div>
							</div>
							<!--/ End Order Widget -->
							<!-- Order Widget -->
							<div class="single-widget">
								<h2>Delivery </h2>
								<div class="content">
									<div style="padding-left:30px;padding-top:15px">
									    <div class="radio">
										  <label><input type="radio" id = "delstore" value = "delstore" name="delivery" class="delivery" checked > Store Pickup</label>
										</div>
										<div class="radio">
										  <label><input type="radio" id = "delpoint" value = "delpoint" name="delivery" class="delivery"> Dropoff Point</label>
										</div>
										<div class="radio">
										  <label><input type="radio" id = "delhome" value = "delhome" name="delivery" class="delivery"> Home Delivery</label>
										</div>
									</div>									
											
								</div>
							</div>							
							<div class="single-widget">

								<h2>Payment</h2>
								<div class="content">
									<div style="padding-left:30px;padding-top:15px">
									    <div class="radio">
										  <label><input type="radio" id = "paystore" value = "paystore" name="payment" class="payment" checked> Pay in Store</label>
										</div>
										<div class="radio">
										  <label><input type="radio" id = "paypal" value = "paypal" name="payment" class="payment"> Paypal</label>
										</div>
										<div class="radio">
										  <label><input type="radio" id = "paycard" value = "paycard" name="payment" class="payment"> Bank card</label>
										</div>
										
										<div class="col-lg-6 col-md-6 col-12" id = "carddetails" style = "display:none">
											<div class="form-group" id="card-number-field">
												<input type="tel" id="cardnum" maxlength="19" placeholder="Card number" >
												<div id="cardnumber-error-dialog" class="field-error"></div>
											</div>
											<div class="form-group">
												<input type="text" maxlength="5" id="expiry" placeholder="MM/YY" style = "width:70px">
												<input type="text" id="cvv" maxlength="3" placeholder="CVV" style = "width:60px">
												<div id="cardexpiry-error-dialog" class="field-error"></div>
												<div id="cardcvv-error-dialog" class="field-error"></div>
											</div>
									    </div>

										<div class="col-lg-6 col-md-6 col-12" id = "paypaldetails" style = "display:none">
											<div class="form-group" id="card-number-field">
												<input type="email" id="ppaccount" placeholder="Paypal account" >
											</div>
									    </div>
									</div>									
											
								</div>
							</div>	
							<!--/ End Order Widget -->
							<!-- Payment Method Widget -->
							<div class="single-widget payement" id = "cardicon" style = "display:none">
								<div class="content">
									<img src="images/mastercard.jpg" alt="#"><img src="images/visa.jpg" alt="#">
								</div>
							</div>
							<div class="single-widget payement" id = "paypalicon" style = "display:none">
								<div class="content">
									<img src="images/paypal.png" alt="#">
								</div>
							</div>
							
							<!--/ End Payment Method Widget -->
							<!-- Button Widget -->
							<div class="single-widget get-button">
								<div class="content">
									<div class="button">
										<button type="submit" class="btn" id="processor">Finalise payment</button>
									</div>
								</div>
							</div>
							<!--/ End Button Widget -->
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