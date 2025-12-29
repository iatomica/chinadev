<?php
require_once('includes/load.php');

			$query = "SELECT * FROM detail_po WHERE products_id = ".$_POST["prid"]." AND purchaseorder_id = ".$_POST["poid"]."";
			$result = $db->query($query);
			//var_dump($db->num_rows($result) > 0);
			$return = $db->num_rows($result) > 0;
			
			echo json_encode($return);
		
?>
