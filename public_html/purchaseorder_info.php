<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Poinfo {
private $productTable = 'products';

//'".$_POST['clientcodeProductid']."'";
public function listPo(){
	global $db;
$openclose = 1;
	

	//SELECT * FROM purchaseorder P
//JOIN clients C ON C.id = P.clients_id WHERE P.openclose = 1 AND C.name LIKE "%Paco ar%"
  
	$sqlQuery = "SELECT P.id,P.clients_id,P.tp,P.finalized,C.name,V.name as vname FROM purchaseorder P JOIN clients C ON C.id = P.clients_id JOIN vendors V ON V.id = P.vendors_id ";
	
	if (!empty($_POST["filter"])){
		if ($_POST["filter"] === '1'){
		$sqlQuery .=" WHERE P.openclose = 0 AND P.finalized = 0 ";
	  }
	}
	if (!empty($_POST["filter"])){
		if ($_POST["filter"] === '2'){
		$sqlQuery .=" WHERE P.openclose = 0 AND P.finalized = 1 ";
	  }
	}
	
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND (C.name LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR P.tp LIKE "%'.$_POST["search"]["value"].'%"';	
		$sqlQuery .= 'OR V.name LIKE "%'.$_POST["search"]["value"].'%")';		
		//$sqlQuery .= ' OR clients_id LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY P.id DESC ';
	}

	
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	

	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT P.id,P.clients_id,P.tp,P.finalized FROM purchaseorder P
    JOIN clients C ON C.id = P.clients_id ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	

	$poData = array();	

    

	while($po = $db->fetch_assoc($result) ) {
		
		$poRows = array();
		$poRows[] = $po['id'];
		$poRows[] = $po['vname'];
		$poRows[] = $po['name'];
		$poRows[] = $po['tp'];
		if($po['finalized'] === '1'){
		 	$poRows[] = '<div class="btn-group">
                    <a href="edit_purchaseorder.php?id='.$po['id'].'" class="btn btn-succes btn-xs"  title="View" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';
             $poRows[] = 'Done';

		}else{
		$poRows[] = '<div class="btn-group">
                    <a href="edit_purchaseorder.php?id='.$po['id'].'" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';
              $poRows[] = 'Pending';     
}
/*
        $poRows[] = '<div class="btn-group">
                    <a href="delete_purchaseorder.php?id='.$po['id'].'" class="btn btn-danger btn-xs"  title="Close" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a> </div>';

*/
	   

		$poData[] = $poRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$poData
	);
	
	echo json_encode($output);
}
}
