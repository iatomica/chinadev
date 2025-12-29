<?php
require_once('includes/load.php');

// Validar parámetros requeridos
if (!isset($_POST['prid'], $_POST['smid'])) {
	echo json_encode(false);
	exit;
}

$product_id = (int)$_POST['prid'];
$stockmovement_id = (int)$_POST['smid'];

if ($product_id <= 0 || $stockmovement_id <= 0) {
	echo json_encode(false);
	exit;
}

$query = "SELECT * FROM detail_sm WHERE products_id = {$product_id} AND stockmovements_id = {$stockmovement_id}";
$result = $db->query($query);
$return = $db->num_rows($result) > 0;

echo json_encode($return);

?>

