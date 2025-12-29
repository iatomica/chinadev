<?php
  $page_title = 'List of Clients';
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
           <a href="add_clients.php" class="btn btn-primary">Add Client</a>
         </div>
        </div>
        
        	<img id ="myImg" class="img-thumbnail" src="uploads/products/BA-52299.JPG">
      
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-bordered table-striped" name="client-info" id="client-info">
            <thead>
              <tr>
              	<th><img id ="myImg" class="img-thumbnail" src="uploads/products/BA-52299.JPG"></th>
                <th>Code</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
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
