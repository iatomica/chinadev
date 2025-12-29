<?php
  $page_title = 'Customer list';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $clients = join_client_table();
?>
<?php include_once('layouts/header.php'); ?>
 <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_clients.php" class="btn btn-primary">Add Customer</a>
         </div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-bordered table-striped" name="client-info" id="client-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Contact</th>
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
 <?php include_once('layouts/footer.php'); ?>
