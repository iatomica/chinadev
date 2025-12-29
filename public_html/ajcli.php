<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
  $all_clients = find_all('clients');
?>

<?php

if(isset($_POST["codeclient"], $_POST["clients_id"],$_POST["products_id"]))
{
 
 $codeclient = $db->escape($_POST["codeclient"]);
 $clients_id = $db->escape($_POST["clients_id"]);
 $products_id = $db->escape($_POST["products_id"]);
 
 $query = "INSERT INTO clientcode (clientcode, clients_id, products_id) VALUES ('{$codeclient}', '{$clients_id}', '{$products_id}')";
 if($db->query($query))
 {
  echo 'Data Inserted';
 }
}

?>
