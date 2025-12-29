<?php
include('client_info.php');
$clientinfo = new Clientinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listClient') {
	$clientinfo->listclient();
}

?>
