<?php
  require_once('includes/load.php');
?>

<?php 

$customer = $_POST['client'];

$query = "select v.name AS vname, v.id AS vid from purchaseorder p JOIN vendors v ON v.id = p.vendors_id JOIN clients c ON c.id = p.clients_id WHERE p.finalized = '0' AND c.id = ".$customer."";

$result = $db->query($query);


$data = array();	



	while($dat = $db->fetch_assoc($result)){ 
		
		$dataRows = array();
		$dataRows['vname'] = $dat['vname'];
		$dataRows['vid'] = $dat['vid'];
		
		$data[] = $dataRows;



	}
	/*
		$output = array(
		"data"    		=>  	$data
	);
	*/
	echo json_encode($data);	
			

 

?>

