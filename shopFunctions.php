 <?php  

function generateEAN()

	{
	  $randomNumber = rand(100000000,999999999); 
	  $code = '200' . str_pad($randomNumber, 9, '0');
	  $weightflag = true;
	  $sum = 0;
	  for ($i = strlen($code) - 1; $i >= 0; $i--)
	  {
		$sum += (int)$code[$i] * ($weightflag?3:1);
		$weightflag = !$weightflag;
	  }
	  $code .= (10 - ($sum % 10)) % 10;
	  return $code;
	}
	
	function lookupClient($serverName,$ccode)
	{
		$conn = createConn($serverName);	
		
		$tsql = "select top 1 c.id,c.prenom,c.nom,c.tel3,c.email,c.adresse,c.codepostal,c.ville, p.code from client c inner join syspays p on p.id = c.IdSysPays where c.codebarres = '".$ccode."'";
		$stmt = sqlsrv_query( $conn, $tsql); 

		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		return  $row;

	}

	function updateClient($conn, $ccode,$fname,$lname,$email,$mobile,$address,$postcode,$town,$state,$country,$countrycode)
	{
		$logFile = 'c:\temp\phplog.txt';

		$countryId = addCountry($conn,$country,$countrycode);

		$tsql = "update client set nom = N'".$lname."', prenom = N'".$fname."', email = N'".$email."', tel3 = N'".$mobile."', adresse = N'".$address."', codepostal = '".$postcode."', ville = N'".$town."',idsyspays = (select isnull(max(id),1) from syspays where code = '".$countrycode."') where codebarres ='".$ccode."' ";

		file_put_contents($logFile, 'running update: '. $tsql . PHP_EOL, FILE_APPEND);

		$stmt = sqlsrv_query( $conn, $tsql); 

		if ($stmt == false)
		{
			return 0;
		}
		else {
			return  1;
		}
	}


	function createProductList($serverName)
	{
		$logFile = 'c:\temp\phplog.txt';
		$conn = createConn($serverName);
		$prodList=array();
		
		$tsql = "select p.[id],p.[CodeABarre],upper(p.[Referen]),floor(p.[Reel]),convert(decimal(10,2),cast(p.vente as float)/100), v.[taux]/100, p.[url],p.idProduitFournisseur,p.idproduitFamille,s.nomfournisseur,f.NomFamille from produit p inner join systauxtva v on v.id = p.IdTauxTVA inner join ProduitFournisseur s on s.id = p.IdProduitFournisseur inner join ProduitFamille f on f.id = p.IdProduitFamille where IdProduitType in (1,3) and p.reel > 0 and p.vente > 0 and p.flagarchive = 0 and p.codeabarre not like '' order by p.Referen";
		$stmt = sqlsrv_query( $conn, $tsql);
		$rowok = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		
		if ($rowok) {
			$stmt = sqlsrv_query( $conn, $tsql);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) {
				$pID = $row[0]; $pBarcode = $row[1]; $pName = $row[2]; $pQuantity = $row[3]; $pPrice = $row[4];$pVat = $row[5]; $pUrl = $row[6]; 
				$pSupplier = $row[7];	$pCategory = $row[8]; $pSupName = $row[9];	$pCatName = $row[10];
				
				$pName = iconv( 'ISO-8859-1', 'UTF-8' , $pName);
				$pSupName = iconv( 'ISO-8859-1', 'UTF-8' , $pSupName);
				$pCatName = iconv( 'ISO-8859-1', 'UTF-8' , $pCatName);
				$arow = array($pID,$pBarcode,$pName,$pQuantity,$pPrice,$pVat,$pUrl,$pSupplier,$pCategory,$pSupName,$pCatName);
								
				array_push($prodList,$arow);
			}
			
			return $prodList;
		}
		else 
		{
			handleError(-7, "Could not load product details.",null);
		}

    }
	
	
	function lookupProduct ($serverName, $searchTerm) {
		
		$conn = createConn($serverName);
		$productList=array();
		
		$tsql = "select p.[id],p.[CodeABarre],upper(p.[Referen]),floor(p.[Reel]),convert(decimal(10,2),cast(p.vente as float)/100), v.[taux]/100, p.[url],p.idProduitFournisseur,p.idproduitFamille,upper(s.[nomfournisseur]),upper(f.[NomFamille]) from produit p inner join systauxtva v on v.id = p.IdTauxTVA inner join ProduitFournisseur s on s.id = p.IdProduitFournisseur inner join ProduitFamille f on f.id = p.IdProduitFamille where IdProduitType in (1,3) and p.reel > 0 and p.vente > 0 and p.codeabarre not like '' and p.flagarchive = 0 and (CodeABarre like '%".$searchTerm."%' or referen like '%".$searchTerm."%') order by p.Referen";
		$stmt = sqlsrv_query( $conn, $tsql);
		$rowok = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		
		if ($rowok) {
			$stmt = sqlsrv_query( $conn, $tsql);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) {
				$pID = $row[0]; $pBarcode = $row[1]; $pName = $row[2]; $pQuantity = $row[3]; $pPrice = $row[4];$pVat = $row[5]; $pUrl = $row[6]; 
				$pSupplier = $row[7];	$pCategory = $row[8]; $pSupName = $row[9];	$pCatName = $row[10];
				$pName = iconv( 'ISO-8859-1', 'UTF-8' , $pName);
				$pSupName = iconv( 'ISO-8859-1', 'UTF-8' , $pSupName);
				$pCatName = iconv( 'ISO-8859-1', 'UTF-8' , $pCatName);
				$arow = array($pID,$pBarcode,$pName,$pQuantity,$pPrice,$pVat,$pUrl,$pSupplier,$pCategory,$pSupName,$pCatName);
								
				array_push($productList,$arow);
			}
			
			return $productList;
		}
		else 
		{
			handleError(-7, "Could not load product details.",null);
		}

    }
	
	function lookupProductCatFilter ($serverName, $searchTerm, $searchCat) {
		
		$conn = createConn($serverName);
		$productList=array();
		
		$tsql = "select p.[id],p.[CodeABarre],upper(p.[Referen]),floor(p.[Reel]),convert(decimal(10,2),cast(p.vente as float)/100), v.[taux]/100, p.[url],p.idProduitFournisseur,p.idproduitFamille,s.nomfournisseur,f.NomFamille from produit p inner join systauxtva v on v.id = p.IdTauxTVA inner join ProduitFournisseur s on s.id = p.IdProduitFournisseur inner join ProduitFamille f on f.id = p.IdProduitFamille where IdProduitType in (1,3) and p.reel > 0 and p.vente > 0 and p.codeabarre not like '' and p.flagarchive = 0 and (CodeABarre like '%".$searchTerm."%' or referen like '%".$searchTerm."%') and p.IdProduitFamille in (select id from ProduitFamille where NomFamille = '".$searchCat."') order by p.Referen";
		$stmt = sqlsrv_query( $conn, $tsql);
		$rowok = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		
		if ($rowok) {
			$stmt = sqlsrv_query( $conn, $tsql);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) {
				$pID = $row[0]; $pBarcode = $row[1]; $pName = $row[2]; $pQuantity = $row[3]; $pPrice = $row[4];$pVat = $row[5]; $pUrl = $row[6]; 
				$pSupplier = $row[7];	$pCategory = $row[8]; $pSupName = $row[9];	$pCatName = $row[10];
				$pName = iconv( 'ISO-8859-1', 'UTF-8' , $pName);
				$arow = array($pID,$pBarcode,$pName,$pQuantity,$pPrice,$pVat,$pUrl,$pSupplier,$pCategory,$pSupName,$pCatName);
								
				array_push($productList,$arow);
			}
			
			return $productList;
		}
		else 
		{
			handleError(-7, "Could not load product details.",null);
		}

    }

	function lookupProductBrand ($serverName, $searchBrand) {
		
		$conn = createConn($serverName);
		$productList=array();
		
		$tsql = "select p.[id],p.[CodeABarre],upper(p.[Referen]),floor(p.[Reel]),convert(decimal(10,2),cast(p.vente as float)/100), v.[taux]/100, p.[url],p.idProduitFournisseur,p.idproduitFamille,s.nomfournisseur,f.NomFamille from produit p inner join systauxtva v on v.id = p.IdTauxTVA inner join ProduitFournisseur s on s.id = p.IdProduitFournisseur inner join ProduitFamille f on f.id = p.IdProduitFamille where IdProduitType in (1,3) and p.reel > 0 and p.vente > 0 and p.codeabarre not like '' and p.flagarchive = 0 and p.IdProduitFournisseur = ".$searchBrand." order by p.Referen";
		$stmt = sqlsrv_query( $conn, $tsql);
		$rowok = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		
		if ($rowok) {
			$stmt = sqlsrv_query( $conn, $tsql);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) {
				$pID = $row[0]; $pBarcode = $row[1]; $pName = $row[2]; $pQuantity = $row[3]; $pPrice = $row[4];$pVat = $row[5]; $pUrl = $row[6]; 
				$pSupplier = $row[7];	$pCategory = $row[8]; $pSupName = $row[9];	$pCatName = $row[10];
				$pName = iconv( 'ISO-8859-1', 'UTF-8' , $pName);
				$arow = array($pID,$pBarcode,$pName,$pQuantity,$pPrice,$pVat,$pUrl,$pSupplier,$pCategory,$pSupName,$pCatName);
								
				array_push($productList,$arow);
			}
			
			return $productList;
		}
		else 
		{
			handleError(-7, "Could not load product details.",null);
		}

    }

	function lookupProductCat ($serverName, $searchCat) {
		
		$conn = createConn($serverName);
		$productList=array();
		
		$tsql = "select p.[id],p.[CodeABarre],upper(p.[Referen]),floor(p.[Reel]),convert(decimal(10,2),cast(p.vente as float)/100), v.[taux]/100, p.[url],p.idProduitFournisseur,p.idproduitFamille,s.nomfournisseur,f.NomFamille from produit p inner join systauxtva v on v.id = p.IdTauxTVA inner join ProduitFournisseur s on s.id = p.IdProduitFournisseur inner join ProduitFamille f on f.id = p.IdProduitFamille where IdProduitType in (1,3) and p.reel > 0 and p.vente > 0 and p.codeabarre not like '' and p.flagarchive = 0 and p.IdProduitFamille = ".$searchCat." order by p.Referen";
		$stmt = sqlsrv_query( $conn, $tsql);
		$rowok = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		
		if ($rowok) {
			$stmt = sqlsrv_query( $conn, $tsql);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) {
				$pID = $row[0]; $pBarcode = $row[1]; $pName = $row[2]; $pQuantity = $row[3]; $pPrice = $row[4];$pVat = $row[5]; $pUrl = $row[6]; 
				$pSupplier = $row[7];	$pCategory = $row[8]; $pSupName = $row[9];	$pCatName = $row[10];
				$pName = iconv( 'ISO-8859-1', 'UTF-8' , $pName);
				$arow = array($pID,$pBarcode,$pName,$pQuantity,$pPrice,$pVat,$pUrl,$pSupplier,$pCategory,$pSupName,$pCatName);
								
				array_push($productList,$arow);
			}
			
			return $productList;
		}
		else 
		{
			handleError(-7, "Could not load product details.",null);
		}

    }

	function createBrandList($serverName)
	{
		
		$conn = createConn($serverName);
		$brandList=array();
		
		$tsql = "select id,NomFournisseur from ProduitFournisseur where flagarchive = 0 and id in (select idProduitFournisseur from produit where flagarchive = 0)";
		$stmt = sqlsrv_query( $conn, $tsql);
		$rowok = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
				
		if ($rowok) {
			$stmt = sqlsrv_query( $conn, $tsql);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) {
				$row[1] = iconv( 'ISO-8859-1', 'UTF-8' , $row[1]);
				array_push($brandList,$row);
			}
			
			return $brandList;
		}
		else 
		{
			echo ('Could not load brand list');
			die();
		}

    }
	
	function createCategoryList($serverName)
	{
		$logFile = 'c:\temp\phplog.txt';
		$conn = createConn($serverName);
		$categoryList=array();
		
		$tsql = "select id,upper(NomFamille) from ProduitFamille where id in (select IdProduitFamille from Produit where reel > 0 and vente > 0 and flagarchive = 0 and IdProduitType in (1,3))";
		$stmt = sqlsrv_query( $conn, $tsql);
		$rowok = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
				
		if ($rowok) {
			$stmt = sqlsrv_query( $conn, $tsql);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) {
				$row[1] = iconv( 'ISO-8859-1', 'UTF-8' , $row[1]);
				array_push($categoryList,$row);
				
			}
			
			return $categoryList;
		}
		else 
		{
			echo ('Could not load category list');
			die();
		}

    }
	

	function checkClient($conn,$fname,$lname,$mobile)
	{
		$tsql = "select max(id) from client where nom = '".$lname."' and prenom = '".$fname."' and tel3 = '".$mobile."'";
		
		$stmt = sqlsrv_query( $conn, $tsql); 
		if ($stmt == false)
		{
			$cid = -4;
			handleError(-7,"line 71 ".$tsql,null); //debug
		}
		else 
		{
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
			if ($row[0] < 1 )
			{
				$cid = 0;
			}
			else {
				$cid = $row[0];
			}
		}
		return $cid;

	}

	function addCountry($conn,$country,$countrycode){
		
		$logFile = 'c:\temp\phplog.txt';
		$tsql = "insert into syspays(libelle,code,guidCs,iGuid) select '".$country."',".$countrycode.", newid(), newid() where not exists (select 1 from syspays where libelle = '".$country."');";

		$stmt = sqlsrv_query( $conn, $tsql); 
		if ($stmt == false)
		{
			echo ('Could not add country');
			die();
		}

		$tsql = "select max(id) from syspays where libelle = '".$country."';";
		$stmt = sqlsrv_query( $conn, $tsql); 
		if ($stmt == false)
		{
			echo ('Could not handle country');
			die();
		}

		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		if ($row[0] < 1 )
		{
			file_put_contents($logFile,'no row shay  -  '.$row,FILE_APPEND);
			return false;
		}
		else
		{
			$countryId = $row[0];
		}
	return $countryId;
	
    }

	
	function createClient($conn,$ccode,$fname,$lname,$mobile,$email,$address,$postcode,$town,$state,$country,$countrycode)
    {
		$cid = checkClient($conn,$fname,$lname,$mobile);
		$countryId = addCountry($conn,$country,$countrycode);

		if ($cid > 0){
			return $cid;
		}
		else {	
			//$tsql = "insert into client (codebarres,nom,prenom,tel3,email,datecreation,[GUID]) select '".$ccode."','".$lname."','".$fname."','".$mobile."','".$email."',getdate(),'{'+cast(newid() as nvarchar(36))+'}';";
			$tsql = "insert into client (codebarres,nom,prenom,tel3,email,adresse,codepostal,ville,idsyspays,datecreation,[GUID]) select '".$ccode."','".$lname."','".$fname."','".$mobile."','".$email."','".$address."','".$postcode."','".$town."','".$countryId."',getdate(),'{'+cast(newid() as nvarchar(36))+'}';";
			
			$stmt = sqlsrv_query( $conn, $tsql); 
			if ($stmt == false)
			{
				$cid = -4;
			}
			else 	
			{
				$tsql = "select max(id) from client";
				$stmt = sqlsrv_query( $conn, $tsql); 
				$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
				if ($stmt == false)
				{
					$cid = -4;
				}
				else {
					$cid = $row[0];
				}
			}

			return $cid;
		}
	}
	
	function createConn($serverName) 
	{	
		$uid = "sa"; $pwd = "_PWD4sa_"; $databaseName = "Merlin"; 
		$connectionInfo = array( "UID"=>$uid, "PWD"=>$pwd, "Database"=>$databaseName); 
		$conn = sqlsrv_connect( $serverName, $connectionInfo);
		
		if (!$conn) {
			echo "The site is currently down for maintenance. Please try again later.";
			die();
		}
		else {
			return $conn;
		}
	}
	
    
	function handleError($errNum,$arg1,$arg2)
	{
		$errMsg = '';
		
		switch ($errNum) {
		  case 0:
			$errMsg = "Please fill out all fields OR a client ID.";
			break;
		  case -1:
			$errMsg = "You cannot order more than ".$arg1." of item ".$arg2;
			break;
		  case -2:
			$errMsg = "Invalid connection info.";
			break;
		 case -3:
			$errMsg = "No client found with code ".$arg1;
			break;
		case -4:
			$errMsg = "Could not create client.";
			break;
		case -5:
			$errMsg = "Invalid Statement: ".$arg1;
			break;
		case -6:
			$errMsg = "You have selected the same item multiple times: ".$arg1; 
			$ccode = $arg2;
			break;
		case -7:
			$errMsg = "Debug Output: ".$arg1;
			break;
		case -8:
			$errMsg = "You must update your software version or process at least one bill.";
			break;
		default:
			$errMsg = "Unidentified error.";
		}
	
		return $errMsg;	
				
	}

	function getClientHistory($serverName,$ccode)
	{
		$logFile = 'c:\temp\phplog.txt';

		$conn = createConn($serverName);
		
		$tsql = "select p.[id],p.[CodeABarre],upper(p.[Referen]),clt.nbcode,p.[url],s.nomfournisseur,f.NomFamille, convert(decimal(10,2),cast(clt.prixpaye as float)/100), clt.idcaisseticket,cast(cast(dbo.getdatefromlong(IdCaisseTicket) as date) as nvarchar) from produit p inner join systauxtva v on v.id = p.IdTauxTVA inner join ProduitFournisseur s on s.id = p.IdProduitFournisseur inner join ProduitFamille f on f.id = p.IdProduitFamille inner join caisseligticket clt on clt.numarticle = p.id where clt.idexecutant = (select max(id) from perso where nom = 'SALES' and prenom = 'ONLINE') and clt.idclient = (select max(id) from client where codebarres = '".$ccode."') order by clt.idcaisseticket desc;";
		
		$stmt = sqlsrv_query( $conn, $tsql);

		$historyList = [];

		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) {
			$pID = $row[0]; $pBarcode = $row[1]; $pName = $row[2]; $pQuantity = $row[3]; $pUrl = $row[4]; $pSupName = $row[5];$pCatName = $row[6];$pPrice = $row[7];$pTotal = $pPrice*$pQuantity; 
			$pOrderNumber = $row[8];$pOrderDate = $row[9]; 
			

			$pName = iconv( 'ISO-8859-1', 'UTF-8' , $pName);
			$arow = array($pID,$pBarcode,$pName,$pQuantity,$pUrl,$pSupName,$pCatName,$pPrice,$pTotal,$pOrderNumber,$pOrderDate);
							
			array_push($historyList,$arow);
		}
			
		return $historyList;

	}

	
	function createStockOut($cid)
	{		
		$tsql = "insert into sysmenu (flagarchive, groupe,libelle,guidcs,iguid) select 0,1010,'ONLINE SALES',newid(),newid() where not exists (select id from sysmenu where libelle = 'ONLINE SALES' and groupe = 1010);insert into ProduitMouvement (commentmvt,id,idperso,motif,typemvt,guidcs,iguid) select 'online sales : '+c.nom+', '+c.Prenom, (cast (DATEDIFF(day,'1990-01-01',getdate()) as int) * 100000) + datediff(second,convert(date,getdate()),getdate()), -1,max(s.id),2,newid(),newid() from sysmenu s,client c where s.libelle = 'ONLINE SALES' and s.groupe = 1010 and c.id =".$cid." group by c.Nom,c.Prenom";

		return $tsql;
	}
	
	function addProduct($pid,$qty)
	{
		$tsql=$tsql.";insert into ProduitMvtDetail (idproduit,IdProduitMvt,quantite,GuidCS,iguid) select ".$pid.",max(pm.id),".$qty.",newid(),newid() from ProduitMouvement pm;";
		$tsql=$tsql."update produit set reel = reel - ".$qty." where id = ".$pid.";";

		return $tsql;
	}	
  

	function createTicket($tid,$cid)
	{		
		$tsql = "insert into caisseticket (id,idclient,dateheure,EtatTicket,EtatTik,TypeTik,IdCaisseJour,guidcs,iguid,numver,idlocation,idtrancheage,fichesuiveuse) select ".$tid.",".$cid.",".$tid.",
		1,0,4,(cast (DATEDIFF(day,'1990-01-01',getdate()) as int) * 100000),newid(),newid(),numver,1,1,'' from caisseticket ct where ct.id = (select max(id) from caisseticket) group by ct.numver;";
		$tsql=$tsql."insert into perso (nom,prenom,idlocation) select 'SALES','ONLINE',1 where not exists (select 1 from perso where nom = 'SALES' and prenom = 'ONLINE');";
		
		return $tsql;
	}
	
	function addTicketLines($tid,$cid,$pid,$qty,$basePrice,$price,$exVat) //add each line with prices
	{		

		$lid = $tid+$pid;
		
		$tsql = "insert into caisseligticket (etatligtik,id,idcaisseticket,idclient,idexecutant,idtauxtva,nbcode,numarticle,montantht,prixdebase,prixpaye,TypeLigTik,guidcs,iguid) select 0,".$lid.",".$tid.",".$cid.",e.id,p.idtauxtva,".$qty.",".$pid.",".$exVat.",".$basePrice.",".$price.",2,newid(),newid() from perso e, produit p where e.nom = 'SALES' and e.prenom = 'ONLINE' and p.id = ".$pid.";";
		$tsql=$tsql."update produit set reel = reel - ".$qty." where id = ".$pid.";";
	
		return $tsql;
	}
	
	function addDeliveryCharge($tid,$cid,$shipping,$conn) //add each line with prices
	{		
		$logFile = 'c:\temp\phplog.txt';

		//delivery charge is set to 5 as an example
		$tsql = "insert into prestation ([code],[IdPrestationFamille],[IdTauxTVA],[Sexe], [libelle], [Prix], [guidcs],[iguid], [description]) select top 1 'OLDEL',14,v.id,3,'DELIVERY',".$shipping.",newid(),newid(),'CHARGE FOR ONLINE DELIVERY' from SysTauxTVA v where v.id = (select max(idtauxtva) from prestation where flagarchive = 0) and not exists (select 1 from prestation where libelle = 'DELIVERY');update prestation set [Prix] = ".$shipping." where libelle = 'DELIVERY'";

		$stmt = sqlsrv_query( $conn, $tsql); 
		if ($stmt == false)
		{
			echo ('Could not add delivery charge');
			die();
		}

		$tsql = "select max(p.id),p.Prix,cast(cast(p.Prix as decimal)/(1+(cast(v.taux as decimal)/10000))as int) from prestation p, systauxtva v where p.Libelle = 'DELIVERY' and v.id = p.IdTauxTVA group by p.Prix, v.Taux;";
		$stmt = sqlsrv_query( $conn, $tsql); 
		if ($stmt == false)
		{
			echo ('Could not find delivery charge');
			die();
		}

		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		if ($row[0] < 1 )
		{
			file_put_contents($logFile,'no row shay  -  '.$row,FILE_APPEND);
			return false;
		}
		else
		{
			$pid = $row[0]; $price = $row[1];$basePrice = $price;$qty=1;$exVat=$row[2];
		}
	
		
		$lid = $tid+$pid;
		
		$tsql = "insert into caisseligticket (etatligtik,id,idcaisseticket,idclient,idexecutant,idtauxtva,nbcode,numarticle,montantht,prixdebase,prixpaye,TypeLigTik,guidcs,iguid) select 0,".$lid.",".$tid.",".$cid.",e.id,p.idtauxtva,".$qty.",".$pid.",".$exVat.",".$basePrice.",".$price.",1,newid(),newid() from perso e, prestation p where e.nom = 'SALES' and e.prenom = 'ONLINE' and p.id = ".$pid.";";
		
		return $tsql;
	}
	
	
	
	function finishTicket($tid,$price,$exVat) //add prices  
	{	
		$tsql = "update caisseticket set CAVente = ".$price.",MontantHT = ".$exVat.",prixfacture = ".$price.", code = (select max(code)+1 from caisseticket), EtatTicket = 0, etattik = left(idcaissejour,5),NbLigPai = 1,
TypeTik = 1, idsysmachine = (select top 1 idsysmachine from caisseticket where EtatTicket = 0 and EtatTik > 0) where id = ".$tid;

		return $tsql;
	}
	
	function addPayment($tid,$cid,$price,$paymentType)// standard epayment
	{		
		$payid = $tid+100;

		if ($paymentType == 'paystore'){
			$payID = "mp.id = 5;";
		}
		else {
			$payID = "mp.libelle = 'ePay';";
		}
		
		
		$tsql = "update caissetypemoypaiement set Visibilite = 31 where id = 12 and Visibilite = 30; update caissetypemoypaiement set Visibilite = 25 where id = 5 and Visibilite = 24;insert into CaisseMoyPaiement (icone,IdCaisseTypeMoyPaiement,libelle,guidcs,iguid) select 'psmartphone',12,'ePay',newid(),newid() where not exists (select 1 from CaisseMoyPaiement where libelle = 'ePay');insert into CaisseLigPaiement (id,IdCaisseTicket, prix,PrixOrg,IdCaisseMoyPaiement,InfoMisc3,EtatLigPai,IdCaisseMonnaie,guidcs,iguid) select ".$payid.",".$tid.",".$price.",".$price.",mp.id, c.nom+', '+c.prenom, 0,1, newid(),newid() from client c,CaisseMoyPaiement mp where c.id = ".$cid." and ".$payID;
		
		if ($paymentType == 'paystore'){
			$tsql = $tsql."update client set credit = credit + ".$price." where id = ".$cid.";";
		}
		
		
		return $tsql;
	}
	
	function checkVersion($conn)
	{
		$tsql = "select 1 from caisseticket where numver >= 28058";
		$stmt = sqlsrv_query( $conn, $tsql); 
		if ($stmt == false)
		{
			handleError(-2,null,null);
		}
		else 
		{
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
			if ($row[0] < 1 )
	        {
				return false;
			}
			else
			{
				return true;
			}
		}

	}
	
	function getTime() {
		$then = new DateTimeImmutable('1990-01-01');
		$date = new DateTimeImmutable();
		$days = $date->diff($then)->days;
		$days = $days * 100000;

		$midnight= new DateTimeImmutable('today',new DateTimeZone('Europe/Paris'));
		$diff = (new DateTimeImmutable(null,new DateTimeZone('Europe/Paris')))->diff($midnight);
		$seconds = $diff->s + $diff->i * 60 + $diff->h * 60 * 60;

		$tid = $days+$seconds;

		return $tid;

	}
  
	
	function checkLogin($ccode,$fname,$lname,$email,$mobile) {	
	
		if ( $ccode == '') {
			if ($fname == '' || $lname == ''  || $email == ''  || $mobile == '') {
				handleError(0,null,null);
			}
		}
		
		$clientDetail = array();
		array_push($clientDetail,$ccode,$fname,$lname,$email,$mobile);
		
		return $clientDetail;
	}	
	
	
	function checkLists($productList,$qty) {	
		//check all lists
		
		foreach ($productList as $value) {
			$pstock = $value[2];
			if ($qty > $pstock){
				handleError(-6,$pname,$ccode);	
			}
		}

		return $productList;
	}

	function name_sort($a,$b) {
          return $a[2]>$b[2];
	}	
	function price_sort($a,$b) {
          return $a[4]>$b[4];
	}
	function price_sort_dec($a,$b) {
          return $a[4]<$b[4];
	}
	
	function generateProductDisplay($serverName,$productList,$searchTerm) {
				$logFile = 'c:\temp\phplog.txt';
		$outputHTML = '';
		
		if ($searchTerm != '') {
			$productList = lookupProduct ($serverName, $searchTerm);
		}
		
		if ($productList != null){
			foreach ($productList as $prod) {
				$pbarcode = $prod[1];$pname = $prod[2];$pqty = $prod[3];$pprice = $prod[4];$purl = $prod[6]; $psupplier = $prod[7];$pcategory = $prod[8];$psupname = $prod[9]; $pcatname = $prod[10];
			
				//$pname = iconv( 'ISO-8859-1', 'UTF-8' , $pname);$psupname = iconv( 'ISO-8859-1', 'UTF-8' , $psupname);$pcatname = iconv( 'ISO-8859-1', 'UTF-8' , $pcatname);
						if ($purl == ''){
							if (file_exists("images/".$pcatname.".png")){
								$purl = "images/".$pcatname.".png";
							}
							else {
								$purl = "images/DEFAULT.png";
							}
						}	
							
				$outputHTML = $outputHTML.'<div class="col-lg-4 col-md-6 col-12"> <div class="single-product"><div class="product-img"><img class="default-img" src="'.$purl.'"alt="" width="500" height="500">
							<div class="button-head">
								<div class="product-action">
									<a title="Quick View" href="#"><i class="ti-eye" id="mod'.$pbarcode.'"></i><span>Quick View</span></a>
								</div>
								<div class="product-action-2">
									<a title="Add to cart" href="#" class = "cart" id = scart'.$pbarcode.'>Add to cart</a>
									<a title="Remove from cart" href="#" class = "uncart" id = suncart'.$pbarcode.' style = "display:none">Remove from cart</a>
								</div>
							</div>
						</div>
						<div class="product-content">
							<div class="product-price">
								<span><b>'.$pname.'</b></span>
							</div>

							<div class="product-price">
								<span>['.$psupname.']</span><span id="upri'.$pbarcode.'" style="float:right">€'.$pprice.'</span>
							</div>
						</div>
					</div>
				</div>';
			}
		}
		
		
	return $outputHTML;

	}	
	
	
	
	function generateModal($prod){
	
		$outputHTML = '';
		
		$pbarcode = $prod[1];$pname = $prod[2];$pqty = $prod[3];$pprice = $prod[4];$purl = $prod[6]; $psupplier = $prod[7];$pcategory = $prod[8];$psupname = $prod[9]; $pcatname = $prod[10];
		//$pname = iconv( 'ISO-8859-1', 'UTF-8' , $pname);$psupname = iconv( 'ISO-8859-1', 'UTF-8' , $psupname);$pcatname = iconv( 'ISO-8859-1', 'UTF-8' , $pcatname);
		
		if ($purl == ''){
			if (file_exists("images/".$pcatname.".png")){
				$purl = "images/".$pcatname.".png";
			}
			else {
				$purl = "images/DEFAULT.png";
			}
		}	
		
		$outputHTML = '<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
						</div>
						<div class="modal-body">
							<div class="row no-gutters">
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<!-- Product Slider -->
										<div class="product-gallery">
											<div class="quickview-slider-active">
												<div class="single-slider">
													<img src="'.$purl.'" alt="#" width="500" height="500">
												</div>
											</div>
										</div>
									<!-- End Product slider -->
								</div>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<div class="quickview-content">
										<h2>'.$pname.'</h2>
										<div class="quickview-ratting-review">
											<div class="quickview-stock">
												<span><i class="fa fa-check-circle-o"></i> '.$pqty.' in stock</span>
											</div>
										</div>
										<h3>€'.$pprice.'</h3>
										<div class="quickview-peragraph">
											<p>'.$psupname.'<br>'.$pcatname.'<br>'.$pname.'<br><br>'.$pbarcode.'</p>
										</div>
										<br><br>
										<div class="add-to-cart">
											<a href="#" class="btn cart" id = cart'.$pbarcode.'>Add to cart</a>
										</div>
										<div class="add-to-cart">
											<a href="#" class="btn uncart" id = uncart'.$pbarcode.'>Remove from cart</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			
			
		return $outputHTML;
	
	}
	
	
	function displayCart($serverName, $productList){
	
		$outputHTML = '';
		$total= 0;
		
		if ($productList != null){
			foreach ($productList as $prod) {
				$pid = $prod[0];$pbarcode = $prod[1];$pname = $prod[2];$pqty = $prod[3];$pprice = $prod[4];$purl = $prod[6]; $psupplier = $prod[7];
				$pcategory = $prod[8];$psupname = $prod[9]; $pcatname = $prod[10]; $pordered = $prod[11];
				$pname = iconv( 'ISO-8859-1', 'UTF-8' , $pname);$psupname = iconv( 'ISO-8859-1', 'UTF-8' , $psupname);$pcatname = iconv( 'ISO-8859-1', 'UTF-8' , $pcatname);
						if ($purl == ''){
							if (file_exists("images/".$pcatname.".png")){
								$purl = "images/".$pcatname.".png";
							}
							else {
								$purl = "images/DEFAULT.png";
							}
						}	
							
				$outputHTML = $outputHTML.'<div class="row" style="align-items-center">
							<div class="col-md-2"><img src="'.$purl.'" alt="#" width="150" height="150" id = "img'.$pbarcode.'"></div>
							<div class="col-md-5"><span id="det'.$pbarcode.'"><br>'.$pname.'<br>'.$psupname.'<br>'.$pcatname.'<br>'.$pbarcode.'</span></div>
							<div class="col-md-1"><br><label id="lab'.$pbarcode.'">'.$pprice.'</label></div>
							<div><input hidden type="number" id="unipri'.$pbarcode.'" value="'.$pprice.'"></div>
							<div class="col-md-1"><br><input class ="quan" type = "number" id="quan'.$pbarcode.'" value="'.$pordered.'" min="1" max="'.$pqty.'"></div>
							<div class="col-md-1 subtot" id=price'.$pbarcode.'><br>'.number_format($pprice*$pordered, 2).'</div>
							<div class="col-md-1"><br><i class="uncart far fa-trash-alt fa-2x" id=uncart'.$pbarcode.' ></i></div>
						  </div>';
			
				$total+=$pprice*$pordered;
			
			}
			
			$total = number_format((float)$total, 2, '.', '');
			//generate bottom with price  
			
				$outputHTML = $outputHTML.'<div class="row" style="align-items-center;padding-bottom:40px">
				<div class="col-md-2"></div>
				<div class="col-md-5" style="font-weight:bold" id="cartdesc"></div>
				<div class="col-md-1"></div>
				<div class="col-md-1" style="font-weight:bold" id="totallabel">Total (€)<br></div>
				<div class="col-md-1" id="grandtotal" name="grandtotal">'.$total.'<br></div>
				<div class="col-md-1" id="checkout"><button class="btn" type="submit">Checkout</button></div>
			  </div>';
			
		
		}
		
		
		
		return $outputHTML;
	}

	function displayHistory($serverName, $historyList){
	
		$outputHTML = '';
		$logFile = 'c:\temp\phplog.txt';

		$date = date("d/m : H:i :");

		if ($historyList != null){
			foreach ($historyList as $row) {
				$pID = $row[0]; $pBarcode = $row[1]; $pName = $row[2]; $pQuantity = $row[3]; $pUrl = $row[4]; $pSupName = $row[5];$pCatName = $row[6];$pPrice = $row[7];$pTotal = $row[8]; 
				$pOrderNumber = $row[9]; $pOrderDate = $row[10]; 
				$pName = iconv( 'ISO-8859-1', 'UTF-8' , $pName);$pSupName = iconv( 'ISO-8859-1', 'UTF-8' , $pSupName);$pCatName = iconv( 'ISO-8859-1', 'UTF-8' , $pCatName);

						if ($pUrl == ''){
							if (file_exists("images/".$pCatName.".png")){
								$pUrl = "images/".$pCatName.".png";
							}
							else {
								$pUrl = "images/DEFAULT.png";
							}
						}	
							
				$outputHTML = $outputHTML.'<div class="row" style="align-items-center">
							<div class="col-md-1"><img src="'.$pUrl.'" alt="#" width="150" height="150" id = "img'.$pBarcode.'"></div>
							<div class="col-md-3"><span id="det'.$pBarcode.'">'.$pName.'<br>'.$pSupName.'<br>'.$pCatName.'<br>'.$pBarcode.'<br><br></span></div>
							<div class="col-md-1"><br><label id="upri'.$pBarcode.'">'.$pPrice.'</label></div>
							<div class="col-md-1"><br><label id="quan'.$pBarcode.'">'.$pQuantity.'</label></div>
							<div class="col-md-1"><br><label id="itemtot'.$pBarcode.'">'.number_format($pTotal, 2).'</label></div>
							<div class="col-md-2"><br><label id="ordernum'.$pBarcode.'">'.$pOrderNumber.'</label></div>
							<div class="col-md-2"><br><label id="orderdate'.$pBarcode.'">'.$pOrderDate.'</label></div>
							<div class="col-md-1"><br><i class="fas fa-redo fa-2x" id=cart'.$pBarcode.' ></i></div>
						  </div>';
			
		
			}

		}

		return $outputHTML;
	}


	
?>