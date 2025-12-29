<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Stinfo {
private $stTable = 'stockmovements';

//'".$_POST['clientcodeProductid']."'";
public function listStock(){
	global $db;		
	$sqlQuery = "SELECT * FROM stock s JOIN clients d where s.clients_id = d.id ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND (d.name LIKE "%'.$_POST["search"]["value"].'%" or s.codeclient LIKE "%'.$_POST["search"]["value"].'%") ';
		//$sqlQuery .= 'AND products_id "%'.$_POST["search"]["value"].'%" ';			
		//$sqlQuery .= ' OR clients_id LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY s.id DESC ';
	}
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT * FROM stock ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$stData = array();	


	
	while($st = $db->fetch_assoc($result) ) {
		$stRows = array();
		if ($st['qty'] > '0'){
		$stRows[] = $st['id'];
		$stRows[] = $st['products_id'];
		$stRows[] = $st['name'];
		$stRows[] = $st['codeclient'];
		$stRows[] = $st['qty'];
		$stData[] = $stRows;
	}
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$stData
	);
	
	echo json_encode($output);
}
}
?>


