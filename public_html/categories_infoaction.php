<?php
include('categories_info.php');
$categoriesinfo = new Categoriesinfo();
if(!empty($_POST['action']) && $_POST['action'] == 'listCategories') {
	$categoriesinfo->listCategories();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getSubcat') {
	$categoriesinfo->getSubcat();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getCat') {
	$categoriesinfo->getCat();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateCat') {
	$categoriesinfo->updateCat();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateSubcat') {
	$categoriesinfo->updateSubcat();
}

if(!empty($_POST['action']) && $_POST['action'] == 'catDelete') {
	$categoriesinfo->catDelete();
}

if(!empty($_POST['action']) && $_POST['action'] == 'subcatDelete') {
	$categoriesinfo->subcatDelete();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addSubcat') {
	$categoriesinfo->addSubcat($_POST['subcatIdAdd']);
}

if(!empty($_POST['actionDel']) && $_POST['actionDel'] == 'subcatDeleteRes') {
	$categoriesinfo->subcatDeleteRes();
}

if(!empty($_POST['actionDel']) && $_POST['actionDel'] == 'catDeleteRes') {
	$categoriesinfo->catDeleteRes();
}

?>

