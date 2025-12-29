<?php
  $page_title = 'Add stock movement';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$all_movement_type = find_all('movements_type');
$all_stock_deposit = find_all('stock_deposit');
$all_clients = find_all('clients');
$smid = '0';

?>

<?php

if(isset($_POST['add_stockmovement'])){
	
		$sm_movement  = remove_junk($db->escape($_POST['movement-sm']));
		$sm_deposit  = remove_junk($db->escape($_POST['deposit-sm']));
		$sm_depositto  = remove_junk($db->escape(nullvar($_POST['depositto-sm'])));
		$sm_reason = remove_junk($db->escape($_POST['reason-sm']));
    $sm_customer = remove_junk($db->escape($_POST['client-addstock']));
		$date = make_date();
		
    $finalized = '0';

		$query  = "INSERT INTO stockmovements (";
		$query .=" datetime,observation,finalized,movements_type_id,stock_deposit_id,stock_deposit_to_id,clients_id";
		$query .=") VALUES (";
		$query .="'{$date}','{$sm_reason}','{$finalized}','{$sm_movement}','{$sm_deposit}',{$sm_depositto},{$sm_customer}";
		$query .=")";
		if($db->query($query)){
        $smid = obtaing_max_id('stockmovements');
        redirect('edit_stockmovement.php?id='.(int)$smid.'', false);
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
            <span>STOCK MOVEMENT</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="add_stockmovement" method="post" class="clearfix" enctype="multipart/form-data" >     
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Movement Type</label>
              	<select class="form-control" id="movement-sm" name="movement-sm" required>
                      <?php  foreach ($all_movement_type as $mt): ?>
                      <option value="<?php echo (int)$mt['id'];?>">
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
                  <select class="form-control" name="client-addstock" id="client-addstock">
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>"<?php if($salesid['clients_id'] === $cli['id']): echo "selected"; endif; ?>>
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                   </div>
                </div>
             </div>	
            <div id="hidedeposito" class="hidedep">
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Deposit</label>			
 					      <select class="form-control" id ="deposit-sm" name="deposit-sm">
                      <?php  foreach ($all_stock_deposit as $sd): ?>
                      <option value="<?php echo (int)$sd['id'];?>">
                      <?php echo $sd['depositname'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				         </div>
                </div>
             </div>
           </div>
           <div id="hidedeposit" class="hidedep">
             <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                 <label for="qty">Deposit to</label>				
 					          <select class="form-control" id="depositto-sm" name="depositto-sm">
                      <option value="">Select Deposit</option>
                      <?php  foreach ($all_stock_deposit as $sd): ?>
                      <option value="<?php echo (int)$sd['id'];?>">
                      <?php echo $sd['depositname'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				          </div>
                </div>
             </div>
          </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="qty">Reason</label>
              <div class="input-group">
               <input type="text" class="form-control" name="reason-sm" placeholder="Insert Reason" autocomplete="off" required>                    
             </div>
           </div>
         </div>
       </div>
      
	<div class="form-group">
<button type="submit" name="add_stockmovement" class="btn btn-danger">Generate Stock Movement</button>
</div>
         </form>
		  </div>
		</div>
	</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>
