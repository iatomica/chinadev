<?php
 $page_title = 'Update Purchase Order '.$poid.'';
  require_once('includes/load.php');
  require_once('libs/libraryexcel/php-excel-reader/excel_reader2.php');
  require_once('libs/libraryexcel/SpreadsheetReader.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$all_clients = find_all('clients');
$all_vendors = find_all('vendors');
$all_moneys = find_all('moneys');
$poid = find_by_id('purchaseorder',(int)$_GET['id']);
//$page_title = 'Update Purchase Order '.$poid.'';
?>

<?php
/*

Html para importar archivos excel

       <form method="POST" action="prueba_Uploadexcel.php" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label for="qty">Import excel File</label>            
                <input type="file" name="file" class="form-control">
           </div>
         </div>
       </div>
       <div><input type="hidden" name="poid-import" id="poid" value="<?php echo remove_junk($poid['id']);?>"/></div>
       <div><input type="hidden" name="customer-import" id="customer-import" value="<?php echo remove_junk($poid['clients_id']);?>"/></div>
       <div class="form-group">
        <div><button type="submit" name="submiter" class="btn btn-success">Import</button></div>
       </div>
  </form>

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
            <span>Purchase Order N° <?php echo $poid['id'] ?></span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="addpurchaseorder" method="post" class="clearfix" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>
 					         <select class="form-control" name="client-po" id="client-po" disabled>
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>"<?php if($poid['clients_id'] === $cli['id']): echo "selected"; endif; ?>>
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				        </div>
             </div>
           </div>
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Supplier</label>
                   <select class="form-control" name="client-po" id="client-po" disabled>
                      <option value="">Select Supplier</option>
                      <?php  foreach ($all_vendors as $ven): ?>
                      <option value="<?php echo (int)$ven['id'];?>"<?php if($poid['vendors_id'] === $ven['id']): echo "selected"; endif; ?>>
                      <?php echo $ven['id'] .' - '. $ven['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                </div>
             </div>
           </div>
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Currency</label>	
 					      <select class="form-control" name="money-po" <?php if($poid['finalized'] === '1'): echo "disabled"; endif; ?> disabled>
                      <option value="">Select Currency</option>
                      <?php  foreach ($all_moneys as $money): ?>
                      <option value="<?php echo (int)$money['id'];?>"<?php if($poid['moneys_id'] === $money['id']): echo "selected"; endif; ?>>
                      <?php echo $money['moneytype'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				           </div>
                </div>
             </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="qty">TP</label>
              <div class="input-group">
               <input type="text" class="form-control" name="tp-po" step="any" placeholder="Insert TP" value="<?php echo remove_junk($poid['tp']);?>" autocomplete="off"<?php if($poid['finalized'] === '1'): echo "disabled"; endif; ?> disabled>                    
             </div>
           </div>
         </div>
       </div>
	   <div><input type="hidden" name="poid" id="poid" value="<?php echo remove_junk($poid['id']);?>"/></div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="qty">TP Supervisor</label>
              <div class="input-group">
               <input type="text" class="form-control" name="tpsup-po" step="any" placeholder="Insert TP Supervisor" value="<?php echo remove_junk($poid['tpname']);?>" autocomplete="off" <?php if($poid['finalized'] === '1'): echo "disabled"; endif; ?> disabled>                    
             </div>
           </div>
         </div>   
	     </div>
         <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="qty">Observation</label>            
               <input type="text" class="form-control" name="observation-po" step="any" placeholder="Insert Observation" value="<?php echo remove_junk($poid['observation']);?>" autocomplete="off"<?php if($poid['finalized'] === '1'): echo "disabled"; endif; ?> disabled>                    
           </div>
         </div>
       </div>
   </form>
   
   
  <div class="form-group">
    <div ><button type="button" name="hide-totals-po" id="hide-totals-po" class="btn btn-success btn-xs">Hide totals</button><button type="button" name="show-totals-po" id="show-totals-po" class="btn btn-success btn-xs">Show totals</button></div>  
  </div>

         <div class="table-responsive">
              <table class="table table-bordered table-striped"  name="detail-po" id="detail-po">
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
                  <th>Volume</th>
                  <th>Total CBM</th>                
                  <th>Total NW</th>
                  <th>Total GW</th>
                  <th>Total Amount</th>
                  <th>Pending</th>
                  <th>E.T.A</th>
                  <th>Edit</th>
                  <th>Delete</th>
                  <th>Change Status</th>
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
                <th></th>
            </tr>
            </tfoot>
            </table>  

            <div><button type="button" name="additem-po" id="additem-po" class="btn btn-success btn-xs" <?php if($poid['finalized'] === '1'): echo "disabled"; endif; ?>>Add item</button></div>
            <div class="pull-right">
           <a href="purchaseorder.php" class="btn btn-primary">Submit</a>
         </div>
          </div>


		  </div>
		</div>
	</div>
</div>
</div>
<div id="poModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form method="post" id="poForm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><i class="fa fa-plus"></i>Add item</h4>
          
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Customer Alias</label>
            <input type="text" class="form-control" id="po-code-name" name="po-code-name" placeholder="Customer Alias" autocomplete="off" required>
            <div id="po-clientcodename"></div>      
          </div>
          <div><input type="hidden" name="poid-id" id="poid-id" value="<?php echo remove_junk($poid['id']);?>"/></div>
          <div class="form-group">
            <label for="lastname" class="control-label">Id Product</label>              
            <input type="text" readonly="readonly" class="form-control"  id="po-productid" name="po-productid" placeholder="Product id" >             
          </div>   
           <div class="form-group">
            <label for="name" class="control-label">Descripcion</label>
            <input type="text" class="form-control" id="po-code-description" name="po-code-description" placeholder="Description" readonly="readonly">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">QTY</label>
            <input type="number" class="form-control" id="po-code-qty" name="po-code-qty" placeholder="QTY" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">UxB</label>
            <input type="number" class="form-control" id="po-code-uxb" name="po-code-uxb" placeholder="UxB" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">Price</label>
            <input type="text" class="form-control" id="po-code-fob" name="po-code-fob" placeholder="Insert price" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">CTN</label>
            <input type="number" class="form-control" id="po-code-ctn" name="po-code-ctn" placeholder="CTN" readonly="readonly" required autocomplete="off">
          </div>
          <div class="form-group">
            <label for="name" class="control-label">CBM</label>
            <input type="number" step="any" class="form-control" id="po-code-cbm" name="po-code-cbm" placeholder="CBM" required autocomplete="off"> 
            </div>      
          <div class="form-group">
            <label for="name" class="control-label">NW</label>
            <input type="number" step="any" class="form-control" id="po-code-nw" name="po-code-nw" placeholder="Net weight" required autocomplete="off">      
          </div>
          
           <div class="form-group">
            <label for="name" class="control-label">GW</label>
            <input type="number" step="any" class="form-control" id="po-code-gw" name="po-code-gw" placeholder="Gross weight" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">Volume</label>
            <input type="number" class="form-control" id="po-code-volume" name="po-code-volume" placeholder="Volume" autocomplete="off">      
          </div>

          <div class="form-group">
            <label for="name" class="control-label">ETA</label>
          <input class="datepicker form-control" id="po-eta" name="po-eta" autocomplete="off">
          </div>
        <div class="modal-footer">
          <input type="hidden" name="po-id" id="po-id"/>
          <input type="hidden" name="po-code-qty-hidden" id="po-code-qty-hidden" value="" />
          <input type="hidden" name="action" id="action" value="" />
          <input type="hidden" name="action1" id="action1" value="" />
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
   </form>
</div>
</div>







<?php include_once('layouts/footer.php'); ?>
