<?php
include('itemsrg.php');
$itemrg = new Itemrg();
if(!empty($_POST['action']) && $_POST['action'] == 'listItemrg') {
	$itemrg->listItemrg($_POST['rgid']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'addItemrg') {
	$itemrg->addItemrg();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getItemrg') {
	$itemrg->getItemrg();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateItemrg') {
	$itemrg->updateItemrg();
}
if(!empty($_POST['action']) && $_POST['action'] == 'ItemrgDelete') {
	$itemrg->ItemrgDelete();
}
/*
if(!empty($_POST['action-po']) && $_POST['action-po'] == 'processpo') {
	$itemrg->processpo($_POST["rg-code-qty"]);
}
if(!empty($_POST['action-po']) && $_POST['action-po'] == 'processpoUpdate') {
	$itemrg->processpoUpdate($_POST["rg-code-qty"]);
	
}
*/
?>
