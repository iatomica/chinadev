<?php
include('product_info.php');
$productinfo = new Productinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listProduct') {
	$productinfo->listProduct();
}
if(!empty($_POST['action']) && $_POST['action'] == 'listProduct1') {
	$productinfo->listProduct1();
}

?>
