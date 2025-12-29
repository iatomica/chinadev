<?php
include('prueba_info.php');
$des = new Desaprob();
if(!empty($_POST['action']) && $_POST['action'] == 'desaprobar') {
	$des->desaprobar();
}

?>
