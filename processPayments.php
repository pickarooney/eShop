 <?php  

	include 'shopFunctions.php';
	include 'mail/vendor/autoload.php';

	if(!isset($_SESSION)) {
		session_start();
	}
		
		
	//initialise
	$serverName = $_SESSION['serverName'];
	$ccode = ''; $fname = '';$lname='';$email='';$mobile='';$add1='';$add2='';$postcode='';$town='';$state='';$country='';$countrycode='';
	
	$logFile = 'c:\temp\phplog.txt';

	if(isset($_SESSION['ccode']) && !empty($_SESSION['ccode'])){
		$ccode = $_SESSION['ccode'];
	}


	// connect to server	
	//$serverName = 'tcp:pickarooney.sytes.net,33433';
	//$serverName="tcp:170.253.55.50,1433";   //carlos
	$conn = createConn($serverName);  
	

	
	if ($_POST['ccode'] != null){
		$ccode = $_POST['ccode'];
		$_SESSION['ccode'] = $ccode;
	}
	if ($_POST['fname'] != null){
		$fname = ucwords(strtolower($_POST['fname']));
	}
	if ($_POST['lname'] != null){
		$lname = ucwords(strtolower($_POST['lname']));
	}
	if ($_POST['mobile'] != null){
		$mobile = $_POST['mobile'];
	}
	if ($_POST['email'] != null){
		$email = $_POST['email'];
	}
	if ($_POST['add1'] != null){
		$add1 = ucwords(strtolower($_POST['add1']));
	}
	if ($_POST['add2'] != null){
		$add2 = ucwords(strtolower($_POST['add2']));
	}
	if ($_POST['postcode'] != null){
		$postcode = $_POST['postcode'];
	}	
	if ($_POST['town'] != null){
		$town = ucwords(strtolower($_POST['town']));
	}	
	if ($_POST['state'] != null){
		$state = $_POST['state'];
	}	
	if ($_POST['country'] != null){
		$country = $_POST['country'];
	}
	if ($_POST['countrycode'] != null){
		$countrycode = $_POST['countrycode'];
	}	
	
	if ($_POST['paymentType'] != null){
		$paymentType = $_POST['paymentType'];
	}
	else{
		$paymentType = '';
	}
	if ($_POST['deliveryType'] != null){
		$deliveryType = $_POST['deliveryType'];
		$shipping = $_POST['shipping'];
	}	
	else{
		$deliveryType = '';
	}
	
	if (isset($_POST['cartList'])){
		$productList=array();$product = '';
		$cartList = $_POST['cartList'];
		foreach ($cartList as $value) {
			
			$product = lookupProduct ($serverName, $value[0]); //search on barcode only
			array_push($product[0],$value[1]); //add selected quantity to end of product details
			array_push($productList,$product[0]);
		}
	}	
	
	
	if ( $ccode == '') {
		if ($fname == '' || $lname == ''  || $email == ''  || $mobile == '') {
			echo 'No code supplied and client details missing';
			die();
		}
	}
	
	if ($conn === false) {
		echo 'Could not connect to server';
		die();
	}
	
	if ( $ccode == '') {
		$ccode = generateEAN();
		$address = $add1.', '.$add2;
		$cid = createClient($conn,$ccode,$fname,$lname,$mobile,$email,$address,$postcode,$town,$state,$country,$countrycode); 
	}
	else {
		$clientDetails = lookupClient($serverName,$ccode);
		if (empty($clientDetails)){
			echo 'The provided client number was not found.';
			die();
		}
		else {
			$cid = $clientDetails[0];$fname = $clientDetails[1];$lname = $clientDetails[2];$mobile = $clientDetails[3];$email = $clientDetails[4];$address=$clientDetails[5];$postcode=$clientDetails[6];$town=$clientDetails[7];
		}
	}

	if ( ($address == '' || $town == '' || $postcode = '') && $deliveryType == 'delhome'){
		echo 'Please provide an address for home deliveries.';
		die();
	}
	
	checkVersion($conn);
	
    if (checkVersion($conn)) {
		$tid = getTime();
		
		$tsql = createTicket($tid,$cid); //create ticket 
		
		$lines = 0;$totalAmount = 0;$totalExVat=0;
		
		foreach ($productList as $value) {  //create ticketlines
			$lines++;
			$pid = $value[0];
			$qty = $value[11];
			$basePrice = str_replace(".","",str_replace("€","",$value[4]));
			$price = $qty*$basePrice;									
			$exVat = round($price / (1+($value[5]/100)));
								
			$totalAmount+=$price;
			$totalExVat+=$exVat;
			
			$tsql = $tsql.addTicketLines($tid,$cid,$pid,$qty,$basePrice,$price,$exVat);
		}
		
		if ($deliveryType == 'delhome') { //add delivery of 5 euros (example)
			$tsql = $tsql.addDeliveryCharge($tid,$cid,$shipping,$conn);
			$totalAmount+=$shipping;
		}
		
		$tsql= $tsql.addPayment($tid,$cid,$totalAmount,$paymentType); //add payment
									
		$tsql= $tsql.finishTicket($tid,$totalAmount,$totalExVat); //finalise ticket

		$stmt = sqlsrv_query( $conn, $tsql); 
		if ($stmt == false)
		{
			echo 'Invalid command sent to database: '.$tsql;
			die();
		}

	//send mail here and echo the html back
	
		//$outputHTML = '';
		
		if ($fname != '') {
			$outputHTML = "<h2>Order number ".$tid." </h2><br><p>Thank you for your order, ".$fname." . Below you will find a recap of your purchase(s).</p><br><p>Please note your customer number for quicker ordering in future: ".$ccode."<br><br><br>";
		}
		else {
			$outputHTML = "<h2>Order number ".$tid." </h2><br><p>Thank you for your order. Below you will find a recap of your purchase(s).</p><br>";
		}

		$mailHTML = $outputHTML.'<table class="tg" style="text-align:left"><thead style="text-align:left"><tr><th class="tg-0lax"></th><th class="tg-0lax" style="text-align:left">Product Description</th><th class="tg-0lax">UP</th><th class="tg-0lax">Qty</th><th class="tg-0lax">Price</th><th class="tg-0lax"></th></tr></thead><tbody>';
				
		if ($productList != null){
			foreach ($productList as $prod) {
				$pid = $prod[0];$pbarcode = $prod[1];$pname = $prod[2];$pqty = $prod[3];$pprice = $prod[4];$purl = $prod[6]; $psupplier = $prod[7];
				$pcategory = $prod[8];$psupname = $prod[9]; $pcatname = $prod[10]; $pordered = $prod[11];
				//$pname = iconv( 'ISO-8859-1', 'UTF-8' , $pname);$psupname = iconv( 'ISO-8859-1', 'UTF-8' , $psupname);$pcatname = iconv( 'ISO-8859-1', 'UTF-8' , $pcatname);
						if ($purl == ''){
							if (file_exists("images/".$pcatname.".png")){
								$purl = "images/".$pcatname.".png";
							}
							else {
								$purl = "images/DEFAULT.png";
							}
						}	
				
				$longURL = 'http://pickarooney.sytes.net/eShop/'.$purl;

				$outputHTML = $outputHTML.'<div class="row" style="align-items-center">
							<div class="col-md-2"><img src="'.$purl.'" alt="#" width="150" height="150" id = "img'.$pbarcode.'"></div>
							<div class="col-md-5"><span id="det'.$pbarcode.'"><br>'.$pname.'<br>'.$psupname.'<br>'.$pcatname.'<br>'.$pbarcode.'</span></div>
							<div class="col-md-1"><br><label>'.$pprice.'</label></div>
							<div class="col-md-1"><br><label>'.$pordered.'</label></div>
							<div class="col-md-1 subtot" id=price'.$pbarcode.'><br>'.number_format($pprice*$pordered, 2).'</div>
						  </div>';

				$mailHTML = $mailHTML.'  <tr>
							<td class="tg-0lax"><img src="'.$longURL.'" alt="#" width="150" height="150" id = "img'.$pbarcode.'"></td>
							<td class="tg-0lax"><span id="det'.$pbarcode.'"><br>'.$pname.'<br>'.$psupname.'<br>'.$pcatname.'<br>'.$pbarcode.'</span></td>
							<td class="tg-0lax"><br><label>'.$pprice.'</label></td>
							<td class="tg-0lax"><br><label>'.$pordered.'</label></td>
							<td class="tg-0lax"><br>'.number_format($pprice*$pordered, 2).'</td>
						  </tr>';


			}

		
			//generate bottom with price  
				$totalAmount = number_format((float)$totalAmount/100, 2, '.', '');
				
				$outputHTML = $outputHTML.'<div class="row" style="align-items-center;padding-bottom:40px">
				<div class="col-md-2"></div>
				<div class="col-md-5" style="font-weight:bold" id="cartdesc"></div>
				
				<div class="col-md-2" style="font-weight:bold" id="totallabel">Total inc. shipping (€)<br></div>
				<div class="col-md-1" id="grandtotal" name="grandtotal">'.$totalAmount.'<br></div>
			  </div>';
			
			$mailHTML = $mailHTML.'<tr style = "font-weight: bold"><td class="tg-0lax"></td><td class="tg-0lax"></td></td><td class="tg-0lax"><td class="tg-0lax"><br><label>Total inc. shipping (€)</label></td><td class="tg-0lax"><br>'.$totalAmount.'</td></tr></tbody></table>';

		}
		
			$htmlHeader = '<html><head><meta charset="utf-8"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/bootstrap.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/magnific-popup.min.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/jquery.fancybox.min.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/themify-icons.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/jquery-ui.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/niceselect.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/animate.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/flex-slider.min.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/owl-carousel.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/slicknav.min.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/jquery.nice-number.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/reset.css">
<link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/style.css"><link rel="stylesheet" href="http://pickarooney.sytes.net/eShop/css/responsive.css"></head><body>';
			$htmlFooter = '</body>';
		
			if ($fname == '') {$fname = 'Client';}
			$subject = "Confirmation of Order no. ".$tid;
			$message = $mailHTML;
			$message = $htmlHeader.$message.$htmlFooter;

			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->SMTPAuth = true;
			
			$mail->Host = 'smtp.mailtrap.io';
			$mail->Username = '35e643c4943456'; //paste one generated by Mailtrap
			$mail->Password = '616d1803b5ef6a';
			$mail->SMTPSecure = 'tls';
			$mail->Port = 2525;
			$mail->setFrom('info@mailtrap.io', 'Mailtrap');
			$mail->addReplyTo('info@mailtrap.io', 'Mailtrap');
			$mail->addAddress('recipient1@mailtrap.io', 'Tim'); 
			$mail->setFrom('contact@resourcecode.eu', 'ResourceCode Online Sales');

			/*$mail->Host = 'cpanel.freehosting.com';
			$mail->Username = 'contact@resourcecode.eu';
			$mail->Password = 'c@l3s3r@1gn3';
			$mail->SMTPSecure = 'tls';
			$mail->Port = 465;
			$mail->setFrom('contact@resourcecode.eu', 'ResourceCode Online Sales');
			$mail->addAddress($email);*/

			/*$mail->Host = 'smtp.gmail.com';
			$mail->Username = 'pickarooney@gmail.com';
			$mail->Password = '3w1nchurch1ll';
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;
			$mail->setFrom('pickarooney@gmail.com', 'ResourceCode Online Sales');
			$mail->addAddress($email);*/

			
			$mail-> Subject = $subject;
			$mail->isHTML(true);
			$mail->Body = $message;
			$mail -> Send();
			
			if($mail->send()){
				file_put_contents($logFile, 'Mail was sent to '.$email . PHP_EOL, FILE_APPEND);
			}else{
				file_put_contents($logFile, 'Mail was not sent to '.$email . PHP_EOL, FILE_APPEND);
				file_put_contents($logFile, 'Mailer Error: ' . $mail->ErrorInfo . PHP_EOL, FILE_APPEND);
			}
		
		
		echo $outputHTML;
	
	}
	else
	{
		echo 'The software version is not recent enough';
		die();
	}


?>

