<?php


require_once('libs/PHPExcel/PHPExcel.php');

// Retrieve the JSON data sent from the client-side
$data = json_decode($_POST['data'], true);

// Define the column names
$columnNames = array('Id','Image','Customer Alias', 'Description','Supplier' , 'Customer', 'TP','UXB', 'QTY', 'CTN', 'PRICE', 'TOTAL AMMOUNT','Unit', 'CBM','GW','NW');
 // Replace with your actual column names

// Define the desired column order
$columnOrder = array(0,4,5,6,2,3,7,8,9,10,11,12,13,14,15,1); // Replace with the desired column order indices

$reorderedColumnNames = array();
foreach ($columnOrder as $index) {
    $reorderedColumnNames[] = $columnNames[$index];
}

// Create a new PHPExcel object (You may need to install the PHPExcel library)
$objPHPExcel = new PHPExcel();

// Set the column names as the first row in the worksheet
$objPHPExcel->getActiveSheet()->fromArray(array($reorderedColumnNames), NULL, 'A1');

// Set the data to the worksheet starting from the second row
$rowIndex = 2 ;
foreach ($data as $row) {
    $reorderedRow = array();
    foreach ($columnOrder as $index) {
        $reorderedRow[] = $row[$index];
    }

    // Extract src value from the Image column
    $imageIndex = array_search(1, $columnOrder); // Replace 'Image' with the actual column name
    if ($imageIndex !== false) {
        $imageHTML = $reorderedRow[$imageIndex];
        $pattern = '/src="(.*?)"/'; // Regular expression pattern to match the src attribute value
        preg_match($pattern, $imageHTML, $matches);
        $srcValue = isset($matches[1]) ? $matches[1] : ''; // Extracted src value
        
        // Add image to the Excel worksheet
        $objDrawingPType = new PHPExcel_Worksheet_Drawing();
        $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        $objDrawingPType->setName("Image");
        
        $objDrawingPType->setPath($srcValue);


        // Calculate the dimensions of the image
        list($width, $height, $type, $attr) = getimagesize($srcValue);

        $objDrawingPType->setCoordinates('P' .$rowIndex);
       
        $objDrawingPType->setOffsetX(10);
        $objDrawingPType->setOffsetY(10);
        $objDrawingPType->setWidth(5);                 //set width, height
        $objDrawingPType->setHeight(75);
        $objDrawingPType->setOffsetX(1);
        $objDrawingPType->setOffsetY(5);

        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15); // Adjust the divisor for desired width
        $objPHPExcel->getActiveSheet()->getRowDimension($rowIndex)->setRowHeight(75); // Adjust the additional height as needed
    
        

        // Set the image object as the value of the cell
       
    }

    // Set the rest of the row values
    $objPHPExcel->getActiveSheet()->fromArray(array($reorderedRow), NULL, 'A' . $rowIndex);
    $rowIndex++;
}
     
    $columnaP = 'P';
    for ($fila = 1; $fila <= $rowIndex; $fila++) {
    $celdaP = $columnaP . $fila;
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue($celdaP,'');
    
        }
    
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('P1','Image');

// Save the file to the specified folder
$filename = 'stockcheckup.xls';
$path = 'exports/' . $filename; // Specify the folder path where you want to save the file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($path);

// Check if the file was saved successfully and send the response to the client
if (file_exists($path)) {
    echo 'success';
} else {
    echo 'error';
}
?>
