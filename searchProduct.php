 <?php  
 
	include 'shopFunctions.php';
	
	if(!isset($_SESSION)) {
		session_start();
	}
	
	$serverName = $_SESSION['serverName'];

	//$serverName = '(local)';
	//$serverName = 'tcp:pickarooney.sytes.net,33433';
	//$serverName="tcp:170.253.55.50,1433";   //carlos

	$logFile = 'c:\temp\phplog.txt';
	
	if (isset($_POST['searchTerm'])){
		$searchTerm = $_POST['searchTerm'];
		
		if (isset($_POST['searchCat'])){
			$searchCat = $_POST['searchCat'];
		}
		else {
			$searchCat = '';
		}	
		if ($searchCat != '' && $searchCat != 'All Items'){
			$productList = lookupProductCatFilter ($serverName, $searchTerm, $searchCat);
			echo generateProductDisplay($serverName,$productList,'');
		}
		else {
			$productList = lookupProduct ($serverName, $searchTerm);
			echo generateProductDisplay($serverName,$productList,'');
		}
	}	

	if (isset($_POST['sortOrder'])){
		$sortOrder = $_POST['sortOrder'];
		$productList = createProductList($serverName);
		switch ($sortOrder) {
		  case "Name":
			usort($productList,"name_sort" );
			echo generateProductDisplay($serverName,$productList,'');
			break;
		  case "Price (Low to High)":
			usort($productList,"price_sort" );
			echo generateProductDisplay($serverName,$productList,'');
			break;
		  case "Price (High to Low)":
			usort($productList,"price_sort_dec" );
			echo generateProductDisplay($serverName,$productList,'');
			break;
		  default:
			usort($productList,"name_sort" );
			echo generateProductDisplay($serverName,$productList,'');
		}
	}	

	if (isset($_POST['filterBrand'])){
		$filterBrand = $_POST['filterBrand'];
		if ($filterBrand == 0) {
			$productList = createProductList($serverName);
		}
		else {
			$productList = lookupProductBrand ($serverName, $filterBrand);
		}
		echo generateProductDisplay($serverName,$productList,'');
	}	
	
	if (isset($_POST['filterCat'])){
		$filterCat = $_POST['filterCat'];
		if ($filterCat == 0) {
			$productList = createProductList($serverName);
		}
		else {
			$productList = lookupProductCat ($serverName, $filterCat);
		}	
		echo generateProductDisplay($serverName,$productList,'');
	}	
	
	if (isset($_POST['searchCode'])){
		$searchCode = $_POST['searchCode'];
		$productList = lookupProduct ($serverName, $searchCode);
		echo generateModal($productList[0]);
	}	

	if (isset($_POST['cartList'])){
		$productList=array();$product = '';
		$cartList = $_POST['cartList'];
		foreach ($cartList as $value) {

			$product = lookupProduct ($serverName, $value[0]); //search on barcode only
			if (!empty($product)){
				array_push($product[0],$value[1]); //add selected quantity to end of product details
				array_push($productList,$product[0]);
			}	
		}
		if (!empty($productList)){
			echo displayCart($serverName,$productList);
		}
		else {
			echo 'OutOfStock';
		}
	}	

	if (isset($_POST['recapList'])){
		$productList=array();$product = '';
		$cartList = $_POST['recapList'];
		foreach ($cartList as $value) {
			//$product = lookupProduct ($serverName, $value);
			$product = lookupProduct ($serverName, $value[0]); //search on barcode only
			array_push($product[0],$value[1]); //add selected quantity to end of product details
			array_push($productList,$product[0]);
		}
		echo displayRecap($serverName,$productList);
	}	

	if (isset($_POST['ccode'])){

		$logFile = 'c:\temp\phplog.txt';


		$historyList=array();$product = '';
		$ccode = $_POST['ccode'];
		$historyList = getClientHistory($serverName,$ccode);

		if (empty($historyList)){
			echo "<br><br><p>No history for this client</p>";
		}
		else{
			echo displayHistory($serverName,$historyList);
		}
	}	

	if (isset($_POST['searchClientCode'])){
		
		$logFile = 'c:\temp\phplog.txt';
		$ccode = $_POST['searchClientCode'];
        
		$clientDetails = lookupClient($serverName,$ccode);
		if (empty($clientDetails)){
			echo 0;
		}
		else {
			echo 1;
		}
	}	

	if (isset($_POST['logOut'])){
		$_SESSION['ccode'] = 'Guest Account';
		echo "Guest Account";
	}	


?>


