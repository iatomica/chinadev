<?php
  $page_title = 'Update Client';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_moneys = find_all1('moneys');
?>
<?php
$client = find_by_id('clients',(int)$_GET['id']);
if(!$client){
  $session->msg("d","Missing product id.");
  redirect('clients.php');
  
}
?>

<?php

 if(isset($_POST['client'])){

 $req_fields = array('client-name');
 validate_fields($req_fields);

if(empty($errors)){

$c_name = remove_junk($db->escape($_POST['client-name']));
$c_address = remove_junk($db->escape($_POST['client-address']));
$c_city = remove_junk($db->escape($_POST['client-city']));
$c_phone = remove_junk($db->escape($_POST['client-phone']));
$c_website = remove_junk($db->escape($_POST['client-website']));
$c_contact = remove_junk($db->escape($_POST['client-contact']));
$c_email = remove_junk($db->escape($_POST['client-email']));
$c_bank = remove_junk($db->escape($_POST['client-bank']));
$c_comission = remove_junk($db->escape($_POST['client-comission']));
$c_country = remove_junk($db->escape($_POST['client-country']));
$c_taxpayer = remove_junk($db->escape($_POST['client-taxpayer']));
$c_money = remove_junk($db->escape($_POST['client-money']));
$c_openclose = true;


$query = "UPDATE clients SET";
$query .=" name ='{$c_name}', address ='{$c_address}', city ='{$c_city}', phone ='{$c_phone}', website ='{$c_website}', contact ='{$c_contact}', email ='{$c_email}', bank ='{$c_bank}', comission ='{$c_comission}', openclose ='{$c_openclose}', country = '{$c_country}', taxpayer = '{$c_taxpayer}', moneys_id = '{$c_money}' ";
$query .=" WHERE id ='{$client['id']}'";

$result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Customer updated. ");
                 redirect('clients.php', false);
               } else {
                 $session->msg('d',' Update Customer failed.');
                 redirect('edit_clients.php?id='.$client['id'], false);
               }

}else{
       $session->msg("d", $errors);
       redirect('edit_clients.php?id='.$client['id'], false);
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
            <span>Update Customer</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="edit_clients.php?id=<?php echo (int)$client['id'] ?>">
            </div> 
              <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Company name</label>                  
                     <input type="text" class="form-control" name="client-name" placeholder="Insert customer name" value="<?php echo remove_junk($client['name']);?>" autocomplete="off">
                 </div>
                 <div class="col-md-6">
                                  
                    <label for="qty">address</label>                  
                     <input type="text" class="form-control" name="client-address" placeholder="Insert customer address" value="<?php echo remove_junk($client['address']);?>" autocomplete="off">
                  </div>
               </div> 
          </div>
              <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">City</label>                  
                     <input type="text" class="form-control" name="client-city" placeholder="Insert customer city" value="<?php echo remove_junk($client['city']);?>" autocomplete="off">
                 </div>
                 <div class="col-md-6">
                                  
                    <label for="qty">Phone</label>                  
                     <input type="number" class="form-control" name="client-phone" placeholder="Insert customer phone" value="<?php echo remove_junk($client['phone']);?>" autocomplete="off">
                  </div>
               </div> 
          </div>
			<div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                                  
                    <label for="qty">Website</label>                  
                     <input type="text" class="form-control" name="client-website" placeholder="Insert customer website"value="<?php echo remove_junk($client['website']);?>" autocomplete="off">
                  </div> 
                  <div class="col-md-6">
                                  
                    <label for="qty">Contact</label>                  
                     <input type="text" class="form-control" name="client-contact" placeholder="Insert customer contact" value="<?php echo remove_junk($client['contact']);?>" autocomplete="off">
                  </div>                
               </div> 
          </div>                        
			           <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Comission</label>                  
                     <input type="number" class="form-control" name="client-comission" placeholder="Insert customer comission" value="<?php echo remove_junk($client['comission']);?>" autocomplete="off">
                 </div>
                 <div class="col-md-6">              
                    <label for="qty">Country</label>                  
                     <input type="text" class="form-control" name="client-country" placeholder="Insert customer country" value="<?php echo remove_junk($client['country']);?>" autocomplete="off">
                  </div>      
                </div>
            </div>
             <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                                  
                    <label for="qty">Tax Payer</label>                  
                     <input type="text" class="form-control" name="client-taxpayer" value="<?php echo remove_junk($client['taxpayer']);?>" placeholder="Insert Tax payer" autocomplete="off">
                  </div> 

                 <div class="col-md-4">
                    <div class="form-group">
                    <label for="qty">Currency</label>
                    <select class="form-control" name="client-money" required>
                      <option value="">Select Money Type</option>
                      <?php  foreach ($all_moneys as $money): ?>
                      <option value="<?php echo (int)$money['id'];?>" <?php if($client['moneys_id'] === $money['id']): echo "selected"; endif; ?> >
                      <?php echo $money['moneytype'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                </div>
            </div>
          <button type="submit" name="client" class="btn btn-danger">Update Customer</button> 
          </form>
          </div>
          </div>
          </div>
          </div>
      </div>
<?php include_once('layouts/footer.php'); ?>
