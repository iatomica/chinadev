<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Vendorinfo {
private $vendorTable = 'vendors';

//'".$_POST['clientcodevendorid']."'";
public function listVendor(){
	global $db;		
	$sqlQuery = "SELECT * FROM vendors WHERE openclose = '1'";	
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND name LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR id LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR contact LIKE "%'.$_POST["search"]["value"].'%" ';		
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
    $sqlQuery1 = "SELECT * FROM vendors WHERE openclose = '1'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$vendorData = array();	



	while($vendor = $db->fetch_assoc($result) ) {
		
		$vendorRows = array();
		$vendorRows[] = $vendor['id']; 
		$vendorRows[] = utf8_encode($vendor['name']);
		$vendorRows[] = $vendor['address'];
		$vendorRows[] = $vendor['city'];
		$vendorRows[] = $vendor['phone'];
		$vendorRows[] = $vendor['website'];
		$vendorRows[] = $vendor['contact'];
		$vendorRows[] = $vendor['bus_number'];
		$vendorRows[] = $vendor['email'];
		$vendorRows[] = $vendor['bank_account'];
		$vendorRows[] = $vendor['bank'];
		$vendorRows[] = $vendor['beneficiary_name'];
		$vendorRows[] = $vendor['taxpayer'];

		if ($vendor['id'] === '0'){
			$vendorRows[] = '';
			$vendorRows[] = '';
		}
else{
			$vendorRows[] = '<div class="btn-group">
                    <a href="edit_vendors.php?id='.$vendor['id'].'" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';

			$vendorRows[] = '<div class="btn-group">
                    <a href="delete_vendors.php?id='.$vendor['id'].'" class="btn btn-danger btn-xs"  title="Close" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a> </div>';
}
		

		$vendorData[] = $vendorRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$vendorData
	);
	
	echo json_encode($output);
}
}

