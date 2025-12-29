<?php
  $page_title = 'Add Client';
  require_once('includes/load.php');
  $all_vendors = find_all('vendors');
  ?>

  
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
   <div class="row">
  <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Export Makers</span>
         </strong>
        </div>
        <div class="panel-body">
        	 <div class="form-group">
           <div class="row">
            <div class="col-md-2">
              <div class="form-group">
              <label for="qty">Maker</label>		
 				<select class="form-control" name="exp_makers" id="exp_makers" required>
                      <option value="0">All</option>
                      <?php  foreach ($all_vendors as $ven): ?>
                      <option value="<?php echo (int)$ven['id'];?>">
                      <?php echo $ven['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				   </div>
        		<div class="col-md-6">
                 <div class="form-group">     
                  <div><input type="button" download="exportmakers.xls" id="export_makers" name="export_makers" class="btn btn-primary" value="Export to Excel"/></div>
                     
                 </div>
             </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
