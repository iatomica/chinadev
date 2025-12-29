<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $po_product = find_by_id('detail_po',(int)$_GET['id']);
  if(!$po_product){
    $session->msg("d","ID vacío");
    
  }
?>
<?php
  change_status_item_po((int)$po_product['id']);
  
?>

