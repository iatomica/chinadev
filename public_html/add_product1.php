<?php
require_once('includes/load.php'); 
     $query = "INSERT INTO products VALUES (DEFAULT,DEFAULT,DEFAULT,'0',DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,'1',DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT)";
     $db->query($query);
     $id1='hola';
     $id = obtaing_max_id_row('products');

     echo json_encode($id);
 ?>
