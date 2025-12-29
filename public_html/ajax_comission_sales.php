<?php
require_once('includes/load.php');
if(isset($_POST["client"]))  
 {  
 			
			$query = "SELECT * FROM clients WHERE id = ".$_POST["client"]."";
			$result = $db->query($query);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
						
		}
?>