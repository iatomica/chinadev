<?php
  $page_title = 'Add Customer';
  require_once('includes/load.php');
  $all_moneys = find_all1('moneys');
  ?>
<?php
if(isset($_POST['add_clients'])){
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
$c_openclose = '1';

$query ="INSERT INTO clients (";
$query .=" name,address,city,phone,website,contact,email,bank,comission,openclose,country,taxpayer,moneys_id";
$query .=") VALUES (";
$query .="'{$c_name}', '{$c_address}', '{$c_city}', '{$c_phone}', '{$c_website}', '{$c_contact}', '{$c_email}', '{$c_bank}', '{$c_comission}','{$c_openclose}','{$c_country}','{$c_taxpayer}','{$c_money}')";

if($db->query($query)){
       $session->msg('s',"Customer was added successfully. ");
       redirect('clients.php', false);
        } else {
       $session->msg('d','Registration has failed.');
       
     }
}
else{
     $session->msg("d", $errors);
     redirect('add_clients.php',false);


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
            <span>Add Customer</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form id="clients" method="post" action="add_clients.php" class="clearfix">
            </div> 
              <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Company name</label>                  
                     <input type="text" class="form-control" name="client-name" placeholder="Insert client name" autocomplete="off">
                 </div>
                 <div class="col-md-6">
                                  
                    <label for="qty">address</label>                  
                     <input type="text" class="form-control" name="client-address" placeholder="Insert client address" autocomplete="off">
                  </div>
               </div> 
          </div>
              <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">City</label>                  
                     <input type="text" class="form-control" name="client-city" placeholder="Insert client city" autocomplete="off">
                 </div>
                 <div class="col-md-6">
                                  
                    <label for="qty">Phone</label>                  
                     <input type="number" class="form-control" name="client-phone" placeholder="Insert client phone" autocomplete="off">
                  </div>
               </div> 
          </div>
			<div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                                  
                    <label for="qty">Website</label>                  
                     <input type="text" class="form-control" name="client-website" placeholder="Insert client website" autocomplete="off">
                  </div>  
                  <div class="col-md-6">
                                  
                    <label for="qty">Contact</label>                  
                     <input type="text" class="form-control" name="client-contact" placeholder="Insert client contact" autocomplete="off">
                  </div>                
               </div> 
          </div>                        
           <div class="form-group">
                <div class="row">
                	 <div class="col-md-6">
                    <label for="qty">Comission</label>                  
                     <input type="number" class="form-control" name="client-comission" placeholder="Insert client comission" autocomplete="off">
                 </div>
                  <div class="col-md-6">
                                  
                    <label for="qty">Country</label>                  
                     <input type="text" class="form-control" name="client-country" placeholder="Insert customer country" autocomplete="off">
                  </div> 

                </div>
            </div>
            <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                                  
                    <label for="qty">Tax Payer</label>                  
                     <input type="text" class="form-control" name="client-taxpayer" placeholder="Insert Tax payer" autocomplete="off">
                  </div>
                   <div class="col-md-4">
                    <div class="form-group">
                    <label for="qty">Currency</label>
                    <select class="form-control" name="client-money" required>
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
          <button type="submit" name="add_clients" class="btn btn-danger">Add Customer</button> 
          </form>
          </div>
          </div>
          </div>
          </div>
      </div>
    </div>

   


<?php include_once('layouts/footer.php'); ?>
