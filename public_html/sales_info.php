<?php
require_once('includes/load.php');
require_once('libs/PHPExcel/PHPExcel.php');
$all_codevendors = find_all('vendorcode');

class Salesinfo {
//private $productTable = 'products';

//'".$_POST['clientcodeProductid']."'";
public function listSales(){
	global $db;		
	$sqlQuery = "SELECT * FROM sales ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'where id LIKE "%'.$_POST["search"]["value"].'%" ';
		//$sqlQuery .= 'OR id LIKE "%'.$_POST["search"]["value"].'%" ';			
		//$sqlQuery .= ' OR clients_id LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY id DESC ';
	}
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT * FROM sales ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$salesData = array();	



	while($sales = $db->fetch_assoc($result) ) {
		$names = joinsqlname('sales','clients','clients_id','name',$sales['clients_id']);
		$salesRows = array();
		$salesRows[] = $sales['id'];
		$salesRows[] = $sales['numbers'];
		$salesRows[] = $names;


		$salesRows[] = '<div class="btn-group">
                    <a href="edit_sales.php?id='.$sales['id'].'" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';

if ($sales['finalized'] === '1'){

	$salesRows[] = '<button type="button" download="Invoice '.$sales['numbers'].'.xls"  name="download" id="'.$sales['id'].'" class="btn btn-info btn-xs download"><span class="glyphicon glyphicon-download"></span>Download</button>';
}
   else{

   		$salesRows[] = 'Pending to submit';
   }
		

/*
		$salesRows[] = '<div class="btn-group">
                    <a href="download_invoice.php?id='.$sales['id'].'" class="btn btn-info btn-xs" name="download" title="Download" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-download"></span>
                    </a> </div>';
       

*/
	   

		$salesData[] = $salesRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$salesData
	);
	
	echo json_encode($output);
}

public function downloadSales(){

$idsales = $_POST['id'];
$saleid = select_sale($idsales);
$customer_id = select_customer($saleid['clients_id']);
$from = 'templates/invoice.xls';
$to = 'invoices/Invoice '.$saleid['numbers'].'.xls';
//Ejecuto el comando para copiar los archivos de la carpeta from a to
copy($from, $to);
$i = 25;
$fileType = 'Excel5';

//$fileName1 = 'Libro123.xls';
// Read the file
$objReader = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objReader->load($to);
// Change the file
$itemSales = items_sales_excel($idsales);

$items_Sc = items_sc_sales($idsales);

$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D10',''.$customer_id['name'].'')
      ->setCellValue('D11',''.$customer_id['taxpayer'].'')
      ->setCellValue('D12',''.$customer_id['address'].'')
      ->setCellValue('D13',''.$customer_id['country'].' '.$customer_id['city'].''); 


$containers = container_sales_excel($idsales);
$f = 15;
$e = 15;
foreach ($containers as $container){
     if($f <= 19 ) {
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$f.'',''.$container['name'].'')
      ->setCellValue('D'.$f.'',''.$container['sealn'].'');   
      $f++;
     }
     
     elseif($e < 19){
     $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('E'.$e.'',''.$container['name'].'')
      ->setCellValue('H'.$e.'',''.$container['sealn'].'');   
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



$total_comission = sum_total_comission($saleid['id'],$saleid['comission']);
$total_usd_comission = $total_comission / $saleid['currencyrate'];
$total_tot_RMB = $total_comission / $saleid['comission'] * '100';
$total_tot_USD = $total_tot_RMB / $saleid['currencyrate'];

$total_com_dec =round($total_comission, 2);
$total_com_dec_usd = round($total_usd_comission, 2);

$sum_USD_sc = sum_usd_sc($idsales);
$sum_RMB_sc = sum_rmb_sc($idsales);
$sum_USD_sc_dec = round($sum_USD_sc, 2);
$sum_RMB_sc_dec = round($sum_RMB_sc, 2);

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D'.$i.'','TOTAL')
->setCellValue('J'.$i.'',''.round($total_tot_USD,2).'')
->setCellValue('K'.$i.'',''.round($total_tot_RMB,2).'');

$i++;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D'.$i.'','SHIPPING COST')
->setCellValue('J'.$i.'',''.$sum_USD_sc_dec.'')
->setCellValue('K'.$i.'',''.$sum_RMB_sc_dec.'');

$i++;

$comisionString = "COMISION            {$saleid['comission']}%";
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D'.$i.'', ''.$comisionString.'' )
->setCellValue('J'.$i.'',''.$total_com_dec_usd.'')
->setCellValue('K'.$i.'',''.$total_com_dec.'');

$i++;
//problema esta aca en esta suma 
$total_fob_USD = $total_tot_USD + $total_com_dec_usd + $sum_USD_sc;
$total_fob_RMB = $total_tot_RMB + $sum_RMB_sc + $total_com_dec;


$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D'.$i.'','TOTAL FOB NINGBO')
->setCellValue('J'.$i.'',''.$total_fob_USD.'')
->setCellValue('K'.$i.'',''.$total_fob_RMB.'');





$n= $i + 4;
$t= $i + 3;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D'.$t.'','SHIPPING COST YIWU')
->setCellValue('D'.$n.'','CONCEPT')
->setCellValue('E'.$n.'','Currency rate')
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



/*
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('D'.$x.'','COMISSION')
->setCellValue('E'.$x.'',''.$saleid['currencyrate'].'')
->setCellValue('F'.$x.'',''.$total_com_dec_usd.'')
->setCellValue('G'.$x.'',''.$total_com_dec.'');


$x++;
*/




$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('E'.$x.'','TOTAL')
->setCellValue('F'.$x.'',''.$sum_USD_sc_dec.'')
->setCellValue('G'.$x.'',''.$sum_RMB_sc_dec.'');




//Insertar imagen en la factura.

$objDrawingPType = new PHPExcel_Worksheet_Drawing();
$objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
$objDrawingPType->setName("logoinvoice.png");
$objDrawingPType->setPath("templates/logoinvoice.png");
$objDrawingPType->setCoordinates('A1');
$objDrawingPType->setWidth(5);                 //set width, height
$objDrawingPType->setHeight(150);
$objDrawingPType->setOffsetX(1);
$objDrawingPType->setOffsetY(5);





$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
$objWriter->save($to);


$filename = 'Invoice'.$saleid['numbers'].'.xls';
		


		
		echo json_encode($saleid);


}
}

?>





