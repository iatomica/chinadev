<?php
  $page_title = 'Products List';
  require_once('includes/load.php');
  
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
  <?php echo display_msg($msg); ?>
     </div>
    

         <div class="form-group">
<button type="button" name="button-lol" id="button-lol" onclick="inv()" class="btn btn-success">button-lol</button>
</div>
       </div>

        </div>
        </div>
      </div>
    </div>
  </div>
<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>

  
<?php include_once('layouts/footer.php'); ?>