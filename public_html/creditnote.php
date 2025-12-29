<?php
  $page_title = 'Generate Credit Note';
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

if(isset($_POST['add_creditnotes'])){
	
		if($_POST['creditnoteto-cn'] === '1'){
		$req_fields = array('vendor-cn','amount-cn');
        validate_fields($req_fields);
		if(empty($errors)){
		$cn_vendor  = remove_junk($db->escape($_POST['vendor-cn']));
		$cn_ammount  = remove_junk($db->escape($_POST['amount-cn']));
		$cn_receivedgoods  = remove_junk($db->escape($_POST['cn-id-receivedgoods']));
		$cn_observation = remove_junk($db->escape($_POST['observation-cn']));
		$cn_bank ='NULL';
		$cn_debit ='0';
		$cn_bank_account = 'NULL';
		$cn_client = 'NULL';
		$cn_current_type = '3';
    $credit = sum_credit_vendor($cn_vendor) + $cn_ammount;
    $debit = sum_debit_vendor($cn_vendor);
    $cn_balance = $debit - $credit;
		//$date = '2021-04-21';

		$date = make_date();
		

		$query  = "INSERT INTO currentaccount (";
		$query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,observation";
		$query .=") VALUES (";
		$query .="'{$cn_bank}','{$cn_bank_account}','{$cn_debit}','{$cn_ammount}','{$cn_vendor}',{$cn_client},NULLIF('{$cn_receivedgoods}',''),'{$cn_current_type}','{$cn_balance}','{$date}','{$cn_observation}'";
		$query .=")";
		if($db->query($query)){
        
        redirect('creditnote.php', false);
        }
    	}
    }
    if($_POST['creditnoteto-cn'] === '2'){
		$req_fields = array('client-cn','amount-cn');
        validate_fields($req_fields);
		if(empty($errors)){
		$cn_client  = remove_junk($db->escape($_POST['client-cn']));
		$cn_ammount  = remove_junk($db->escape($_POST['amount-cn']));
		$cn_receivedgoods  = remove_junk($db->escape($_POST['receivedgoods-cn']));
		$cn_observation = remove_junk($db->escape($_POST['observation-cn']));
		$cn_bank ='NULL';
		$credit = sum_credit_client($cn_client) + $cn_ammount;
    $debit = sum_debit_client($cn_client);
		$cn_bank_account = 'NULL';
		$cn_vendor = 'NULL';
		$cn_current_type = '3';
    $cn_balance = $debit - $credit;

		//$date = '2021-04-21';

		$date = make_date();
		

		$query  = "INSERT INTO currentaccount (";
		$query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,sales_id,observation";
		$query .=") VALUES (";
		$query .="'{$cn_bank}','{$cn_bank_account}','0','{$cn_ammount}',NULL,'{$cn_client}',NULL,'{$cn_current_type}','{$cn_balance}','{$date}',NULL,'{$cn_observation}'";
		$query .=")";
		if($db->query($query)){
        
        redirect('creditnote.php', false);
        }
    	}
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
            <span>Credit Note</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="add_payments" method="post" class="clearfix" enctype="multipart/form-data" >     
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Select to credit note</label>
              	<select class="form-control" id="creditnoteto-cn" name="creditnoteto-cn" required>
                      <option value="">Select option</option>
                      <option value="1">To Supplier</option>
                      <option value="2">To customer</option>   
                    </select>
 				   </div>
                 </div>
               </div>
           <div style="display: none;" id="hide-creditnote-cli-sa">
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>	
 					<select class="form-control" id="client-cn" name="client-cn" >
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>">
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				  </div>
                </div>
             </div>
         </div>
             <div style="display: none;" id="hide-creditnote-ma-rg">
           <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                 <label for="qty">Supplier</label>		
 					<select class="form-control" id="vendor-cn" name="vendor-cn">
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
            <input type="text" class="form-control" id="receivedgoods-cn" name="receivedgoods-cn" placeholder="Receivedgoods N°" autocomplete="off">
            <div id="cn-receivedgoods"></div>      
          	</div>
         	</div>
          </div>
         </div>
         <div style="display: none;" id="hide-creditnote-am-ob">
          <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="qty">Insert Amount</label>
              <div class="input-group">
               <input type="number" class="form-control" id="amount-cn" step ="any" name="amount-cn" placeholder="Insert Ammount" autocomplete="off" required>             
             </div>
           </div>
         </div>
       </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="qty">Observation</label>
              <div class="input-group">
               <input type="text" class="form-control" id="observation-cn" name="observation-cn" placeholder="Insert Observation" autocomplete="off">                    
             </div>
           </div>
         </div>
       </div>
      </div>
     <div style="display: none;" id="hide-creditnote-button">
	<div class="form-group">
<button type="submit" name="add_creditnotes" class="btn btn-danger">Generate Credit Note</button>
</div>
</div>
<input type="hidden" name="cn-id-receivedgoods" id="cn-id-receivedgoods" value="">
         </form>
		  </div>
		</div>
	</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>

