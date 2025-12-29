<?php
include('vendor_info.php');
$vendorinfo = new Vendorinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listVendor') {
	$vendorinfo->listVendor();
}

?>
