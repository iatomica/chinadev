<?php
  $page_title = 'Add Client';
  require_once('includes/load.php');
  $all_clients = find_all('clients');
  ?>

  
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
   <div class="row">
  <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Export Products</span>
         </strong>
        </div>
        <div class="panel-body">
        	 <div class="form-group">
                <div class="row">
            
             <div class="col-md-2">
              <div class="form-group">

                    <label for="qty">Select Customer</label>
                    <select class="form-control" name="exp_customer_products1" id="exp_customer_products1">
                      <option value="0">All</option>
                      <?php  foreach ($all_clients as $client): ?>
                      <option value="<?php echo (int)$client['id'];?>">
                      <?php echo $client['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
               </div>

        </div>
        <div class="col-md-6">
                 <div class="form-group">     
                  <div><input type="button" download="exportproducts.xls" id="export_products1" name="export_products1" class="btn btn-primary" value="Export to Excel"/></div>
                     
                 </div>
             </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>