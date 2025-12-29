<?php
require_once('includes/load.php');

require('libs/libraryexcel/php-excel-reader/excel_reader2.php');
require('libs/libraryexcel/SpreadsheetReader.php');



if(isset($_POST['submiter'])){


  $mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet'];
  if(in_array($_FILES["file"]["type"],$mimes)){


    $uploadFilePath = 'uploads/products/'.basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);


    $Reader = new SpreadsheetReader($uploadFilePath);


    $totalSheet = count($Reader->sheets());

   
    echo "You have total ".$totalSheet." sheets";


    $html="<table>";
    //$html.="<tr><th>name</th><th>address</th></tr>";


    
    for($i=0;$i<$totalSheet;$i++){


      $Reader->ChangeSheet($i);


      foreach ($Reader as $Row)
      {
        $cusid = remove_junk($db->escape(intvar($_POST['customer-import'])));
        $html.="<tr>";
        $cusalias = isset($Row[0]) ? $Row[0] : '';
        $uxb = isset($Row[1]) ? $Row[1] : '';
        $qty= isset($Row[2]) ? $Row[2] : '';
        $price = isset($Row[3]) ? $Row[3] : '';
        $eta = isset($Row[4]) ? $Row[4] : '';
        $html.="<td>".$cusalias."</td>";
        $html.="<td>".$uxb."</td>";
        $html.="<td>".$qty."</td>";
        $html.="<td>".$price."</td>";
        $html.="</tr>";

        $item = charge_po_items_excel($cusalias,$cusid);
        
        

        $ctn = $qty / $uxb;
        $total_cbm = $ctn * $item['cbm'];
        $total_gw = $qty * $item['grossweight'];
        $total_nw = $qty * $item['netweight'];
        $total_price = $qty * $price;
       
        $po = remove_junk($db->escape(intvar($_POST['poid-import'])));
        /*

        $ctn = '10';
        $total_cbm = '10';
        $total_gw = '10';
        $total_nw = '10';
        $total_price = '10';
        $eta = '2021-12-12';
        $po = '22';

*/

        $query = "insert into detail_po (clientcode_po, desc_pr_po,uxb_po,price_po,cbm_po,qty_po,gw,nw, ctn,cbm_total,gw_total,nw_total,price_total,eta,purchaseorder_id,products_id,finalized,pendent,volume) values ('".$item['clientcode']."','".$item['desc_english']."','".$uxb."','".$price."','".$item['cbm']."','".$qty."','".$item['grossweight']."','".$item['netweight']."','".$ctn."','".$total_cbm."','".$total_gw."','".$total_nw."','".$total_price."','".$eta."','".$po."','".$item['products_id']."',0,'".$qty."','".$item['volume']."')";


        $db->query($query);

        }


    }


    $html.="</table>";
    echo $html;
    echo "<br> Data Inserted in dababase </br>";


  }else { 
    die("<br> Sorry, File type is not allowed. Only Excel file </br>"); 
  }


}


?>