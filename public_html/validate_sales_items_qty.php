<?php
require_once('includes/load.php');

			$query = "SELECT SUM(partial_invoice) FROM detail_rg WHERE products_id = ".$_POST["prid"]." AND purchaseorder_id = ".$_POST["salestp"]." AND invoiced = 3";
			$result = $db->query($query);
			//var_dump($db->num_rows($result) > 0);
			$row = $db->fetch_assoc($result);

			$query1 = "SELECT SUM(qty_rg) FROM detail_rg WHERE products_id = ".$_POST["prid"]." AND purchaseorder_id = ".$_POST["salestp"]." AND invoiced = 0";
			$result1 = $db->query($query1);
			//var_dump($db->num_rows($result) > 0);
			$row1 = $db->fetch_assoc($result1);

			$qty_total = $row['SUM(partial_invoice)'] + $row1['SUM(qty_rg)'];
			$qty_sale = $_POST["sales_qty"];
			if ($qty_sale > $qty_total){

				$return = true;

			}
			else{
				
				$return = $qty_total;
			
			}


			

			


			//$return = $db->num_rows($result) > 0;
			
			echo json_encode($return);
		
?>
