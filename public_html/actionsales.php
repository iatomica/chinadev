<?php
include('itemssales.php');
$itemsales = new Itemsales(); 
if(!empty($_POST['action']) && $_POST['action'] == 'listItemsales') {
	$itemsales->listItemsales($_POST['salesid']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'addItemsales') {
	$itemsales->addItemsales();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getItemsales') {
	$itemsales->getItemsales();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateItemsales') {
	$itemsales->updateItemsales();
}
if(!empty($_POST['action']) && $_POST['action'] == 'ItemsalesDelete') {
	$itemsales->ItemsalesDelete();
}
