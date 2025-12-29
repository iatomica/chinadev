<?php
  $page_title = 'Products List';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
  require_once('libs/libraryexcel/php-excel-reader/excel_reader2.php');
  require_once('libs/libraryexcel/SpreadsheetReader.php');
  require_once('libs/PHPExcel/PHPExcel.php');

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
          <span>Products</span>
        </strong>   
         <div class="pull-right">
           <a href="add_product.php" class="btn btn-primary">Add Product</a>
         </div>
        </div>
      </div>
        <div class="panel-body">
          <div class="container">
	<h1>Excel Upload</h1>


	<form method="POST" action="exportexcel_sales.php" enctype="multipart/form-data">
		<div class="form-group">
			<label>Import Excel File</label>
			<input type="file" name="file" class="form-control">
		</div>
		<div class="form-group">
			<button type="submit" name="exp_sales_excel" class="btn btn-success">Upload</button>
		</div>
	</form>
</div>
         


        </div>
        </div>
      </div>
    </div>
  </div>



<?php include_once('layouts/footer.php'); ?>
