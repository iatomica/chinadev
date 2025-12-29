<?php
  $page_title = 'Update Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$catid = find_by_id_catsub('productscatsub',(int)$product['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_moneys = find_all('moneys');
$all_units = find_all('units');
$all_vendors = find_all('vendors');
$all_clients = find_all('clients');
$all_packagings = find_all('packaging');
$all_catsub = find_all('productscatsub');
$all_prices = find_all('price_type');
if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
 if(isset($_POST['product'])){
  global $db;
    $req_fields = array('product-title-en','product-categorie');
    validate_fields($req_fields);

  
  $photo = new Media();
  $photo->upload($_FILES['file_upload'],$product['id']);
  if($photo->process_media($product['media_id'])){

    $session->msg('s','Imagen subida al servidor.');

  } else{
    $session->msg('d',join($photo->errors));

  }

  $filecode = $photo->return_filename($_FILES['file_upload'],$product['id']);

    if ($_FILES['file_upload']["error"] == 4){
      $p_media = $product['media_id'];
      
    } else {

      $p_media = select_image_pr('media',$filecode);

    }


    $p_code  = remove_junk($db->escape($_POST['product-code']));
    $p_desc_en  = remove_junk($db->escape($_POST['product-title-en']));
    $p_desc_es  = remove_junk($db->escape($_POST['product-title-es']));
    $p_desc_pg  = remove_junk($db->escape($_POST['product-title-pg']));
    $p_desc_ch  = remove_junk($db->escape($_POST['product-title-ch']));
    $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
    $p_subcat = remove_junk($db->escape($_POST['product-subcategorie']));    
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
    $p_inner = remove_junk($db->escape(intvar($_POST['product-inner'])));
    $p_cbm = remove_junk($db->escape(intvar($_POST['product-cbm'])));
    $p_openclose = true;
    $p_price_type = remove_junk($db->escape($_POST['product-price-type']));
    $p_netweight = remove_junk($db->escape($_POST['product-netweight']));
    $p_grossweight = remove_junk($db->escape($_POST['product-grossweight']));
    $p_volume = remove_junk($db->escape(intvar($_POST['product-volume'])));
    $p_weight = remove_junk($db->escape(intvar($_POST['product-weight'])));



       
       $query   = "UPDATE products SET";
       $query  .=" desc_english ='{$p_desc_en}',price ='{$p_price}',media_id = '{$p_media}', moneys_id = '{$p_money}', desc_spanish = '{$p_desc_es}',";
       $query  .=" desc_chinese = '{$p_desc_ch}' , desc_portuguese = '{$p_desc_pg}', color = '{$p_color}', material = '{$p_material}', size = '{$p_size}', cbm = '{$p_cbm}', openclose = '{$p_openclose}', uxb = '{$p_uxb}', inners = '{$p_inner}', units_id = '{$p_unit}', packaging_id = '{$p_packaging}', ean13 = '{$p_codebar_ean13}', dun14 ='{$p_codebar_dun14}', price_type_id = '{$p_price_type}',netweight = '{$p_netweight}',grossweight = '{$p_grossweight}', volume = '{$p_volume}', product_weight = '{$p_weight}' ";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
       

       $cat = select_categorie_products($product['id']);

      if ($cat === true){

         $query1  = "UPDATE productscatsub SET";
      $query1 .= " categories_id = '{$p_cat}', subcategories_id = '{$p_subcat}'";
      $query1 .=" WHERE products_id = '{$product['id']}'";
      $result1 = $db->query($query1);

       }
        if($cat === false){
          $query2 = "INSERT INTO productscatsub (";
    $query2 .="products_id, categories_id, subcategories_id";
    $query2 .=") VALUES (";
    $query2 .="'{$product['id']}','{$p_cat}','{$p_subcat}'";
    $query2 .=")";
     $db->query($query2);
    }
    

      if($result || $result1 && $db->affected_rows() === 1 ){

        $session->msg('s',"The product has been updated successfully. ");
        redirect('product.php', false);
      }else {
        redirect('product.php', false);

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
            <span>Update Product</span>
         </strong>
        </div>
          <div class="panel-body">
            <div id="tabProduct">
            <ul>
            <li><a href="#CustomerSettings">Customer Settings</a></li>
            <li id="PrInfo" name="PrInfo"><a href="#ProductInfo">Product Info</a></li>
            
        </ul>
         <div class="col-md-12">
          <form id="editproduct" method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>" class="clearfix" enctype="multipart/form-data">
            
        <div id="ProductInfo">
           <div class="form-group">
              <label for="qty">Id</label>
            <div class="row">
            <div class="col-md-4">
                   <div class="input-group">                    
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="text" readonly="readonly" class="form-control" name="product-id" id="product-id" value="<?php echo remove_junk($product['id']);?>" autocomplete="off">      
                  </div>
                 </div>
               </div>
              <label for="qty">Descriptions</label>
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title-en" placeholder="English" value="<?php echo remove_junk($product['desc_english']);?>" autocomplete="off" required>
               </div>
               <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title-es" placeholder="Spanish" value="<?php echo remove_junk($product['desc_spanish']);?>" autocomplete="off">
               </div>
               <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title-pg" placeholder="Portuguese" value="<?php echo remove_junk($product['desc_portuguese']);?>" autocomplete="off">
               </div>
               <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title-ch" placeholder="Chinese" value="<?php echo remove_junk($product['desc_chinese']);?>" autocomplete="off">
               </div>
             </div>
               <div class="form-group">                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="qty">Category</label>
                    <select id="product-categorie" class="form-control" name="product-categorie" required>
                      <option value="">Select Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>"<?php if($catid['categories_id'] === $cat['id']): echo "selected"; endif; ?>>
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>               
              <div id="productsubcategorie" class="col-md-5"> </div>               
             </div>
           </div>
              <div class="form-group">                
            <div class="row"> 
             <div class="col-md-2">
              
                <img class="img-thumbnail1" name="img_products" id="img_products" src="uploads/products/no-image.jpg">
            </div>
        </div>
   </div>
            <div class="form-group">                
                <div class="row">                  
                  <div class="col-md-6">
                    <div class="form-group">

                       
                    <input type="file" name="file_upload" id="file_upload" multiple="multiple" class="btn-default">Choose your image</input>                
                 </div>
                </div>
              </div>
            </div>

          <div class="input-group">
          <input type="hidden" name="p-id" id="p-id" value="<?php echo remove_junk($product['id']);?>"/>
            <input type="hidden" name="subcategorye" id="subcategorye" value="<?php echo remove_junk($catid['subcategories_id']);?>">
          </div>
                <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                    <div class="form-group">
                    <label for="qty">Currency</label>
                    <select class="form-control" name="product-money" required>
                      <option value="">Select Money Type</option>
                      <?php  foreach ($all_moneys as $money): ?>
                      <option value="<?php echo (int)$money['id'];?>" <?php if($product['moneys_id'] === $money['id']): echo "selected"; endif; ?> >
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
                  <input type="number" class="form-control" name="product-price" step="any" placeholder="Insert Price" value="<?php echo remove_junk($product['price']);?>" autocomplete="off" required>    
                </div>
              </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                    <label for="qty">Price Type</label>
                    <select class="form-control" name="product-price-type" required>
                      <option value="">Select Price Type</option>
                      <?php  foreach ($all_prices as $price): ?>
                      <option value="<?php echo (int)$price['id'];?>" <?php if($product['price_type_id'] === $price['id']): echo "selected"; endif; ?> >
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
              <label for="qty">EAN13</label>
             <input type="number" class="form-control" name="product-ean13" placeholder="Insert Codebar EAN13" value="<?php echo remove_junk($product['ean13']);?>" autocomplete="off" required>
           </div>
          </div>
        </div>
          <div class="col-md-4">
        <div class="form-group">
          <label for="qty">         </label>
          <label for="qty"></label>
          <div class="input-group">  
          <label for="qty">DUN14</label>                   
            <input type="number" class="form-control" name="product-dun14" placeholder="Insert Codebar DUN14" value="<?php echo remove_junk($product['dun14']);?>" autocomplete="off" required>
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
         <input type="text" class="form-control" name="product-cbm" placeholder="Insert CBM" value="<?php echo remove_junk($product['cbm']);?>" autocomplete="off" required>
         <span class="input-group-addon">M3</span>
       </div>
     </div>
        </div>
          <div class="col-md-3">
         <div class="form-group">
      <label for="qty">Product size</label>
      <div class="input-group">
       <input type="text" class="form-control" name="product-size" placeholder="Insert Product size" value="<?php echo remove_junk($product['size']);?>" autocomplete="off" required>
     </div>
   </div>
    </div>
     <div class="col-md-3">
     <div class="form-group">
    <label for="qty">Material</label>
    <div class="input-group">
     <input type="text" class="form-control" name="product-material" placeholder="Insert Material" value="<?php echo remove_junk($product['material']);?>" autocomplete="off" required>
     </div>
     </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
        <label for="qty">Product weight</label>
        <div class="input-group">
         <input type="number" step="any" class="form-control" name="product-weight" placeholder="Insert Product Weight" value="<?php echo remove_junk($product['product_weight']);?>" autocomplete="off" required>
         <span class="input-group-addon">GR</span>
       </div>
     </div>
        </div>
    <div class="col-md-3">
    <div class="form-group">
    <label for="qty">Color</label>
    <div class="input-group">
     <input type="text" class="form-control" name="product-color" placeholder="Insert Color" value="<?php echo remove_junk($product['color']);?>" autocomplete="off" required>
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
       <input type="text" class="form-control" name="product-uxb" placeholder="Insert Uxb" value="<?php echo remove_junk($product['uxb']);?>" autocomplete="off" required>                  
     </div>
   </div>
 </div>
 <div class="col-md-3">
  <div class="form-group">
    <label for="qty">Inner</label>
    <div class="input-group">
     <input type="text" class="form-control" name="product-inner" placeholder="Insert Inner" value="<?php echo remove_junk($product['inners']);?>" autocomplete="off" required>
   </div>
 </div>
</div>
<div class="col-md-3">
  <div class="form-group">
    <label for="qty">Unit</label>
    <select class="form-control" name="product-unit" required>
      <option value="">Select Unit Type</option>
      <?php  foreach ($all_units as $unit): ?>
        <option value="<?php echo (int)$unit['id'];?>" <?php if($product['units_id'] === $unit['id']): echo "selected"; endif; ?> >
          <?php echo $unit['unittype'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
    
  <div class="col-md-3">
    <div class="form-group">
      <label for="qty">Packaging</label>
      <select class="form-control" name="product-packaging" required>
        <option value="">Select Packaging</option>
        <?php  foreach ($all_packagings as $packaging): ?>
          <option value="<?php echo (int)$packaging['id'];?>" <?php if($product['packaging_id'] === $packaging['id']): echo "selected"; endif; ?> >
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
         <input type="number" step="any" class="form-control" name="product-netweight" placeholder="Insert CTN Net Weight" value="<?php echo remove_junk($product['netweight']);?>" autocomplete="off" required>
         <span class="input-group-addon">KG</span>
       </div>
     </div>
        </div>
            <div class="col-md-3">
        <div class="form-group">
        <label for="qty">CTN Gross Weight</label>
        <div class="input-group">
         <input type="number" step="any" class="form-control" name="product-grossweight" placeholder="Insert CTN Gross Weight" value="<?php echo remove_junk($product['grossweight']);?>" autocomplete="off" required>
         <span class="input-group-addon">KG</span>
       </div>
     </div>
        </div>
            <div class="col-md-3">
        <div class="form-group">
        <label for="qty">Volume</label>
        <div class="input-group">
         <input type="text" class="form-control" name="product-volume" placeholder="Insert Volume" value="<?php echo remove_junk($product['volume']);?>" autocomplete="off" required>
         <span class="input-group-addon">ML</span>
       </div>
     </div>
        </div>

      </div>
    </div>
       <button type="submit" name="product" class="btn btn-danger">Update</button>  
      
 </form>
</div>
<div id="CustomerSettings">
             <div class="form-group">
             <label for="qty23">Customer Alias</label>             
             <div><button type="button" name="addclientcode" id="addclientcode" class="btn btn-success btn-xs">Add Customer alias</button></div>
              
            <div class="table-responsive">
              <table class="table table-bordered table-striped" name="clientcode" id="clientcode">
              <thead>
                <tr>
                  <th>Customer Alias</th>
                  <th>Customer</th>
                  <th>Update</th>
                  <th>Delete</th>
                </tr>
            </thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </table>
          </div> 
              </div>
</div>
</div>
</div>
</div>
</div>
</div>

        
<div id="clientcodeModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form method="post" id="clientcodeForm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="closecli" name="closecli" data-dismiss="modal">×</button>
          <h4 class="modal-title"><i class="fa fa-plus"></i> Edit Customer Alias</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="control-label">Customer Alias</label>
            <input type="text" class="form-control" id="clientcodeName" name="clientcodeName" placeholder="Customer Alias" required autocomplete="off">      
          </div>
          <div class="form-group">
            <label for="age" class="control-label">Customer</label>              
            <select class="form-control" name="clientcodeClient" id="clientcodeClient">
                <?php  foreach ($all_clients as $cli): ?>
                <option value="<?php echo (int)$cli['id'] ?>">           
                <?php echo $cli['name'] ?></option>
                <?php endforeach; ?>

                </select>              
          </div>      
          <div class="form-group">
            <label for="lastname" class="control-label">Id Product</label>              
            <input type="text" readonly="readonly" class="form-control"  id="clientcodeProductid" name="clientcodeProductid" placeholder="Product id" value ="<?php echo remove_junk($product['id']);?>">             
          </div>   
         
        <div class="modal-footer">
          <input type="hidden" name="clientcodeId" id="clientcodeId"/>
          <input type="hidden" name="action" id="action" value="" />
          <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
        </div>
      </div>
   </form>
</div>
</div>


<?php include_once('layouts/footer.php'); ?>