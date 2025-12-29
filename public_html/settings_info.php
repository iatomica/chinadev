<?php
require_once('includes/load.php');
require_once('libs/PHPExcel/PHPExcel.php');

class Settingsinfo {
//private $productTable = 'products';

//'".$_POST['clientcodeProductid']."'";
public function listPricetype(){
	global $db;		
	$sqlQuery = "SELECT * FROM price_type WHERE openclose = '1' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'where pryce_type LIKE "%'.$_POST["search"]["value"].'%" ';
		//$sqlQuery .= 'OR id LIKE "%'.$_POST["search"]["value"].'%" ';			
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
    $sqlQuery1 = "SELECT * FROM price_type ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$pricetypeData = array();	



	while($pricetype = $db->fetch_assoc($result) ) {
		$pricetypeRows = array();
		$pricetypeRows[] = $pricetype['id'];
		$pricetypeRows[] = $pricetype['price_type'];
		$pricetypeRows[] = '<button type="button" name="update" id="'.$pricetype["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$pricetypeRows[] = '<button type="button" name="delete" id="'.$pricetype["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';

	   

		$pricetypeData[] = $pricetypeRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$pricetypeData
	);
	
	echo json_encode($output);
}
public function getPricetype(){
		global $db;	
		if($_POST["pricetypeId"]) {
			$sqlQuery = "SELECT * FROM price_type WHERE id = '".$_POST["pricetypeId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addPricetype(){
	global $db;
	$insertQuery = "INSERT INTO price_type (price_type,openclose) 
	VALUES ('".$_POST["pricetypeName"]."', '1')";
	
	$db->query($insertQuery);

}

public function updatePricetype(){
	global $db;	
	if($_POST['pricetypeId']) {	
		$updateQuery = "UPDATE  price_type SET price_type = '".$_POST["pricetypeName"]."' WHERE id ='".$_POST["pricetypeId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
}

public function pricetypeDelete(){
	global $db;
	
		$sqlDelete = "UPDATE price_type SET openclose = '0' WHERE id = '".$_POST["pricetype"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	
  }

public function listCurrency(){
	global $db;		
	$sqlQuery = "SELECT * FROM moneys WHERE openclose = '1' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'where pryce_type LIKE "%'.$_POST["search"]["value"].'%" ';
		//$sqlQuery .= 'OR id LIKE "%'.$_POST["search"]["value"].'%" ';			
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
    $sqlQuery1 = "SELECT * FROM moneys ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$currencyData = array();	



	while($currency = $db->fetch_assoc($result) ) {
		$currencyRows = array();
		$currencyRows[] = $currency['id'];
		$currencyRows[] = $currency['moneytype'];
		$currencyRows[] = '<button type="button" name="update" id="'.$currency["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$currencyRows[] = '<button type="button" name="delete" id="'.$currency["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';

	   

		$currencyData[] = $currencyRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$currencyData
	);
	
	echo json_encode($output);
}
public function getCurrency(){
		global $db;	
		if($_POST["currencyId"]) {
			$sqlQuery = "SELECT * FROM moneys WHERE id = '".$_POST["currencyId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addCurrency(){
	global $db;
	$insertQuery = "INSERT INTO moneys (moneytype,openclose) 
	VALUES ('".$_POST["currencyName"]."', '1')";
	
	$db->query($insertQuery);

}

public function updateCurrency(){
	global $db;	
	if($_POST['currencyId']) {	
		$updateQuery = "UPDATE  moneys SET moneytype = '".$_POST["currencyName"]."' WHERE id ='".$_POST["currencyId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
}

public function currencyDelete(){
	global $db;
	
		$sqlDelete = "UPDATE moneys SET openclose = '0' WHERE id = '".$_POST["currency"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	
  }


public function listPackagings(){
	global $db;		
	$sqlQuery = "SELECT * FROM packaging WHERE openclose = '1' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'where packagingtype LIKE "%'.$_POST["search"]["value"].'%" ';
		//$sqlQuery .= 'OR id LIKE "%'.$_POST["search"]["value"].'%" ';			
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
    $sqlQuery1 = "SELECT * FROM packaging ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$packagingsData = array();	



	while($packagings = $db->fetch_assoc($result) ) {
		$packagingsRows = array();
		$packagingsRows[] = $packagings['id'];
		$packagingsRows[] = $packagings['packagingtype'];
		$packagingsRows[] = '<button type="button" name="update" id="'.$packagings["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$packagingsRows[] = '<button type="button" name="delete" id="'.$packagings["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';

	   

		$packagingsData[] = $packagingsRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$packagingsData
	);
	
	echo json_encode($output);
}
public function getPackagings(){
		global $db;	
		if($_POST["packagingsId"]) {
			$sqlQuery = "SELECT * FROM packaging WHERE id = '".$_POST["packagingsId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addPackagings(){
	global $db;
	$insertQuery = "INSERT INTO packaging (packagingtype,openclose) 
	VALUES ('".$_POST["packagingsName"]."', '1')";
	
	$db->query($insertQuery);

}

public function updatePackagings(){
	global $db;	
	if($_POST['packagingsId']) {	
		$updateQuery = "UPDATE  packaging SET packagingtype = '".$_POST["packagingsName"]."' WHERE id ='".$_POST["packagingsId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
}

public function packagingsDelete(){
	global $db;
	
		$sqlDelete = "UPDATE packaging SET openclose = '0' WHERE id = '".$_POST["packagings"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	
  }

public function listUnits(){
	global $db;		
	$sqlQuery = "SELECT * FROM units WHERE openclose = '1' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'where unittype LIKE "%'.$_POST["search"]["value"].'%" ';
		//$sqlQuery .= 'OR id LIKE "%'.$_POST["search"]["value"].'%" ';			
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
    $sqlQuery1 = "SELECT * FROM units ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$unitsData = array();	



	while($units = $db->fetch_assoc($result) ) {
		$unitsRows = array();
		$unitsRows[] = $units['id'];
		$unitsRows[] = $units['unittype'];
		$unitsRows[] = '<button type="button" name="update" id="'.$units["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$unitsRows[] = '<button type="button" name="delete" id="'.$units["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';

	   

		$unitsData[] = $unitsRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$unitsData
	);
	
	echo json_encode($output);
}
public function getUnits(){
		global $db;	
		if($_POST["unitsId"]) {
			$sqlQuery = "SELECT * FROM units WHERE id = '".$_POST["unitsId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addUnits(){
	global $db;
	$insertQuery = "INSERT INTO units (unittype,openclose) 
	VALUES ('".$_POST["unitsName"]."', '1')";
	
	$db->query($insertQuery);

}

public function updateUnits(){
	global $db;	
	if($_POST['unitsId']) {	
		$updateQuery = "UPDATE  units SET unittype = '".$_POST["unitsName"]."' WHERE id ='".$_POST["unitsId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
}

public function unitsDelete(){
	global $db;
	
		$sqlDelete = "UPDATE units SET openclose = '0' WHERE id = '".$_POST["units"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	
  }




}