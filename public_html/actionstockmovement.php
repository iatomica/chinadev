<?php
include('itemssm.php');
$itemsm = new Itemsm();
if(!empty($_POST['action']) && $_POST['action'] == 'listItemsm') {
	$itemsm->listItemsm($_POST['smid']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'addItemsm') {
	$itemsm->addItemsm();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getItemsm') {
	$itemsm->getItemsm();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateItemsm') {
	$itemsm->updateItemsm();
}
if(!empty($_POST['action']) && $_POST['action'] == 'ItemsmDelete') {
	$itemsm->ItemsmDelete();
}


?>


