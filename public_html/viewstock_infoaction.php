<?php
include('viewstock_info.php');
$stinfo = new Stinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listStock') {
	$stinfo->listStock();
}

?>

