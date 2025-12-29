<?php
include('stockmovement_info.php');
$sminfo = new Sminfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listSm') {
	$sminfo->listSm();
}

?>
