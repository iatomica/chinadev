<?php
  $page_title = 'Supplier list';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $vendors = join_vendor_table();
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
          <span>Suppliers</span>
        </strong>
         <div class="pull-right">
           <a href="add_vendors.php" class="btn btn-primary">Add Supplier</a>
         </div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="vendor-info" id="vendor-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Phone</th>
                <th>Website</th>
                <th>Contact</th>
                <th>Bus Number</th>
                <th>Email</th>
                <th>Bank Account</th>
                <th>Bank</th>
                <th>Beneficiary Name</th>
                <th>Tax Payer</th>
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