<?php
require_once('includes/load.php');

			$query = "SELECT * FROM detail_sales WHERE products_id = ".$_POST["prid"]." AND sales_id = ".$_POST["salesid"]." AND uxb_sales =".$_POST["uxb"]." AND tp = ".$_POST["sales_tp"]."";
			$result = $db->query($query);
			//var_dump($db->num_rows($result) > 0);
			$return = $db->num_rows($result) > 0;
			
			echo json_encode($return);
		
?>
