<?php
include('settings_info.php');
$settingsinfo = new Settingsinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listPricetype') {
	$settingsinfo->listPricetype();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addPricetype') {
	$settingsinfo->addPricetype();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getPricetype') {
	$settingsinfo->getPricetype();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updatePricetype') {
	$settingsinfo->updatePricetype();
}
if(!empty($_POST['action']) && $_POST['action'] == 'pricetypeDelete') {
	$settingsinfo->pricetypeDelete();
}
if(!empty($_POST['action']) && $_POST['action'] == 'listCurrency') {
	$settingsinfo->listCurrency();
}
if(!empty($_POST['action1']) && $_POST['action1'] == 'addCurrency') {
	$settingsinfo->addCurrency();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getCurrency') {
	$settingsinfo->getCurrency();
}
if(!empty($_POST['action1']) && $_POST['action1'] == 'updateCurrency') {
	$settingsinfo->updateCurrency();
}
if(!empty($_POST['action']) && $_POST['action1'] == 'currencyDelete') {
	$settingsinfo->currencyDelete();
}
if(!empty($_POST['action2']) && $_POST['action2'] == 'listUnits') {
	$settingsinfo->listUnits();
}
if(!empty($_POST['action2']) && $_POST['action2'] == 'addUnits') {
	$settingsinfo->addUnits();
}
if(!empty($_POST['action2']) && $_POST['action2'] == 'getUnits') {
	$settingsinfo->getUnits();
}
if(!empty($_POST['action2']) && $_POST['action2'] == 'updateUnits') {
	$settingsinfo->updateUnits();
}
if(!empty($_POST['action2']) && $_POST['action2'] == 'unitsDelete') {
	$settingsinfo->unitsDelete();
}
if(!empty($_POST['action3']) && $_POST['action3'] == 'listPackagings') {
	$settingsinfo->listPackagings();
}
if(!empty($_POST['action3']) && $_POST['action3'] == 'addPackagings') {
	$settingsinfo->addPackagings();
}
if(!empty($_POST['action3']) && $_POST['action3'] == 'getPackagings') {
	$settingsinfo->getPackagings();
}
if(!empty($_POST['action3']) && $_POST['action3'] == 'updatePackagings') {
	$settingsinfo->updatePackagings();
}
if(!empty($_POST['action3']) && $_POST['action3'] == 'packagingsDelete') {
	$settingsinfo->packagingsDelete();
}


?>
