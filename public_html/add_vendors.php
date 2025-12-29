<?php
  $page_title = 'Add Supplier';
  require_once('includes/load.php');
  $all_moneys = find_all1('moneys');
  ?>
<?php
if(isset($_POST['add_vendors'])){


$v_name = remove_junk($db->escape($_POST['vendor-name']));
$v_address = remove_junk($db->escape($_POST['vendor-address']));
$v_city = remove_junk($db->escape($_POST['vendor-city']));
$v_phone = remove_junk($db->escape($_POST['vendor-phone']));
$v_website = remove_junk($db->escape($_POST['vendor-website']));
$v_contact = remove_junk($db->escape($_POST['vendor-contact']));
$v_bus_number = remove_junk($db->escape($_POST['vendor-bus_number']));
$v_email = remove_junk($db->escape($_POST['vendor-email']));
$v_bank_account = remove_junk($db->escape($_POST['vendor-bank_account']));
$v_bank = remove_junk($db->escape($_POST['vendor-bank']));
$v_beneficiary_name = remove_junk($db->escape($_POST['vendor-beneficiary-name']));
$v_taxpayer = remove_junk($db->escape($_POST['vendor-taxpayer']));
$v_money = remove_junk($db->escape($_POST['vendor-money']));
$v_openclose = true;

$query ="INSERT INTO vendors (";
$query .=" name,address,city,phone,website,contact,bus_number,email,bank_account,bank,openclose,beneficiary_name,taxpayer,moneys_id";
$query .=") VALUES (";
$query .="'{$v_name}', '{$v_address}', '{$v_city}', '{$v_phone}', '{$v_website}', '{$v_contact}', '{$v_bus_number}', '{$v_email}', '{$v_bank_account}', '{$v_bank}','{$v_openclose}','{$v_beneficiary_name}','{$v_taxpayer}','{$v_money}' )";

if($db->query($query)){
       $session->msg('s',"Supplier was added successfully. ");
        } else {
       $session->msg('d','Registration has failed.');
       
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
  <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add Supplier</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form id="vendors" method="post" action="add_vendors.php" class="clearfix">
            </div> 
              <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Name</label>                  
                     <input type="text" class="form-control" name="vendor-name" placeholder="Insert Supplier name" required autocomplete="off">
                 </div>
                 <div class="col-md-6">
                                  
                    <label for="qty">address</label>                  
                     <input type="text" class="form-control" name="vendor-address" placeholder="Insert Supplier address" autocomplete="off">
                  </div>
               </div> 
          </div>
              <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">City</label>                  
                     <input type="text" class="form-control" name="vendor-city" placeholder="Insert Supplier city" autocomplete="off">
                 </div>
                 <div class="col-md-6">
                                  
                    <label for="qty">Phone</label>                  
                     <input type="number" class="form-control" name="vendor-phone" placeholder="Insert Supplier phone" autocomplete="off">
                  </div>
               </div> 
          </div>
			<div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                                  
                    <label for="qty">Website</label>                  
                     <input type="text" class="form-control" name="vendor-website" placeholder="Insert Supplier website" autocomplete="off">
                  </div> 
                  <div class="col-md-6">
                                  
                    <label for="qty">Contact</label>                  
                     <input type="text" class="form-control" name="vendor-contact" placeholder="Insert Supplier contact" autocomplete="off">
                  </div>                
               </div> 
          </div>                        
			<div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Shop Number</label>                  
                     <input type="text" class="form-control" name="vendor-bus_number" placeholder="Insert Supplier shop number" autocomplete="off">
                  </div>
                  <div class="col-md-6">          
                    <label for="qty">Email</label>                  
                     <input type="text" class="form-control" name="vendor-email" placeholder="Insert Supplier email" autocomplete="off">
                  </div>
               </div> 
          </div>
          <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Number Bank Account</label>                  
                     <input type="number" class="form-control" name="vendor-bank_account" placeholder="Insert Supplier Bank account" autocomplete="off">
                 </div>
                 <div class="col-md-6">          
                    <label for="qty">Bank</label>                  
                     <input type="text" class="form-control" name="vendor-bank" placeholder="Insert Supplier bank" autocomplete="off">
                  </div>
               </div> 
          </div>
          <div class="form-group">
                <div class="row">
                   <div class="col-md-6">
                    <label for="qty">Beneficiary Name</label>                  
                     <input type="text" class="form-control" name="vendor-beneficiary-name" placeholder="Insert Beneficiary name" autocomplete="off">
                  </div>
                   <div class="col-md-6">
                    <label for="qty">Tax Payer</label>                  
                     <input type="text" class="form-control" name="vendor-taxpayer" placeholder="Insert Tax payer" autocomplete="off">
                  </div>
               </div> 
          </div> 
           <div class="form-group">
            <div class="row">
           <div class="col-md-4">
                    <div class="form-group">
                    <label for="qty">Currency</label>
                    <select class="form-control" name="vendor-money" required>
                      <option value="">Select Currency</option>
                    <?php  foreach ($all_moneys as $money): ?>
                    <option value="<?php echo (int)$money['id'] ?>">
                    <?php echo $money['moneytype'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div> 
              </div>
            </div>
          <button type="submit" name="add_vendors" class="btn btn-danger">Add Supplier</button> 
          </form>
          </div>
          </div>
          </div>
          </div>
      </div>
<?php include_once('layouts/footer.php'); ?>