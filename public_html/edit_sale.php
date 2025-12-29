$(document).ready(function(){ 
  var employeeData = $('#employeeList').DataTable({
    "lengthChange": false,
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"action.php",
      type:"POST",
      data:{action:'listEmployee'},
      dataType:"json"
    },
    "columnDefs":[
      {
        "targets":[0, 6, 7],
        "orderable":false,
      },
    ],
    "pageLength": 10
  });   
  $('#addEmployee').click(function(){
    $('#employeeModal').modal('show');
    $('#employeeForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add Employee");
    $('#action').val('addEmployee');
    $('#save').val('Add');
  });   
  $("#employeeList").on('click', '.update', function(){
    var empId = $(this).attr("id");
    var action = 'getEmployee';
    $.ajax({
      url:'action.php',
      method:"POST",
      data:{empId:empId, action:action},
      dataType:"json",
      success:function(data){
        $('#employeeModal').modal('show');
        $('#empId').val(data.id);
        $('#empName').val(data.name);
        $('#empAge').val(data.age);
        $('#empSkills').val(data.skills);       
        $('#address').val(data.address);
        $('#designation').val(data.designation);  
        $('.modal-title').html("<i class='fa fa-plus'></i> Edit Employee");
        $('#action').val('updateEmployee');
        $('#save').val('Save');
      }
    })
  });
  $("#employeeModal").on('submit','#employeeForm', function(event){
    event.preventDefault();
    $('#save').attr('disabled','disabled');
    var formData = $(this).serialize();
    $.ajax({
      url:"action.php",
      method:"POST",
      data:formData,
      success:function(data){       
        $('#employeeForm')[0].reset();
        $('#employeeModal').modal('hide');        
        $('#save').attr('disabled', false);
        employeeData.ajax.reload();
      }
    })
  });   
  $("#employeeList").on('click', '.delete', function(){
    var empId = $(this).attr("id");   
    var action = "empDelete";
    if(confirm("Are you sure you want to delete this employee?")) {
      $.ajax({
        url:"action.php",
        method:"POST",
        data:{empId:empId, action:action},
        success:function(data) {          
          employeeData.ajax.reload();
        }
      })
    } else {
      return false;
    }
  }); 
});




<?php
  $page_title = 'Edit sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$sale = find_by_id('sales',(int)$_GET['id']);
if(!$sale){
  $session->msg("d","Missing product id.");
  redirect('sales.php');
}
?>
<?php $product = find_by_id('products',$sale['product_id']); ?>
<?php

  if(isset($_POST['update_sale'])){
    $req_fields = array('title','quantity','price','total', 'date' );
    validate_fields($req_fields);
        if(empty($errors)){
          $p_id      = $db->escape((int)$product['id']);
          $s_qty     = $db->escape((int)$_POST['quantity']);
          $s_total   = $db->escape($_POST['total']);
          $date      = $db->escape($_POST['date']);
          $s_date    = date("Y-m-d", strtotime($date));

          $sql  = "UPDATE sales SET";
          $sql .= " product_id= '{$p_id}',qty={$s_qty},price='{$s_total}',date='{$s_date}'";
          $sql .= " WHERE id ='{$sale['id']}'";
          $result = $db->query($sql);
          if( $result && $db->affected_rows() === 1){
                    update_product_qty($s_qty,$p_id);
                    $session->msg('s',"Sale updated.");
                    redirect('edit_sale.php?id='.$sale['id'], false);
                  } else {
                    $session->msg('d',' Sorry failed to updated!');
                    redirect('sales.php', false);
                  }
        } else {
           $session->msg("d", $errors);
           redirect('edit_sale.php?id='.(int)$sale['id'],false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
  <div class="panel">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>All Sales</span>
     </strong>
     <div class="pull-right">
       <a href="sales.php" class="btn btn-primary">Show all sales</a>
     </div>
    </div>
    <div class="panel-body">
       <table class="table table-bordered">
         <thead>
          <th> Product title </th>
          <th> Qty </th>
          <th> Price </th>
          <th> Total </th>
          <th> Date</th>
          <th> Action</th>
         </thead>
           <tbody  id="product_info">
              <tr>
              <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
                <td id="s_name">
                  <input type="text" class="form-control" id="sug_input" name="title" value="<?php echo remove_junk($product['name']); ?>">
                  <div id="result" class="list-group"></div>
                </td>
                <td id="s_qty">
                  <input type="text" class="form-control" name="quantity" value="<?php echo (int)$sale['qty']; ?>">
                </td>
                <td id="s_price">
                  <input type="text" class="form-control" name="price" value="<?php echo remove_junk($product['sale_price']); ?>" >
                </td>
                <td>
                  <input type="text" class="form-control" name="total" value="<?php echo remove_junk($sale['price']); ?>">
                </td>
                <td id="s_date">
                  <input type="date" class="form-control datepicker" name="date" data-date-format="" value="<?php echo remove_junk($sale['date']); ?>">
                </td>
                <td>
                  <button type="submit" name="update_sale" class="btn btn-primary">Update sale</button>
                </td>
              </form>
              </tr>
           </tbody>
       </table>

    </div>
  </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>
