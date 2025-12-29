<?php
require_once('includes/load.php');

class ProductsrdnfoSimple {

	// Función para limpiar y sanitizar datos
	private function sanitizeData($value) {
		if ($value === null || $value === '') {
			return '';
		}
		
		// Convertir a string y limpiar caracteres problemáticos
		$value = (string)$value;
		
		// Remover caracteres de control excepto tab, newline, carriage return
		$value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
		
		// Convertir a UTF-8 válido
		if (!mb_check_encoding($value, 'UTF-8')) {
			$value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
		}
		
		// Limpiar caracteres problemáticos adicionales
		$value = str_replace(['?', ''], '', $value);
		
		return trim($value);
	}

	public function listProductR(){
		// HEADERS HTTP CORRECTOS PARA DATATABLES
		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: no-cache, must-revalidate');
		
		// SET TIMEOUT PARA EVITAR COLGADURAS
		set_time_limit(30); // 30 segundos máximo
		
		global $db;
		
		try {
			// Consulta base optimizada
			$sql = "SELECT d.*, v.name AS vname, c.name AS cname, p.tp 
					FROM detail_po d 
					JOIN purchaseorder p ON p.id = d.purchaseorder_id 
					JOIN vendors v ON v.id = p.vendors_id 
					JOIN clients c ON p.clients_id = c.id 
					WHERE d.finalized = '0'";
			
			// AGREGAR FILTRO DE BÚSQUEDA OPTIMIZADO
			if(!empty($_POST["search"]["value"])){
				$searchValue = $db->escape($_POST["search"]["value"]);
				
				// Solo buscar si el valor tiene al menos 2 caracteres para evitar consultas muy lentas
				if (strlen($searchValue) >= 2) {
					$sql .= ' AND (c.name LIKE "%'.$searchValue.'%" ';
					$sql .= 'OR d.clientcode_po LIKE "%'.$searchValue.'%" ';
					$sql .= 'OR d.desc_pr_po LIKE "%'.$searchValue.'%" )';
				}
			}
			
			// AGREGAR ORDENAMIENTO
			if(!empty($_POST["order"])){
				$sql .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'];
			} else {
				$sql .= ' ORDER BY d.id DESC';
			}
			
			// LIMITAR RESULTADOS PARA EVITAR COLGADURAS
			$sql .= ' LIMIT 1000';
			
			// VERIFICAR CONEXIÓN A BD
			if (!$db) {
				echo json_encode(array(
					"draw" => intval($_POST["draw"]),
					"recordsTotal" => 0,
					"recordsFiltered" => 0,
					"data" => array(),
					"error" => "No database connection"
				));
				return;
			}
			
			// EJECUTAR CONSULTA CON TIMEOUT
			$result = $db->query($sql);
			
			if(!$result) {
				echo json_encode(array(
					"draw" => intval($_POST["draw"]),
					"recordsTotal" => 0,
					"recordsFiltered" => 0,
					"data" => array(),
					"error" => "Database error: " . $db->error
				));
				return;
			}
			
			$data = array();
			$rowCount = 0;
			
			while($row = $db->fetch_assoc($result)) {
				$rowCount++;
				
				try {
					// LÓGICA ORIGINAL DE IMÁGENES RESTAURADA
					$product_media = select_product_rd($row['products_id']);
					$image1 = 'uploads/products/no-image.jpg';
					
					if ($product_media === '0'){
						$image_html = '<img class="img-thumbnail" src="'.$image1.'" alt="image" id="main">';
					} else {
						$image = name_image($product_media);
						$image2 = 'uploads/products/'.$image;
						$image_html = '<img value="'.$image.'" id="'.$image.'" class="img-thumbnail" onClick="imagen(id)" src="'.$image2.'">';
					}
					
					$data[] = array(
						$this->sanitizeData($row['products_id']),
						$image_html, // IMAGEN ORIGINAL RESTAURADA
						$this->sanitizeData($row['clientcode_po']),
						$this->sanitizeData($row['desc_pr_po']),
						$this->sanitizeData($row['vname']),
						$this->sanitizeData($row['cname']),
						$this->sanitizeData($row['tp']),
						$this->sanitizeData($row['uxb_po']),
						$this->sanitizeData($row['pendent']),
						round($row['pendent'] / $row['uxb_po'], 2),
						$this->sanitizeData($row['price_po']),
						round($row['pendent'] * $row['price_po'], 2),
						$this->sanitizeData($row['cbm_po']),
						$this->sanitizeData($row['gw']),
						$this->sanitizeData($row['nw']),
						$this->sanitizeData($row['eta'])
					);
					
					// LIMITE DE SEGURIDAD - máximo 1000 filas
					if ($rowCount >= 1000) {
						break;
					}
					
				} catch (Exception $e) {
					continue; // Saltar fila problemática
				}
			}
			
			echo json_encode(array(
				"draw" => intval($_POST["draw"]),
				"recordsTotal" => count($data),
				"recordsFiltered" => count($data),
				"data" => $data
			));
			
		} catch (Exception $e) {
			echo json_encode(array(
				"draw" => intval($_POST["draw"]),
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => array(),
				"error" => "General error: " . $e->getMessage()
			));
		}
	}

	public function listProductD(){
		// HEADERS HTTP CORRECTOS PARA DATATABLES
		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: no-cache, must-revalidate');
		
		// Forzar codificación UTF-8
		mb_internal_encoding('UTF-8');
		mb_http_output('UTF-8');
		
		global $db;		
		$sqlQuery = "SELECT
		d.partial_invoice,
		d.price_rg,
		d.invoiced,
		c.name,
		d.clientcode_rg,
		d.desc_pr_rg, 
		v.name AS vname, 
		f.tp,
		d.uxb_rg,
		SUM(d.qty_rg) AS total_qty_rg,
		SUM(d.ctn) AS total_ctn_rg, 
		d.cbm_rg,
		d.gw,
		d.nw,
		d.products_id
		FROM 
		detail_rg d 
		JOIN 
		receivedgoods r ON d.receivedgoods_id = r.id 
		JOIN 
		purchaseorder f ON d.purchaseorder_id = f.id 
		JOIN 
		vendors v ON v.id = r.vendors_id 
		JOIN 
		clients c ON r.clients_id = c.id 
		WHERE 
		(d.invoiced = '0' OR d.invoiced = '3')";

		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'AND (c.name LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'or d.clientcode_rg LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'or d.desc_pr_rg LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'or r.numbers LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'or f.tp LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'or v.name LIKE "%'.$_POST["search"]["value"].'%" )';		
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'GROUP BY d.id,d.clientcode_rg,d.desc_pr_rg,f.tp,vname,d.uxb_rg,d.cbm_rg,d.gw,d.nw ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'GROUP BY d.id,d.clientcode_rg,d.desc_pr_rg,f.tp,vname,d.uxb_rg,d.cbm_rg,d.gw,d.nw ORDER BY d.id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = $db->query($sqlQuery);

		// Updated for pagination	
		$sqlQuery1 = "SELECT d.invoiced,c.name,d.clientcode_rg,d.desc_pr_rg,d.products_id,v.name AS vname,f.tp,d.uxb_rg,SUM(d.qty_rg) AS total_qty_rg,SUM(d.ctn) AS total_ctn_rg,d.cbm_rg FROM detail_rg d JOIN receivedgoods r ON d.receivedgoods_id = r.id 
JOIN 
    purchaseorder f ON d.purchaseorder_id = f.id 
JOIN 
    vendors v ON v.id = r.vendors_id 
JOIN 
    clients c ON r.clients_id = c.id 
WHERE 
    (d.invoiced = '0' OR d.invoiced = '3')
GROUP BY c.name,d.id,d.clientcode_rg,d.desc_pr_rg,f.tp,vname,d.uxb_rg,d.cbm_rg,d.gw,d.nw ";
		$result1 = $db->query($sqlQuery1);
		$numRows = $db->num_rows($result1);       
	   
		$productsDData = array();	

		while($productsD = $db->fetch_assoc($result) ) {
			$productsDRows = array();

			$productsDRows[] = $productsD['products_id'];
			$product_media = select_product_rd($productsD['products_id']);
			$image1 = 'uploads/products/no-image.jpg';

			// Read image path, convert to base64 encoding
			$type1 = pathinfo($image1, PATHINFO_EXTENSION);
			$data1 = file_get_contents($image1);
			$imgData = base64_encode($data1);
			$src = 'data:image/' . $type1 . ';base64,'.$imgData;
			
			$image = name_image($product_media);
			
			if ($product_media === '0'){
				$productsDRows[]  = '<img class="img-thumbnail" src="'.$image1.'" alt="image" id="main">';
			}
			else{
				$image2 = 'uploads/products/'.$image.'';

				// Read image path, convert to base64 encoding
				$type2 = pathinfo($image2, PATHINFO_EXTENSION);
				$data2 = file_get_contents($image2);
				$imgData1 = base64_encode($data2);
				$src1 = 'data:image/' . $type2 . ';base64,'.$imgData1;

				$productsDRows[]  = '<img value="'.$image.'" id="'.$image.'" class="img-thumbnail" onClick ="imagen(id)" src="'.$image2.'">' ;
			}
			
			// Limpiar caracteres problemáticos
			$productsDRows[] = $this->cleanString($productsD['clientcode_rg']);
			$productsDRows[] = $this->cleanString($productsD['desc_pr_rg']);
			$productsDRows[] = $this->cleanString($productsD['vname']);
			$productsDRows[] = $this->cleanString($productsD['name']);
			$productsDRows[] = $this->cleanString($productsD['tp']);
			$productsDRows[] = $productsD['uxb_rg'];

			if($productsD['invoiced'] === '0'){
				$productsDRows[] = $productsD['total_qty_rg'];
				$productsDRows[] = $productsD['total_ctn_rg'];
				$total_ammount_rg = $productsD['price_rg'] * $productsD['total_qty_rg'];
			}

			if($productsD['invoiced'] === '3'){
				$productsDRows[] = $productsD['partial_invoice'];
				$productsDRows[] = $productsD['partial_invoice'] / $productsD['uxb_rg'];
				$total_ammount_rg = $productsD['price_rg'] * $productsD['partial_invoice'];
			}

			$units = select_units_rd($productsD['products_id']);

			$productsDRows[] = $productsD['price_rg'];
			$productsDRows[] = round($total_ammount_rg,2);
			$productsDRows[] = $this->cleanString($units);
			$productsDRows[] = $productsD['cbm_rg'];
			$productsDRows[] = $productsD['gw'];
			$productsDRows[] = $productsD['nw'];
			
			$productsDData[] = $productsDRows;
		}
		
		// Limpiar buffer de salida
		if (ob_get_level()) ob_end_clean();
		
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$productsDData
		);
		
		// Generar JSON con manejo de caracteres especiales
		$json_output = json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		
		if (json_last_error() !== JSON_ERROR_NONE) {
			// En caso de error, generar JSON de error válido
			$error_output = array(
				"draw" => intval($_POST["draw"]),
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => array(),
				"error" => "Error al generar JSON: " . json_last_error_msg()
			);
			echo json_encode($error_output, JSON_UNESCAPED_UNICODE);
		} else {
			echo $json_output;
		}
	}
	
	// Función para limpiar strings problemáticos
	private function cleanString($str) {
		if (empty($str)) return '';
		
		// Convertir a string
		$str = (string)$str;
		
		// Remover caracteres de control problemáticos
		$str = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $str);
		
		// Convertir a UTF-8 válido
		if (!mb_check_encoding($str, 'UTF-8')) {
			$str = mb_convert_encoding($str, 'UTF-8', 'ISO-8859-1');
		}
		
		// Limpiar caracteres problemáticos adicionales
		$str = str_replace(['?', ''], '', $str);
		
		return trim($str);
	}
}
?>
