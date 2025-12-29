<?php
require_once('includes/load.php');

 			
			$query = "SELECT * FROM detail_po WHERE products_id = ".$_POST["idproducts"]." AND purchaseorder_id =".$_POST["tp_id"]."";
			$result = $db->query($query);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
						
		
?>
