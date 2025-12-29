<?php
require_once('includes/load.php');

			$query = "SELECT * FROM clientcode WHERE products_id = ".$_POST["prid"]." AND openclose = '1'";
			$result = $db->query($query);
			//var_dump($db->num_rows($result) > 0);
			$return = $db->num_rows($result) > 0;
			
			echo json_encode($return);
		
?>