<?php
  $page_title = 'Products to receive and deliver';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
 
?>
<?php include_once('layouts/header.php'); ?>


<div id="tabProductRd">
            <ul>
            <li><a href="#receive">OUTSTANDING</a></li>
            <li><a href="#deliver">STOCK CHECKUP</a></li>
        </ul>
<div id="receive">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>OUTSTANDING</span>
        </strong>   
        </div>
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="receive-info" id="receive-info">
            <thead>
              <tr>
                <th>Id Product</th>
                <th>Image</th>
                <th>Alias Customer</th>
                <th>Description</th>
                <th>Supplier</th>
                <th>Customer</th>
                <th>TP</th>
                <th>Uxb</th>
                <th>Qty</th>
                <th>CTN</th>
                <th>Price</th>
                <th>Total Ammount</th>
                <th>CBM</th>
                <th>G.W</th>
                <th>N.W</th>
                <th>Eta</th>          
              </tr>
            </thead>
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="deliver">
	 <div class="row">
       <div class="col-md-12">
        <div class="panel panel-default">
         <div class="panel-heading clearfix">
          <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>STOCK CKECKUP</span>
        </strong>   
        </div>
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="deliver-info" id="deliver-info">
            <thead>
              <tr>
                <th>Id Product</th>
                <th>Image</th>
                <th>Alias Customer</th>
                <th>Description</th>
                <th>Supplier</th>
                <th>Customer</th>
                <th>TP</th>
                <th>Uxb</th>
                <th>Qty</th>
                <th>CTN</th>
                <th>Price</th>
                <th>Total Ammount</th>
                <th>Unit</th>
                <th>CBM</th>
                <th>Gross Weight</th>
                <th>Net Weight</th>
              </tr>
            </thead>
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>