<?php
require_once('includes/load.php');
require_once('libs/PHPExcel/PHPExcel.php');
class Export_info{

public function exportAccounts(){

$from = 'templates/currentaccounts.xls';
$to = 'exports/currentaccounts.xls';
//Ejecuto el comando para copiar los archivos de la carpeta from a to
copy($from, $to);

$fileType = 'Excel5';
$idcustomer = $_POST['idcustomer'];
$objReader1 = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel1 = $objReader1->load($to);

$cus_accounts = items_currentaccount($idcustomer);
$f=2;
foreach ($cus_accounts as $cus_account){

$objPHPExcel1->setActiveSheetIndex(0)
->setCellValue('A'.$f.'',''.$cus_account['debit'].'')
->setCellValue('B'.$f.'',''.$cus_account['credit'].'')  
->setCellValue('C'.$f.'',''.$cus_account['balance'].''); 
$f++;

}

$objWriter1 = PHPExcel_IOFactory::createWriter($objPHPExcel1, $fileType);
$objWriter1->save($to);


$filename = 'currentaccount.xls';
		
$resulta3 = $_POST['resultado'];

echo json_encode($resulta3);		
		


}

public function exportProducts(){




$from = 'templates/exportproducts.xls';
$to = 'exports/exportproducts.xls';
//Ejecuto el comando para copiar los archivos de la carpeta from a to
copy($from, $to);

$fileType = 'Excel5';
$idcustomer = $_POST['idcustomer1'];

if ($idcustomer === '0'){
$objReader1 = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel1 = $objReader1->load($to);

$exports = select_products_exports_all($idcustomer);


$f=2;
foreach ($exports as $exp){

$objPHPExcel1->setActiveSheetIndex(0)
->setCellValue('A'.$f.'',''.$exp['pid'].'')

->setCellValue('B'.$f.'',''.$exp['clientcode'].'')
->setCellValue('C'.$f.'',''.$exp['desc_english'].'')
->setCellValue('D'.$f.'',''.$exp['price'].'')
->setCellValue('E'.$f.'',''.$exp['price_type'].'')
->setCellValue('F'.$f.'',''.$exp['moneytype'].'')
->setCellValue('G'.$f.'',''.$exp['color'].'')
->setCellValue('H'.$f.'',''.$exp['material'].'')
->setCellValue('I'.$f.'',''.$exp['size'].'')
->setCellValue('J'.$f.'',''.$exp['cbm'].'')
->setCellValue('K'.$f.'',''.$exp['uxb'].'')
->setCellValue('L'.$f.'',''.$exp['categorye'].'')
->setCellValue('M'.$f.'',''.$exp['subcategorye'].'')
->setCellValue('N'.$f.'',''.$exp['inners'].'')
->setCellValue('O'.$f.'',''.$exp['unittype'].'')
->setCellValue('P'.$f.'',''.$exp['packagingtype'].'')
->setCellValue('Q'.$f.'',''.$exp['ean13'].'')
->setCellValue('R'.$f.'',''.$exp['volume'].'')
->setCellValue('S'.$f.'',''.$exp['product_weight'].'')
->setCellValue('T'.$f.'',''.$exp['netweight'].'')

->setCellValue('U'.$f.'',''.$exp['grossweight'].''); 
$f++;

}

$objWriter1 = PHPExcel_IOFactory::createWriter($objPHPExcel1, $fileType);
$objWriter1->save($to);


$filename = 'exportproducts.xls';
		

}

else{

$objReader1 = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel1 = $objReader1->load($to);

$exports = select_products_exports($idcustomer);


$f=2;
foreach ($exports as $exp){



$objPHPExcel1->setActiveSheetIndex(0)
->setCellValue('A'.$f.'',''.$exp['pid'].'')
->setCellValue('B'.$f.'',''.$exp['clientcode'].'')
->setCellValue('C'.$f.'',''.$exp['desc_english'].'')
->setCellValue('D'.$f.'',''.$exp['price'].'')
->setCellValue('E'.$f.'',''.$exp['price_type'].'')
->setCellValue('F'.$f.'',''.$exp['moneytype'].'')
->setCellValue('G'.$f.'',''.$exp['color'].'')
->setCellValue('H'.$f.'',''.$exp['material'].'')
->setCellValue('I'.$f.'',''.$exp['size'].'')
->setCellValue('J'.$f.'',''.$exp['cbm'].'')
->setCellValue('K'.$f.'',''.$exp['uxb'].'')
->setCellValue('L'.$f.'','categorye')
->setCellValue('M'.$f.'','subcategorye')
->setCellValue('N'.$f.'',''.$exp['inners'].'')
->setCellValue('O'.$f.'',''.$exp['unittype'].'')
->setCellValue('P'.$f.'',''.$exp['packagingtype'].'')
->setCellValue('Q'.$f.'',''.$exp['ean13'].'')
->setCellValue('R'.$f.'',''.$exp['volume'].'')
->setCellValue('S'.$f.'',''.$exp['product_weight'].'')
->setCellValue('T'.$f.'',''.$exp['netweight'].'')

->setCellValue('U'.$f.'',''.$exp['grossweight'].''); 
$f++;
//$idpr = select_cat_sub('4');
/*
$objPHPExcel1->setActiveSheetIndex(0)
->setCellValue('T'.$f.'',''.$idpr['name'].'')

->setCellValue('U'.$f.'',''.$idpr['name'].''); 
*/

}

$objWriter1 = PHPExcel_IOFactory::createWriter($objPHPExcel1, $fileType);
$objWriter1->save($to);


$filename = 'exportproducts.xls';
		


}
echo json_encode($exports);


}

public function exportMakers(){

$from = 'templates/exportmakers.xls';
$to = 'exports/exportmakers.xls';
//Ejecuto el comando para copiar los archivos de la carpeta from a to
copy($from, $to);

$fileType = 'Excel5';
$idmaker = $_POST['idmaker'];

if($idmaker === '0'){

$objReader1 = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel1 = $objReader1->load($to);

$maker = select_maker_export_all();
$f=2;
foreach ($maker as $mak){

$objPHPExcel1->setActiveSheetIndex(0)
->setCellValue('A'.$f.'',''.$mak['id'].'')
->setCellValue('B'.$f.'',''.$mak['name'].'')  
->setCellValue('C'.$f.'',''.$mak['bank'].'')
->setCellValue('D'.$f.'',''.$mak['bank_account'].'')
->setCellValue('E'.$f.'',''.$mak['phone'].'');
$f++;

}

$objWriter1 = PHPExcel_IOFactory::createWriter($objPHPExcel1, $fileType);
$objWriter1->save($to);


$filename = 'exportmakers.xls';
		


echo json_encode($filename);	


}
else{
$objReader1 = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel1 = $objReader1->load($to);

$maker = select_maker_export($idmaker);
$f=2;
foreach ($maker as $mak){

$objPHPExcel1->setActiveSheetIndex(0)
->setCellValue('A'.$f.'',''.$mak['id'].'')
->setCellValue('B'.$f.'',''.$mak['name'].'')  
->setCellValue('C'.$f.'',''.$mak['bank'].'')
->setCellValue('D'.$f.'',''.$mak['bank_account'].'')
->setCellValue('E'.$f.'',''.$mak['phone'].'');
$f++;

}

$objWriter1 = PHPExcel_IOFactory::createWriter($objPHPExcel1, $fileType);
$objWriter1->save($to);


$filename = 'exportmakers.xls';
		


echo json_encode($filename);		
}
}



}

?>

