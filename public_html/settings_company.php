<?php
  $page_title = 'Company Expenses Settings';
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
</div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Company Expenses Settings</span>
        </strong>
         
        </div>
        <div class="panel-body">
           
        
          <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="company-info" id="company-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Concept</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
          </table>
        </div>
         <div><button type="button" name="addcompany" id="addcompany" class="btn btn-success btn-xs">Add Concept</button></div>
        </div>
    </div>
</div>


<div class="modal fade" id="companyModal" role="dialog">
    <div class="modal-dialog" role="document">
    <form method="post" id="companyForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Insert Concept</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Concept</label>
            <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Concept" required autocomplete="off">      
          </div>  
         
        <div class="modal-footer">
          <input type="hidden" name="companyId" id="companyId"/>
          <input type="hidden" name="action" id="action" value="" />
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
   </form>
  </div>
</div>



<?php include_once('layouts/footer.php'); ?>

