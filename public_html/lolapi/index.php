<?php
  $page_title = 'Products List';
  require_once('includes/load.php');
  header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
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
           <a href="add_product.php" class="btn btn-primary">Add Product</a>
         </div>
        </div>
      </div>
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-bordered table-striped"  name="productss-info" id="productss-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Image</th>
                <th>Description </th>
                <th>Price</th>
                <th>Edit</th>
                <th>Close</th>
              </tr>
            </thead>
           
          </table>

           <div class="row">
          <div class="col-md-6	">
            <div class="form-group">
              <label for="qty">Summoner</label>	
              
               <input type="text" class="form-control" name="summoner" id="summoner" step="any" placeholder="Insert Observation" value="" autocomplete="off">                    
             
           </div>
         </div>

         <div class="form-group">
<button type="button" name="button-lol" id="button-lol" onclick="inv()" class="btn btn-success">button-lol</button>
</div>
       </div>

        </div>
        </div>
      </div>
    </div>
  </div>
<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>

  
<?php include_once('layouts/footer.php'); ?>
