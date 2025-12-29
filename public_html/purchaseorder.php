<?php
  $page_title = 'Sale Confirmation list';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
 
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
          <span>Purchase Orders</span>
        </strong>   
         <div class="pull-right">
           <a href="add_purchaseorder.php" class="btn btn-primary">Add Purchase Order</a>
         </div>
        </div>
        <div class="panel-body">
          <div class="form-group">
       <div class="row">
         <div class="col-md-3">
          <div class="form-group">
            <label for="qty">Filters</label>
            <select class="form-control datatable_filter" name="po-filter" id="po-filter">
              <option value="0">ALL</option>
                <option value="1">PENDING</option>
                <option value="2">DONE</option>
              </select>
            </div>
          </div>
        </div>
      </div>
          <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="pos1-info" id="pos1-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Supplier</th>
                <th>Customer</th>
                <th>TP</th>
                <th>Edit</th>
                <th>Status</th>
              </tr>
            </thead>
           
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>

  
  <?php include_once('layouts/footer.php'); ?>