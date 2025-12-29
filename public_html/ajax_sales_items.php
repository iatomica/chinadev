<?php
require_once('includes/load.php');
if(isset($_POST["idproduct"]))
 {  
			$query = "SELECT * FROM detail_rg WHERE purchaseorder_id = ".$_POST["sales_tp"]." AND products_id = ".$_POST["idproduct"]." AND (invoiced = 0 OR invoiced = 3)";
			$result = $db->query($query);
			$row = $db->fetch_assoc($result);
			echo json_encode($row); 
						
		}
?>