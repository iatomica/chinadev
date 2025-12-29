<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Itemca {
private $smTable = 'stockmovements';

//'".$_POST['clientcodeProductid']."'";
public function listItemca(){
	global $db;	
	$wer = '1';

	if ($_POST["vendor"] === '0' || $_POST["client"] === '0') {


	if ($_POST["vendor"] === '0'){
		$sqlQuery = "SELECT * FROM currentaccount c JOIN vendors v ON c.vendors_id = v.id WHERE c.id = (SELECT max(id) from currentaccount b WHERE c.vendors_id = b.vendors_id)";
		
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT * FROM currentaccount c JOIN vendors v ON c.vendors_id = v.id WHERE c.id = (SELECT max(id) from currentaccount b WHERE c.vendors_id = b.vendors_id)";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$vendorCaData = array();	



	while($vendorCa = $db->fetch_assoc($result) ) {
		
		if($vendorCa['balance'] !== '0.00'){
		$currency = select_currency($vendorCa['moneys_id']);
		$vendorCaRows = array();
		
		$vendorCaRows[] = $vendorCa['id']; 
		$vendorCaRows[] = $vendorCa['name'];
		$vendorCaRows[] = $vendorCa['balance'];
		$vendorCaRows[] = $vendorCa['bank'];
		$vendorCaRows[] = $vendorCa['bank_account'];
		$vendorCaRows[] = $vendorCa['beneficiary_name'];
		$vendorCaRows[] = $currency['moneytype'];
		$vendorCaRows[] = '';
		$vendorCaRows[] = '';
		$vendorCaRows[] = '';
		$vendorCaData[] = $vendorCaRows;
	}
		



		
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$vendorCaData
	);
	
	echo json_encode($output);
}

if ($_POST["client"] === '0'){
	$sqlQuery = "SELECT * FROM currentaccount c JOIN clients v ON c.clients_id = v.id WHERE c.id = (SELECT max(id) from currentaccount b WHERE c.clients_id = b.clients_id)";
		
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT * FROM currentaccount c JOIN clients v ON c.clients_id = v.id WHERE c.id = (SELECT max(id) from currentaccount b WHERE c.clients_id = b.clients_id)";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$clientsCAData = array();	



	while($clientsCA = $db->fetch_assoc($result) ) {
		if($clientsCA['balance'] !== '0.00'){
		$currency = select_currency($clientsCA['moneys_id']);
		$clientsCARows = array();
		$clientsCARows[] = $clientsCA['id']; 
		$clientsCARows[] = $clientsCA['name'];
		$clientsCARows[] = $clientsCA['balance'];
		$clientsCARows[] = $clientsCA['taxpayer'];
		$clientsCARows[] = $currency['moneytype'];
		$clientsCARows[] = '';
		$clientsCARows[] = '';
		$clientsCARows[] = '';
		$clientsCARows[] = '';
		$clientsCARows[] = '';
		$clientsCAData[] = $clientsCARows;
	}
		



		
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$clientsCAData
	);
	
	echo json_encode($output);


	}


}

else
{
	


	$sqlQuery = "SELECT * FROM currentaccount ";
	
	
		if ($_POST["vendor_or_client"] === '1'){

	 $sqlQuery.=" WHERE vendors_id = ".$_POST["vendor"]." ";
	}
	if ($_POST["vendor_or_client"] === '2'){

	 $sqlQuery.=" WHERE clients_id = ".$_POST["client"]." "; 
	}
	if($_POST["movdate"] === '2'){
		$sqlQuery.=" AND date BETWEEN '".$_POST["datemov"]."' AND '".$_POST["datemovto"]."' ";
	}

	if ($_POST["all"] === 'false'){
	 $sqlQuery .= "AND (";
	}
	
    $count = 0;
	foreach ($_POST["checkarray"] as $check) {
		
		if ($count === 0){

			$sqlQuery.= $check;
		}

		else
		{
			
			$sqlQuery.=" or ".$check." ";
		
		}
		
		$count++;

	}
	

	

    if ($_POST["all"] === 'false'){
	$sqlQuery .= ") ";
    }

    


	if(!empty($_POST["search"]["value"])){
		$sv = $db->escape($_POST["search"]["value"]);
		$sqlQuery .= " AND (CAST(id AS CHAR) LIKE '%$sv%' OR observation LIKE '%$sv%') ";
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY id ASC ';
	}
	if($_POST["movdate"] === '1'){
		$sqlQuery.=" LIMIT 15";
	}
	
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT * FROM currentaccount";
    if ($_POST["vendor_or_client"] === '1'){

	 $sqlQuery1 .=" WHERE vendors_id = ".$_POST["vendor"]." ";
	}
    if ($_POST["vendor_or_client"] === '2'){

	 $sqlQuery1 .=" WHERE clients_id = ".$_POST["client"]." ";
	}

	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$caData = array();	

if ($_POST["vendor_or_client"] === '1'){

	
	while($ca = $db->fetch_assoc($result) ) {
		$catype = name_currentacount_type($ca['currentaccount_type_id']);
		$carg = name_receivedgoods($ca['receivedgoods_id']);

		$caRows = array();

		
		$caRows[] = $ca['id'];
		$caRows[] = $ca['expiration'];
		$caRows[] = $catype;
		$caRows[] = $carg;
		$caRows[] = 'Current Account';
		
		if($ca['debit'] != '0'){
			$caRows[] = $ca['debit'];
		}
		else{
			$caRows[] = "";
		}
		if($ca['credit'] != '0'){
			$caRows[] = $ca['credit'];
		}
		else{
			$caRows[] = "";
		}

		$caRows[] = $ca['balance'];
		$caRows[] = $ca['observation'];
		$caRows[] = $ca['date'];
		
		



		
		
		$caData[] = $caRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$caData

	);
	
	echo json_encode($output);

   }

   if ($_POST["vendor_or_client"] === '2'){

   	while($ca = $db->fetch_assoc($result) ) {
		$catype = name_currentacount_type($ca['currentaccount_type_id']);
		$carg = name_sales($ca['sales_id']);

		$caRows = array();

		
		$caRows[] = $ca['id'];
		$caRows[] = $ca['date'];
		$caRows[] = $catype;
		$caRows[] = $carg;
		$caRows[] = 'Current Account';
		
		if($ca['debit'] != '0'){
			$caRows[] = $ca['debit'];
		}
		else{
			$caRows[] = "";
		}
		if($ca['credit'] != '0'){
			$caRows[] = $ca['credit'];
		}
		else{
			$caRows[] = "";
		}

		$caRows[] = $ca['balance'];
		$caRows[] = $ca['observation'];
		$caRows[] = $ca['expiration'];
		
		



		
		
		$caData[] = $caRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$caData
	);
	
	echo json_encode($output);



}

}

}

public function getVendorca(){
		global $db;	
		

		if($_POST["client"] === '0' || $_POST["client"] != '') {
			$sqlQuery = "SELECT * FROM clients WHERE id = '".$_POST["client"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
		
		if($_POST["vendor"] ==='0' || $_POST["vendor"] != '') {
			$sqlQuery = "SELECT * FROM vendors WHERE id = '".$_POST["vendor"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}
}


?>

