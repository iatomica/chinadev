<?php
  $sales = find_by_id('sales',(int)$_GET['id']);
  if(!$sales){
    $session->msg("d","ID vacío");
    redirect('vendors.php');
  }
?>
<?php
require_once('includes/load.php');

require_once('libs/PHPExcel/PHPExcel.php');

$idsales = '1';
$from = 'templates/invoice.xls';
$to = 'invoices/invoice.xls';
//Ejecuto el comando para copiar los archivos de la carpeta from a to
copy($from, $to);
$i = 25;
$fileType = 'Excel5';

//$fileName1 = 'Libro123.xls';
// Read the file
$objReader = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objReader->load($to);
// Change the file
$itemSales = items_sales_excel($sales['id']);
$saleid = select_sale($sales['id']);

$items_Sc = items_sc_sales($sales['id']);


$containers = container_sales_excel($idsales);
$f = 15;
$e = 15;
foreach ($containers as $container){
     if($f <= 19 ) {
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D'.$f.'',''.$container['name'].'');    
      $f++;
     }
     
     elseif($e < 19){
     $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('E'.$e.'',''.$container['name'].'');    
      $e++;

     }


}




$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('J10',''.$saleid['numbers'].'')
->setCellValue('J12',''.$saleid['date'].'');




foreach ($itemSales as $item){
$unitusd = $item['price_sales'] / $saleid['currencyrate'];
$totalusd = $unitusd * $item['qty_sales'];
$totalrmb = $item['price_sales'] * $item['qty_sales'];

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$i.'',''.$item['clientcode_sales'].'')
->setCellValue('B'.$i.'',''.$item['tp'].'')
->setCellValue('C'.$i.'','YI')
->setCellValue('D'.$i.'',''.$item['desc_pr_sales'].'')
->setCellValue('E'.$i.'',''.$item['qty_sales'].'')
->setCellValue('F'.$i.'',''.$item['unittype'].'')
->setCellValue('G'.$i.'',''.$item['price_sales'].'')
->setCellValue('H'.$i.'',''.$saleid['currencyrate'].'')
->setCellValue('I'.$i.'',''.$unitusd.'')
->setCellValue('J'.$i.'',''.$totalusd.'')
->setCellValue('K'.$i.'',''.$totalrmb.'');
$i++;
}

$n= $i + 4;
$t= $i + 3;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D'.$t.'','SHIPPING COST YIWU')
->setCellValue('D'.$n.'','CONCEPT')
->setCellValue('E'.$n.'','CURRENCY RATE')
->setCellValue('F'.$n.'','USD')
->setCellValue('G'.$n.'','RMB');

$x= $i + 5;
// Write the file

foreach ($items_Sc as $itemsc){
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D'.$x.'',''.$itemsc['concept'].'')
->setCellValue('E'.$x.'',''.$itemsc['currencyrate'].'')
->setCellValue('F'.$x.'',''.$itemsc['price_usd'].'')
->setCellValue('G'.$x.'',''.$itemsc['price_rmb'].'');
$x++;
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
$objWriter->save($to);






?>