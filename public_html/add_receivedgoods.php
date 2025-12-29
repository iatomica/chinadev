<?php
  $page_title = 'Add Received goods';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
?>
<?php
$all_vendors = find_all('vendors');
$all_clients = find_all('clients');
$rgid = '0';
$all_stock_deposit = find_all('stock_deposit');


?>

<?php

if(isset($_POST['add_receivedgoods'])){
	$req_fields = array('vendor-rg');
	validate_fields($req_fields);

	if(empty($errors)){
		$rg_vendor  = remove_junk($db->escape($_POST['vendor-rg']));
		$rg_observation = remove_junk($db->escape($_POST['observation-rg']));
    $rg_stock_depposit = '1';
		$date = remove_junk($db->escape($_POST['received-date-rg']));
    $expiration = remove_junk($db->escape($_POST['expiration-date-rg']));
		$openclose = true;
		$finalized = '0';
    $total_price = '0';
    $number = remove_junk($db->escape($_POST['number-rg']));
    $clients_id = remove_junk($db->escape($_POST['client-rg']));
    $rg_paycondition = remove_junk($db->escape($_POST['paycondition-rg']));


		$query  = "INSERT INTO receivedgoods (";
		$query .="observation,date,openclose,total_price,finalized,vendors_id,stock_deposit_id,numbers,clients_id,paycondition,expiration";
		$query .=") VALUES (";
		$query .="'{$rg_observation}','{$date}','{$openclose}','{$total_price}','{$finalized}','{$rg_vendor}','{$rg_stock_depposit}','{$number}','{$clients_id}','{$rg_paycondition}', '{$expiration}'";
		$query .=")";
		if($db->query($query)){
        $rgid = obtaing_max_id('receivedgoods');
        redirect('edit_receivedgoods.php?id='.(int)$rgid.'', false);
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
            <span>Received goods</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="addreceivedgoods" method="post" class="clearfix" enctype="multipart/form-data" >      
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>    
                    <select class="form-control" id="client-rg" name="client-rg">
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
              <label for="qty">Supplier</label>    
                    <select class="form-control" id="vendor-rg" name="vendor-rg">
                    </select>
                   </div>
                </div>
             </div>
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label for="qty">Received Date</label>  
                <div class="input-group">
               <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                  <input class="datepicker form-control" id="received-date-rg" name="received-date-rg" placeholder="Received date" autocomplete="off">

                </div>
            </div>
       </div>
    </div>
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label for="qty">Expiration Date</label>  
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                  <input class="datepicker form-control" id="expiration-date-rg" name="expiration-date-rg" placeholder="Expiration date" autocomplete="off"> 
                </div>
            </div>
       </div>
    </div>
    

            
              <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="qty">N°</label>           
               <input type="text" class="form-control" name="number-rg" step="any" placeholder="Insert number" autocomplete="off">                    
            </div>
         </div>
       </div>
       <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label for="qty">Payment condition</label>  
              <input type="text" class="form-control" name="paycondition-rg" step="any" placeholder="Insert Payment condition" autocomplete="off">
           </div>
         </div>
       </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="qty">Observation</label>           
               <input type="text" class="form-control" name="observation-rg" step="any" placeholder="Insert Observation" autocomplete="off">                    
            </div>
         </div>
       </div>

       <div class="form-group">
			<button type="submit" name="add_receivedgoods" class="btn btn-danger">Generate Received goods</button>
	   </div>
      </form>
		  </div>
		</div>
	</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>
