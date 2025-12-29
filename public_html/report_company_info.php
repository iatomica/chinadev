<?php
require_once('includes/load.php');

class itemCompany {

//'".$_POST['clientcodeProductid']."'";
public function listCompany(){

    global $db;		
	$sqlQuery = "SELECT * FROM company_movements co JOIN concept_company C ON C.id = co.concept_company_id WHERE date BETWEEN '".$_POST["datestart"]."' AND '".$_POST["dateend"]."' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND C.concept_name LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR co.price LIKE "%'.$_POST["search"]["value"].'%" ';			
		//$sqlQuery .= ' OR clients_id LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY co.id DESC ';
	}
	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT * FROM company_movements co JOIN concept_company C ON C.id = co.concept_company_id WHERE date BETWEEN '".$_POST["datestart"]."' AND '".$_POST["dateend"]."' ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$companyData = array();	


	
	while($company = $db->fetch_assoc($result) ) {
		
		$companyRows = array();
		
		$companyRows[] = $company['id'];
		$companyRows[] = $company['date'];
		$companyRows[] = $company['concept_name'];
		$companyRows[] = $company['observation'];
		$companyRows[] = $company['price'];
		



		
    
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
}
?>
