<?php
  $page_title = 'Stock movements list';
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
          <span>STOCK MOVEMENTS</span>
        </strong>   
         <div class="pull-right">
           <a href="add_stockmovement.php" class="btn btn-primary">Add movement</a>
         </div>
        </div>
        <div class="panel-body">
          <div class="form-group">
       <div class="row">
         <div class="col-md-3">
          <div class="form-group">
            <label for="qty">Filters</label>
            <select class="form-control datatable_filter" name="sm-filter" id="sm-filter">
              <option value="0">ALL</option>
                <option value="1">Stock Ajustement</option>
                <option value="2">Deposit to Deposit</option>
              </select>
            </div>
          </div>
        </div>
      </div>
          <div class="table-responsive">
          <table class="table table-bordered table-striped"  name="sm-info" id="sm-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Movement type</th>
                <th>Deposit to Deposit</th>
                <th>Reason</th>
                <th>Date</th>
                <th>View</th>
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
