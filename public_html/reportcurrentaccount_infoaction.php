<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include('reportcurrentaccount_info.php');
$itemca = new Itemca();
if(!empty($_POST['action']) && $_POST['action'] == 'listItemca') {
	$itemca->listItemca($_POST["client"],$_POST['vendor'],$_POST['vendor_or_client']);
}

if(!empty($_POST['action']) && $_POST['action'] == 'getVendorca') {
	$itemca->getVendorca();
}


?>