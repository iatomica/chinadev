<?php
  $page_title = 'Add Invoices';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$all_clients = find_all('clients');
$salesid = '0';
$all_stock_deposit = find_all('stock_deposit');
?>

<?php

if(isset($_POST['add_sales'])){
	

		$sales_client  = remove_junk($db->escape($_POST['client-sales']));
		$sales_observation = remove_junk($db->escape($_POST['observation-sales']));
    	$sales_stock_depposit = '1';
		$date = make_date();
		$numbers = remove_junk($db->escape($_POST['number-sales']));
		$currencyrate = remove_junk($db->escape($_POST['currencyrate-sales']));
    $comission = remove_junk($db->escape($_POST['comission-sales']));
    	$totalprice_rmb = '0';
    	$totalprice_usd = '0';

    	if($comission === '0' || ''){
          
          echo '<script>if(confirm("THE COMISSION IS 0, press accept to modify the option")){
            window.location="edit_clients.php?id="+'.(int)$sales_client.';
          }</script>';
          

      }
      else{
		$query  = "INSERT INTO sales (";
		$query .="totalprice_rmb,date,totalprice_usd,currencyrate,numbers,clients_id,observation,stock_deposit_id,comission";
		$query .=") VALUES (";
		$query .="'{$totalprice_rmb}','{$date}','{$totalprice_usd}','{$currencyrate}','{$numbers}','{$sales_client}','{$sales_observation}','{$sales_stock_depposit}','{$comission}'";
		$query .=")";
		if($db->query($query)){
        $salesid = obtaing_max_id('sales');
        redirect('edit_sales.php?id='.(int)$salesid.'', false);
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
            <span>Invoices</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="addsales" method="post" class="clearfix" enctype="multipart/form-data" >      
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>		
 					          <select class="form-control" name="client-sales" id="client-sales" required>
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
                  <label for="qty">N°</label>           
               <input type="text" class="form-control" name="number-sales" id="number-sales" step="any" placeholder="Insert number" autocomplete="off">                    
            </div>
         </div>
       </div>
 	<div class="row">
      <div class="col-md-1">
       <div class="form-group">
           <label for="name" class="control-label">Currency rate</label>
           <input type="number" step="any" class="form-control" id="currencyrate-sales" name="currencyrate-sales" placeholder="Currency rate" required autocomplete="off">
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-1">
       <div class="form-group">
           <label for="name" class="control-label">Comission</label>
           <input type="text" class="form-control" id="comission-sales" name="comission-sales" value="" placeholder="Comission" readonly="readonly" required autocomplete="off">
          </div>
      </div>
  </div>
       
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="qty">Observation</label>           
               <input type="text" class="form-control" name="observation-sales" id="observation-sales" step="any" placeholder="Insert Observation" autocomplete="off">                    
            </div>
         </div>
       </div>

       <div class="form-group">
			<button type="submit" name="add_sales" class="btn btn-danger">Generate Invoce</button>
	   </div>
      </form>
		  </div>
		</div>
	</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>

