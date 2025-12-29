<?php
// ARCHIVO DE ACCIÓN SIMPLE PARA FUNCIONES OPTIMIZADAS
// Maneja las llamadas AJAX de DataTables

include('products_rd_info.php');

// Crear instancia de la clase
$productrdinfo = new ProductsrdnfoSimple();

// Manejar acciones según el POST
if(!empty($_POST['action'])) {
    switch($_POST['action']) {
        case 'listProductR':
            $productrdinfo->listProductR();
            break;
            
        case 'listProductD':
            $productrdinfo->listProductD();
            break;
            
        default:
            echo json_encode(array(
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => array(),
                "error" => "Acción no válida"
            ));
            break;
    }
} else {
    echo json_encode(array(
        "draw" => 0,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => array(),
        "error" => "No se especificó acción"
    ));
}
?>
