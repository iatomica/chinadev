<?php
include('itemscontainer.php');
$container = new Container();
if(!empty($_POST['action']) && $_POST['action'] == 'listContainer') {
	$container->listContainer($_POST['saleid']);
}
if(!empty($_POST['action1']) && $_POST['action1'] == 'addContainer') {
	$container->addContainer();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getContainer') {
	$container->getContainer();
}
if(!empty($_POST['action1']) && $_POST['action1'] == 'updateContainer') {
	$container->updateContainer();
}
if(!empty($_POST['action']) && $_POST['action'] == 'containerDelete') {
	$container->containerDelete();
}
?>