<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $po = find_by_id('purchaseorder',(int)$_GET['id']);
  if(!$po){
    $session->msg("d","ID vacío");
    redirect('purchaseorder.php');
  }
?>
<?php
  $delete_id = close_reg('purchaseorder',(int)$po['id']);
  if($delete_id){
      $session->msg("s","Purchase order is closed");
      redirect('purchaseorder.php');
  } else {
      $session->msg("d","Fail the process");
      redirect('purchaseorder.php');
  }
?>
