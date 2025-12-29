<?php
require_once('includes/load.php');
if(isset($_POST["idproduct"]))  
 {  
			$query = "SELECT * FROM products WHERE id = ".$_POST["idproduct"]."";
			$result = $db->query($query);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
						
		}
?>

