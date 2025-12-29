<?php
  $page_title = 'Generate Debit Note';
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

if(isset($_POST['add_debitnotes'])){
	
		if($_POST['debitnoteto-dn'] === '1'){
		$req_fields = array('vendor-dn','amount-dn');
        validate_fields($req_fields);
		if(empty($errors)){
		$dn_vendor  = remove_junk($db->escape($_POST['vendor-dn']));
		$dn_ammount  = remove_junk($db->escape($_POST['amount-dn']));
		$dn_receivedgoods  = remove_junk($db->escape(nullvar1($_POST['dn-id-receivedgoods'])));
		$dn_observation = remove_junk($db->escape(nullvar($_POST['observation-dn'])));
		$dn_bank ='NULL';
		$dn_credit ='0';
		$dn_bank_account = 'NULL';
		$dn_client = 'NULL';
		$dn_current_type = '2';
    $credit = sum_credit_vendor($dn_vendor);
    $debit = sum_debit_vendor($dn_vendor) + $dn_ammount;
    $dn_balance = $debit - $credit;
		//$date = '2021-04-21';

		$date = make_date();
		

		$query  = "INSERT INTO currentaccount (";
		$query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,observation";
		$query .=") VALUES (";
		$query .="'{$dn_bank}','{$dn_bank_account}','{$dn_ammount}','{$dn_credit}','{$dn_vendor}',NULL,NULLIF('{$dn_receivedgoods}',''),'{$dn_current_type}','{$dn_balance}','{$date}','{$dn_observation}'";
		$query .=")";
		if($db->query($query)){
        
        redirect('debitnote.php', false);
        }
    	}
    }
    if($_POST['debitnoteto-dn'] === '2'){
		$req_fields = array('client-dn','amount-dn');
        validate_fields($req_fields);
		if(empty($errors)){
		$dn_client  = remove_junk($db->escape($_POST['client-dn']));
		$dn_ammount  = remove_junk($db->escape($_POST['amount-dn']));
		$dn_receivedgoods  = remove_junk($db->escape(nullvar($_POST['receivedgoods-dn'])));
		$dn_observation = remove_junk($db->escape(nullvar($_POST['observation-dn'])));
    $dn_current_type = '2';
		$dn_bank ='NULL';
		$dn_debit ='0';
		$dn_bank_account = 'NULL';
		$dn_vendor = 'NULL';
		$dn_current_type = '2';
    $credit = sum_credit_client($dn_client);
    $debit = sum_debit_client($dn_client) + $dn_ammount;
    $balance = $debit - $credit;

		//$date = '2021-04-21';

		$date = make_date();
		

		$query  = "INSERT INTO currentaccount (";
		$query .="bank,bank_account,debit,credit,vendors_id,clients_id,receivedgoods_id,currentaccount_type_id,balance,date,sales_id,observation";
		$query .=") VALUES (";
		$query .="'NULL','NULL','{$dn_ammount}','0',NULL,'{$dn_client}',NULL,'{$dn_current_type}','{$balance}','{$date}',NULL,'{$dn_observation}'";
		$query .=")";
		if($db->query($query)){
        
        redirect('debitnote.php', false);
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
            <span>Debit Note</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="add_debitnotes" method="post" class="clearfix" enctype="multipart/form-data" >     
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Select to debit note</label>
              	<select class="form-control" id="debitnoteto-dn" name="debitnoteto-dn" required>
                      <option value="">Select option</option>
                      <option value="1">To Supplier</option>
                      <option value="2">To customer</option>   
                    </select>
 				   </div>
                 </div>
               </div>
           <div style="display: none;" id="hide-debitnote-cli-sa">
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>	
 					<select class="form-control" id="client-dn" name="client-dn" >
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
             <div style="display: none;" id="hide-debitnote-ma-rg">
           <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                 <label for="qty">Supplier</label>		
 					<select class="form-control" id="vendor-dn" name="vendor-dn">
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
            <input type="text" class="form-control" id="receivedgoods-dn" name="receivedgoods-dn" placeholder="Receivedgoods N°" autocomplete="off">
            <div id="dn-receivedgoods"></div>  
          	</div>
         	</div>
          </div>
         </div>
         <div style="display: none;" id="hide-debitnote-am-ob">
          <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="qty">Insert Amount</label>
              <div class="input-group">
               <input type="number" class="form-control" id="amount-dn" step ="any" name="amount-dn" placeholder="Insert Ammount" autocomplete="off" required>             
             </div>
           </div>
         </div>
       </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="qty">Observation</label>
              <div class="input-group">
               <input type="text" class="form-control" id="observation-dn" name="observation-dn" placeholder="Insert Observation" autocomplete="off">                    
             </div>
           </div>
         </div>
       </div>
      </div>
     <div style="display: none;" id="hide-debitnote-button">
	<div class="form-group">
<button type="submit" name="add_debitnotes" class="btn btn-danger">Generate Debit note</button>
</div>
</div>
<input type="hidden" name="dn-id-receivedgoods" id="dn-id-receivedgoods" value="">

         </form>
		  </div>
		</div>
	</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>

