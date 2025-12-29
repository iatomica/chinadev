<?php
include('codecli.php');
$clientcode1 = new Clientcode();
if(!empty($_POST['action']) && $_POST['action'] == 'listClientcode') {
	$clientcode1->listClientcode($_POST['prid']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'addClientcode') {
	$clientcode1->addClientcode();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getClientcode') {
	$clientcode1->getClientcode();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateClientcode') {
	$clientcode1->updateClientcode();
}
if(!empty($_POST['action']) && $_POST['action'] == 'clientcodeDelete') {
	$clientcode1->clientcodeDelete();
}
?>