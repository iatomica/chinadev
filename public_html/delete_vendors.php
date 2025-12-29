<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $vendors = find_by_id('vendors',(int)$_GET['id']);
  if(!$vendors){
    $session->msg("d","ID vacío");
    redirect('vendors.php');
  }
?>
<?php
  $delete_id = close_reg('vendors',(int)$vendors['id']);
  if($delete_id){
      $session->msg("s","Vendor is closed");
      redirect('vendors.php');
  } else {
      $session->msg("d","Fail the process");
      redirect('vendors.php');
  }
?>
