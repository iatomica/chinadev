<?php
require_once('includes/load.php');
require_once('libs/PHPExcel/PHPExcel.php');

class Settingscompanyinfo {
//private $productTable = 'products';

//'".$_POST['clientcodeProductid']."'";
public function listCompany(){
	global $db;		
	$sqlQuery = "SELECT * FROM concept_company WHERE openclose = '1' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'where concept_name LIKE "%'.$_POST["search"]["value"].'%" ';
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
   
	$companyData = array();	



	while($company = $db->fetch_assoc($result) ) {
		$companyRows = array();
		$companyRows[] = $company['id'];
		$companyRows[] = $company['concept_name'];
		$companyRows[] = '<button type="button" name="update" id="'.$company["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$companyRows[] = '<button type="button" name="delete" id="'.$company["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';

	   

		$companyData[] = $companyRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$companyData
	);
	
	echo json_encode($output);
}
public function getCompany(){
		global $db;	
		if($_POST["companyId"]) {
			$sqlQuery = "SELECT * FROM concept_company WHERE id = '".$_POST["companyId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addCompany(){
	global $db;
	$insertQuery = "INSERT INTO concept_company (concept_name,openclose) 
	VALUES ('".$_POST["companyName"]."', '1')";
	
	$db->query($insertQuery);

}

public function updateCompany(){
	global $db;	
	if($_POST['companyId']) {	
		$updateQuery = "UPDATE  concept_company SET concept_name = '".$_POST["companyName"]."' WHERE id ='".$_POST["companyId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
}

public function companyDelete(){
	global $db;
	
		$sqlDelete = "UPDATE concept_company SET openclose = '0' WHERE id = '".$_POST["company"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	
  }

}
?>
