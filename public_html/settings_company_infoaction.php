<?php
include('settings_company_info.php');
$settingscompanyinfo = new Settingscompanyinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listCompany') {
	$settingscompanyinfo->listCompany();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addCompany') {
	$settingscompanyinfo->addCompany();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getCompany') {
	$settingscompanyinfo->getCompany();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateCompany') {
	$settingscompanyinfo->updateCompany();
}
if(!empty($_POST['action']) && $_POST['action'] == 'companyDelete') {
	$settingscompanyinfo->companyDelete();
}

?>
