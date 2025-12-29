<?php
  $page_title = 'Sales list';
  require_once('includes/load.php');
  $all_clients = find_all('clients');
  // Checkin What level user has permission to view this page
   page_require_level(2);
 
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
            <span>Export Data</span>
         </strong>
        </div>
        <div class="panel-body">
        	
        	 <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                 <div class="form-group">
                   <label for="qty">Export Products</label>   
                                
                     <div><input type="button" download="currentaccount.xls" id="exportaccounts" name="exportaccounts" class="btn btn-primary" value="Export to Excel"/></div>
            
                 </div>
             </div>
             <div class="col-md-4">
              <div class="form-group">

                    <label for="qty">Select Customer</label>
                    <select class="form-control" id="exp_accounts_customer" name="exp_accounts_customer">
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $client): ?>
                      <option value="<?php echo (int)$client['id'];?>">
                      <?php echo $client['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
               </div> 
          
      
        </div>
    </div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>
