<?php
require_once('libs/PHPExcel/PHPExcel.php');

// Retrieve the JSON data sent from the client-side
$data = json_decode($_POST['data'], true);

// Define the column names
$columnNames = array('Id', 'Image', 'Description', 'Price', 'Customer Alias', 'Price Type', 'Money Type', 'Color', 'Material', 'Size', 'CBM', 'UXB','Categorye','Subcategorye','Inners','Unit Type','Packagingtype','Code Bar Ean13','Code Bar Dun14','Volume','Pruduct Weight', 'CTN Netweight', 'CTN Grossweight');
 // Replace with your actual column names

// Define the desired column order
// Reorder so that Customer Alias (4) quede justo después de Image (1)
$columnOrder = array( 0,1,4,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,17,18);

$reorderedColumnNames = array();
foreach ($columnOrder as $index) {
    $reorderedColumnNames[] = $columnNames[$index];
}

// Create a new PHPExcel object (You may need to install the PHPExcel library)
$objPHPExcel = new PHPExcel();

// Set the column names as the first row in the worksheet
$objPHPExcel->getActiveSheet()->fromArray(array($reorderedColumnNames), NULL, 'A1');

// Set the data to the worksheet starting from the second row
$rowIndex = 2;
foreach ($data as $row) {
    $reorderedRow = array();
    foreach ($columnOrder as $index) {
        $reorderedRow[] = $row[$index];
    }

    // Extract src value from the Image column
    $imageIndex = array_search('Image', $columnNames); // original index of Image
    if ($imageIndex !== false) {
        // find position of Image within the reordered array
        $imagePos = array_search($imageIndex, $columnOrder);
        $imageHTML = isset($reorderedRow[$imagePos]) ? $reorderedRow[$imagePos] : '';
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

        $objDrawingPType->setCoordinates('B' .$rowIndex);
       
        $objDrawingPType->setOffsetX(10);
        $objDrawingPType->setOffsetY(10);
        $objDrawingPType->setWidth(5);                 //set width, height
        $objDrawingPType->setHeight(75);
        $objDrawingPType->setOffsetX(1);
        $objDrawingPType->setOffsetY(5);

        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Adjust the divisor for desired width
        $objPHPExcel->getActiveSheet()->getRowDimension($rowIndex)->setRowHeight(75); // Adjust the additional height as needed
    
        

        // Clear textual/HTML content for image cell to avoid showing URL/HTML under the picture
        if ($imagePos !== false) {
            $reorderedRow[$imagePos] = '';
        }
    }

    // Set the rest of the row values
    $objPHPExcel->getActiveSheet()->fromArray(array($reorderedRow), NULL, 'A' . $rowIndex);
    $rowIndex++;
}

// Save the file to the specified folder
$filename = 'exported_products.xls';
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