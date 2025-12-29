<?php 
require_once('includes/load.php');
$all_contaients = find_all('Shippingcost');

class Shippingcost {
private $shippingTable = 'shippingcost_cost';


 public function listShippingcost($saleid){
	global $db;		
	$sqlQuery = "SELECT * FROM shipping_cost WHERE sales_id = '".$saleid."'";
	$result = $db->query($sqlQuery);
    $sqlQuery1 = "SELECT * FROM shipping_cost WHERE sales_id = '".$saleid."'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$shippingData = array();	



	while($shipping = $db->fetch_assoc($result)){ 
		
		$shippingRows = array();
		$shippingRows[] = $shipping['concept'];
		$shippingRows[] = $shipping['currencyrate'];
		$shippingRows[] = $shipping['price_usd'];
		$shippingRows[] = $shipping['price_rmb'];				
		$shippingRows[] = '<button type="button" name="update" id="'.$shipping["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$shippingData[] = $shippingRows;

	}

	$output = array(
		"draw"	=>  intval($_POST["draw"]),
		"recordsTotal" 	=>  $numRows,
		"recordsFiltered" 	=>  $numRows,
		"data"    		=>  	$shippingData
	);
	
	echo json_encode($output);
}



//".$_POST["ContainerId"]."
	public function getShippingcost(){
		global $db;	
		if($_POST["shippingcostId"]) {
			$sqlQuery = "SELECT * FROM shipping_cost WHERE id = '".$_POST["shippingcostId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addShippingcost(){
	global $db;

	


	
	$insertQuery = "INSERT INTO shipping_cost (sales_id,concept,currencyrate,price_rmb,price_usd) 
	VALUES ('".$_POST["shippingcostSalesid"]."','".$_POST["senyefee"]."','".$_POST["senyefeeCR"]."', '".$_POST["senyefeeRMB"]."', '".$_POST["senyefeeUSD"]."')";
	
	$db->query($insertQuery);





	

	
	$insertQuery = "INSERT INTO shipping_cost (sales_id,concept,currencyrate,price_rmb,price_usd) 
	VALUES ('".$_POST["shippingcostSalesid"]."','".$_POST["delfinfee"]."','".$_POST["delfinfeeCR"]."', '".$_POST["delfinfeeRMB"]."', '".$_POST["delfinfeeUSD"]."')";
	
	$db->query($insertQuery);







	
	$insertQuery = "INSERT INTO shipping_cost (sales_id,concept,currencyrate,price_rmb,price_usd) 
	VALUES ('".$_POST["shippingcostSalesid"]."','".$_POST["warehousefee"]."','".$_POST["warehousefeeCR"]."', '".$_POST["warehousefeeRMB"]."', '".$_POST["warehousefeeUSD"]."')";
	
	$db->query($insertQuery);







	
	$insertQuery = "INSERT INTO shipping_cost (sales_id,concept,currencyrate,price_rmb,price_usd) 
	VALUES ('".$_POST["shippingcostSalesid"]."','".$_POST["loadingfee"]."','".$_POST["loadingfeeCR"]."', '".$_POST["loadingfeeRMB"]."', '".$_POST["loadingfeeUSD"]."')";
	
	$db->query($insertQuery);






	
	$insertQuery = "INSERT INTO shipping_cost (sales_id,concept,currencyrate,price_rmb,price_usd) 
	VALUES ('".$_POST["shippingcostSalesid"]."','".$_POST["invlegfee"]."','".$_POST["invlegfeeCR"]."', '".$_POST["invlegfeeRMB"]."', '".$_POST["invlegfeeUSD"]."')";
	
	$db->query($insertQuery);






	
	$insertQuery = "INSERT INTO shipping_cost (sales_id,concept,currencyrate,price_rmb,price_usd) 
	VALUES ('".$_POST["shippingcostSalesid"]."','".$_POST["pricelistfee"]."','".$_POST["pricelistfeeCR"]."', '".$_POST["pricelistfeeRMB"]."', '".$_POST["pricelistfeeUSD"]."')";
	
	$db->query($insertQuery);






	
	$insertQuery = "INSERT INTO shipping_cost (sales_id,concept,currencyrate,price_rmb,price_usd) 
	VALUES ('".$_POST["shippingcostSalesid"]."','".$_POST["expodecfee"]."','".$_POST["expodecfeeCR"]."', '".$_POST["expodecfeeRMB"]."', '".$_POST["expodecfeeUSD"]."')";
	
	$db->query($insertQuery);





	
	$insertQuery = "INSERT INTO shipping_cost (sales_id,concept,currencyrate,price_rmb,price_usd) 
	VALUES ('".$_POST["shippingcostSalesid"]."','".$_POST["colegfee"]."','".$_POST["colegfeeCR"]."', '".$_POST["colegfeeRMB"]."', '".$_POST["colegfeeUSD"]."')";
	
	$db->query($insertQuery);










	
}

public function updateShippingcost(){
	global $db;	
		
		$updateQuery = "UPDATE shipping_cost SET price_rmb = '".$_POST["rmb"]."', price_usd = '".$_POST["usd"]."' WHERE id ='".$_POST["updatesc-id"]."'";
		$isUpdated = $db->query($updateQuery);		
		
}

public function shippingcostDelete(){
	global $db;
	if($_POST["con"]) {
		$sqlDelete = "DELETE FROM shipping_cost WHERE id = '".$_POST["con"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->contaientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	}
  }
}
?>
 