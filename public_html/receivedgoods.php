<?php
  $page_title = 'Received goods list';
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
          <span>RECEIVED GOODS</span>
        </strong>   
         <div class="pull-right">
           <a href="add_receivedgoods.php" class="btn btn-primary">Add Received goods</a>
         </div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-bordered table-striped"  name="rg-info" id="rg-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Supplier</th>
                <th>Customer</th>
                <th>Date</th>
                <th>N°</th>
                <th>Expiration</th>
                <th>Observation</th>
                <th>Status</th>
                <th>Edit</th>
                
              </tr>
            </thead>
           
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>

  
  <?php include_once('layouts/footer.php'); ?>
