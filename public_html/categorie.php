<?php
  $page_title = 'Lista de categorías';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  
  $all_categories = find_all('categories')
?>
<?php
if(isset($_POST['add_subcat'])){
   $req_field = array('sub-categorie-name','categorie-sub-name');
   validate_fields($req_field);
   $subcat_name = remove_junk($db->escape($_POST['sub-categorie-name']));
   $catsub_name = remove_junk($db->escape($_POST['categorie-sub-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO subcategories (name,categories_id)";
      $sql .= " VALUES ('{$subcat_name}','{$catsub_name}')";
      if($db->query($sql)){
        $session->msg("s", "Sub-Categoría agregada exitosamente.");
        redirect('categorie.php',false);
      } else {
        $session->msg("d", "Lo siento, registro falló");
        redirect('categorie.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('categorie.php',false);
   }
 }


 if(isset($_POST['add_cat'])){
   $req_field = array('categorie-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['categorie-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO categories (name)";
      $sql .= " VALUES ('{$cat_name}')";
      if($db->query($sql)){
        $session->msg("s", "Categorye was added successfully");
        redirect('categorie.php',false);
      } else {
        $session->msg("d", "Missing db");
        redirect('categorie.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('categorie.php',false);
   }
 }




?>
<?php include_once('layouts/header.php'); ?>

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
   <div class="row">
    <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add Categorie</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="categorie.php">
            <div class="form-group">
                <input type="text" class="form-control" name="categorie-name" placeholder="Inser Categorie" required autocomplete="off">
            </div>
            <button type="submit" name="add_cat" class="btn btn-primary">Add Categorie</button>
        </form>
        </div>
      </div>
    </div>
        
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>List of Categories</span>
       </strong>
      </div>
        <div class="panel-body">
          
          <table class="table table-bordered table-striped table-hover" id="table-cat" name="table-cat">
            <thead>
                <tr>
                    <th>Categorye</th>
                    <th>Subcategories</th>
                </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>

       </div>
    </div>
    </div>
   </div>
  </div>

  <div class="modal fade" id="subcatModal" role="dialog">
    <div class="modal-dialog" role="document">
    <form method="post" id="subcatForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Insert Subcategorie</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Subcategorie Name</label>
            <input type="text" class="form-control" id="subcatName" name="subcatName" placeholder="" required autocomplete="off">
             <input type="hidden" name="subcatIdAdd" id="subcatIdAdd"/>    
          </div>  
         
        <div class="modal-footer">
          <input type="hidden" name="subcatId" id="subcatId"/>
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
