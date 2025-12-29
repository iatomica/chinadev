<?php
$page_title = 'Agregar producto';
require_once('includes/load.php');
  // Checkin What level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_moneys = find_all('moneys');
$all_units = find_all('units');
$all_packagings = find_all('packaging');
$all_vendors = find_all('vendors');
$all_clients = find_all('clients');
$all_prices = find_all('price_type');

?>
<?php
if(isset($_POST['add_product'])){

     //valida campos
  $req_fields = array('product-title-en');
  validate_fields($req_fields);
  
   if(empty($errors)){
    $p_code  = '0';
    $p_desc_en  = remove_junk($db->escape($_POST['product-title-en']));
    $p_desc_es  = remove_junk($db->escape($_POST['product-title-es']));
    $p_desc_pg  = remove_junk($db->escape($_POST['product-title-pg']));
    $p_desc_ch  = remove_junk($db->escape($_POST['product-title-ch']));
    $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
    $p_subcat = remove_junk($db->escape(intvar($_POST['product-subcategorie'])));    
    $p_codebar_ean13 = remove_junk($db->escape(intvar($_POST['product-ean13'])));
    $p_codebar_dun14 = remove_junk($db->escape(intvar($_POST['product-dun14'])));
    $p_color = remove_junk($db->escape($_POST['product-color']));
    $p_material = remove_junk($db->escape($_POST['product-material']));
    $p_size = remove_junk($db->escape($_POST['product-size']));
    $p_uxb = remove_junk($db->escape(intvar($_POST['product-uxb'])));
    $p_price = remove_junk($db->escape(intvar($_POST['product-price'])));
    $p_money = remove_junk($db->escape(intvar($_POST['product-money'])));
    $p_unit = remove_junk($db->escape(intvar($_POST['product-unit'])));
    $p_packaging = remove_junk($db->escape(intvar($_POST['product-packaging'])));
    $p_inner = remove_junk($db->escape(intvar($_POST['product-Inner'])));
    $p_cbm = remove_junk($db->escape(intvar($_POST['product-cbm'])));
    $p_openclose = true;
    $p_price_type = remove_junk($db->escape($_POST['product-price-type']));
    $p_netweight = remove_junk($db->escape(intvar($_POST['product-netweight'])));
    $p_grossweight = remove_junk($db->escape(intvar($_POST['product-grossweight'])));
    $p_volume = remove_junk($db->escape(intvar($_POST['product-volume'])));
    $p_weight = remove_junk($db->escape(intvar($_POST['product-weight'])));
  


   $date   = '2001-01-01 00:00:00';
   $query  = "INSERT INTO products (";
   $query .=" desc_english,price,media_id,date,code,moneys_id,desc_spanish,desc_chinese,desc_portuguese,color,material,size,cbm,openclose,uxb,inners,units_id,packaging_id,ean13,dun14,price_type_id,netweight,grossweight,volume,product_weight";
   $query .=") VALUES (";
   $query .=" '{$p_desc_en}', '{$p_price}', '0', '{$date}', '{$p_code}','{$p_money}' ,'{$p_desc_es}','{$p_desc_ch}','{$p_desc_pg}','{$p_color}','{$p_material}','{$p_size}','{$p_cbm}','{$p_openclose}','{$p_uxb}','{$p_inner}','{$p_unit}','{$p_packaging}','{$p_codebar_ean13}','{$p_codebar_dun14}','{$p_price_type}','{$p_netweight}','{$p_grossweight}','{$p_volume}','{$p_weight}'";
   $query .=")";
   if($db->query($query)){
    $productid1 = obtaing_max_id('products');
     $session->msg('s',"Product {$productid1} has been added successfully. ");
    } else {
      $session->msg('d',' Fail to add product.');
    }
    

    $productid = obtaing_max_id('products');

   $photo = new Media();
   $photo->upload($_FILES['file_upload'],$productid);
   if($photo->process_media()){

    $session->msg('s','Image uploaded.');

   }

   
   $filecode = $photo->return_filename($_FILES['file_upload'],$productid);

   if ($_FILES['file_upload']["error"] == 4){
      $p_media = '0';
    } else {
      $p_media = add_image_pr('media',$filecode);
    }
  $query2 = "UPDATE products SET media_id = '{$p_media}' WHERE id = '{$productid}'";
  $db->query($query2);



        
   $id_product = find_id_pr('products',$productid);
   

    $query1 = "INSERT INTO productscatsub (";
    $query1 .="products_id, categories_id, subcategories_id";
    $query1 .=") VALUES (";
    $query1 .="'{$id_product}','{$p_cat}','{$p_subcat}'";
    $query1 .=")";
    if($db->query($query1)){
      $session->msg('s',"Product {$productid1} has been added successfully. ");
      redirect('add_product.php', false);
    } else {
      $session->msg('d',' Fail to add product.');
      redirect('product.php', false);
          }
    } else{
    $session->msg("d", $errors);
    redirect('add_product.php',false);
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
          <span>Add product</span>
        </strong>
      </div>
      <div class="panel-body">
       <div class="col-md-12">
        <form id="products" method="post" action="add_product.php" class="clearfix" enctype="multipart/form-data" >
         
           
          <div class="form-group">
            <label for="qty">Descriptions</label>
            <div class="input-group">
              <span class="input-group-addon">
               <i class="glyphicon glyphicon-th-large"></i>
             </span>
             <input type="text" class="form-control" name="product-title-en" placeholder="English" autocomplete="off">
           </div>
           <div class="input-group">
            <span class="input-group-addon">
             <i class="glyphicon glyphicon-th-large"></i>
           </span>
           <input type="text" class="form-control" name="product-title-es" placeholder="Spanish" autocomplete="off">
         </div>
         <div class="input-group">
          <span class="input-group-addon">
           <i class="glyphicon glyphicon-th-large"></i>
         </span>
         <input type="text" class="form-control" name="product-title-pg" placeholder="Portuguese" autocomplete="off">
       </div>
       <div class="input-group">
        <span class="input-group-addon">
         <i class="glyphicon glyphicon-th-large"></i>
       </span>
       <input type="text" class="form-control" name="product-title-ch" placeholder="Chinese" autocomplete="off">
     </div>
   </div>
 

 <div class="form-group">                
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="qty">Category</label>
        <select id="product-categorie" class="form-control" name="product-categorie">
          <option value="0">Select Category</option>
          <?php  foreach ($all_categories as $cat): ?>
            <option value="<?php echo (int)$cat['id'] ?>">
              <?php echo $cat['name'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>               
      <div id="productsubcategorie" name="productsubcategorie" class="col-md-5" > </div>               
    </div>
  </div>  

  <div class="form-group">                
    <div class="row">                  
      <div class="col-md-6">
        <div class="form-group">
          <label for="qty">Image</label>    

          <input type="file" name="file_upload"  multiple="multiple"  class="btn btn-primary btn-file"/>   


        </div>
      </div>
    </div>
  </div>
        <div class="form-group">
       <div class="row">
         <div class="col-md-4">
          <div class="form-group">
            <label for="qty">Currency</label>
            <select class="form-control" name="product-money">
              <option value="">Select Currency</option>
              <?php  foreach ($all_moneys as $money): ?>
                <option value="<?php echo (int)$money['id'] ?>">
                  <?php echo $money['moneytype'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="qty">Price</label>
              <div class="input-group">
               <span class="input-group-addon">
                 <i class="glyphicon glyphicon-usd"></i>
               </span>
               <input type="number" class="form-control" name="product-price" step="any" placeholder="Insert Price" autocomplete="off">                    
             </div>
           </div>
         </div>
         <div class="col-md-4">
          <div class="form-group">
            <label for="qty">Price Type</label>
            <select class="form-control" name="product-price-type">
              <option value="1">Select Price Type</option>
              <?php  foreach ($all_prices as $price): ?>
                <option value="<?php echo (int)$price['id'] ?>"<?php if($price['id'] === '1'): echo "selected"; endif; ?>>
                  <?php echo $price['price_type'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
       </div>
     </div>
     <div class="form-group">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="qty">Code bars</label>
            <div class="input-group">
             <input type="number" class="form-control" name="product-ean13" placeholder="Insert Codebar EAN13" autocomplete="off">
           </div>
         </div>
       </div>
       <div class="col-md-4">
        <div class="form-group">
          <label for="qty"></label>
          <div class="input-group">                     
            <input type="number" class="form-control" name="product-dun14" placeholder="Insert Codebar DUN14" autocomplete="off">
          </div>
        </div>
      </div>

    </div>
  </div>               
  <div class="form-group">
    <div class="row">
     <div class="col-md-3">
      <div class="form-group">
        <label for="qty">CBM</label>
        <div class="input-group">
         <input type="text" class="form-control" name="product-cbm" placeholder="Insert CBM" autocomplete="off">
         <span class="input-group-addon">M3</span>
       </div>
     </div>
   </div>
   <div class="col-md-3">
    <div class="form-group">
      <label for="qty">Product Size</label>
      <div class="input-group">
       <input type="text" class="form-control" name="product-size" placeholder="Insert product Size" autocomplete="off">
     </div>
   </div>
 </div>
 <div class="col-md-3">
  <div class="form-group">
    <label for="qty">Material</label>
    <div class="input-group">
     <input type="text" class="form-control" name="product-material" placeholder="Insert Material" autocomplete="off">
   </div>
 </div>
</div>
<div class="col-md-3">
        <div class="form-group">
        <label for="qty">Product weight</label>
        <div class="input-group">
         <input type="number" step="any" class="form-control" name="product-weight" placeholder="Insert Product Weight" autocomplete="off">
         <span class="input-group-addon">GR</span>
       </div>
     </div>
        </div>
<div class="col-md-3">
  <div class="form-group">
    <label for="qty">Color</label>
    <div class="input-group">
     <input type="text" class="form-control" name="product-color" placeholder="Insert Color" autocomplete="off">
   </div>
 </div>
</div>

</div>
</div>  
<div class="form-group">
  <div class="row">
   <div class="col-md-3">
    <div class="form-group">
      <label for="qty">Uxb</label>
      <div class="input-group">
       <input type="text" class="form-control" name="product-uxb" placeholder="Insert uxb" autocomplete="off">                    
     </div>
   </div>
 </div>
 <div class="col-md-3">
  <div class="form-group">
    <label for="qty">Inner</label>
    <div class="input-group">
     <input type="text" class="form-control" name="product-Inner" placeholder="Insert Inner" autocomplete="off">
   </div>
 </div>
</div>
<div class="col-md-3">
  <div class="form-group">
    <label for="qty">Unit</label>
    <select class="form-control" name="product-unit">
      <option value="">Select Unit Type</option>
      <?php  foreach ($all_units as $unit): ?>
        <option value="<?php echo (int)$unit['id'] ?>">
          <?php echo $unit['unittype'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>  
  <div class="col-md-3">
    <div class="form-group">
      <label for="qty">Packaging</label>
      <select class="form-control" name="product-packaging">
        <option value="">Select Packaging</option>
        <?php  foreach ($all_packagings as $packaging): ?>
          <option value="<?php echo (int)$packaging['id'] ?>">
            <?php echo $packaging['packagingtype'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>                                        
  </div>
</div>
<div class="form-group">
    <div class="row">
     <div class="col-md-3">
      <div class="form-group">
        <label for="qty">CTN Net Weight</label>
        <div class="input-group">
         <input type="number" step="any" class="form-control" name="product-netweight" placeholder="Insert CTN Net Weight" autocomplete="off">
         <span class="input-group-addon">KG</span>
       </div>
     </div>
   </div>
     <div class="col-md-3">
      <div class="form-group">
        <label for="qty">CTN Gross Weight</label>
        <div class="input-group">
         <input type="number" step="any" class="form-control" name="product-grossweight" placeholder="Insert CTN Gross Weight" autocomplete="off">
         <span class="input-group-addon">KG</span>
       </div>
     </div>
   </div>
     <div class="col-md-3">
      <div class="form-group">
        <label for="qty">Volume</label>
        <div class="input-group">
         <input type="number" class="form-control" name="product-volume" placeholder="Insert Volume" autocomplete="off">
         <span class="input-group-addon">ML</span>
       </div>
     </div>
   </div>
   </div>
   </div>                         
<button type="submit" name="add_product" class="btn btn-danger">Add product</button>  
</form>        
</div>
</div>
</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>