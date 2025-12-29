<?php
$page_title = 'Update Supplier';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_moneys = find_all1('moneys');
?>
<?php
$vendor = find_by_id('vendors',(int)$_GET['id']);
if(!$vendor){
  $session->msg("d","Missing product id.");
  redirect('vendors.php');
}
?>

<?php

 if(isset($_POST['vendor'])){

 $req_fields = array('vendor-name');
 validate_fields($req_fields);

if(empty($errors)){

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
$v_beneficiary__name = remove_junk($db->escape($_POST['vendor-beneficiary-name']));
$v_taxpayer = remove_junk($db->escape($_POST['vendor-taxpayer']));
$v_money = remove_junk($db->escape($_POST['vendor-money']));
$v_openclose = true;


$query = "UPDATE vendors SET";
$query .=" name ='{$v_name}', address ='{$v_address}', city ='{$v_city}', phone ='{$v_phone}', website ='{$v_website}', contact ='{$v_contact}', bus_number ='{$v_bus_number}', email ='{$v_email}', bank_account ='{$v_bank_account}', bank ='{$v_bank}', openclose ='{$v_openclose}', beneficiary_name = '{$v_beneficiary__name}', taxpayer = '{$v_taxpayer}', moneys_id = '{$v_money}'";
$query .=" WHERE id ='{$vendor['id']}'";

$result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Supplier updated. ");
                 redirect('vendors.php', false);
               } else {
                 $session->msg('d',' Update Supplier failed.');
                 redirect('edit_vendors.php?id='.$vendor['id'], false);
               }

}else{
       $session->msg("d", $errors);
       redirect('edit_vendors.php?id='.$vendor['id'], false);
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
            <span>Update Supplier</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="edit_vendors.php?id=<?php echo (int)$vendor['id'] ?>">
            </div> 
              <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Name</label>                  
                     <input type="text" class="form-control" name="vendor-name" placeholder="Insert Supplier name" value="<?php echo remove_junk($vendor['name']);?>" autocomplete="off">
                 </div>
                 <div class="col-md-6">
                                  
                    <label for="qty">address</label>                  
                     <input type="text" class="form-control" name="vendor-address" placeholder="Insert Supplier address" value="<?php echo remove_junk($vendor['address']);?>" autocomplete="off">
                  </div>
               </div> 
          </div>
              <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">City</label>                  
                     <input type="text" class="form-control" name="vendor-city" placeholder="Insert Supplier city" value="<?php echo remove_junk($vendor['city']);?>">
                 </div>
                 <div class="col-md-6">
                                  
                    <label for="qty">Phone</label>                  
                     <input type="number" class="form-control" name="vendor-phone" placeholder="Insert Supplier phone" value="<?php echo remove_junk($vendor['phone']);?>" autocomplete="off">
                  </div>
               </div> 
          </div>
			<div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                                  
                    <label for="qty">Website</label>                  
                     <input type="text" class="form-control" name="vendor-website" placeholder="Insert Supplier website"value="<?php echo remove_junk($vendor['website']);?>" autocomplete="off">
                  </div> 
                  <div class="col-md-6">
                                  
                    <label for="qty">Contact</label>                  
                     <input type="text" class="form-control" name="vendor-contact" placeholder="Insert Supplier contact" value="<?php echo remove_junk($vendor['contact']);?>" autocomplete="off">
                  </div>                
               </div> 
          </div>                        
			<div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Shop Number</label>                  
                     <input type="text" class="form-control" name="vendor-bus_number" placeholder="Insert Supplier shop number" value="<?php echo remove_junk($vendor['bus_number']);?>" autocomplete="off">
                  </div>
                  <div class="col-md-6">          
                    <label for="qty">Email</label>                  
                     <input type="text" class="form-control" name="vendor-email" placeholder="Insert Supplier email" value="<?php echo remove_junk($vendor['email']);?>" autocomplete="off">
                  </div>
               </div> 
          </div>
          <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Number Bank Account</label>                  
                     <input type="number" class="form-control" name="vendor-bank_account" placeholder="Insert Supplier Bank account" value="<?php echo remove_junk($vendor['bank_account']);?>" autocomplete="off">
                 </div>
                 <div class="col-md-6">          
                    <label for="qty">Bank</label>                  
                     <input type="text" class="form-control" name="vendor-bank" placeholder="Insert Supplier bank" value="<?php echo remove_junk($vendor['bank']);?>">
                  </div>
               </div> 
          </div>
          <div class="form-group">
                <div class="row">
                   <div class="col-md-6">
                    <label for="qty">Beneficiary Name</label>                  
                     <input type="text" class="form-control" name="vendor-beneficiary-name" placeholder="Insert Beneficiary name" value="<?php echo remove_junk($vendor['beneficiary_name']);?>" autocomplete="off">
                  </div>
                   <div class="col-md-6">
                    <label for="qty">Tax Payer</label>                  
                     <input type="text" class="form-control" name="vendor-taxpayer" value="<?php echo remove_junk($vendor['taxpayer']);?>" autocomplete="off">
                  </div>
               </div> 
          </div>
          <div class="form-group">
                <div class="row">
           <div class="col-md-4">
                    <div class="form-group">
                    <label for="qty">Currency</label>
                    <select class="form-control" name="vendor-money">
                      <option value="">Select Money Type</option>
                      <?php  foreach ($all_moneys as $money): ?>
                      <option value="<?php echo (int)$money['id'];?>" <?php if($vendor['moneys_id'] === $money['id']): echo "selected"; endif; ?> >
                      <?php echo $money['moneytype'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          <button type="submit" name="vendor" class="btn btn-danger">Update Supplier</button> 
          </form>
          </div>
          </div>
          </div>
          </div>
      </div>
<?php include_once('layouts/footer.php'); ?>

