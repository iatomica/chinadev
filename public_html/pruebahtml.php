<?php
  $page_title = 'Received goods list';
require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
require_once('libs/PHPExcel/PHPExcel.php');
 
?>

  /*

    $sales_ammount = sum_items_sales($salesid['id']);
    $credit = sum_credit_client($salesid['id']);
    $debit = sum_debit_client($salesid['clients_id']) + $sales_ammount;
    $sales_balance = $debit - $credit;
    
    $query  = "INSERT INTO currentaccount (";
    $query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,sales_id";
    $query .=") VALUES (";
    $query .="'NULL','NULL','{$sales_ammount}','0',NULL,'{$salesid['clients_id']}',NULL,'5','{$sales_balance}','{$date}','{$salesid['id']}'";
    $query .=")";
       
       $db->query($query);
        

finalized_sales($salesid['id']);

redirect('sales.php',false);
}
*/
?>

<?php include_once('layouts/header.php'); ?>

  

     <body>
       
<form>

      <button type="button" name="desaprobe" id="desaprobe" class="btn btn-info btn-xs download"><span class="glyphicon glyphicon-download"></span>Download</button>
           </form>
  </body>
 
  <?php include_once('layouts/footer.php'); ?>

