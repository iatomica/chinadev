<?php
include('itemspo.php');
$itempo = new Itempo();
if(!empty($_POST['action']) && $_POST['action'] == 'listItempo') {
	$itempo->listItempo($_POST['poid']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'addItempo') {
	$itempo->addItempo();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getItempo') {
	$itempo->getItempo();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateItempo') {
	$itempo->updateItempo();
}
if(!empty($_POST['action']) && $_POST['action'] == 'ItempoDelete') {
	$itempo->ItempoDelete();
}
if(!empty($_POST['action']) && $_POST['action'] == 'changeStatusItempo') {
	$itempo->changeStatusItempo();
}


?>