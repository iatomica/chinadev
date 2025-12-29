<?php
  $page_title = 'Generate Company Expenses';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php

$all_clients = find_all('clients');
$all_vendors = find_all('vendors');

$smid = '0';

?>

<?php

if(isset($_POST['add_payments'])){
		


		 }

?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Report Company Expenses</span>
          <?php
            $page_title = 'Generate Company Expenses';
            require_once('includes/load.php');
            // Checkin What level user has permission to view this page
            page_require_level(2);

            $all_clients = find_all('clients');
            $all_vendors = find_all('vendors');
            $smid = '0';

            if (isset($_POST['add_payments'])) {
              // (reservado para lógica futura)
            }

            include_once('layouts/header.php');
          ?>
</div>
</div>
         </form>
		  </div>
		</div>
	</div>
</div>
</div>

<div id="companyModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form method="post" id="companyForm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" id="closeCompanyModal" name="closeCompanyModal" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><i class="fa fa-plus"></i>Report</h4>
          
        </div>
        <div class="modal-body">
           <div class="form-group">
            <label for="name" class="control-label">Company report</label>
                 
          </div>
          
          
          <div class="table-responsive">
             
              
             
              <table class="table table-bordered table-striped" style="width:100%" name="detail-company" id="detail-company">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Date</th>
                  <th>Concept</th>
                  <th>Observation</th>
                  <th>Price</th>
                 
                </tr>
            </thead>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                
            </tr>
            </tfoot>
            </table>
        <div class="modal-footer">
          <input type="hidden" name="po-id" id="po-id"/>
          <input type="hidden" name="po-code-qty-hidden" id="po-code-qty-hidden" value="" />
          <input type="hidden" name="action" id="action" value="" />
          <input type="hidden" name="action1" id="action1" value="" />
          <button type="button" name="close-company" id="close-company" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
   </form>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>


