<?php
require_once('includes/load.php');
$all_codeclients = find_all('clientcode');

class Clientcode {
private $codecliTable = 'clientcode';


 public function listClientcode($prid){
	global $db;		
	$sqlQuery = "SELECT * FROM clientcode WHERE products_id = '".$prid."'";
	$result = $db->query($sqlQuery);
    $sqlQuery1 = "SELECT * FROM clientcode WHERE products_id = '".$prid."'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$codecliData = array();	



	while($codecli = $db->fetch_assoc($result)){ 
		
		$codecliRows = array();
		$names = joinsqlname('clientcode','clients','clients_id','name',$codecli['clients_id']);
		$codecliRows[] = $codecli['clientcode'];
		$codecliRows[] = $names;
		$codecliRows[] = $codecli['products_id'];				
		$codecliRows[] = '<button type="button" name="update" id="'.$codecli["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$codecliRows[] = '<button type="button" name="delete" id="'.$codecli["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
		$codecliData[] = $codecliRows;

	}

	$output = array(
		"draw"	=>  intval($_POST["draw"]),
		"recordsTotal" 	=>  $numRows,
		"recordsFiltered" 	=>  $numRows,
		"data"    		=>  	$codecliData
	);
	
	echo json_encode($output);
}



//".$_POST["clientcodeId"]."
	public function getClientcode(){
		global $db;	
		if($_POST["clientcodeId"]) {
			$sqlQuery = "SELECT * FROM clientcode WHERE id = '".$_POST["clientcodeId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addClientcode(){
	global $db;
	$insertQuery = "INSERT INTO ".$this->codecliTable." (clientcode, clients_id, products_id) 
	VALUES ('".$_POST["clientcodeName"]."', '".$_POST["clientcodeClient"]."', '".$_POST["clientcodeProductid"]."')";
	
		//$insertQuery = "INSERT INTO clientcode (clientcode, clients_id, products_id) 
		//VALUES ('asd456', '1', '29')";
		//".$_POST["clientcodeClient"]."
	$db->query($insertQuery);
}

public function updateClientcode(){
	global $db;	
	if($_POST['clientcodeId']) {	
		$updateQuery = "UPDATE  clientcode SET clientcode = '".$_POST["clientcodeName"]."', clients_id = '".$_POST["clientcodeClient"]."', products_id = '".$_POST["clientcodeProductid"]."' WHERE id ='".$_POST["clientcodeId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
}

public function clientcodeDelete(){
	global $db;
	if($_POST["client"]) {
		$sqlDelete = "UPDATE clientcode SET openclose = '0' WHERE id = '".$_POST["client"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	}
  }
}
?>
