<?php
include('export_info.php');
$exp_info = new Export_info();
if(!empty($_POST['action']) && $_POST['action'] == 'exportAccounts') {
	$exp_info->exportAccounts();
}

if(!empty($_POST['action']) && $_POST['action'] == 'exportProducts') {
	$exp_info->exportProducts();
}
if(!empty($_POST['action']) && $_POST['action'] == 'exportMakers') {
	$exp_info->exportMakers();
}


?>