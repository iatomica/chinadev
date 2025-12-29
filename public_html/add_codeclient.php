<?php
  $page_title = 'Add code client';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_clients = find_all('clients');
  $all_codeclients = find_all('clientcode');
  
//joinsqlname('clientcode','clients','clients_id','name','2');
 
   /*<div class="form-group">                
               <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                <button type="button" onClick="window.open('add_codeclient.php')">Add code client</button>
               </div>
             </div>
             </div>
            </div>     
            */ // para cuando este el boton   

if(isset($_POST['add_code'])){

$clientcode  = remove_junk($db->escape($_POST['product-codeclient']));
$client  = remove_junk($db->escape($_POST['product-client']));

$query = "INSERT INTO clientcode (";
    $query .= "clientcode,clients_id,products_id)";
    $query .= " VALUES";
    $query .= " ('{$clientcode}','{$client}','29')";

    if($db->query($query)){
     $session->msg('s',"The code client has been added successfully. ");
    } else {
      $session->msg('d',' Fail to add codeclient.');
    }



} 
?>
<?php include_once('layouts/header.php'); ?>
<script src="libs/js/jquery.dataTables.min.js" defer></script>
<script src="libs/js/dataTables.bootstrap.min.js" defer></script>
<link rel="stylesheet" href="libs/css/dataTables.bootstrap.min.css" />

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
            <span>Add code product client</span>
         </strong>
         </div>
     </div>
 </div>
</div>
<form id="clients" method="post" action="add_codeclient.php" class="clearfix" enctype="multipart/form-data" >
<div class="form-group">                
    <div class="row">
<div class="col-md-6">
        <div class="form-group">
          <label for="qty">Client</label>
          <select class="form-control" name="product-client">
            <option value="0">Select Client</option>
            
              <option value="1"></option>
              
            </select>
          </div>
        </div>
        <div id="alert_message"></div>
        <div class="col-md-4">
            <div class="form-group">
              <label for="qty">Client Code</label>
              <div class="input-group">
               <input type="text" class="form-control" name="product-codeclient" placeholder="Insert Client Code" value="1">                    
             </div>
           </div>
         </div>
         <button type="button" name="add1" id="add1" class="btn btn-info">Add</button>
      <button type="submit" name="add_code" id="add_code" class="btn btn-info">Add code</button>

          <button type="button" name="addclientcode" id="addclientcode" class="btn btn-success btn-xs">Add client code</button>
        
   </div>
 </div>
 <table class="table table-bordered table-striped" name="clientcode" id="clientcode">
   <thead>
              <tr>
                <th>ClientCode</th>
                <th>Client</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
           
 </table>
 
    </form>  
  <div id="clientcodeModal" class="modal fade">
  <div class="modal-dialog">
    <form method="post" id="clientcodeForm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title"><i class="fa fa-plus"></i> Edit Client code</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Client code</label>
            <input type="text" class="form-control" id="clientcodeName" name="clientcodeName" placeholder="Client code" required>      
          </div>
          <div class="form-group">
            <label for="age" class="control-label">Client</label>              
            <select class="form-control" name="clientcodeClient" id="clientcodeClient">
                <?php  foreach ($all_clients as $cli): ?>
                <option value="<?php echo (int)$cli['id'] ?>">           
                <?php echo $cli['name'] ?></option>
                <?php endforeach; ?>

                </select>              
          </div>      
          <div class="form-group">
            <label for="lastname" class="control-label">Id Product</label>              
            <input type="text" class="form-control"  id="clientcodeProductid" name="clientcodeProductid" placeholder="Product id" required>             
          </div>   
         
        <div class="modal-footer">
          <input type="hidden" name="clientcodeId" id="clientcodeId"/>
          <input type="hidden" name="action" id="action" value="" />
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
 </div>
</div>
         
<?php include_once('layouts/footer.php'); ?>
