<?php
require_once('includes/load.php');

 			$date = date();
		$query = "INSERT INTO company_movements (observation,price,concept_company_id,date) VALUES ('".$_POST["observation"]."','".$_POST["price"]."','".$_POST["concept"]."','".$_POST["strDate"]."')";
			$db->query($query);
			
				
						
		
?>
