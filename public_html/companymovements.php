<?php
  $page_title = 'Add Company Expenses';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  $all_concept = find_all('concept_company');

  include_once('layouts/header.php');
?>
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
            <span>Company Expenses</span>
          </strong>
          </div>
          <div class="panel-body">
         <div class="col-md-12">
          <form id="company" method="post" class="clearfix" enctype="multipart/form-data" >      
            <div class="row">
            <div class="col-md-4">
              <div class="form-group">
              <label for="qty">Concept</label>		
 					          <select class="form-control" name="concept-company" id="concept-company" required>
                      <option value="">Select Concept</option>
                      <?php  foreach ($all_concept as $con): ?>
                      <option value="<?php echo (int)$con['id'];?>">
                      <?php echo $con['concept_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
 				  </div>
                </div>
             </div>
             <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="qty">Price</label>           
               <input type="text" class="form-control" name="price-company" id="price-company" step="any" placeholder="Insert price" autocomplete="off">                    
            </div>
         </div>
       </div>
 	
  
       
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="qty">Observation</label>           
               <input type="text" class="form-control" name="observation-company" id="observation-company" step="any" placeholder="Insert Observation" autocomplete="off">                    
            </div>
         </div>
       </div>

       <div class="form-group">
			<button type="submit" name="add_company" id="add_company" class="btn btn-danger">Generate Movement</button>
	   </div>
      </form>
		  </div>
		</div>
	</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>


