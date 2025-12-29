<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Clientinfo {
private $clientTable = 'clients';

//'".$_POST['clientcodeclientid']."'";
public function listClient(){
	global $db;		
	$sqlQuery = "SELECT * FROM clients WHERE openclose = '1'";	
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND name LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR id LIKE "%'.$_POST["search"]["value"].'%" ';			
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
    $sqlQuery1 = "SELECT * FROM clients WHERE openclose = '1'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$clientData = array();	



	while($client = $db->fetch_assoc($result) ) {
		
		$clientRows = array();
		$clientRows[] = $client['id']; 
		$clientRows[] = $client['name'];
		$clientRows[] = $client['phone'];
		$clientRows[] = $client['contact'];	

		if($client['id'] === '0'){
			$clientRows[] = '';	
			$clientRows[] = '';	
		
		}
		else{
			$clientRows[] = '<div class="btn-group">
                    <a href="edit_clients.php?id='.$client['id'].'" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';

			$clientRows[] = '<div class="btn-group">
                    <a href="delete_clients.php?id='.$client['id'].'" class="btn btn-danger btn-xs"  title="Close" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a> </div>';

		}
		
		$clientData[] = $clientRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$clientData
	);
	
	echo json_encode($output);
}
}

