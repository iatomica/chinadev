<?php
  $page_title = 'Generate Currentaccount Report';
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
            <span>Report Current accounts</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="add_payments" method="post" class="clearfix" enctype="multipart/form-data" >     
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Select to Report</label>
              	<select class="form-control" id="reportcato-ca" name="reportcato-ca">
                      <option value="">Select option</option>
                      <option value="1">Supplier</option>
                      <option value="2">Customer</option>   
                    </select>
 				   </div>
                 </div>
               </div>
           <div style="display: none;" id="hide-reportca-cli">
            <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Customer</label>	
 					<select class="form-control" id="client-ca" name="client-ca" >
                      <option value="">Select Customer</option>
                      <?php  foreach ($all_clients as $cli): ?>
                      <option value="<?php echo (int)$cli['id'];?>">
                      <?php echo $cli['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				  </div>
                </div>
             </div>
         </div>
             <div style="display: none;" id="hide-reportca-ma">
           <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                 <label for="qty">Supplier</label>		
 					<select class="form-control" id="vendor-ca" name="vendor-ca">
                      <option value="">Select Supplier</option>
                      <?php  foreach ($all_vendors as $ven): ?>
                      <option value="<?php echo (int)$ven['id'];?>">
                      <?php echo $ven['id'] .' - '. $ven['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				   </div>
                </div>
             </div>
             </div>
            <div style="display: none;" id="hide-reportca-filters">
			 <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Select Movement Date</label>
              	<select class="form-control" id="reportmovdate-ca" name="reportmovdate-ca">
                      <option value="1" selected>Last 15 movements</option>
                      <option value="2">Date range</option>
                    </select>
 				   </div>
                 </div>
               </div>

		</div>
		   <div style="display: none;" id="hide-reportca-filter-date">
		   	<div class="row">
            <div class="col-md-2">
		   	<div class="form-group">
                <div class="input-group">
                  <input type="date" class="datepicker form-control" id="reportca-start-date" name="reportca-start-date" placeholder="From">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="date" class="datepicker form-control" id="reportca-end-date" name="reportca-end-date" placeholder="To">
                </div>
            </div>
		   </div>
		</div>
	</div>
<div class="form-group">
 <div style="display: none;" id="hide-reportca-button">
 	 <div class="form-group">
 	 	<div class="form-check">
 	 	 <div class="row">
 	 	 
 	 	  <input type="checkbox" class="form-check-input" id="report-check-all">
    	 <label class="form-check-label" for="report-check-all">All</label>
    	
       </div>
       <div class="row">
    	 <input type="checkbox" class="form-check-input" id="report-check-payment">
    	 <label class="form-check-label" for="report-check-payment">Payments</label>
    	</div>
    	<div class="row">
    	 <input type="checkbox" class="form-check-input" id="report-check-debitnote">
    	 <label class="form-check-label" for="report-check-debitnote">Debit Notes</label>
    	</div>
    	<div class="row">
    	 <input type="checkbox" class="form-check-input" id="report-check-creditnote">
    	 <label class="form-check-label" for="report-check-creditnote">Credit Notes</label>
    	</div>
    	<div class="row">
    	 <input type="checkbox" class="form-check-input" id="report-check-rg">
    	 <label class="form-check-label" for="report-check-rg">Received Goods</label>
    	</div>
    	<div class="row">
    	 <input type="checkbox" class="form-check-input" id="report-check-sales">
    	 <label class="form-check-label" for="report-check-sales">Sales</label>
    	</div>
  	</div>

 	 </div>
  </div>
</div>
	<div class="form-group">
<button type="button" name="add_reportca" id="add_reportca" class="btn btn-success">Generate Report</button>
</div>
</div>
         </form>
		  </div>
		</div>
	</div>
</div>
</div>

<div id="caModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form method="post" id="caForm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" id="closeCaModal" name="closeCaModal" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><i class="fa fa-plus"></i>Report</h4>
          
        </div>
        <div class="modal-body">
          
         
           <div class="form-group">
            <label for="name" class="control-label">Current Account</label>
            <input type="text" class="form-control" id="ca-modal-vendor" name="ca-modal-vendor" readonly="readonly">      
          </div>
          
          
          <div class="table-responsive">
             
              
             
              <table class="table table-bordered table-striped" style="width:100%" name="detail-ca" id="detail-ca">
                <thead>
                <tr>
                  <th id="exp_0" name="exp_0">Id</th>
                  <th id="exp_1" name="exp_1">Name</th>
                  <th id="exp_2" name="exp_2">Type</th>
                  <th id="exp_3" name="exp_3">Number</th>
                  <th id="exp_4" name="exp_4">Destination</th>
                  <th id="exp_5" name="exp_5">Debit</th>
                  <th id="exp_6" name="exp_6">Credit</th>
                  <th id="exp_7" name="exp_7">Balance</th>
                  <th id="exp_8" name="exp_8">Observation</th>
                  <th id="exp_9" name="exp_9"></th>
                 
                </tr>
            </thead>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
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
          <button type="button" name="close-rp-ca" id="close-rp-ca" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
   </form>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>

