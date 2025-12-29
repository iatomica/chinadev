<?php
  $page_title = 'Products Settings';
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
    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Products Settings</span>
        </strong>
         
        </div>
        <div class="panel-body">
            <div id="tabSettings">
            <ul>
            <li><a href="#PriceType">Price Type</a></li>
            <li><a href="#Currency">Currency</a></li>
            <li><a href="#Units">Units</a></li>
            <li><a href="#Packagings">Packagings</a></li>
             </ul>
         <div id="PriceType">
          <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="pricetype-info" id="pricetype-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
          </table>
        </div>
         <div><button type="button" name="addpricetype" id="addpricetype" class="btn btn-success btn-xs">Add Price Type</button></div>
        

        </div>

        <div id="Currency">
        	 <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="currency-info" id="currency-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
          </table>
        </div>
        <div><button type="button" name="addcurrency" id="addcurrency" class="btn btn-success btn-xs">Add Currency</button></div>
  
        </div>
        <div id="Units">
        	 <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="units-info" id="units-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
          </table>
        </div>
        <div><button type="button" name="addunits" id="addunits" class="btn btn-success btn-xs">Add Units</button></div>
        
        </div>
        <div id="Packagings">
        	 <div class="table-responsive">
          <table class="table table-bordered table-striped" style="width:100%" name="packagings-info" id="packagings-info">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
          </table>
        </div>
         <div><button type="button" name="addpackagings" id="addpackagings" class="btn btn-success btn-xs">Add Packaging</button></div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="pricetypeModal" role="dialog">
    <div class="modal-dialog" role="document">
    <form method="post" id="pricetypeForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Insert pricetype</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Currency</label>
            <input type="text" class="form-control" id="pricetypeName" name="pricetypeName" placeholder="pricetype" required autocomplete="off">      
          </div>  
         
        <div class="modal-footer">
          <input type="hidden" name="pricetypeId" id="pricetypeId"/>
          <input type="hidden" name="action" id="action" value="" />
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
   </form>
 </div>
  </div>
</div>


<div class="modal fade" id="currencyModal" role="dialog">
    <div class="modal-dialog" role="document">
    <form method="post" id="currencyForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Insert Currency</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Currency</label>
            <input type="text" class="form-control" id="currencyName" name="currencyName" placeholder="Currency" required autocomplete="off">      
          </div>  
         
        <div class="modal-footer">
          <input type="hidden" name="currencyId" id="currencyId"/>
          <input type="hidden" name="action1" id="action1" value="" />
          <input type="submit" name="save1" id="save1" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
   </form>
 </div>
  </div>
</div>

<div class="modal fade" id="unitsModal" role="dialog">
    <div class="modal-dialog" role="document">
    <form method="post" id="unitsForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Insert Unit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Unit</label>
            <input type="text" class="form-control" id="unitsName" name="unitsName" placeholder="Unit" required autocomplete="off">      
          </div>  
         
        <div class="modal-footer">
          <input type="hidden" name="unitsId" id="unitsId"/>
          <input type="hidden" name="action2" id="action2" value="" />
          <input type="submit" name="save2" id="save2" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
   </form>
  </div>
</div>

<div class="modal fade" id="packagingsModal" role="dialog">
    <div class="modal-dialog" role="document">
    <form method="post" id="packagingsForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Insert packagings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">packagings</label>
            <input type="text" class="form-control" id="packagingsName" name="packagingsName" placeholder="packagings" required autocomplete="off">      
          </div>  
         
        <div class="modal-footer">
          <input type="hidden" name="packagingsId" id="packagingsId"/>
          <input type="hidden" name="action3" id="action3" value="" />
          <input type="submit" name="save3" id="save3" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
   </form>
  </div>
</div>



<?php include_once('layouts/footer.php'); ?>
