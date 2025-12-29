<?php
require_once('includes/load.php');
$output = '';
global $db;

if(isset($_POST["exp_products_excel"]))
{
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
$spreadsheet = new Spreadsheet();
$Excel_writer = new Xlsx($spreadsheet);
 
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();
 
$activeSheet->setCellValue('A1', 'Id');
$activeSheet->setCellValue('B1', 'Description English');
$activeSheet->setCellValue('C1', 'Description Spanish');
$activeSheet->setCellValue('D1', 'F.O.B');
$activeSheet->setCellValue('E1', 'Money');
$activeSheet->setCellValue('F1', 'Color');
$activeSheet->setCellValue('G1', 'Material');
$activeSheet->setCellValue('H1', 'Size');
$activeSheet->setCellValue('I1', 'CBM');
$activeSheet->setCellValue('J1', 'Packing');
$activeSheet->setCellValue('K1', 'Inner');
$activeSheet->setCellValue('L1', 'Unit');
$activeSheet->setCellValue('M1', 'Packaging');
$activeSheet->setCellValue('N1', 'EAN13');
$activeSheet->setCellValue('O1', 'DUN14');
 
$query = $db->query("SELECT * FROM products WHERE openclose = '1'");
 
if($query->num_rows > 0) {
    $i = 2;
    while($row = $query->fetch_assoc()) {
        $activeSheet->setCellValue('A'.$i , $row['id']);
        $activeSheet->setCellValue('B'.$i , $row['desc_english']);
        $activeSheet->setCellValue('C'.$i , $row['desc_spanish']);
        $activeSheet->setCellValue('D'.$i , $row['price']);
        $activeSheet->setCellValue('E'.$i , $money);
        $activeSheet->setCellValue('F'.$i , $row['color']);
        $activeSheet->setCellValue('G'.$i , $row['material']);
        $activeSheet->setCellValue('H'.$i , $row['size']);
        $activeSheet->setCellValue('I'.$i , $row['cbm']);
        $activeSheet->setCellValue('J'.$i , $row['packing']);
        $activeSheet->setCellValue('K'.$i , $row['inners']);
        $activeSheet->setCellValue('L'.$i , $unit);
        $activeSheet->setCellValue('M'.$i , $packaging);
        $activeSheet->setCellValue('N'.$i , $row['ean13']);
        $activeSheet->setCellValue('O'.$i , $row['dun14']);
        $i++;
        
    }
}
 
$filename = 'products.xlsx';
 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='. $filename);
header('Cache-Control: max-age=0');
$Excel_writer->save('php://output');

}
?>