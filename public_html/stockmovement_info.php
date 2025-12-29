<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Sminfo {
private $smTable = 'stockmovements';

//'".$_POST['clientcodeProductid']."'";
public function listSm(){
	global $db;		
	$sqlQuery = "SELECT * FROM stockmovements ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND desc_english LIKE "%'.$_POST["search"]["value"].'%" ';
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
    $sqlQuery1 = "SELECT * FROM stockmovements ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$smData = array();	


	
	while($sm = $db->fetch_assoc($result) ) {
		
		$smRows = array();
		$stock_mov_type_name = obtaing_name_smt($sm['movements_type_id']);
		$smRows[] = $sm['id'];
		$smRows[] = $stock_mov_type_name;
		if($sm['movements_type_id'] === '1'){
			$smRows[] = $sm['stock_deposit_id'];
		}
		elseif($sm['movements_type_id'] === '2'){
			$smRows[] = ''.$sm['stock_deposit_id'].' ---> '.$sm['stock_deposit_to_id'].'';

		}
		$smRows[] = $sm['observation'];
		$smRows[] = $sm['datetime'];


		
		$smRows[] = '<div class="btn-group">
                    <a href="edit_stockmovement.php?id='.$sm['id'].'" class="btn btn-info btn-xs"  title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';
        if ($sm['finalized'] === '1'){
        	$smRows[] = 'Done';
        }
		else
		{
			$smRows[] = 'Pending';
		}

		$smData[] = $smRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$smData
	);
	
	echo json_encode($output);
}
}
?>

