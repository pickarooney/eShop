 <?php  
 
	include 'shopFunctions.php';
	
	if(!isset($_SESSION)) {
		session_start();
	}
		
	$serverName = $_SESSION['serverName'];

	$ccode = ''; $fname = '';$lname='';$email='';$mobile='';$add1='';$add2='';$address='';$postcode='';$town='';$state='';$country='';$countrycode='';

	//$serverName = '(local)';
	//$serverName = 'tcp:pickarooney.sytes.net,33433';
	//$serverName="tcp:170.253.55.50,1433";   //carlos

	if(isset($_GET['data']) && !empty($_GET['data'])){
		$_SESSION['ccode'] = $_GET['data'];
		$ccode = $_GET['data'];
	}
	else if (isset($_SESSION['ccode']) && !empty($_SESSION['ccode'])){
		$ccode = $_SESSION['ccode'];
	}


	$conn = createConn($serverName);
	$logFile = 'c:\temp\phplog.txt';

	if (!empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['mobile']) && !empty($_POST['email']) ){


		if ($_POST['ccode'] != null){
			$ccode = $_POST['ccode'];
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
			$address = $add1;
		}
		if ($_POST['add2'] != null){
			$add2 = ucwords(strtolower($_POST['add2']));
			$address = $add1.', '.$add2;
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

		$res = updateClient($conn, $ccode,$fname,$lname,$email,$mobile,$address,$postcode,$town,$state,$country,$countrycode);
		
		if ($res) {
			$_SESSION['ccode'] = $ccode;
			file_put_contents($logFile, 'Client details updated'.  PHP_EOL, FILE_APPEND);
			echo 'Client details updated.';
		}
		else {
			file_put_contents($logFile, 'Client details not updated'.  PHP_EOL, FILE_APPEND);
			echo 'Client details could not be updated.';
		}
	}	
	else {
		echo 'Details are incomplete';
	}	
?>


