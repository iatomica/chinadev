<?php
require_once('includes/load.php');
$all_contaients = find_all('Container');

class Container {
private $contaiTable = 'container_sales';


 public function listContainer($saleid){
	global $db;		
	$sqlQuery = "SELECT * FROM container_sales WHERE sales_id = '".$saleid."'";
	$result = $db->query($sqlQuery);
    $sqlQuery1 = "SELECT * FROM container_sales WHERE sales_id = '".$saleid."'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$contaiData = array();	



	while($contai = $db->fetch_assoc($result)){  
		
		$contaiRows = array();
		$contaiRows[] = $contai['name'];
		$contaiRows[] = $contai['sealn'];
		//$contaiRows[] = $contai['products_id'];				
		$contaiRows[] = '<button type="button" name="update" id="'.$contai["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$contaiRows[] = '<button type="button" name="delete" id="'.$contai["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
		$contaiData[] = $contaiRows;

	}

	$output = array(
		"draw"	=>  intval($_POST["draw"]),
		"recordsTotal" 	=>  $numRows,
		"recordsFiltered" 	=>  $numRows,
		"data"    		=>  	$contaiData
	);
	
	echo json_encode($output);
}



//".$_POST["ContainerId"]."
	public function getContainer(){
		global $db;	
		if($_POST["containerId"]) {
			$sqlQuery = "SELECT * FROM container_sales WHERE id = '".$_POST["containerId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addContainer(){
	global $db;
	$insertQuery = "INSERT INTO ".$this->contaiTable." (name,sealn, sales_id) 
	VALUES ('".$_POST["ContainerName"]."','".$_POST["ContainerSealn"]."', '".$_POST["ContainerSalesid"]."')";
	
	$db->query($insertQuery);
}

public function updateContainer(){
	global $db;	
	if($_POST['container-id']) {	
		$updateQuery = "UPDATE container_sales SET name = '".$_POST["ContainerName"]."',sealn ='".$_POST["ContainerSealn"]."', sales_id = '".$_POST["ContainerSalesid"]."' WHERE id ='".$_POST["container-id"]."'";
		$isUpdated = $db->query($updateQuery);	
	}	
}

public function containerDelete(){
	global $db;
	if($_POST["con"]) {
		$sqlDelete = "DELETE FROM container_sales WHERE id = '".$_POST["con"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->contaientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	}
  }
}
?>