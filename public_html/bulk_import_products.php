<?php
// No mostrar errores en la salida para evitar corromper archivos Excel
error_reporting(0);
ini_set('display_errors', 0);

// Configurar UTF-8 para manejar caracteres chinos
mb_internal_encoding('UTF-8');
ini_set('default_charset', 'UTF-8');

// Logging dedicado para diagnosticar 500 en hosting (seguro para producción, escribe a archivo)
ini_set('log_errors', 1);
if (!defined('IMPORT_LOG_PATH')) {
    define('IMPORT_LOG_PATH', __DIR__ . '/php-error-import.log');
}
ini_set('error_log', IMPORT_LOG_PATH);
register_shutdown_function(function(){
    $e = error_get_last();
    if ($e && in_array($e['type'] ?? 0, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log('FATAL shutdown: ' . print_r($e, true));
    }
});

try {
    require_once('includes/load.php');
    error_log("includes/load.php loaded successfully");
} catch (Exception $e) {
    error_log("Failed to load includes/load.php: " . $e->getMessage());
    die("Failed to load required files: " . $e->getMessage());
}

// Sanea texto para Excel (quita emojis/astrales y controles no imprimibles)
function sanitizeExcelText($text) {
    if ($text === null) { return ''; }
    $text = (string)$text;
    // Asegurar UTF-8 sin convertir caracteres válidos BMP (mantiene chino)
    if (!mb_detect_encoding($text, 'UTF-8', true)) {
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
    }
    // Remover caracteres de control no imprimibles
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $text);
    // Remover astrales (emojis) incompatibles con XLS (BIFF8)
    $text = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $text);
    return $text;
}

// Check if user is logged in
if(!$session->isUserLoggedIn(true)) {
    redirect('login.php');
}

// Get all data for dropdowns with error handling
try {
    $all_categories = find_all('categories');
    $all_moneys = find_all('moneys');
    $all_units = find_all('units');
    $all_packagings = find_all('packaging');
    $all_clients = find_all('clients');
    $all_prices = find_all('price_type');
    $all_subcategories = find_all('subcategories');
} catch (Exception $e) {
    error_log("Database error in bulk_import_products.php: " . $e->getMessage());
    $all_categories = [];
    $all_moneys = [];
    $all_units = [];
    $all_packagings = [];
    $all_clients = [];
    $all_prices = [];
    $all_subcategories = [];
}

// Process bulk import
if(isset($_POST['process_import']) && isset($_FILES['excel_file'])) {
    try {
        $file = $_FILES['excel_file'];
        // Trazas iniciales de subida
        error_log("bulk_import: process_import triggered");
        error_log("bulk_import: _FILES[excel_file] => " . print_r($file, true));
        error_log('bulk_import: is_uploaded_file=' . ((isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) ? 'yes' : 'no'));
        if (isset($file['error'])) { error_log('bulk_import: upload error code=' . $file['error']); }
        if (empty($file['tmp_name'])) { error_log('bulk_import: empty tmp_name; upload_tmp_dir=' . ini_get('upload_tmp_dir')); }
        
        // Validate file
        if($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error uploading file: ' . $file['error']);
        }
        
        // Only allow .xls files
        if(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'xls') {
            throw new Exception('Only .xls files are supported. Please save your Excel file as .xls (Excel 97-2003) format.');
        }
        
        // Info adicional del archivo temporal
        $mime = (isset($file['tmp_name']) && function_exists('mime_content_type')) ? mime_content_type($file['tmp_name']) : 'n/a';
        error_log('bulk_import: tmp=' . ($file['tmp_name'] ?? 'n/a') . ' name=' . ($file['name'] ?? 'n/a') . ' size=' . ($file['size'] ?? 'n/a') . ' mime=' . $mime);

        // Load PHPExcel - use the correct method that works
        require_once('libs/PHPExcel/PHPExcel.php');
        
        // Create Excel5 reader for .xls files with error handling
        $reader = PHPExcel_IOFactory::createReader('Excel5');
        
        // Set reader options for better compatibility
        $reader->setReadDataOnly(true);
        $reader->setIncludeCharts(false);
        
        // Configurar codificación para caracteres especiales
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        
        try {
            $spreadsheet = $reader->load($file['tmp_name']);
            error_log("bulk_import: Excel loaded, active sheet: " . $spreadsheet->getActiveSheet()->getTitle());
        } catch (Exception $e) {
            throw new Exception('Failed to load Excel file: ' . $e->getMessage());
        }
        
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Get data with safer approach
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        
        // Limit rows to prevent memory issues
        if($highestRow > 1000) {
            $highestRow = 1000;
        }
        
        // Read data row by row to avoid memory issues
        $data = [];
        $headers = [];
        
        // Simple approach - use rangeToArray but with error handling
        try {
            // Si la hoja principal no es exactamente la primera, forzar a 'Product Import Template'
            try {
                $worksheet = $spreadsheet->setActiveSheetIndexByName('Product Import Template');
            } catch (Exception $e) { /* ignore, fallback to active sheet */ }
            $active = $spreadsheet->getActiveSheet();
            // Read full range including new columns up to AB
            $data = $active->rangeToArray('A1:AB' . $highestRow, null, true, false);
            $headers = array_shift($data);
            
            // Remove the note row (row 2) and keep only data rows
            if(isset($data[0])) {
                unset($data[0]); // Remove row 2 (note)
                $data = array_values($data); // Reindex array
            }
            error_log("bulk_import: data rows after header/note removal = " . count($data));
        } catch (Exception $e) {
            throw new Exception('Failed to read Excel data: ' . $e->getMessage());
        }
        
        if (empty($data)) {
            $session->msg('w', 'No se encontraron filas de datos en la hoja principal. Asegúrate de ingresar desde la fila 3.');
        }
        
        // Expected headers
        $expectedHeaders = [
            'Product Name (English)*', 'Product Name (Spanish)', 'Product Name (Chinese)', 'Product Name (Portuguese)',
            'Category ID*', 'Subcategory ID', 'Buy Price', 'Sale Price', 'Currency ID*', 'Unit ID*', 'Packaging ID*',
            'Min Stock', 'Quantity', 'Customer ID*', 'Customer Alias*', 'Price Type ID', 'Color', 'Material', 'Size',
            'CBM', 'Inner Packing', 'EAN13 Barcode', 'DUN14 Barcode', 'Weight (kg)', 'Open/Close (1=Yes, 0=No)',
            'CTN Net Weight (kg)', 'CTN Gross Weight (kg)', 'Volume (ML)'
        ];
        
        // Validación flexible de encabezados: no forzar cantidad exacta de columnas
        
        // Log headers for debugging
        error_log("Headers found: " . implode(', ', $headers));
        
        // Check if this is the correct template by looking for key headers
        $normalizedHeaders = array_map(function($h){ return trim(strtolower($h)); }, $headers);
        $keyHeaders = ['product name (english)*', 'category id*', 'customer id*'];
        $foundKeyHeaders = 0;
        foreach($keyHeaders as $keyHeader) {
            if(in_array($keyHeader, $normalizedHeaders)) {
                $foundKeyHeaders++;
            }
        }
        
        if($foundKeyHeaders < 2) {
            $session->msg('d', 'El archivo no coincide con la plantilla. Descarga la plantilla desde esta página y vuelve a intentarlo.');
            generateErrorReport([
                [
                    'row' => 1,
                    'errors' => ['Template inválida: Encabezados clave no encontrados'],
                    'data' => $headers
                ]
            ], $expectedHeaders);
            exit;
        }
        
        // Process rows
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        $successProducts = [];
        
        foreach($data as $rowIndex => $row) {
            $rowNumber = $rowIndex + 3; // Since we removed row 2, adjust accordingly
            
            // Skip empty rows
            if(empty(array_filter($row))) {
                continue;
            }
            
            // Ensure row has enough columns
            // Normalize row length to expected columns (28 cols A..AB): pad missing with empty strings
            $expectedCols = 28; // A..AB
            if (count($row) < $expectedCols) {
                $row = array_pad($row, $expectedCols, '');
            }
            if(count($row) < $expectedCols) {
                $errors[] = [
                    'row' => $rowNumber, 
                    'errors' => ['Row has insufficient columns. Expected ' . $expectedCols . ', got ' . count($row)], 
                    'data' => $row
                ];
                $errorCount++;
                continue;
            }
            
            try {
                // Reglas: todos los campos son requeridos salvo desc_spanish/desc_chinese/desc_portuguese y subcategory/buy/min/qty/price_type opcionales.
                // Validate required fields
                $validationErrors = [];
                
                // Safe validation with array bounds checking
                if(!isset($row[0]) || empty(trim($row[0]))) {
                    $validationErrors[] = 'Product Name (English) is required';
                }
                if(!isset($row[4]) || trim($row[4]) === '') {
                    $validationErrors[] = 'Category ID is required';
                }
                if(!isset($row[8]) || trim($row[8]) === '') {
                    $validationErrors[] = 'Currency ID is required';
                }
                if(!isset($row[9]) || trim($row[9]) === '') {
                    $validationErrors[] = 'Unit ID is required';
                }
                if(!isset($row[10]) || trim($row[10]) === '') {
                    $validationErrors[] = 'Packaging ID is required';
                }
                if(!isset($row[13]) || trim($row[13]) === '') {
                    $validationErrors[] = 'Customer ID is required';
                }
                if(!isset($row[14]) || trim($row[14]) === '') {
                    $validationErrors[] = 'Customer Alias is required';
                }
                if(!isset($row[7]) || trim($row[7]) === '') { $validationErrors[] = 'Sale Price is required'; }
                if(!isset($row[16]) || trim($row[16]) === '') { $validationErrors[] = 'Color is required'; }
                if(!isset($row[17]) || trim($row[17]) === '') { $validationErrors[] = 'Material is required'; }
                if(!isset($row[18]) || trim($row[18]) === '') { $validationErrors[] = 'Size is required'; }
                if(!isset($row[21]) || trim($row[21]) === '') { $validationErrors[] = 'EAN13 Barcode is required'; }
                if(!isset($row[22]) || trim($row[22]) === '') { $validationErrors[] = 'DUN14 Barcode is required'; }
                if(!isset($row[23]) || trim($row[23]) === '') { $validationErrors[] = 'Weight (kg) is required'; }
                // Open/Close will be hardcoded to 1 on insert
                if(!isset($row[25]) || trim($row[25]) === '') { $validationErrors[] = 'CTN Net Weight is required'; }
                if(!isset($row[26]) || trim($row[26]) === '') { $validationErrors[] = 'CTN Gross Weight is required'; }
                if(!isset($row[27]) || trim($row[27]) === '') { $validationErrors[] = 'Volume is required'; }
                // New numeric validations for CTN weights and volume (optional but if present must be numeric)
                // Note: index 24 = Open/Close (already handled as int below). New CTN weights at 25,26 and Volume at 27
                if(isset($row[25]) && trim($row[25]) !== '' && !is_numeric(str_replace(',', '.', $row[25]))) {
                    $validationErrors[] = 'CTN Net Weight must be numeric';
                }
                if(isset($row[26]) && trim($row[26]) !== '' && !is_numeric(str_replace(',', '.', $row[26]))) {
                    $validationErrors[] = 'CTN Gross Weight must be numeric';
                }
                if(isset($row[27]) && trim($row[27]) !== '' && !is_numeric(str_replace(',', '.', $row[27]))) {
                    $validationErrors[] = 'Volume must be numeric';
                }
                // Price Type is optional, but if provided must be numeric and valid
                
                // Validate numeric fields
                if(isset($row[4]) && !empty(trim($row[4]))) {
                    if(!is_numeric($row[4])) {
                        $validationErrors[] = 'Category ID must be numeric';
                    }
                }
                if(isset($row[8]) && !empty(trim($row[8]))) {
                    if(!is_numeric($row[8])) {
                        $validationErrors[] = 'Currency ID must be numeric';
                    }
                }
                if(isset($row[9]) && !empty(trim($row[9]))) {
                    if(!is_numeric($row[9])) {
                        $validationErrors[] = 'Unit ID must be numeric';
                    }
                }
                if(isset($row[10]) && !empty(trim($row[10]))) {
                    if(!is_numeric($row[10])) {
                        $validationErrors[] = 'Packaging ID must be numeric';
                    }
                }
                if(isset($row[13]) && !empty(trim($row[13]))) {
                    if(!is_numeric($row[13])) {
                        $validationErrors[] = 'Customer ID must be numeric';
                    }
                }
                if(isset($row[15]) && trim($row[15]) !== '') {
                    if(!is_numeric($row[15])) {
                        $validationErrors[] = 'Price Type ID must be numeric';
                    }
                }
                // Numeric formats for required numeric fields
                if(isset($row[7]) && trim($row[7]) !== '' && !is_numeric(str_replace(',', '.', $row[7]))) {
                    $validationErrors[] = 'Sale Price must be numeric';
                }
                if(isset($row[23]) && trim($row[23]) !== '' && !is_numeric(str_replace(',', '.', $row[23]))) {
                    $validationErrors[] = 'Weight (kg) must be numeric';
                }
                // Open/Close ignored (always 1 in DB)
                
                if(!empty($validationErrors)) {
                    $errors[] = ['row' => $rowNumber, 'errors' => $validationErrors, 'data' => $row];
                    $errorCount++;
                    error_log('bulk_import: row ' . $rowNumber . ' validation errors: ' . implode('; ', $validationErrors));
                    continue;
                }
                
                // Validate foreign keys exist
                $categoryExists = false;
                $currencyExists = false;
                $unitExists = false;
                $packagingExists = false;
                $customerExists = false;
                $priceTypeExists = true; // optional by template
                $subcategoryExists = true; // optional by template
                
                foreach($all_categories as $cat) {
                    if($cat['id'] == $row[4]) { $categoryExists = true; break; }
                }
                foreach($all_moneys as $money) {
                    if($money['id'] == $row[8]) { $currencyExists = true; break; }
                }
                foreach($all_units as $unit) {
                    if($unit['id'] == $row[9]) { $unitExists = true; break; }
                }
                foreach($all_packagings as $pack) {
                    if($pack['id'] == $row[10]) { $packagingExists = true; break; }
                }
                foreach($all_clients as $client) {
                    if($client['id'] == $row[13]) { $customerExists = true; break; }
                }
                // Check price type if provided
                if(isset($row[15]) && trim($row[15]) !== '') {
                    $priceTypeExists = false;
                    foreach($all_prices as $pt) {
                        if($pt['id'] == $row[15]) { $priceTypeExists = true; break; }
                    }
                }
                // Check subcategory if provided and match with category
                if(isset($row[5]) && trim($row[5]) !== '') {
                    $subcategoryExists = false;
                    $subcategoryMatchesCategory = false;
                    foreach($all_subcategories as $sc) {
                        if($sc['id'] == $row[5]) {
                            $subcategoryExists = true;
                            if(isset($sc['categories_id']) && $sc['categories_id'] == $row[4]) {
                                $subcategoryMatchesCategory = true;
                            }
                            break;
                        }
                    }
                    if(!$subcategoryExists) {
                        $validationErrors[] = 'Subcategory ID ' . $row[5] . ' does not exist';
                    } elseif(!$subcategoryMatchesCategory) {
                        $validationErrors[] = 'Subcategory does not belong to Category ID ' . $row[4];
                    }
                }
                
                if(!$categoryExists) $validationErrors[] = 'Category ID ' . $row[4] . ' does not exist';
                if(!$currencyExists) $validationErrors[] = 'Currency ID ' . $row[8] . ' does not exist';
                if(!$unitExists) $validationErrors[] = 'Unit ID ' . $row[9] . ' does not exist';
                if(!$packagingExists) $validationErrors[] = 'Packaging ID ' . $row[10] . ' does not exist';
                if(!$customerExists) $validationErrors[] = 'Customer ID ' . $row[13] . ' does not exist';
                if(isset($row[15]) && trim($row[15]) !== '' && !$priceTypeExists) $validationErrors[] = 'Price Type ID ' . $row[15] . ' does not exist';
                
                if(!empty($validationErrors)) {
                    $errors[] = ['row' => $rowNumber, 'errors' => $validationErrors, 'data' => $row];
                    $errorCount++;
                    error_log('bulk_import: row ' . $rowNumber . ' FK errors: ' . implode('; ', $validationErrors));
                    continue;
                }
                
                // Map to real DB schema fields (see add_product.php)
                $productData = [
                    'desc_english' => mb_convert_encoding(trim($row[0]), 'UTF-8', 'UTF-8'),
                    'desc_spanish' => mb_convert_encoding(trim($row[1]), 'UTF-8', 'UTF-8'),
                    'desc_chinese' => mb_convert_encoding(trim($row[2]), 'UTF-8', 'UTF-8'),
                    'desc_portuguese' => mb_convert_encoding(trim($row[3]), 'UTF-8', 'UTF-8'),
                    'categories_id' => $row[4],
                    'subcategories_id' => !empty($row[5]) ? $row[5] : null,
                    'price' => !empty($row[7]) ? floatval($row[7]) : 0,
                    'moneys_id' => $row[8],
                    'units_id' => $row[9],
                    'packaging_id' => $row[10],
                    'cbm' => !empty($row[19]) ? floatval($row[19]) : 0,
                    'inners' => !empty($row[20]) ? intval($row[20]) : 0,
                    'ean13' => trim($row[21]),
                    'dun14' => trim($row[22]),
                    'product_weight' => !empty($row[23]) ? floatval($row[23]) : 0,
                    'openclose' => 1, // hardcoded as requested
                    'color' => mb_convert_encoding(trim($row[16]), 'UTF-8', 'UTF-8'),
                    'material' => mb_convert_encoding(trim($row[17]), 'UTF-8', 'UTF-8'),
                    'size' => mb_convert_encoding(trim($row[18]), 'UTF-8', 'UTF-8'),
                    'price_type_id' => (isset($row[15]) && trim($row[15]) !== '') ? intval($row[15]) : null,
                    'uxb' => 0,
                    'netweight' => (isset($row[25]) && trim($row[25]) !== '') ? floatval(str_replace(',', '.', $row[25])) : 0,
                    'grossweight' => (isset($row[26]) && trim($row[26]) !== '') ? floatval(str_replace(',', '.', $row[26])) : 0,
                    'volume' => (isset($row[27]) && trim($row[27]) !== '') ? floatval(str_replace(',', '.', $row[27])) : 0,
                ];

                // Transacción por fila: si falla alias no dejamos producto colgado
                dbBegin();
                $productId = insertProduct($productData);
                if(!$productId) {
                    error_log('bulk_import: insertProduct failed on row ' . $rowNumber);
                    dbRollback();
                } else {
                    error_log('bulk_import: product inserted id=' . $productId . ' on row ' . $rowNumber);
                }
                
                if($productId) {
                    // Insert category/subcategory relation
                    insertProductCategorySub($productId, $productData['categories_id'], $productData['subcategories_id']);

                    // Insert customer alias
                    $customerAliasData = [
                        'clientcode' => mb_convert_encoding(trim($row[14]), 'UTF-8', 'UTF-8'),
                        'clients_id' => $row[13],
                        'products_id' => $productId,
                        'openclose' => 1 // hardcoded as requested
                    ];
                    
                    $aliasResult = insertCustomerAlias($customerAliasData);
                    if($aliasResult['status'] === 'error') {
                        error_log('bulk_import: insertCustomerAlias failed on row ' . $rowNumber);
                        // revertir producto si alias requerido falla
                        dbRollback();
                    }
                    
                    if($aliasResult['status'] === 'ok' || $aliasResult['status'] === 'duplicate') {
                        dbCommit();
                        $successCount++;
                        $successProducts[] = [
                            'row' => $rowNumber,
                            'product_id' => $productId,
                            'name' => $productData['desc_english'],
                            'customer_alias' => $customerAliasData['clientcode']
                        ];
                    } else {
                        // registrar error claro
                        $errors[] = [
                            'row' => $rowNumber,
                            'errors' => ['Product inserted but Customer Alias failed (errno: ' . ($aliasResult['errno'] ?? 'n/a') . ')'],
                            'data' => $row
                        ];
                        $errorCount++;
                    }
                } else {
                    $errors[] = [
                        'row' => $rowNumber,
                        'errors' => ['Failed to insert product'],
                        'data' => $row
                    ];
                    $errorCount++;
                }
                
            } catch (Exception $e) {
                $errors[] = [
                    'row' => $rowNumber,
                    'errors' => ['Exception: ' . $e->getMessage()],
                    'data' => $row
                ];
                $errorCount++;
            }
        }
        
        // Generate reports immediately and exit to force download
        if($errorCount > 0) {
            generateErrorReport($errors, $headers);
            exit;
        }
        
        if($successCount > 0) {
            generateSuccessReport($successProducts);
            exit;
        }
        
        // Show results
        $session->msg('w', 'No hubo filas válidas ni errores detectables. Verifica que la hoja principal tenga datos desde la fila 3 y que los encabezados coincidan.');
        
        // Store results for display in the UI
        $_SESSION['import_results'] = [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'total_rows' => $successCount + $errorCount
        ];
        
    } catch (Exception $e) {
        $session->msg('d', 'Import failed: ' . $e->getMessage());
    }
}

// Download template
if(isset($_POST['download_template'])) {
    try {
        // Load PHPExcel - use the correct method that works
        require_once('libs/PHPExcel/PHPExcel.php');
        
        // Configurar codificación para caracteres especiales
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        
        $spreadsheet = new PHPExcel();
        
        // Set document properties with UTF-8 support
        $spreadsheet->getProperties()->setCreator("Warehouse Inventory System")
            ->setLastModifiedBy("Warehouse Inventory System")
            ->setTitle("Product Import Template")
            ->setSubject("Product Import Template")
            ->setDescription("Template for bulk product import");
        
        // Main sheet
        $mainSheet = $spreadsheet->getActiveSheet();
        $mainSheet->setTitle('Product Import Template');
        
        $headers = array(
            'Product Name (English)*', 'Product Name (Spanish)', 'Product Name (Chinese)', 'Product Name (Portuguese)',
            'Category ID*', 'Subcategory ID', 'Buy Price', 'Sale Price', 'Currency ID*', 'Unit ID*', 'Packaging ID*',
            'Min Stock', 'Quantity', 'Customer ID*', 'Customer Alias*', 'Price Type ID', 'Color', 'Material', 'Size',
            'CBM (Volume)', 'Inner Packing', 'EAN13 Barcode', 'DUN14 Barcode', 'Weight (kg)', 'Open/Close (1=Yes, 0=No)',
            'CTN Net Weight (kg)', 'CTN Gross Weight (kg)', 'Volume (ML)'
        );
        
        $mainSheet->fromArray(array_map('sanitizeExcelText', $headers), NULL, 'A1');
        
        // Style header row (extend to AB)
        $mainSheet->getStyle('A1:AB1')->getFont()->setBold(true);
        $mainSheet->getStyle('A1:AB1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $mainSheet->getStyle('A1:AB1')->getFill()->getStartColor()->setRGB('4472C4');
        $mainSheet->getStyle('A1:AB1')->getFont()->setColor(new PHPExcel_Style_Color('FFFFFF'));
        
        // Add note about where to start data entry (sin emojis para compatibilidad XLS)
        $mainSheet->setCellValue('A2', 'START ENTERING YOUR PRODUCT DATA FROM ROW 3 BELOW');
        $mainSheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $mainSheet->getStyle('A2')->getFont()->setColor(new PHPExcel_Style_Color('FF6600'));
        $mainSheet->getStyle('A2:X2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $mainSheet->getStyle('A2:X2')->getFill()->getStartColor()->setRGB('FFF2CC');
        
        // Merge cells for the note
        $mainSheet->mergeCells('A2:AB2');
        
        // Reference sheets
        createReferenceSheet($spreadsheet, 'Categories', $all_categories, ['ID', 'Category Name']);
        createReferenceSheet($spreadsheet, 'Subcategories', $all_subcategories, ['ID', 'Subcategory Name']);
        createReferenceSheet($spreadsheet, 'Currencies', $all_moneys, ['ID', 'Currency Name']);
        createReferenceSheet($spreadsheet, 'Units', $all_units, ['ID', 'Unit Name']);
        createReferenceSheet($spreadsheet, 'Packaging', $all_packagings, ['ID', 'Packaging Name']);
        createReferenceSheet($spreadsheet, 'Clients', $all_clients, ['ID', 'Client Name']);
        createReferenceSheet($spreadsheet, 'Price Types', $all_prices, ['ID', 'Price Type Name']);
        
        // Examples sheet with sample data
        $examplesSheet = $spreadsheet->createSheet();
        $examplesSheet->setTitle('Examples');
        
        // Title (sin emojis)
        $examplesSheet->setCellValue('A1', 'SAMPLE DATA EXAMPLES');
        $examplesSheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $examplesSheet->getStyle('A1')->getFont()->setColor(new PHPExcel_Style_Color('4472C4'));
        
        // Copy headers to examples sheet
        $examplesSheet->fromArray(array_map('sanitizeExcelText', $headers), NULL, 'A3');
        $examplesSheet->getStyle('A3:AB3')->getFont()->setBold(true);
        $examplesSheet->getStyle('A3:AB3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $examplesSheet->getStyle('A3:AB3')->getFill()->getStartColor()->setRGB('4472C4');
        $examplesSheet->getStyle('A3:AB3')->getFont()->setColor(new PHPExcel_Style_Color('FFFFFF'));
        
        // Example 1
        $examplesSheet->setCellValue('A4', sanitizeExcelText('Sample Product'));
        $examplesSheet->setCellValue('B4', sanitizeExcelText('Producto de Ejemplo'));
        $examplesSheet->setCellValue('C4', sanitizeExcelText('示例产品'));
        $examplesSheet->setCellValue('D4', sanitizeExcelText('Produto de Exemplo'));
        $examplesSheet->setCellValue('E4', '1');
        $examplesSheet->setCellValue('F4', '1');
        $examplesSheet->setCellValue('G4', '10.50');
        $examplesSheet->setCellValue('H4', '15.99');
        $examplesSheet->setCellValue('I4', '1');
        $examplesSheet->setCellValue('J4', '1');
        $examplesSheet->setCellValue('K4', '1');
        $examplesSheet->setCellValue('L4', '10');
        $examplesSheet->setCellValue('M4', '100');
        $examplesSheet->setCellValue('N4', '1');
        $examplesSheet->setCellValue('O4', 'SAMPLE001');
        $examplesSheet->setCellValue('P4', '1');
        $examplesSheet->setCellValue('Q4', 'Red');
        $examplesSheet->setCellValue('R4', 'Plastic');
        $examplesSheet->setCellValue('S4', 'Medium');
        $examplesSheet->setCellValue('T4', '0.05');
        $examplesSheet->setCellValue('U4', '5');
        $examplesSheet->setCellValue('V4', '1234567890123');
        $examplesSheet->setCellValue('W4', '12345678901234');
        $examplesSheet->setCellValue('X4', '2.00');
        $examplesSheet->setCellValue('Z4', '10.50'); // CTN Net Weight example (Z)
        $examplesSheet->setCellValue('AA4', '11.20'); // CTN Gross Weight example (AA)
        $examplesSheet->setCellValue('AB4', '0.025'); // Volume example (AB)
        
        // Example 2
        $examplesSheet->setCellValue('A5', sanitizeExcelText('Another Product'));
        $examplesSheet->setCellValue('B5', sanitizeExcelText('Otro Producto'));
        $examplesSheet->setCellValue('C5', sanitizeExcelText('另一个产品'));
        $examplesSheet->setCellValue('D5', sanitizeExcelText('Outro Produto'));
        $examplesSheet->setCellValue('E5', '2');
        $examplesSheet->setCellValue('F5', '2');
        $examplesSheet->setCellValue('G5', '25.00');
        $examplesSheet->setCellValue('H5', '35.00');
        $examplesSheet->setCellValue('I5', '2');
        $examplesSheet->setCellValue('J5', '2');
        $examplesSheet->setCellValue('K5', '2');
        $examplesSheet->setCellValue('L5', '5');
        $examplesSheet->setCellValue('M5', '50');
        $examplesSheet->setCellValue('N5', '2');
        $examplesSheet->setCellValue('O5', 'SAMPLE002');
        $examplesSheet->setCellValue('P5', '2');
        $examplesSheet->setCellValue('Q5', 'Blue');
        $examplesSheet->setCellValue('R5', 'Metal');
        $examplesSheet->setCellValue('S5', 'Large');
        $examplesSheet->setCellValue('T5', '0.10');
        $examplesSheet->setCellValue('U5', '10');
        $examplesSheet->setCellValue('V5', '9876543210987');
        $examplesSheet->setCellValue('W5', '98765432109876');
        $examplesSheet->setCellValue('X5', '5.50');
        $examplesSheet->setCellValue('Z5', '8.00');
        $examplesSheet->setCellValue('AA5', '8.80');
        $examplesSheet->setCellValue('AB5', '0.050');
        
        // Add note (sin emojis)
        $examplesSheet->setCellValue('A7', 'COPY THESE EXAMPLES TO THE MAIN SHEET STARTING FROM ROW 3');
        $examplesSheet->getStyle('A7')->getFont()->setBold(true)->setSize(12);
        $examplesSheet->getStyle('A7')->getFont()->setColor(new PHPExcel_Style_Color('FF6600'));
        
        // Set column widths for examples
        foreach(range('A', 'Z') as $col) {
            $examplesSheet->getColumnDimension($col)->setWidth(15);
        }
        $examplesSheet->getColumnDimension('AA')->setWidth(15);
        $examplesSheet->getColumnDimension('AB')->setWidth(15);
        
        // Instructions sheet
        $instructionsSheet = $spreadsheet->createSheet();
        $instructionsSheet->setTitle('Instructions');
        $instructionsSheet->setCellValue('A1', 'BULK PRODUCT IMPORT INSTRUCTIONS');
        $instructionsSheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $instructionsSheet->getStyle('A1')->getFont()->setColor(new PHPExcel_Style_Color('4472C4'));
        
        $instructionsSheet->setCellValue('A3', 'REQUIRED FIELDS (marked with *):');
        $instructionsSheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
        $instructionsSheet->getStyle('A3')->getFont()->setColor(new PHPExcel_Style_Color('FF0000'));
        
        $instructionsSheet->setCellValue('A4', '• Product Name (English) - Must not be empty');
        $instructionsSheet->setCellValue('A5', '• Category ID - Use ID from Categories sheet');
        $instructionsSheet->setCellValue('A6', '• Currency ID - Use ID from Currencies sheet');
        $instructionsSheet->setCellValue('A7', '• Unit ID - Use ID from Units sheet');
        $instructionsSheet->setCellValue('A8', '• Packaging ID - Use ID from Packaging sheet');
        $instructionsSheet->setCellValue('A9', '• Customer ID - Use ID from Clients sheet');
        $instructionsSheet->setCellValue('A10', '• Customer Alias - Must be unique for each customer');
        
        $instructionsSheet->setCellValue('A12', 'OPTIONAL FIELDS:');
        $instructionsSheet->getStyle('A12')->getFont()->setBold(true)->setSize(14);
        $instructionsSheet->getStyle('A12')->getFont()->setColor(new PHPExcel_Style_Color('FFA500'));
        
        $instructionsSheet->setCellValue('A13', '• Product Names in Spanish, Chinese, Portuguese');
        $instructionsSheet->setCellValue('A14', '• Subcategory ID, Buy Price, Sale Price');
        $instructionsSheet->setCellValue('A15', '• Min Stock, Quantity, Price Type ID');
        $instructionsSheet->setCellValue('A16', '• Color, Material, Size, CBM (m3), Inner Packing');
        $instructionsSheet->setCellValue('A17', '• EAN13, DUN14 Barcodes, Weight, Open/Close');
        
        $instructionsSheet->setCellValue('A19', 'HOW TO USE:');
        $instructionsSheet->getStyle('A19')->getFont()->setBold(true)->setSize(14);
        $instructionsSheet->getStyle('A19')->getFont()->setColor(new PHPExcel_Style_Color('00AA00'));
        
        $instructionsSheet->setCellValue('A20', '1. Check the reference sheets for valid IDs');
        $instructionsSheet->setCellValue('A21', '2. Go to "Examples" sheet to see sample data');
        $instructionsSheet->setCellValue('A22', '3. Copy examples to main sheet starting from row 3');
        $instructionsSheet->setCellValue('A23', '4. Customer Alias must be unique per customer');
        $instructionsSheet->setCellValue('A24', '5. Weight should be in kilograms (e.g., 2.00)');
        $instructionsSheet->setCellValue('A25', '6. CBM should be in cubic meters (e.g., 0.05)');
        $instructionsSheet->setCellValue('A26', '7. Open/Close: 1=Yes, 0=No');
        $instructionsSheet->setCellValue('A27', '8. Row 2 in main sheet is just a note - ignore it');
        
        // Set column width for instructions
        $instructionsSheet->getColumnDimension('A')->setWidth(60);
        
        // Download file
        $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');
        $filename = 'product_import_template.xls';
        
        // Create exports directory if it doesn't exist
        if (!file_exists('exports')) {
            mkdir('exports', 0755, true);
        }
        
        // Save file first to handle UTF-8 properly
        $tempPath = 'exports/' . $filename;
        $writer->save($tempPath);
        
        // Clear any output buffers before sending headers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set headers for file download with UTF-8 support
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($tempPath));
        
        // Output file and clean up
        readfile($tempPath);
        unlink($tempPath);
        exit; // Use exit instead of return for file downloads
        
    } catch (Exception $e) {
        $session->msg('d', 'Template download failed: ' . $e->getMessage());
    }
}

// Database functions
// Compatibilidad de transacciones para distintos wrappers MySQLi
function dbBegin() {
    global $db;
    if (method_exists($db, 'begin_transaction')) { return $db->begin_transaction(); }
    if (method_exists($db, 'begin')) { return $db->begin(); }
    return $db->query('START TRANSACTION');
}

function dbCommit() {
    global $db;
    if (method_exists($db, 'commit')) { return $db->commit(); }
    return $db->query('COMMIT');
}

function dbRollback() {
    global $db;
    if (method_exists($db, 'rollback')) { return $db->rollback(); }
    return $db->query('ROLLBACK');
}
function insertProduct($data) {
    global $db;
    // Map to real schema used by add_product.php
    $sql = "INSERT INTO products (
            desc_english, price, media_id, date, code, moneys_id, desc_spanish, desc_chinese, desc_portuguese,
            color, material, size, cbm, openclose, uxb, inners, units_id, packaging_id, ean13, dun14,
            price_type_id, netweight, grossweight, volume, product_weight
        ) VALUES (?, ?, 0, NOW(), '0', ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Si el wrapper soporta prepare(), usarlo; si no, fallback con escape() y query directa
    if (method_exists($db, 'prepare') && ($stmt = $db->prepare($sql))) {
        $descSpanish = $data['desc_spanish'];
        $descChinese = $data['desc_chinese'];
        $descPortuguese = $data['desc_portuguese'];
        $priceTypeId = $data['price_type_id'];
        if ($priceTypeId === null) { $priceTypeId = 1; }

        $stmt->bind_param("sdissssssdiiiissidddd",
            $data['desc_english'],
            $data['price'],
            $data['moneys_id'],
            $descSpanish,
            $descChinese,
            $descPortuguese,
            $data['color'],
            $data['material'],
            $data['size'],
            $data['cbm'],
            $data['openclose'],
            $data['inners'],
            $data['units_id'],
            $data['packaging_id'],
            $data['ean13'],
            $data['dun14'],
            $priceTypeId,
            $data['netweight'],
            $data['grossweight'],
            $data['volume'],
            $data['product_weight']
        );

        if($stmt->execute()) {
            $productId = $db->insert_id();
            $stmt->close();
            return $productId;
        } else {
            error_log("Execute failed: " . $stmt->error);
            $stmt->close();
            return false;
        }
    } else {
        // Fallback seguro: construir SQL escapando valores y casteando números
        $descEnglish = $db->escape($data['desc_english']);
        $descSpanish = $db->escape($data['desc_spanish']);
        $descChinese = $db->escape($data['desc_chinese']);
        $descPortuguese = $db->escape($data['desc_portuguese']);
        $color = $db->escape($data['color']);
        $material = $db->escape($data['material']);
        $size = $db->escape($data['size']);
        $ean13 = $db->escape($data['ean13']);
        $dun14 = $db->escape($data['dun14']);
        $moneysId = intval($data['moneys_id']);
        $unitsId = intval($data['units_id']);
        $packagingId = intval($data['packaging_id']);
        $inners = intval($data['inners']);
        $openclose = intval($data['openclose']);
        $cbm = (float)$data['cbm'];
        $price = (float)$data['price'];
        $priceTypeId = $data['price_type_id'];
        if ($priceTypeId === null) { $priceTypeId = 1; }
        $priceTypeId = intval($priceTypeId);
        $netweight = (float)$data['netweight'];
        $grossweight = (float)$data['grossweight'];
        $volume = (float)$data['volume'];
        $product_weight = (float)$data['product_weight'];

        $sqlDirect = "INSERT INTO products (
            desc_english, price, media_id, date, code, moneys_id, desc_spanish, desc_chinese, desc_portuguese,
            color, material, size, cbm, openclose, uxb, inners, units_id, packaging_id, ean13, dun14,
            price_type_id, netweight, grossweight, volume, product_weight
        ) VALUES (
            '{$descEnglish}', {$price}, 0, NOW(), '0', {$moneysId}, '{$descSpanish}', '{$descChinese}', '{$descPortuguese}',
            '{$color}', '{$material}', '{$size}', {$cbm}, {$openclose}, 0, {$inners}, {$unitsId}, {$packagingId}, '{$ean13}', '{$dun14}',
            {$priceTypeId}, {$netweight}, {$grossweight}, {$volume}, {$product_weight}
        )";

        $ok = $db->query($sqlDirect);
        if ($ok) {
            return $db->insert_id();
        }
        return false;
    }
}

function insertCustomerAlias($data) {
    global $db;
    
    // Intentar con prepared; si no, usar query directa escapando
    if (method_exists($db, 'prepare') && ($stmt = $db->prepare("INSERT INTO clientcode (clientcode, clients_id, products_id, openclose) VALUES (?, ?, ?, ?)"))) {
        $stmt->bind_param("siii", $data['clientcode'], $data['clients_id'], $data['products_id'], $data['openclose']);
        if($stmt->execute()) {
            $stmt->close();
            return ['status' => 'ok'];
        } else {
            $errno = $stmt->errno;
            $err = $stmt->error;
            $stmt->close();
            if ($errno === 1062) { return ['status' => 'duplicate']; }
            if ($errno === 1364 || $errno === 1054) {
                if ($stmt2 = $db->prepare("INSERT INTO clientcode (clientcode, clients_id, products_id) VALUES (?, ?, ?)")) {
                    $stmt2->bind_param("sii", $data['clientcode'], $data['clients_id'], $data['products_id']);
                    if ($stmt2->execute()) { $stmt2->close(); return ['status' => 'ok']; }
                    $errno2 = $stmt2->errno; $err2 = $stmt2->error; $stmt2->close();
                    if ($errno2 === 1062) { return ['status' => 'duplicate']; }
                    error_log("Execute failed (fallback): " . $err2);
                    return ['status' => 'error', 'errno' => $errno2, 'message' => $err2];
                }
            }
            error_log("Execute failed: " . $err);
            return ['status' => 'error', 'errno' => $errno, 'message' => $err];
        }
    } else {
        $clientcode = $db->escape($data['clientcode']);
        $clientsId = intval($data['clients_id']);
        $productsId = intval($data['products_id']);
        $openclose = intval($data['openclose']);
        $sql = "INSERT INTO clientcode (clientcode, clients_id, products_id, openclose) VALUES ('{$clientcode}', {$clientsId}, {$productsId}, {$openclose})";
        $ok = $db->query($sql);
        if ($ok) { return ['status' => 'ok']; }
        // Segundo intento sin openclose
        $sql2 = "INSERT INTO clientcode (clientcode, clients_id, products_id) VALUES ('{$clientcode}', {$clientsId}, {$productsId})";
        $ok2 = $db->query($sql2);
        if ($ok2) { return ['status' => 'ok']; }
        return ['status' => 'error'];
    }
}

function insertProductCategorySub($productId, $categoryId, $subcategoryId) {
    global $db;
    if(empty($categoryId)) { return; }
    if(empty($subcategoryId)) { $subcategoryId = 0; }
    if (method_exists($db, 'prepare') && ($stmt = $db->prepare($sql = "INSERT INTO productscatsub (products_id, categories_id, subcategories_id) VALUES (?, ?, ?)"))) {
        $stmt->bind_param("iii", $productId, $categoryId, $subcategoryId);
        $ok = $stmt->execute();
        if(!$ok) { error_log("Execute failed (productscatsub): " . $stmt->error); }
        $stmt->close();
        return $ok;
    } else {
        $pid = intval($productId); $cid = intval($categoryId); $sid = intval($subcategoryId);
        $ok = $db->query("INSERT INTO productscatsub (products_id, categories_id, subcategories_id) VALUES ({$pid}, {$cid}, {$sid})");
        return (bool)$ok;
    }
}

// Map error message to column index in the template (0-based)
function mapErrorToColumnIndex($message) {
    $msg = strtolower($message);
    if (strpos($msg, 'product name (english)') !== false) return 0; // A
    if (strpos($msg, 'category id') !== false) return 4; // E
    if (strpos($msg, 'subcategory') !== false) return 5; // F
    if (strpos($msg, 'currency id') !== false) return 8; // I
    if (strpos($msg, 'unit id') !== false) return 9; // J
    if (strpos($msg, 'packaging id') !== false) return 10; // K
    if (strpos($msg, 'customer id') !== false) return 13; // N
    if (strpos($msg, 'customer alias') !== false) return 14; // O
    if (strpos($msg, 'price type id') !== false) return 15; // P
    if (strpos($msg, 'net weight') !== false) return 25; // Z
    if (strpos($msg, 'gross weight') !== false) return 26; // AA
    if (strpos($msg, 'volume') !== false) return 27; // AB
    return null;
}

// Convert 0-based column index to Excel column letters (A, B, ... AA)
function columnIndexToLetter($index) {
    $index = intval($index);
    $letters = '';
    while ($index >= 0) {
        $letters = chr($index % 26 + 65) . $letters;
        $index = intval($index / 26) - 1;
    }
    return $letters;
}

function generateErrorReport($errors, $headers) {
    try {
        // Load PHPExcel - use the correct method that works
        require_once('libs/PHPExcel/PHPExcel.php');
        
        // Configurar codificación para caracteres especiales
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        
        $spreadsheet = new PHPExcel();
        
        // Error Summary sheet
        $summarySheet = $spreadsheet->getActiveSheet();
        $summarySheet->setTitle('Error Summary');
        
        // Title (sin emojis)
        $summarySheet->setCellValue('A1', 'BULK IMPORT ERROR SUMMARY');
        $summarySheet->setCellValue('A3', sanitizeExcelText('Generated on: ' . date('Y-m-d H:i:s')));
        $summarySheet->setCellValue('A4', 'Total Errors: ' . count($errors));
        $summarySheet->setCellValue('A5', 'Total Rows with Errors: ' . count($errors));
        
        // Style title
        $summarySheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $summarySheet->getStyle('A1')->getFont()->setColor(new PHPExcel_Style_Color('FF0000'));
        
        // Add error details table (sin emojis)
        $summarySheet->setCellValue('A7', 'QUICK ERROR OVERVIEW:');
        $summarySheet->getStyle('A7')->getFont()->setBold(true)->setSize(14);
        
        // Headers for quick overview
        $summarySheet->setCellValue('A9', 'Row #');
        $summarySheet->setCellValue('B9', 'Product Name');
        $summarySheet->setCellValue('C9', 'Main Error');
        $summarySheet->setCellValue('D9', 'Action Required');
        
        // Style headers
        $summarySheet->getStyle('A9:D9')->getFont()->setBold(true);
        $summarySheet->getStyle('A9:D9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $summarySheet->getStyle('A9:D9')->getFill()->getStartColor()->setRGB('FF0000');
        $summarySheet->getStyle('A9:D9')->getFont()->setColor(new PHPExcel_Style_Color('FFFFFF'));
        
        // Set column widths
        $summarySheet->getColumnDimension('A')->setWidth(10);
        $summarySheet->getColumnDimension('B')->setWidth(30);
        $summarySheet->getColumnDimension('C')->setWidth(40);
        $summarySheet->getColumnDimension('D')->setWidth(30);
        
        // Fill error overview table
        $row = 10;
        foreach($errors as $error) {
            $summarySheet->setCellValue('A' . $row, 'Row ' . $error['row']);
            $summarySheet->setCellValue('B' . $row, sanitizeExcelText($error['data'][0] ?? 'N/A'));
            
            // Get the first/main error
            $mainError = $error['errors'][0] ?? 'Unknown error';
            $summarySheet->setCellValue('C' . $row, sanitizeExcelText($mainError));
            
            // Provide action guidance
            $action = getActionGuidance($mainError);
            $summarySheet->setCellValue('D' . $row, sanitizeExcelText($action));
            
            // Highlight error rows
            $summarySheet->getStyle('A' . $row . ':D' . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $summarySheet->getStyle('A' . $row . ':D' . $row)->getFill()->getStartColor()->setRGB('FFE6E6');
            
            $row++;
        }
        // Add instructions (no emojis)
        $summarySheet->setCellValue('A' . ($row + 2), 'HOW TO FIX THE ERRORS:');
        $summarySheet->getStyle('A' . ($row + 2))->getFont()->setBold(true)->setSize(14);
        
        $instructionRow = $row + 4;
        $summarySheet->setCellValue('A' . $instructionRow, '1. Check the "Detailed Errors" sheet for specific error details');
        $instructionRow++;
        $summarySheet->setCellValue('A' . $instructionRow, '2. Fix the errors in your Excel file');
        $instructionRow++;
        $summarySheet->setCellValue('A' . $instructionRow, '3. Re-import the corrected file');
        $instructionRow++;
        $summarySheet->setCellValue('A' . $instructionRow, '4. Check the "Original Data with Errors" sheet to see your data with highlights');
        
        // Build a map of cell errors per row for precise highlighting
        $cellErrorsByRow = [];
        foreach ($errors as $err) {
            $rnum = intval($err['row']);
            foreach ($err['errors'] as $msg) {
                $colIdx = mapErrorToColumnIndex($msg);
                if ($colIdx !== null) {
                    if (!isset($cellErrorsByRow[$rnum])) { $cellErrorsByRow[$rnum] = []; }
                    if (!isset($cellErrorsByRow[$rnum][$colIdx])) { $cellErrorsByRow[$rnum][$colIdx] = []; }
                    $cellErrorsByRow[$rnum][$colIdx][] = $msg;
                }
            }
        }
        
        // Detailed Errors sheet
        $errorSheet = $spreadsheet->createSheet();
        $errorSheet->setTitle('Detailed Errors');
        
        // Title (sin emojis)
        $errorSheet->setCellValue('A1', sanitizeExcelText('DETAILED ERROR ANALYSIS'));
        $errorSheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $errorSheet->getStyle('A1')->getFont()->setColor(new PHPExcel_Style_Color('FF0000'));
        
        // Headers for detailed errors (one row per error, very clear)
        $errorHeaders = [
            'Row', 'Column', 'Value Entered', 'Error', 'How to Fix'
        ];
        $errorSheet->fromArray(array_map('sanitizeExcelText', $errorHeaders), NULL, 'A3');
        
        // Style error headers
        $errorSheet->getStyle('A3:E3')->getFont()->setBold(true);
        $errorSheet->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $errorSheet->getStyle('A3:E3')->getFill()->getStartColor()->setRGB('FF0000');
        $errorSheet->getStyle('A3:E3')->getFont()->setColor(new PHPExcel_Style_Color('FFFFFF'));
        
        // Set column widths
        $errorSheet->getColumnDimension('A')->setWidth(10); // Row
        $errorSheet->getColumnDimension('B')->setWidth(12); // Column
        $errorSheet->getColumnDimension('C')->setWidth(28); // Value
        $errorSheet->getColumnDimension('D')->setWidth(45); // Error
        $errorSheet->getColumnDimension('E')->setWidth(45); // How to Fix
        
        $row = 4;
        foreach ($errors as $err) {
            $rnum = intval($err['row']);
            foreach ($err['errors'] as $emsg) {
                $colIdx = mapErrorToColumnIndex($emsg);
                $colLetter = $colIdx !== null ? columnIndexToLetter($colIdx) : '';
                $valueEntered = ($colIdx !== null && isset($err['data'][$colIdx])) ? $err['data'][$colIdx] : '';
                $errorSheet->setCellValue('A' . $row, 'Row ' . $rnum);
                $errorSheet->setCellValue('B' . $row, $colLetter);
                $errorSheet->setCellValue('C' . $row, sanitizeExcelText($valueEntered));
                $errorSheet->setCellValue('D' . $row, sanitizeExcelText($emsg));
                $errorSheet->setCellValue('E' . $row, sanitizeExcelText(getActionGuidance($emsg)));
                $errorSheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $errorSheet->getStyle('A' . $row . ':E' . $row)->getFill()->getStartColor()->setRGB('FFE6E6');
                $row++;
            }
        }
        $errorSheet->freezePane('A4');
        
        // Add helpful information at the bottom (sin emojis)
        $errorSheet->setCellValue('A' . ($row + 2), 'HELPFUL TIPS:');
        $errorSheet->getStyle('A' . ($row + 2))->getFont()->setBold(true)->setSize(14);
        
        $tipRow = $row + 4;
        $errorSheet->setCellValue('A' . $tipRow, '• All IDs must be numbers (not text)');
        $tipRow++;
        $errorSheet->setCellValue('A' . $tipRow, '• Check the reference sheets to ensure IDs exist');
        $tipRow++;
        $errorSheet->setCellValue('A' . $tipRow, '• Customer Alias must be unique for each customer');
        $tipRow++;
        $errorSheet->setCellValue('A' . $tipRow, '• Required fields cannot be empty');
        $tipRow++;
        $errorSheet->setCellValue('A' . $tipRow, '• Spanish, Chinese, and Portuguese names are optional');
        
        // Fix & Correct sheet: only rows with errors, with per-cell highlights and guidance columns
        $fixSheet = $spreadsheet->createSheet();
        $fixSheet->setTitle('Fix & Correct');
        $fixSheet->setCellValue('A1', 'CORRECT THESE ROWS AND RE-IMPORT (ONLY ERROR ROWS SHOWN)');
        $fixSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        // Headers (original + Errors + How to Fix)
        $fixHeaders = $headers;
        $fixHeaders[] = 'Errors';
        $fixHeaders[] = 'How to Fix';
        $fixSheet->fromArray(array_map('sanitizeExcelText', $fixHeaders), NULL, 'A3');
        $lastHeaderIndex = count($fixHeaders) - 1; // includes extras
        $lastColLetter = columnIndexToLetter($lastHeaderIndex);
        $fixSheet->getStyle('A3:' . $lastColLetter . '3')->getFont()->setBold(true);
        $fixSheet->getStyle('A3:' . $lastColLetter . '3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $fixSheet->getStyle('A3:' . $lastColLetter . '3')->getFill()->getStartColor()->setRGB('FF0000');
        $fixSheet->getStyle('A3:' . $lastColLetter . '3')->getFont()->setColor(new PHPExcel_Style_Color('FFFFFF'));
        // Set widths
        foreach (range(0, $lastHeaderIndex) as $i) {
            $fixSheet->getColumnDimension(columnIndexToLetter($i))->setWidth(15);
        }
        $fixRowOut = 4;
        foreach ($errors as $err) {
            // Compose row data (original data)
            $rowData = [];
            for ($i = 0; $i < count($headers); $i++) {
                $rowData[] = sanitizeExcelText($err['data'][$i] ?? '');
            }
            // Errors and fix columns
            $errorText = implode('; ', array_map('sanitizeExcelText', $err['errors']));
            $howToText = sanitizeExcelText(getActionGuidance($err['errors'][0] ?? ''));
            $rowData[] = $errorText;
            $rowData[] = $howToText;
            $fixSheet->fromArray($rowData, NULL, 'A' . $fixRowOut);
            // Light red row background
            $fixSheet->getStyle('A' . $fixRowOut . ':' . $lastColLetter . $fixRowOut)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $fixSheet->getStyle('A' . $fixRowOut . ':' . $lastColLetter . $fixRowOut)->getFill()->getStartColor()->setRGB('FFE6E6');
            // Per-cell strong highlight + comment
            if (isset($cellErrorsByRow[$err['row']])) {
                foreach ($cellErrorsByRow[$err['row']] as $colIdx => $msgs) {
                    $colLetter = columnIndexToLetter($colIdx);
                    $addr = $colLetter . $fixRowOut;
                    $fixSheet->getStyle($addr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                    $fixSheet->getStyle($addr)->getFill()->getStartColor()->setRGB('FFC7CE');
                    $comment = $fixSheet->getComment($addr);
                    $comment->getText()->createTextRun('Error: ' . sanitizeExcelText(implode(' | ', $msgs)));
                }
            }
            $fixRowOut++;
        }
        $fixSheet->freezePane('A4');
        
        // Original Data with Errors sheet
        $dataSheet = $spreadsheet->createSheet();
        $dataSheet->setTitle('Original Data with Errors');
        
        // Title (sin emojis)
        $dataSheet->setCellValue('A1', sanitizeExcelText('YOUR ORIGINAL DATA - ERROR ROWS HIGHLIGHTED'));
        $dataSheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $dataSheet->getStyle('A1')->getFont()->setColor(new PHPExcel_Style_Color('FF0000'));
        
        // Add original headers
        $dataSheet->fromArray(array_map('sanitizeExcelText', $headers), NULL, 'A3');
        $dataSheet->getStyle('A3:AB3')->getFont()->setBold(true);
        $dataSheet->getStyle('A3:AB3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $dataSheet->getStyle('A3:AB3')->getFill()->getStartColor()->setRGB('FF0000');
        $dataSheet->getStyle('A3:AB3')->getFont()->setColor(new PHPExcel_Style_Color('FFFFFF'));
        
        // Add error rows with highlighting
        $row = 4;
        foreach($errors as $error) {
            $dataSheet->fromArray(array_map('sanitizeExcelText', $error['data']), NULL, 'A' . $row);
            
            // Highlight error rows in red
            $dataSheet->getStyle('A' . $row . ':AB' . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $dataSheet->getStyle('A' . $row . ':AB' . $row)->getFill()->getStartColor()->setRGB('FFE6E6');
            
            // Add error note in column Y
            $dataSheet->setCellValue('Y' . $row, sanitizeExcelText('ERROR: ' . implode('; ', $error['errors'])));
            $dataSheet->getStyle('Y' . $row)->getFont()->setColor(new PHPExcel_Style_Color('FF0000'));
            $dataSheet->getStyle('Y' . $row)->getFont()->setBold(true);
            
            // Per-cell highlight and comments for specific columns with errors
            if (isset($cellErrorsByRow[$error['row']])) {
                foreach ($cellErrorsByRow[$error['row']] as $colIdx => $msgs) {
                    $colLetter = columnIndexToLetter($colIdx);
                    $addr = $colLetter . $row;
                    $dataSheet->getStyle($addr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                    $dataSheet->getStyle($addr)->getFill()->getStartColor()->setRGB('FFC7CE');
                    $comment = $dataSheet->getComment($addr);
                    $comment->getText()->createTextRun('Error: ' . sanitizeExcelText(implode(' | ', $msgs)));
                }
            }
            
            $row++;
        }
        $dataSheet->freezePane('A4');
        
        // Add legend (sin emojis)
        $dataSheet->setCellValue('A' . ($row + 2), 'LEGEND:');
        $dataSheet->getStyle('A' . ($row + 2))->getFont()->setBold(true)->setSize(14);
        
        $legendRow = $row + 4;
        $dataSheet->setCellValue('A' . $legendRow, '• Red header row: Column names');
        $legendRow++;
        $dataSheet->setCellValue('A' . $legendRow, '• Light red rows: Rows with errors');
        $legendRow++;
        $dataSheet->setCellValue('A' . $legendRow, '• Column Y: Specific error messages');
        $legendRow++;
        $dataSheet->setCellValue('A' . $legendRow, '• Copy these rows to fix in your original file');
        
        // Set column widths for data sheet
        foreach(range('A', 'Z') as $col) {
            $dataSheet->getColumnDimension($col)->setWidth(15);
        }
        $dataSheet->getColumnDimension('AA')->setWidth(15);
        $dataSheet->getColumnDimension('AB')->setWidth(15);
        
        // Set active sheet back to first
        $spreadsheet->setActiveSheetIndex(0);
        
        // Download
        $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');
        $filename = 'bulk_import_errors.xls';
        
        // Create exports directory if it doesn't exist
        if (!file_exists('exports')) {
            mkdir('exports', 0755, true);
        }
        
        // Save file first to handle UTF-8 properly
        $tempPath = 'exports/' . $filename;
        $writer->save($tempPath);
        
        // Clear any output buffers before sending headers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set headers for file download with UTF-8 support
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($tempPath));
        
        // Output file and clean up
        readfile($tempPath);
        unlink($tempPath);
        exit; // Use exit instead of return for file downloads
        
    } catch (Exception $e) {
        error_log("Error report generation failed: " . $e->getMessage());
        // Don't exit here, just log the error
    }
}

function generateSuccessReport($successProducts) {
    try {
        // Load PHPExcel - use the correct method that works
        require_once('libs/PHPExcel/PHPExcel.php');
        
        // Configurar codificación para caracteres especiales
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        
        $spreadsheet = new PHPExcel();
        
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Success Report');
        
        // Title and summary (sin emojis)
        $sheet->setCellValue('A1', 'BULK IMPORT SUCCESS REPORT');
        $sheet->setCellValue('A3', sanitizeExcelText('Generated on: ' . date('Y-m-d H:i:s')));
        $sheet->setCellValue('A4', 'Total Successfully Imported: ' . count($successProducts));
        
        // Style title
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getFont()->setColor(new PHPExcel_Style_Color('00AA00'));
        
        // Headers
        $sheet->setCellValue('A6', 'Row');
        $sheet->setCellValue('B6', 'Product ID');
        $sheet->setCellValue('C6', 'Product Name');
        $sheet->setCellValue('D6', 'Customer Alias');
        $sheet->setCellValue('E6', 'Status');
        $sheet->setCellValue('F6', 'Import Time');
        
        // Style headers
        $sheet->getStyle('A6:F6')->getFont()->setBold(true);
        $sheet->getStyle('A6:F6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A6:F6')->getFill()->getStartColor()->setRGB('00AA00');
        $sheet->getStyle('A6:F6')->getFont()->setColor(new PHPExcel_Style_Color('FFFFFF'));
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(10); // Row
        $sheet->getColumnDimension('B')->setWidth(15); // Product ID
        $sheet->getColumnDimension('C')->setWidth(30); // Product Name
        $sheet->getColumnDimension('D')->setWidth(20); // Customer Alias
        $sheet->getColumnDimension('E')->setWidth(20); // Status
        $sheet->getColumnDimension('F')->setWidth(20); // Import Time
        
        $row = 7;
        foreach($successProducts as $product) {
            $sheet->setCellValue('A' . $row, $product['row']);
            $sheet->setCellValue('B' . $row, $product['product_id']);
            $sheet->setCellValue('C' . $row, sanitizeExcelText($product['name']));
            $sheet->setCellValue('D' . $row, sanitizeExcelText($product['customer_alias']));
            $sheet->setCellValue('E' . $row, 'Successfully Imported');
            $sheet->setCellValue('F' . $row, date('Y-m-d H:i:s'));
            
            // Highlight success rows in light green
            $sheet->getStyle('A' . $row . ':F' . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle('A' . $row . ':F' . $row)->getFill()->getStartColor()->setRGB('E6FFE6');
            
            $row++;
        }
        
        // Download
        $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');
        $filename = 'bulk_import_success.xls';
        
        // Create exports directory if it doesn't exist
        if (!file_exists('exports')) {
            mkdir('exports', 0755, true);
        }
        
        // Save file first to handle UTF-8 properly
        $tempPath = 'exports/' . $filename;
        $writer->save($tempPath);
        
        // Clear any output buffers before sending headers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set headers for file download with UTF-8 support
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($tempPath));
        
        // Output file and clean up
        readfile($tempPath);
        unlink($tempPath);
        exit; // Use exit instead of return for file downloads
        
    } catch (Exception $e) {
        error_log("Success report generation failed: " . $e->getMessage());
    }
}

function getActionGuidance($error) {
    switch ($error) {
        case 'Product Name (English) is required':
            return 'Fill in the Product Name (English) column.';
        case 'Category ID is required':
            return 'Select a valid Category ID from the "Categories" sheet.';
        case 'Currency ID is required':
            return 'Select a valid Currency ID from the "Currencies" sheet.';
        case 'Unit ID is required':
            return 'Select a valid Unit ID from the "Units" sheet.';
        case 'Packaging ID is required':
            return 'Select a valid Packaging ID from the "Packaging" sheet.';
        case 'Customer ID is required':
            return 'Select a valid Customer ID from the "Clients" sheet.';
        case 'Customer Alias is required':
            return 'Enter a unique Customer Alias for this customer.';
        case 'Category ID must be numeric':
            return 'Ensure the Category ID is a number.';
        case 'Currency ID must be numeric':
            return 'Ensure the Currency ID is a number.';
        case 'Unit ID must be numeric':
            return 'Ensure the Unit ID is a number.';
        case 'Packaging ID must be numeric':
            return 'Ensure the Packaging ID is a number.';
        case 'Customer ID must be numeric':
            return 'Ensure the Customer ID is a number.';
        case 'Category ID does not exist':
            return 'The selected Category ID does not exist in the "Categories" sheet. Please check the sheet.';
        case 'Currency ID does not exist':
            return 'The selected Currency ID does not exist in the "Currencies" sheet. Please check the sheet.';
        case 'Unit ID does not exist':
            return 'The selected Unit ID does not exist in the "Units" sheet. Please check the sheet.';
        case 'Packaging ID does not exist':
            return 'The selected Packaging ID does not exist in the "Packaging" sheet. Please check the sheet.';
        case 'Customer ID does not exist':
            return 'The selected Customer ID does not exist in the "Clients" sheet. Please check the sheet.';
        case 'Product inserted but Customer Alias failed':
            return 'The product was inserted, but the Customer Alias could not be added. This might be due to a duplicate or an issue with the database.';
        case 'Failed to insert product':
            return 'There was an issue inserting the product into the database. Check the database logs for details.';
        case 'Exception:':
            return 'An unexpected error occurred during processing. Please check the PHP error logs.';
        default:
            return 'Please check the "Detailed Errors" sheet for specific error details.';
    }
}

// Helper function to create reference sheets
function createReferenceSheet($spreadsheet, $title, $data, $headers) {
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle($title);
    // Fuente compatible sin emojis
    $defaultStyle = $sheet->getDefaultStyle()->getFont();
    $defaultStyle->setName('Arial');
    
    $sanitizedHeaders = array_map(function($t){ return preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $t); }, $headers);
    $sheet->fromArray($sanitizedHeaders, NULL, 'A1');
    $sheet->getStyle('A1:B1')->getFont()->setBold(true);
    $sheet->getStyle('A1:B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1:B1')->getFill()->getStartColor()->setRGB('4472C4');
    $sheet->getStyle('A1:B1')->getFont()->setColor(new PHPExcel_Style_Color('FFFFFF'));
    
    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(30);
    
    $row = 2;
    foreach($data as $item) {
        $sheet->setCellValue('A' . $row, $item['id']);
        
        // Use the correct column names based on the table
        switch($title) {
            case 'Categories':
                $sheet->setCellValue('B' . $row, sanitizeExcelText($item['name']));
                break;
            case 'Subcategories':
                $sheet->setCellValue('B' . $row, sanitizeExcelText($item['name']));
                break;
            case 'Currencies':
                $sheet->setCellValue('B' . $row, sanitizeExcelText($item['moneytype']));
                break;
            case 'Units':
                $sheet->setCellValue('B' . $row, sanitizeExcelText($item['unittype']));
                break;
            case 'Packaging':
                $sheet->setCellValue('B' . $row, sanitizeExcelText($item['packagingtype']));
                break;
            case 'Clients':
                $sheet->setCellValue('B' . $row, sanitizeExcelText($item['name']));
                break;
            case 'Price Types':
                $sheet->setCellValue('B' . $row, sanitizeExcelText($item['price_type']));
                break;
            default:
                $sheet->setCellValue('B' . $row, sanitizeExcelText($item['name'] ?? 'N/A'));
        }
        
        $row++;
    }
}

include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span> Bulk Import Products</strong>
      </div>
      <div class="panel-body">
        
        <!-- Download Template Section -->
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h4><i class="glyphicon glyphicon-download"></i> Step 1: Download Template</h4>
              </div>
              <div class="panel-body">
                <form method="post">
                  <button type="submit" name="download_template" class="btn btn-success btn-lg">
                    <i class="glyphicon glyphicon-download"></i> Download Excel Template
                  </button>
                </form>
                
                <div class="alert alert-info" style="margin-top: 15px;">
                  <strong>Template includes:</strong> Main sheet for product data + Reference sheets for categories, currencies, units, packaging, clients, and price types.
                </div>
                
                <div class="alert alert-warning">
                  <strong>Important:</strong> Only .xls files (Excel 97-2003) are supported. Save your Excel files in this format before uploading.
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Upload File Section -->
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h4><i class="glyphicon glyphicon-upload"></i> Step 2: Upload Filled Template</h4>
              </div>
              <div class="panel-body">
                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Select Excel File:</label>
                    <div class="col-sm-9">
                      <input type="file" id="excel_file" name="excel_file" accept=".xls" style="display:none" required>
                      <label for="excel_file" class="btn btn-default">Choose file</label>
                      <span id="excel_file_name" class="text-muted" style="margin-left:8px;">No file chosen</span>
                      <small class="text-muted" style="display:block;">Only .xls files are supported (Excel 97-2003 format)</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                      <button type="submit" name="process_import" class="btn btn-primary btn-lg">
                        <i class="glyphicon glyphicon-upload"></i> Process Import
                      </button>
                    </div>
                  </div>
                </form>
                <script>
                document.addEventListener('DOMContentLoaded', function(){
                  var input = document.getElementById('excel_file');
                  var nameSpan = document.getElementById('excel_file_name');
                  if(input && nameSpan){
                    input.addEventListener('change', function(){
                      nameSpan.textContent = input.files && input.files.length ? input.files[0].name : 'No file chosen';
                    });
                  }
                });
                </script>
                
                <div class="alert alert-info">
                  <h4><i class="glyphicon glyphicon-info-sign"></i> Required Fields (marked with *):</h4>
                  <ul>
                    <li><strong>Product Name (English):</strong> Must not be empty</li>
                    <li><strong>Category ID:</strong> Must use valid ID from Categories sheet</li>
                    <li><strong>Currency ID:</strong> Must use valid ID from Currencies sheet</li>
                    <li><strong>Unit ID:</strong> Must use valid ID from Units sheet</li>
                    <li><strong>Packaging ID:</strong> Must use valid ID from Packaging sheet</li>
                    <li><strong>Customer ID:</strong> Must use valid ID from Clients sheet</li>
                    <li><strong>Customer Alias:</strong> Must not be empty and must be unique</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Import Results Section -->
        <?php if(isset($_SESSION['import_results'])): ?>
            <?php $results = $_SESSION['import_results']; ?>
            
            <?php if($results['error_count'] > 0): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <h4><i class="glyphicon glyphicon-exclamation-sign"></i> Import Completed with Errors</h4>
                            <p><strong>Total Rows Processed:</strong> <?php echo $results['total_rows']; ?></p>
                            <p><strong>Successfully Imported:</strong> <span class="text-success"><?php echo $results['success_count']; ?></span></p>
                            <p><strong>Rows with Errors:</strong> <span class="text-danger"><?php echo $results['error_count']; ?></span></p>
                            <p><strong>Error Report:</strong> An Excel file has been downloaded with detailed error information.</p>
                            <p><strong>Next Steps:</strong></p>
                            <ol>
                                <li>Review the error report to see which rows failed and why</li>
                                <li>Fix the errors in your Excel file</li>
                                <li>Re-import the corrected file</li>
                            </ol>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if($results['success_count'] > 0): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <h4><i class="glyphicon glyphicon-ok-sign"></i> Import Successful</h4>
                            <p><strong>Successfully Imported:</strong> <?php echo $results['success_count']; ?> products</p>
                            <p><strong>Success Report:</strong> An Excel file has been downloaded with details of all imported products.</p>
                            <p><strong>Note:</strong> You can now view these products in the main products list.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php 
            // Clear the results from session
            unset($_SESSION['import_results']); 
            ?>
        <?php endif; ?>
        
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>