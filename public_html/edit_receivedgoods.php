<?php
 $page_title = 'Update Received goods '.$rgid.'';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$all_vendors = find_all('vendors');
$all_clients = find_all('clients');
$rgid = find_by_id('receivedgoods',(int)$_GET['id']);
$date = make_date();
$all_stock_deposit = find_all('stock_deposit');
//$page_title = 'Update Purchase Order '.$poid.'';
?>

<?php

if(isset($_POST['insert_receivedgoods'])){
 
  $select_items_rg = select_receivedgoods($rgid['id']);

  foreach ($select_items_rg as $select) {
   
    $select_items_po = select_product_po($select['purchaseorder_id'],$select['products_id']);
        
    $qty_po_final = $select_items_po['pendent'] - $select['qty_rg'];
    
   
    
    if($qty_po_final <= '0' && $select_items_po['finalized'] === '0'){

        pendent_po_update('0',$select_items_po['id']);

        pendent_detail_po_finalized($select_items_po['id']);
           
        

        
        //ver de finalizar aun asi finalized es =1 y en po ya esta finalizado

    }
    else{

          pendent_po_update($qty_po_final,$select_items_po['id']);

        }

          update_item_products($select['products_id'],$select['cbm_rg'],$select['nw'],$select['gw'],$select['volume'],$select['uxb_rg']);

          status_rgdt_update($select['id']);

          status_rg_update($rgid['id']);

          $qty_stock = select_stock($rgid['clients_id'],$select['products_id']);
          $qty_stock_final = $qty_stock['qty'] + $select['qty_rg'];
          
          update_stock($qty_stock_final,$qty_stock['id']);
          $status_po = select_change_status_item_po($select['purchaseorder_id']);
          if($status_po === '0'){
          update_status_po($select['purchaseorder_id']);

      
      }


}
   $rg_ammount = sum_items_rg($rgid['id']);
   $credit = sum_credit_vendor($rgid['vendors_id'])+ $rg_ammount;
   $debit = sum_debit_vendor($rgid['vendors_id']) ;
   $rg_balance = $debit - $credit;
    
    $query  = "INSERT INTO currentaccount (";
    $query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,expiration";
    $query .=") VALUES (";
    $query .="'NULL','NULL','0','{$rg_ammount}','{$rgid['vendors_id']}',NULL,'{$rgid['id']}','4','{$rg_balance}','{$date}','{$rgid['expiration']}'";
    $query .=")";
       
       if($db->query($query)){
        
        redirect('receivedgoods.php',false);

        }
}

 

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
            <span>Received goods N° <?php echo $rgid['id'] ?></span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="addreceivedgoods" method="post" class="clearfix" enctype="multipart/form-data" >
         
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Supplier</label>	
 					        <select class="form-control" name="vendor-rg" id="vendor-rg" disabled>
                      <option value="">Select Supplier</option>
                      <?php  foreach ($all_vendors as $ven): ?>
                      <option value="<?php echo (int)$ven['id'];?>"<?php if($rgid['vendors_id'] === $ven['id']): echo "selected"; endif; ?>>
                      <?php echo $ven['id'] .' - '. $ven['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				           </div>
                </div>
             </div>
             <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>    
                    <select class="form-control" id="client-rg" name="client-rg" disabled>
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>"<?php if($rgid['clients_id'] === $cli['id']): echo "selected"; endif; ?>>
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                   </div>
                </div>
             </div>
           
	         <div><input type="hidden" name="rgid" id="rgid" value="<?php echo remove_junk($rgid['id']);?>"/></div>
           <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="qty">N°</label>           
               <input type="text" class="form-control" name="number-rg" step="any" placeholder="Insert number" autocomplete="off" value="<?php echo remove_junk($rgid['numbers']);?>" disabled>                    
            </div>
         </div>
       </div>
       <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label for="qty">Payment condition</label>  
              <input type="text" class="form-control" name="paycondition-rg" step="any" placeholder="Insert Payment condition" value="<?php echo remove_junk($rgid['paycondition']);?>" autocomplete="off" disabled>
           </div>
         </div>
       </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="qty">Observation</label>	
              <input type="text" class="form-control" name="observation-rg" step="any" placeholder="Insert Observation" value="<?php echo remove_junk($rgid['observation']);?>" autocomplete="off" disabled>
           </div>
         </div>
       </div>

    <div ><button type="button" name="hide-totals-rg" id="hide-totals-rg" class="btn btn-success btn-xs">Hide totals</button><button type="button" name="show-totals-rg" id="show-totals-rg" class="btn btn-success btn-xs">Show totals</button></div>
         <div class="table-responsive">
             
              
             
              <table class="table table-bordered table-striped"  name="detail-rg" id="detail-rg">
                <thead>
                <tr>
                  <th>Id Product</th>
                  <th>Customer Alias</th>
                  <th>Description</th>
                  <th>UxB</th>
                  <th>QTY</th>
                  <th>Price</th>
                  <th>CTN</th>
                  <th>CBM</th>
                  <th>NW</th>
                  <th>GW</th>
                  <th>Total CBM</th>
                  <th>Total NW</th>
                  <th>Total GW</th>
                  <th>Total Amount</th>
                  <th>TP</th>
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
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
            </table>  

            <div><button type="button" name="additem-rg" id="additem-rg" class="btn btn-success btn-xs" <?php if($rgid['finalized'] === '1'): echo "disabled"; endif; ?>>Add item</button></div>
            <div class="pull-right">
           <button type="submit" name="insert_receivedgoods" class="btn btn-primary" <?php if($rgid['finalized'] === '1'): echo "disabled"; endif; ?>>Submit</button>
           </div>
           <div class="pull-right">
           <a href="receivedgoods.php" class="btn btn-primary" style="padding: 7px"><?php if($rgid['finalized'] === '1'): echo "Back"; else: echo "Stay Pending"; endif; ?></a>
         </div>
          </div>
		    </div>
       </form>
		</div>
	</div>
</div>
</div>
<div id="rgModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form method="post" id="rgForm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><i class="fa fa-plus"></i>Add item</h4>
          
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Customer Alias</label>
            <input type="text" class="form-control" id="rg-code-name" name="rg-code-name" placeholder="Customer Alias" autocomplete="off" required>
            <div id="rg-clientcodename"></div>      
          </div>
          <div><input type="hidden" name="rgid-id" id="rgid-id" value="<?php echo remove_junk($rgid['id']);?>"/></div>
          <div><input type="hidden" name="rgclient" id="rgclient" value="<?php echo remove_junk($rgid['clients_id']);?>"/></div>
          <div><input type="hidden" name="rgvendor" id="rgvendor" value="<?php echo remove_junk($rgid['vendors_id']);?>"/></div>
          <div class="form-group">
            <label for="lastname" class="control-label">Id Product</label>              
            <input type="text" readonly="readonly" class="form-control"  id="rg-productid" name="rg-productid" placeholder="Product id" value="" >             
          </div>   
           <div class="form-group">
            <label for="name" class="control-label">Descripcion</label>
            <input type="text" class="form-control" id="rg-code-description" name="rg-code-description" placeholder="Description" readonly="readonly">      
          </div>
          <div class="form-group" id="rg-tps" name="rg-tps" class="col-md-5"></div>
          <div class="form-group">
            <label for="name" class="control-label">QTY</label>
            <input type="number" class="form-control" id="rg-code-qty" name="rg-code-qty" placeholder="QTY" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">UxB</label>
            <input type="number" class="form-control" id="rg-code-uxb" name="rg-code-uxb" placeholder="UxB" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">Price</label>
            <input type="text" class="form-control" id="rg-code-fob" name="rg-code-fob" placeholder="Insert price" required autocomplete="off" readonly="readonly">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">CTN</label>
            <input type="number" class="form-control" id="rg-code-ctn" name="rg-code-ctn" placeholder="CTN" readonly="readonly" required autocomplete="off">
          </div>
          <div class="form-group">
            <label for="name" class="control-label">CBM</label>
            <input type="number" step="any" class="form-control" id="rg-code-cbm" name="rg-code-cbm" placeholder="CBM" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">NW</label>
            <input type="number" step="any" class="form-control" id="rg-code-nw" name="rg-code-nw" placeholder="Net weight" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">GW</label>
            <input type="number" step="any" class="form-control" id="rg-code-gw" name="rg-code-gw" placeholder="Gross weight" required autocomplete="off">      
          </div>
          <input type="hidden" name="rg-tp-hidden" id="rg-tp-hidden"/>
          
        <div class="modal-footer">
          <input type="hidden" name="rg-id" id="rg-id"/>
          
          <input type="hidden" name="action" id="action" value="" />
          <input type="hidden" name="rg-qty-hidden" id="rg-qty-hidden" value="" />
          <input type="hidden" name="action-po" id="action-po" value="" />
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
   </form>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>


