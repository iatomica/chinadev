<?php
include('receivedgoods_info.php');
$rginfo = new Rginfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listRg') {
	$rginfo->listRg();
}

?>


