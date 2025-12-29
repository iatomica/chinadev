<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $rg = find_by_id('receivedgoods',(int)$_GET['id']);
  if(!$rg){
    $session->msg("d","ID vacío");
    redirect('receivedgoods.php');
  }
?>
<?php
  $delete_id = close_reg('receivedgoods',(int)$rg['id']);
  if($delete_id){
      $session->msg("s","Received Goods is closed");
      redirect('receivedgoods.php');
  } else {
      $session->msg("d","Fail the process");
      redirect('receivedgoods.php');
  }
?>

