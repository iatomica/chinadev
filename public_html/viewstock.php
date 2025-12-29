<?php
  $page_title = 'View Stock';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_clients = find_all('clients');
?>
<?php include_once('layouts/header.php'); ?>
  
  <div id="tabStock">
        <ul>
            <li><a href="#GeneralStock">General Stock</a></li>
            <li><a href="#CustomerStock">Customer Stock</a></li>
            
        </ul>
        <div id="GeneralStock">
   <div class="row">
     <div class="col-md-12">
  <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>GENERAL STOCK</span>
        </strong>
        </div>
        <div class="panel-body">
          <div class="form-group">
      </div>
           <div class="table-responsive">
          <table class="table table-bordered table-striped"  name="viewstock-info" id="viewstock-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Product Id</th>
                <th>Customer</th>
                <th>Customer Alias</th>
                <th>Qty</th>
                
              </tr>
            </thead>
           
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
        <div id="CustomerStock">
   <div class="row">
     <div class="col-md-12">
  <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>CUSTOMER STOCK</span>
        </strong>
        </div>
        <div class="panel-body">
          
          <div class="form-group">
            
             <label for="qty">Select Customer</label>
                    <select class="form-control" name="stockCustomerSelect" id="stockCustomerSelect">
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $client): ?>
                      <option value="<?php echo (int)$client['id'];?>">
                      <?php echo $client['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
     
     
           <div class="table-responsive">
          <table class="table table-bordered table-striped"  name="customerStock-info" id="customerStock-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Id Product</th>
                <th>Customer Alias</th>
                <th>Qty</th>
              </tr>
            </thead>
           
          </table>
        </div>
      
        </div>
      </div>
    </div>
  </div>

        </div>
        
</div>
  

  
<?php include_once('layouts/footer.php'); ?>

