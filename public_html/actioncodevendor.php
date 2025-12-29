<?php
include('codeven.php');
$vendorcode = new Vendorcode();
if(!empty($_POST['action']) && $_POST['action'] == 'listVendorcode') {
	$vendorcode->listVendorcode($_POST['prid']);
}
if(!empty($_POST['action1']) && $_POST['action1'] == 'addVendorcode') {
	$vendorcode->addVendorcode();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getVendorcode') {
	$vendorcode->getVendorcode();
}
if(!empty($_POST['action1']) && $_POST['action1'] == 'updateVendorcode') {
	$vendorcode->updateVendorcode();
}
if(!empty($_POST['action']) && $_POST['action'] == 'vendorcodeDelete') {
	$vendorcode->vendorcodeDelete();
}
?>