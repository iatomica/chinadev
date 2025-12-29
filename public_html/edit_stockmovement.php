<?php
 $page_title = 'Edit movement stock '.$smid.'';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$all_movement_type = find_all('movements_type');
$all_stock_deposit = find_all('stock_deposit');
$smid = find_by_id('stockmovements',(int)$_GET['id']);
$all_clients = find_all('clients');
$stock_negative = false;
//$page_title = 'Update Purchase Order '.$poid.'';
?>

<?php

if(isset($_POST['insert_stockmovement'])){

  $sm_customer = remove_junk($db->escape($_POST['client-editstock']));
  
  if ($smid['movements_type_id'] === '1'){

  $search_sm = search_stock_movement($smid['id']);

  

  foreach ($search_sm as $sm) {
    $select_stock = select_stock($sm_customer,$sm['products_id']);
   
    $qty_final = $select_stock['qty'] + $sm['qty_sm'];

      update_stock($qty_final,$select_stock['id']);
    
      update_item_status_detail_sm($sm['id'], '1');

      if (search_stock_movement_finalized($smid['id']) === true){
            $session->msg('s','Any items in a negative stock');
          }
              else
              {
                update_status_stockmovement($smid['id']);

              }
      
         //update_stock($qty_final,$select_stock['id']);
         //update_item_done_detail_sm($sm['id']);
      /*    
          if (search_stock_movement_finalized($sm['id']) === true){
            $session->msg('s','Any items in a negative stock');
              }
              else
              {
                update_status_stockmovement($smid['id']);

              }
*/
/*

      if ($stock_negative === false && $qty_final < '0'){

          $session->msg('s','Any items in a negative stock');
          
         update_item_status_detail_sm($sm['id'], '0');
          }
    else{
          update_stock($qty_final,$select_stock['id']);
          update_item_status_detail_sm($sm['id'], '1');
    if (search_stock_movement_finalized($smid['id']) === true){
            $session->msg('s','Any items in a negative stock');
          }
              else
              {
                update_status_stockmovement($smid['id']);

              }
*/


    }
    

  }


}

/*
elseif ($smid['movements_type_id'] === '2'){
  
  $search_smto = search_stock_movement($smid['id']);

  foreach ($search_smto as $smto) {
     
     $select_stock_deposit = select_stock($smid['stock_deposit_id'],$smto['products_id']); 
     $qty_final_deposit = $select_stock_deposit['qty'] - $smto['qty_sm'];

     
      if ($stock_negative === false && $qty_final_deposit < '0'){
          
       
          $session->msg('s','Any items in a negative stock');
          update_item_status_detail_sm($smto['id'], '0');
          
          }
          else{
           update_stock($qty_final_deposit, $select_stock_deposit['id']);
           
           update_item_status_detail_sm($smto['id'], '1');
          $select_stock_deposit_to = select_stock($smid['stock_deposit_to_id'],$smto['products_id']);
          

          $qty_final_deposit_to = $select_stock_deposit_to['qty'] + $smto['qty_sm'];
          update_stock($qty_final_deposit_to, $select_stock_deposit_to['id']);
          if (search_stock_movement_finalized($smid['id']) === true){
            $session->msg('s','Any items in a negative stock');
          }
              else
              {
                update_status_stockmovement($smid['id']);

              }
          }
            
          /*
     

      

     

       


  }

}

*/



?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Stock Movement N° <?php echo $smid['id'] ?></span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="insertstockmovement" method="post" action="edit_stockmovement.php?id=<?php echo (int)$smid['id'] ?>" class="clearfix" enctype="multipart/form-data" >
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Movement Type</label>
              	<select class="form-control" name="movement-sm" id="movement-sm" disabled>
                      <option value="">Select Type</option>
                      <?php  foreach ($all_movement_type as $mt): ?>
                      <option value="<?php echo (int)$mt['id'];?>"<?php if($smid['movements_type_id'] === $mt['id']): echo "selected"; endif; ?>>
                      <?php echo $mt['movementtype'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				          </div>
                </div>
             </div>
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label> 
                  <select class="form-control" name="client-editstock" id="client-editstock">
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>"<?php if($smid['clients_id'] === $cli['id']): echo "selected"; endif; ?>>
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                   </div>
                </div>
             </div>
            
        
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="qty">Reason</label>
              <div class="input-group">
               <input type="text" class="form-control" name="reason-sm" placeholder="Insert Reason" value="<?php echo remove_junk($smid['observation']);?>" autocomplete="off" disabled>                    
             </div>
           </div>
         </div>
       </div>
	<div><input type="hidden" name="smid" id="smid" value="<?php echo remove_junk($smid['id']);?>"/></div>
         
	
	 
   
         <div class="table-responsive">
  
              <table class="table table-bordered table-striped"  name="detail-sm" id="detail-sm">
                <thead>
                <tr>
                  <th>Id Product</th>
                  <th>Customer Alias</th>
                  <th>Description</th>
                  <th>QTY</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
            </thead>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
            </table>  

            <div><button type="button" name="additem-sm" id="additem-sm" class="btn btn-success btn-xs" <?php if($smid['finalized'] === '1'): echo "disabled"; endif; ?>>Add item</button></div>
            <div class="pull-right">
           <button type="submit" name="insert_stockmovement" class="btn btn-primary" <?php if($smid['finalized'] === '1'): echo "disabled"; endif; ?>>Submit</button>
         </div>
         </form>
        </div>
		  </div>
		</div>
	</div>
  </form>
</div>
</div>
<div id="smModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form method="post" id="smForm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><i class="fa fa-plus"></i>Add item</h4>
          
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Customer Alias</label>
            <input type="text" class="form-control" id="sm-code-name" name="sm-code-name" placeholder="Customer Alias" autocomplete="off" required>
            <div id="sm-clientcodename"></div>      
          </div>
          <div><input type="hidden" name="sm-id" id="sm-id" value="<?php echo remove_junk($smid['id']);?>"/></div>
          <div class="form-group">
            <label for="lastname" class="control-label">Id Product</label>              
            <input type="text" readonly="readonly" class="form-control"  id="sm-productid" name="sm-productid" placeholder="Product id" >             
          </div>   
           <div class="form-group">
            <label for="name" class="control-label">Descripcion</label>
            <input type="text" class="form-control" id="sm-code-description" name="sm-code-description" placeholder="Description" readonly="readonly">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">QTY</label>
            <input type="number" class="form-control" id="sm-code-qty" name="sm-code-qty" placeholder="QTY" required autocomplete="off">      
          </div>
        <div class="modal-footer">
          
          <input type="hidden" name="sm-code-qty-hidden" id="sm-code-qty-hidden" value=""/>
          <input type="hidden" name="sm-ids" id="sm-ids" value=""/>
          <input type="hidden" name="action" id="action" value=""/>
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
   </form>
</div>
</div>







<?php include_once('layouts/footer.php'); ?>
