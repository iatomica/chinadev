<?php
include('itemsshippingcost.php');
$shippingcost = new Shippingcost();
if(!empty($_POST['action']) && $_POST['action'] == 'listShippingcost') {
	$shippingcost->listShippingcost($_POST['saleid']);
}
if(!empty($_POST['action2']) && $_POST['action2'] == 'addShippingcost') {
	$shippingcost->addShippingcost();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getShippingcost') {
	$shippingcost->getShippingcost();
}
if(!empty($_POST['action5']) && $_POST['action5'] == 'updateShippingcost') {
	$shippingcost->updateShippingcost();
}
if(!empty($_POST['action']) && $_POST['action'] == 'shippingcostDelete') {
	$shippingcost->shippingcostDelete();
}
?>
