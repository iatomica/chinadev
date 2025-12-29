<?php
include('customerstock_info.php');
$stinfo = new Stinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listCustomerStock') {
	$stinfo->listCustomerStock();
}

?>

