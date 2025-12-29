<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);

$salesid = find_by_id('sales',(int)$_GET['id']);
$page_title = 'Update Invoices '.$salesid['id'].'';
$all_clients = find_all('clients');
$date = make_date();
$all_stock_deposit = find_all('stock_deposit');
//$page_title = 'Update Purchase Order '.$poid.'';
 
if(isset($_POST['insert_sales'])){ 


   
  $select_items_sales = select_sales_items($salesid['id']);

  foreach ($select_items_sales as $select) {

   $sales_qty = $select['qty_sales'];

   

   $rgitem3 = select_rg_items_3_0($select['tp'],$select['products_id'],$select['uxb_sales']); 
   $rgitem0 = select_rg_items_0_0($select['tp'],$select['products_id'],$select['uxb_sales']);


   foreach ($rgitem3 as $rg3) {

    if ($sales_qty >= $rg3['partial_invoice']){

        update_rg_items_sales_1($rg3['id']);
        $sales_qty = $sales_qty - $rg3['partial_invoice'];


    }
    else{

        $partial = $rg3['partial_invoice'] - $sales_qty;
        update_rg_items_sales_3_1($rg3['id'],$partial);
        $sales_qty = '0';
        break;

    }




   }

      foreach ($rgitem0 as $rg0) {

        if($sales_qty === '0'){

          break;
        }
                       
        if($sales_qty >= $rg0['qty_rg']) {

          update_rg_items_sales_1($rg0['id']);
          $sales_qty = $sales_qty - $rg0['qty_rg'];
         
          

        }
        else{

          $partial1 = $rg0['qty_rg'] - $sales_qty;
          update_rg_items_sales_3_1($rg0['id'],$partial1);

          break;
        }


      }


    $qty_stock = select_stock($salesid['clients_id'],$select['products_id']);
    $qty_stock_final = $qty_stock['qty'] - $select['qty_sales'];
          
    update_stock($qty_stock_final,$qty_stock['id']); 

  }
 
    $sales_ammount = sum_items_sales($salesid['id'],$salesid['comission'],$salesid['currencyrate']);
    $credit = sum_credit_client($salesid['clients_id']);
   
    $debit = sum_debit_client($salesid['clients_id']) + $sales_ammount;
    $sales_ammount_tot = $sales_ammount;
    $sales_balance = $debit - $credit;
    
    $query  = "INSERT INTO currentaccount (";
    $query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,sales_id";
    $query .=") VALUES (";
    $query .="'NULL','NULL','{$sales_ammount_tot}','0',NULL,'{$salesid['clients_id']}',NULL,'5','{$sales_balance}','{$date}','{$salesid['id']}'";
    $query .=")";
       
       $db->query($query);
        

    finalized_sales($salesid['id']);
    redirect('sales.php',false);




 
}

include_once('layouts/header.php'); ?>
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
            <span>Invoice N° <?php echo $salesid['id'] ?></span>
          </strong>
          </div>
          <div class="panel-body">
        <div id="tabSales">
            <ul>
            <li><a href="#ShippingContainer">Shipping cost & Containers</a></li>
            <li><a href="#Invoice">Invoice</a></li>
        </ul>
        <div id="Invoice">
         <div class="col-md-12">
          <form id="addsales" method="post" class="clearfix" enctype="multipart/form-data" >
         
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>	
 					        <select class="form-control" name="client-sales" id="client-sales" disabled>
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>"<?php if($salesid['clients_id'] === $cli['id']): echo "selected"; endif; ?>>
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				           </div>
                </div>
             </div>
             <div><input type="hidden" name="clientid_sales" id="clientid_sales" value="<?php echo remove_junk($salesid['clients_id']);?>"/></div>
            
	         <div><input type="hidden" name="salesid" id="salesid" value="<?php echo remove_junk($salesid['id']);?>"/></div>
           <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="qty">N°</label>           
               <input type="text" class="form-control" name="number-sales" step="any" placeholder="Insert number" autocomplete="off" value="<?php echo remove_junk($salesid['numbers']);?>" disabled>                    
            </div>
         </div>
       </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label for="qty">Observation</label>
               <input type="text" class="form-control" name="observation-sales" step="any" placeholder="Insert Observation" value="<?php echo remove_junk($salesid['observation']);?>" autocomplete="off" disabled>
           </div>
         </div>
       </div>
      </div>


        <input type="hidden" name="saleid" id="saleid" value="<?php echo remove_junk($salesid['id']);?>"/>


	<div class="form-group">
        <div><button type="button" name="hide-totals-sales" id="hide-totals-sales" class="btn btn-success btn-xs">Hide totals</button><button type="button" name="show-totals-sales" id="show-totals-sales" class="btn btn-success btn-xs">Show totals</button></div>
      </div>
    
         <div class="table-responsive">
              <table class="table table-bordered table-striped" style="width:100%" name="detail-sales" id="detail-sales">
                <thead>
                <tr>
                  <th>Id Product</th>
                  <th>Customer Alias</th>
                  <th>TP</th>
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
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
            </table>
               <div><button type="button" name="additem-sales" id="additem-sales" class="btn btn-success btn-xs" <?php if($salesid['finalized'] === '1'): echo "disabled"; endif; ?>>
                Add item
               </button>
          </div>
            <div class="pull-right">
           <button type="submit" name="insert_sales" class="btn btn-primary" <?php if($salesid['finalized'] === '1'): echo "disabled"; endif; ?>>Submit</button>
           </div>
           <div class="pull-right">
           <a href="sales.php" class="btn btn-primary" style="padding: 7px"><?php if($salesid['finalized'] === '1'): echo "Back"; else: echo "Stay Pending"; endif; ?></a>
         </div>
          </div>
		    </div>

<div id="ShippingContainer">
       <div class="form-group">
        <button type="button" id="addcontainer" name="addcontainer" class="btn btn-success btn-xs" <?php if($salesid['finalized'] === '1'): echo "disabled"; endif; ?>>
        Add Container
        </button>
      </div>
      

       <div class="row">    
       <div class="col-md-4">
       <div class="table-responsive">
              <table class="table table-bordered table-striped"  name="sales-container" id="sales-container">
                <thead>
                <tr>
                  <th>Cotainer N°</th>
                   <th>Seal N°</th>
                  <th>Edit</th>
                  <th>Delete</th>      
                </tr>
            </thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </table>  
      </div>
     </div>
   </div>

   <div class="form-group">
        <button type="button" id="addshippingcost" name="addshippingcost" class="btn btn-success btn-xs" <?php if($salesid['finalized'] === '1'): echo "disabled"; endif; ?>>
        Add Shipping Cost
        </button>
      </div>
      
      <div class="row">    
       <div class="col-md-5">
       <div class="table-responsive">
              <table class="table table-bordered table-striped"  name="sales-shippingcost" id="sales-shippingcost">
                <thead>
                <tr>
                  <th>Concept</th>
                  <th>Currency rate</th>
                  <th>USD</th>
                  <th>RMB</th>
                  <th>Update</th>
                </tr>
            </thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </table>  
      </div>
     </div>
   </div>
   </div>
       </form>
		</div>
	</div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="salesModal" tabindex="-1" role="dialog" aria-labelledby="salesModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="salesForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="salesModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
             <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Customer Alias</label>
            <input type="text" class="form-control" id="sales-code-name" name="sales-code-name" placeholder="Customer Alias" autocomplete="off" required>
            <div id="sales-clientcodename"></div>      
          </div>
          <div><input type="hidden" name="salesid-id" id="salesid-id" value="<?php echo remove_junk($salesid['id']);?>"/></div>
          <div class="form-group">
            <label for="lastname" class="control-label">Id Product</label>              
            <input type="text" readonly="readonly" class="form-control"  id="sales-productid" name="sales-productid" placeholder="Product id" value="" >             
          </div>
          <div class="form-group" id="sales-tps" name="sales-tps" class="col-md-5"></div>
           <div class="form-group">
            <label for="name" class="control-label">Descripcion</label>
            <input type="text" class="form-control" id="sales-code-description" name="sales-code-description" placeholder="Description" readonly="readonly">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">QTY</label>
            <label for="name" name="maxqty" id="maxqty" class="control-label"></label>
            <input type="number" class="form-control" id="sales-code-qty" name="sales-code-qty" placeholder="QTY" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">UxB</label>
            <input type="number" class="form-control" id="sales-code-uxb" name="sales-code-uxb" placeholder="UxB" required autocomplete="off" readonly="readonly">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">Price</label>
            <input type="text" class="form-control" id="sales-code-fob" name="sales-code-fob" placeholder="F.O.B" required autocomplete="off" readonly="readonly">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">CTN</label>
            <input type="number" class="form-control" id="sales-code-ctn" name="sales-code-ctn" placeholder="CTN" readonly="readonly" required autocomplete="off">
          </div>
          <div class="form-group">
            <label for="name" class="control-label">CBM</label>
            <input type="number" step="any" class="form-control" id="sales-code-cbm" name="sales-code-cbm" placeholder="CBM" required autocomplete="off">      
          </div>
          
          <div class="form-group">
            <label for="name" class="control-label">NW</label>
            <input type="number" step="any" class="form-control" id="sales-code-nw" name="sales-code-nw" placeholder="Net weight" required autocomplete="off">      
          </div> 
          <div class="form-group">
            <label for="name" class="control-label">GW</label>
            <input type="number" step="any" class="form-control" id="sales-code-gw" name="sales-code-gw" placeholder="Gross weight" required autocomplete="off">      
          </div>
        <div class="modal-footer">
          <input type="hidden" name="sales-id" id="sales-id"/>
          
          <input type="hidden" name="action" id="action" value="" />
          <input type="hidden" name="sales-qty-hidden" id="sales-qty-hidden" value="" />
          <input type="hidden" name="action-po" id="action-po" value="" />
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
     
    </div>
  </form>
  </div>
</div>
<div class="modal fade" id="containerModal" tabindex="-1" role="dialog" aria-labelledby="containerModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="containerForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Insert Container N°</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <div class="form-group">
            <label for="name" class="control-label">Container N°</label>
            <input type="text" class="form-control" id="ContainerName" name="ContainerName" placeholder="Insert container" autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="name" class="control-label">Seal N°</label>
            <input type="text" class="form-control" id="ContainerSealn" name="ContainerSealn" placeholder="Insert Seal number" autocomplete="off">      
          </div>
        <div class="modal-footer">         
        <div><input type="hidden" name="ContainerSalesid" id="ContainerSalesid" value="<?php echo remove_junk($salesid['id']);?>"/></div> 
          <input type="hidden" name="action1" id="action1" value=""/>
          <input type="hidden" name="container-id" id="container-id" value=""/>
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </form>
  </div>
</div>
  <div class="modal fade" id="shippingcostModal" tabindex="-1" role="dialog" aria-labelledby="shippingcostModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="shippingcostForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Insert Shipping Cost</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
         
        <div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <label for="name" class="control-label">Concept</label>
            <input type="text" class="form-control" id="senyefee" name="senyefee" value="SENYE FEE" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <label for="name" class="control-label">Currency Rate</label>
            <input type="number" class="form-control" step="any" id="senyefeeCR" name="senyefeeCR" value="<?php echo remove_junk($salesid['currencyrate']);?>" readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <label for="name" class="control-label">USD</label>
            <input type="number" class="form-control" id="senyefeeUSD" name="senyefeeUSD" value="0" placeholder="" readonly="readonly">      
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <label for="name" class="control-label">RMB</label>
            <input type="number" class="form-control" id="senyefeeRMB" name="senyefeeRMB" value="0" placeholder="">      
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>


       <div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" id="delfinfee" name="delfinfee" value="DELFIN FEE" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="delfinfeeCR" name="delfinfeeCR" value="<?php echo remove_junk($salesid['currencyrate']);?>" readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="delfinfeeUSD" name="delfinfeeUSD" value="0" placeholder="" readonly="readonly">   
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <input type="text" class="form-control" id="delfinfeeRMB" name="delfinfeeRMB" value="0" placeholder="">     
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>

<div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" id="warehousefee" name="warehousefee" value="WAREHOUSE FEE" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="warehousefeeCR" name="warehousefeeCR" value="<?php echo remove_junk($salesid['currencyrate']);?>"readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="warehousefeeUSD" name="warehousefeeUSD" value="0" placeholder="" readonly="readonly">   
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <input type="text" class="form-control" id="warehousefeeRMB" name="warehousefeeRMB" value="0" placeholder="">     
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>
<div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" id="loadingfee" name="loadingfee" value="LOADING FEE" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="loadingfeeCR" name="loadingfeeCR" value="<?php echo remove_junk($salesid['currencyrate']);?>" readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="loadingfeeUSD" name="loadingfeeUSD" value="0" placeholder="" readonly="readonly">   
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <input type="text" class="form-control" id="loadingfeeRMB" name="loadingfeeRMB" value="0" placeholder="">     
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>

<div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" id="invlegfee" name="invlegfee" value="INVOICE LEGALIZE COST" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="invlegfeeCR" name="invlegfeeCR" value="<?php echo remove_junk($salesid['currencyrate']);?>" readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="invlegfeeUSD" name="invlegfeeUSD" value="0" placeholder="" readonly="readonly">   
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <input type="text" class="form-control" id="invlegfeeRMB" name="invlegfeeRMB" value="0" placeholder="">     
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>
<div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" id="pricelistfee" name="pricelistfee" value="PRICE LIST LEGALIZE COST" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="pricelistfeeCR" name="pricelistfeeCR" value="<?php echo remove_junk($salesid['currencyrate']);?>"readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="pricelistfeeUSD" name="pricelistfeeUSD" value="0" placeholder="" readonly="readonly">   
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <input type="text" class="form-control" id="pricelistfeeRMB" name="pricelistfeeRMB" value="0" placeholder="">     
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>
<div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" id="expodecfee" name="expodecfee" value="EXPO DECLARATION COST" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="expodecfeeCR" name="expodecfeeCR" value="<?php echo remove_junk($salesid['currencyrate']);?>" readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="expodecfeeUSD" name="expodecfeeUSD" value="0" placeholder="" readonly="readonly">   
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <input type="text" class="form-control" id="expodecfeeRMB" name="expodecfeeRMB" value="0" placeholder="">     
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>

<div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" id="colegfee" name="colegfee" value="CO LEGALIZE COST" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="colegfeeCR" name="colegfeeCR" value="<?php echo remove_junk($salesid['currencyrate']);?>" readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="colegfeeUSD" name="colegfeeUSD" value="0" placeholder="" readonly="readonly">   
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <input type="text" class="form-control" id="colegfeeRMB" name="colegfeeRMB" value="0" placeholder="">     
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>
        <div class="modal-footer">         
        <div><input type="hidden" name="shippingcostSalesid" id="shippingcostSalesid" value="<?php echo remove_junk($salesid['id']);?>"/></div> 
          <input type="hidden" name="action2" id="action2" value=""/>
          <input type="hidden" name="container-id" id="container-id" value=""/>
          <input type="submit" name="save2" id="save2" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      

    </div>
    </form>
  </div>
</div>


  <div class="modal fade" id="shippingUpdateModal" tabindex="-1" role="dialog" aria-labelledby="shippingUpdateModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="shippingUpdateForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Edit concept</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
        <div class="row">
          <div class="col-md-5">
           <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" id="concept" name="concept" value="" readonly="readonly">      
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="currencyrate" name="currencyrate" value=""readonly="readonly">     
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="usd" name="usd" placeholder="" readonly="readonly">   
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <div class="input-group">
           <div class="form-group">
            <input type="text" class="form-control" id="rmb" name="rmb" placeholder="">     
          </div>
        </div>
      </div>
        </div>
    
      </div>
</div>
        <div class="modal-footer">         
        <div><input type="hidden" name="ContainerSalesid" id="ContainerSalesid" value="<?php echo remove_junk($salesid['id']);?>"/></div> 
          <input type="hidden" name="action5" id="action5" value=""/>
          <input type="hidden" name="updatesc-id" id="updatesc-id" value=""/>
          <input type="submit" name="save5" id="save5" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </form>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>


