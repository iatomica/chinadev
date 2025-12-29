<?php
include('purchaseorder_info.php');
$poinfo = new Poinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listPo') {
	$poinfo->listPo();
}

?>

