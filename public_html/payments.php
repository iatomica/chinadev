<?php
  $page_title = 'Generate Payment';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$all_clients = find_all('clients');
$all_vendors = find_all('vendors');
$smid = '0';
?>
<?php
if(isset($_POST['add_payments'])){
		


		if($_POST['paymentto-pay'] === '1'){
		$req_fields = array('vendor-pay','amount-pay');
    validate_fields($req_fields);
		if(empty($errors)){


		$pay_ammount  = remove_junk($db->escape($_POST['amount-pay']));
		$pay_receivedgoods  = remove_junk($db->escape($_POST['pay-id-receivedgoods']));
		$pay_observation = remove_junk($db->escape(nullvar($_POST['observation-pay'])));
		$pay_bank ='NULL';
		$pay_debit ='0';
		$pay_bank_account = 'NULL';
    $pay_vendor = remove_junk($db->escape($_POST['vendor-pay']));
		$pay_client = 'NULL';
		$pay_current_type = '1';
    $credit = sum_credit_vendor($pay_vendor);
    $debit = sum_debit_vendor($pay_vendor) + $pay_ammount;
    $pay_balance = $debit - $credit;

		//$date = '2021-04-21';

		$date = make_date();
		

		$query  = "INSERT INTO currentaccount (";
		$query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,observation";
		$query .=") VALUES (";
		$query .="'{$pay_bank}','{$pay_bank_account}','{$pay_ammount}','0','{$pay_vendor}',{$pay_client},NULLIF('{$pay_receivedgoods}',''),{$pay_current_type},'{$pay_balance}','{$date}','{$pay_observation}'";
		$query .=")";
		if($db->query($query)){
        
        redirect('payments.php', false);
        }
    	}
    }




    if($_POST['paymentto-pay'] === '2'){
		$req_fields = array('client-pay','amount-pay');
    validate_fields($req_fields);
		if(empty($errors)){
		$pay_client  = remove_junk($db->escape($_POST['client-pay']));
		$pay_ammount  = remove_junk($db->escape($_POST['amount-pay']));
		$pay_receivedgoods  ='';
		$pay_observation = remove_junk($db->escape(nullvar($_POST['observation-pay'])));
		$pay_bank ='NULL';
		$debit = sum_debit_client($pay_client);
    $credit = sum_credit_client($pay_client) + $pay_ammount;
		$pay_bank_account = 'NULL';
		$pay_vendor ='NULL';
		$pay_current_type = '1';
    $pay_balance = $debit - $credit;
    $pay_sales = remove_junk($db->escape($_POST['pay-id-sales']));
		//$date = '2021-04-21';

		$date = make_date();
		

		$query  = "INSERT INTO currentaccount (";
		$query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,sales_id,observation";
		$query .=") VALUES (";
		$query .="'{$pay_bank}','{$pay_bank_account}','0','{$pay_ammount}',NULL,{$pay_client},NULLIF('{$pay_receivedgoods}',''),{$pay_current_type},'{$pay_balance}','{$date}',NULLIF('{$pay_sales}',''), '{$pay_observation}'";
		$query .=")";
		if($db->query($query)){
        
        redirect('payments.php', false);
        }
    	}
    }

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
            <span>Payments</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="add_payments" method="post" class="clearfix" enctype="multipart/form-data" >     
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Select to payment</label>
              	<select class="form-control" id="paymentto-pay" name="paymentto-pay">
                      <option value="">Select option</option>
                      <option value="1">To Supplier</option>
                      <option value="2">For customer</option>   
                    </select>
 				   </div>
                 </div>
               </div>
           <div style="display: none;" id="hide-payment-cli-sa">
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
               <label for="qty">Customer</label>	
 					      <select class="form-control" id="client-pay" name="client-pay">
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>">
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				         </div>
                </div>
             </div>
        <div class="row">
           <div class="col-md-2">
            <div class="form-group">
            <label for="name" class="control-label">Invoice N°</label>
            <input type="text" class="form-control" id="sales-pay" name="sales-pay" placeholder="Invoice N°" autocomplete="off">
            <div id="pay-sales"></div>      
            </div>
          </div>
          </div>
        </div>
          <div style="display: none;" id="hide-payment-ma-rg">
           <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                 <label for="qty">Supplier</label>		
 					         <select class="form-control" id="vendor-pay" name="vendor-pay">
                      <option value="">Select Supplier</option>
                      <?php  foreach ($all_vendors as $ven): ?>
                      <option value="<?php echo (int)$ven['id'];?>">
                      <?php echo $ven['id'] .' - '. $ven['name'] ?></option>
                      <?php endforeach; ?>
                   </select>
 				         </div>
                </div>
            </div>
          <div class="row">
            <div class="col-md-2">
             <div class="form-group">
            <label for="name" class="control-label">Receivedgoods N°</label>
            <input type="text" class="form-control" id="receivedgoods-pay" name="receivedgoods-pay" placeholder="Receivedgoods N°" autocomplete="off">
            <div id="pay-receivedgoods"></div>      
          	</div>
         	</div>
          </div>
         </div>
         <div style="display: none;" id="hide-payment-am-ob">
          <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="qty">Insert Amount</label>
              <div class="input-group">
               <input type="number" class="form-control" id="amount-pay" step ="any" name="amount-pay" placeholder="Insert Ammount" autocomplete="off">             
             </div>
           </div>
         </div>
       </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="qty">Observation</label>
              <div class="input-group">
               <input type="text" class="form-control" id="observation-pay" name="observation-pay" placeholder="Insert Observation" autocomplete="off">                    
             </div>
           </div>
         </div>
       </div>
      </div>
     <div style="display: none;" id="hide-payment-button">
	<div class="form-group">
<button type="submit" name="add_payments" class="btn btn-danger">Generate Payment</button>
</div>
</div>
<input type="hidden" name="pay-id-receivedgoods" id="pay-id-receivedgoods" value="">
<input type="hidden" name="pay-id-sales" id="pay-id-sales" value="">
         </form>
		  </div>
		</div>
	</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>