<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Rginfo {
//private $productTable = 'products';

//'".$_POST['clientcodeProductid']."'";
public function listRg(){
	global $db;		
	$sqlQuery = "SELECT R.id, C.name, V.name AS vname,R.finalized,R.date,R.numbers, R.expiration,R.observation FROM receivedgoods R JOIN vendors V ON V.id = R.vendors_id JOIN clients C ON C.id = clients_id WHERE R.openclose = '1' ";
	if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'AND (C.name LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR R.numbers LIKE "%'.$_POST["search"]["value"].'%"';	
		$sqlQuery .= 'OR V.name LIKE "%'.$_POST["search"]["value"].'%")';		
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
    $sqlQuery1 = "SELECT * FROM receivedgoods WHERE openclose = '1'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$rgData = array();	



	while($rg = $db->fetch_assoc($result) ) {
	
		$rgRows = array();
		$rgRows[] = $rg['id'];
		$rgRows[] = $rg['vname'];
		$rgRows[] = $rg['name'];
		$rgRows[] = $rg['date'];
		$rgRows[] = $rg['numbers'];
		$rgRows[] = $rg['expiration'];
		$rgRows[] = $rg['observation'];
		
		if ($rg['finalized'] === '1' ){
			$rgRows[] = 'Done';
			$button = '<div class="btn-group">
                    <a href="edit_receivedgoods.php?id='.$rg['id'].'" class="btn btn-succes btn-xs"  title="View" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';
		}

		if($rg['finalized'] === '0' ){
			$rgRows[] = 'Pending to submit';
			$button = '<div class="btn-group">
                    <a href="edit_receivedgoods.php?id='.$rg['id'].'" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';
		}

		$rgRows[] = $button;

                    /*
        $rgRows[] = '<div class="btn-group">
                    <a href="delete_receivedgoods.php?id='.$rg['id'].'" class="btn btn-danger btn-xs"  title="Close" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a> </div>';

*/
	   

		$rgData[] = $rgRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$rgData
	);
	
	echo json_encode($output);
}
}

