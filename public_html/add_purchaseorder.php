<?php
  $page_title = 'Add Purchase Order';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$all_clients = find_all('clients');
$all_vendors = find_all('vendors');
$all_moneys = find_all('moneys');
$poid = '0';

?>

<?php

if(isset($_POST['add_porchaseorder'])){
	$req_fields = array('client-po','tp-po');
	validate_fields($req_fields);

	if(empty($errors)){
		$po_client  = remove_junk($db->escape($_POST['client-po']));
		$po_money  = remove_junk($db->escape($_POST['money-po']));
		$po_tp = remove_junk($db->escape($_POST['tp-po']));
		$po_tpname = remove_junk($db->escape($_POST['tpsup-po']));
		$po_observation = remove_junk($db->escape($_POST['observation-po']));
		$date = make_date();
		$openclose = '0';
		$finalized = '0';
    $total_fob = '0';
    $po_vendor  = remove_junk($db->escape($_POST['vendor-po']));

		$query  = "INSERT INTO purchaseorder (";
		$query .=" date,tp,tpname,observation,openclose,finalized,clients_id,moneys_id,total_fob,vendors_id";
		$query .=") VALUES (";
		$query .="'{$date}','{$po_tp}','{$po_tpname}','{$po_observation}','{$openclose}','{$finalized}','{$po_client}','{$po_money}','{$total_fob}','{$po_vendor}'";
		$query .=")";
		if($db->query($query)){
        $poid = obtaing_max_id('purchaseorder');
        redirect('edit_purchaseorder.php?id='.(int)$poid.'', false);
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
            <span>Purchase Order</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="addpurchaseorder" method="post" class="clearfix" enctype="multipart/form-data" >
         
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>		
 					        <select class="form-control" name="client-po">
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
                  <select class="form-control" name="vendor-po">
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
              <label for="qty">Currency</label>
 									<select class="form-control" name="money-po">
                      <option value="">Select Currency</option>
                      <?php  foreach ($all_moneys as $money): ?>
                      <option value="<?php echo (int)$money['id'];?>">
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
               <input type="text" class="form-control" name="tp-po" step="any" placeholder="Insert TP" autocomplete="off">                    
             </div>
           </div>
         </div>
       </div>
         <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="qty">TP Supervisor</label>
              <div class="input-group">
               <input type="text" class="form-control" name="tpsup-po" step="any" placeholder="Insert TP Supervisor" required>                    
             </div>
           </div>
         </div>
	     </div>
	       <div class="row">
          <div class="col-md-6	">
            <div class="form-group">
              <label for="qty">Observation</label>	         
               <input type="text" class="form-control" name="observation-po" step="any" placeholder="Insert Observation" autocomplete="off">
           </div>
         </div>
       </div>
       
	<div class="form-group">
<button type="submit" name="add_porchaseorder" class="btn btn-danger">Generate Purchase Order</button>
</div>




         </form>

		  </div>
		</div>
	</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>