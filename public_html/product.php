<?php

  $page_title = 'Products List';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
  $all_clients = find_all('clients');
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
  <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Products</span>
          </strong>
          <div class="pull-right">
             <button name="addproduct12" id="addproduct12" class="btn btn-primary">ADD PRODUCT</button>
            <a href="bulk_import_products.php" class="btn btn-success">
              <i class="glyphicon glyphicon-upload"></i> Bulk Import
            </a>
            <a href="bulk_import_photos.php" class="btn btn-info">
              <i class="glyphicon glyphicon-picture"></i> Import Photos
            </a>
          </div>
        </div>
      </div>
           <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Select customer to search by Customer alias</label>    
                    <select class="form-control" id="productsCustomerSelect" name="productsCustomerSelect">
                       <option value="0">All products</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>">
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                   </div>
                </div>
             </div>
           
 
          <div class="table-responsive">
          <table class="table table-hover table-bordered" style="width:100%" name="productss-info" id="productss-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Image</th>
                <th>Description</th>
                <th>Price</th>
                <th>Customer Alias</th>
                <th>Price type</th>
                <th>Money Type</th>
                <th>Color</th>
                <th>Material</th>
                <th>Size</th>
                <th>CBM</th>
                <th>UXB</th>
                <th>Categorye</th>
                <th>Subcategorye</th>
                <th>Inners</th>
                <th>Unit Type</th>
                <th>Packaging Type</th>
                <th>Code Bar Ean13</th>
                <th>Code Bar Dun14</th>
                <th>Volume</th>
                <th>Product Weight</th>
                <th>CTN Netweight</th>
                <th>CTN Grossweight</th>
                <th>Edit</th>
                <th>Close</th>
              </tr>
            </thead>
           
          </table>
        </div>
      
        </div>
      </div>
    </div>
  </div>
  
<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content1" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>

  
<?php include_once('layouts/footer.php'); ?>