<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $clients = find_by_id('clients',(int)$_GET['id']);
  if(!$clients){
    $session->msg("d","ID vacío");
    redirect('clients.php');
  }
?>
<?php
  $delete_id = close_reg('clients',(int)$clients['id']);
  if($delete_id){
      $session->msg("s","Client is closed");
      redirect('clients.php');
  } else {
      $session->msg("d","Fail the process");
      redirect('clients.php');
  }
?>

