<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_id('products',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","ID vacío");
    redirect('product.php');
  }
?>
<?php
  $delete_id = close_reg('products',(int)$product['id']);
  if($delete_id){
      $session->msg("s","Product is closed");
      redirect('product.php');
  } else {
      $session->msg("d","Fail the process");
      redirect('product.php');
  }
?>
