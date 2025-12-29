<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Vendorcode {
private $codevenTable = 'vendorcode';

//'".$_POST['clientcodeProductid']."'";
public function listVendorcode($prid){
	global $db;		
	$sqlQuery = "SELECT * FROM vendorcode WHERE products_id = '".$prid."' AND openclose = '1'";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND vendorcode LIKE "%'.$_POST["search"]["value"].'%" ';
		//$sqlQuery .= ' OR clientcode LIKE "%'.$_POST["search"]["value"].'%" ';			
		//$sqlQuery .= ' OR clients_id LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY id DESC ';
	}
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT * FROM vendorcode WHERE products_id = '".$prid."'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$codevenData = array();	



	while($codeven = $db->fetch_assoc($result) ) {
		
		$codevenRows = array();
		$names = joinsqlname('vendorcode','vendors','vendors_id','name',$codeven['vendors_id']); 	
		//$codecliRows[] = $codecli['id'];
		$codevenRows[] = $codeven['vendorcode'];
		$codevenRows[] = $names;		
		//$codevenRows[] = $codecli['products_id'];				
		$codevenRows[] = '<button type="button" name="update" id="'.$codeven["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$codevenRows[] = '<button type="button" name=" delete" id="'.$codeven["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
		$codevenData[] = $codevenRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$codevenData
	);
	
	echo json_encode($output);
}



//".$_POST["clientcodeId"]."
	public function getVendorcode(){
		global $db;	
		if($_POST["vendorcodeId"]) {
			$sqlQuery = "SELECT * FROM vendorcode WHERE id = '".$_POST["vendorcodeId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addVendorcode(){
	global $db;
	$insertQuery = "INSERT INTO vendorcode (vendorcode, vendors_id, products_id) 
	VALUES ('".$_POST["vendorcodeName"]."', '".$_POST["vendorcodeVendor"]."', '".$_POST["vendorcodeProductid"]."')";
	
		//$insertQuery = "INSERT INTO clientcode (clientcode, clients_id, products_id) 
		//VALUES ('asd456', '1', '29')";
		//".$_POST["clientcodeClient"]."
	$db->query($insertQuery);
}

public function updateVendorcode(){
	global $db;	
	if($_POST['vendorcodeId']) {	
		$updateQuery = "UPDATE  vendorcode SET vendorcode = '".$_POST["vendorcodeName"]."', vendors_id = '".$_POST["vendorcodeVendor"]."', products_id = '".$_POST["vendorcodeProductid"]."' WHERE id ='".$_POST["vendorcodeId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
}

public function vendorcodeDelete(){
	global $db;
	if($_POST["vendor"]) {
		$sqlDelete = "UPDATE vendorcode SET openclose = '0' WHERE id = '".$_POST["vendor"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	}
  }
}
?>