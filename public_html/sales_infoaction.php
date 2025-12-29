<?php
include('sales_info.php');
$salesinfo = new Salesinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listSales') {
	$salesinfo->listSales();
}
if(!empty($_POST['action']) && $_POST['action'] == 'downloadSales') {
	$salesinfo->downloadSales();
}

?>

