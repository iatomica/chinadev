<?php
header('Content-Type: text/html; charset=utf-8');
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Productinfo {
private $productTable = 'products';

//'".$_POST['clientcodeProductid']."'";

public function listProduct1(){
		global $db;		
	$sqlQuery = "SELECT *, P.id AS pid FROM products P JOIN packaging PA ON P.packaging_id = PA.id JOIN price_type PRT ON P.price_type_id = PRT.id JOIN units U ON P.units_id = U.id JOIN moneys M ON P.moneys_id = M.id WHERE P.openclose='1' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND P.desc_english LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR P.id LIKE "%'.$_POST["search"]["value"].'%" ';			
		//$sqlQuery .= ' OR clients_id LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY P.id DESC ';
	}
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT *, P.id AS pid FROM products P JOIN packaging PA ON P.packaging_id = PA.id JOIN price_type PRT ON P.price_type_id = PRT.id JOIN units U ON P.units_id = U.id JOIN moneys M ON P.moneys_id = M.id WHERE P.openclose='1' ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$productData = array();	


	
	while($product = $db->fetch_assoc($result) ) {
		
		$productRows = array();
		$catsub = select_cat_sub($product['pid']);

		$image = name_image($product['media_id']);	
		//$codecliRows[] = $codecli['id'];
		$productRows[] = $product['pid'];
		//$productRows[] = '<img class="img-thumbnail" src="uploads/products/BA-20167.JPG" alt="">';
		$photo = "'{$image}'";
		
		$image1 = 'uploads/products/no-image.jpg';

 // Read image path, convert to base64 encoding
 		$type1 = pathinfo($image1, PATHINFO_EXTENSION);
		$data1 = file_get_contents($image1);

 		$imgData = base64_encode($data1);

 // Format the image SRC:  data:{mime};base64,{data};
 		$src = 'data:image/' . $type1 . ';base64,'.$imgData;
		


		if ($product['media_id'] === '0'){
			$productRows[] = '<img class="img-thumbnail" src="'.$image1.'" alt="image" id="main">';
		}
		else{
			$image2 = 'uploads/products/'.$image.'';

 // Read image path, convert to base64 encoding
 		$type2 = pathinfo($image2, PATHINFO_EXTENSION);
		$data2 = file_get_contents($image2);

 		$imgData1 = base64_encode($data2);

 // Format the image SRC:  data:{mime};base64,{data};
 		$src1 = 'data:image/' . $type2 . ';base64,'.$imgData1;

			$productRows[] = '<img value="'.$image.'" id="'.$image.'" class="img-thumbnail" onClick ="imagen(id)" src="'.$image2.'">' ;
		}
		
		$productRows[] = $product['desc_english']; 
		$productRows[] = $product['price'];
		$productRows[] = 'Customer Alias';
		$productRows[] = $product['price_type'];
		$productRows[] = $product['moneytype'];
		$productRows[] = $product['color'];
		$productRows[] = $product['material'];
		$productRows[] = $product['size'];
		$productRows[] = $product['cbm'];
		$productRows[] = $product['uxb'];
		$productRows[] = $catsub['cname'];
		$productRows[] = $catsub['sname'];
		$productRows[] = $product['inners'];
		$productRows[] = $product['unittype'];
		$productRows[] = $product['packagingtype'];
		$productRows[] = $product['ean13'];
		$productRows[] = $product['dun14'];
		$productRows[] = $product['volume'];
		$productRows[] = $product['product_weight'];
		$productRows[] = $product['netweight'];
		$productRows[] = $product['grossweight'];



		//$productRows[] = '<button type="button" name="update" href="edit_product.php?id='.$product['id'].'" class="btn btn-info btn-xs update"  title="Edit">Update </button>';
		$productRows[] ='<div class="btn-group"><a href="" name="editproduct" id="'.$product['pid'].'" class="btn btn-info btn-xs update editproduct"><span class="glyphicon glyphicon-edit"></span></a>';


		/* '<div class="btn-group">
                    <type=button class="btn btn-info btn-xs" id="editproduct" name"editproduct" title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';
*/
		$productRows[] = '<div class="btn-group">
                    <a href="delete_product.php?id='.$product['pid'].'" class="btn btn-danger btn-xs"  title="Close" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a> </div>';
    
		$productData[] = $productRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$productData
	);
	
	echo json_encode($output);

}

public function listProduct(){
   
	if ($_POST["filter"] === '0'){
	global $db;		
	$sqlQuery = "SELECT *, P.id AS pid FROM products P JOIN packaging PA ON P.packaging_id = PA.id JOIN price_type PRT ON P.price_type_id = PRT.id JOIN units U ON P.units_id = U.id JOIN moneys M ON P.moneys_id = M.id WHERE P.openclose='1' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND P.desc_english LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR P.id LIKE "%'.$_POST["search"]["value"].'%" ';			
		//$sqlQuery .= ' OR clients_id LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY P.id DESC ';
	}
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT *, P.id AS pid FROM products P JOIN packaging PA ON P.packaging_id = PA.id JOIN price_type PRT ON P.price_type_id = PRT.id JOIN units U ON P.units_id = U.id JOIN moneys M ON P.moneys_id = M.id WHERE P.openclose='1' ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$productData = array();	


	
	while($product = $db->fetch_assoc($result) ) {
		
		$productRows = array();
		$catsub = select_cat_sub($product['pid']);
		$image = name_image($product['media_id']);	
		//$codecliRows[] = $codecli['id'];
		$productRows[] = $product['pid'];
		//$productRows[] = '<img class="img-thumbnail" src="uploads/products/BA-20167.JPG" alt="">';
		$photo = "'{$image}'";
		if ($product['media_id'] === '0'){
			$productRows[] = '<img class="img-thumbnail" src="uploads/products/no-image.jpg" alt="" id="main">';
		}
		else{
			$productRows[] = '<img value="'.$image.'" id="'.$image.'" class="img-thumbnail" onClick ="imagen(id)" src="uploads/products/'.$image.'">' ;
		}
		
		$productRows[] = $product['desc_english']; 
		$productRows[] = $product['price'];
		$productRows[] = 'Customer Alias';
		$productRows[] = $product['price_type'];
		$productRows[] = $product['moneytype'];
		$productRows[] = $product['color'];
		$productRows[] = $product['material'];
		$productRows[] = $product['size'];
		$productRows[] = $product['cbm'];
		$productRows[] = $product['uxb'];
		$productRows[] = $catsub['cname'];
		$productRows[] = $catsub['sname'];
		$productRows[] = $product['inners'];
		$productRows[] = $product['unittype'];
		$productRows[] = $product['packagingtype'];
		$productRows[] = $product['ean13'];
		$productRows[] = $product['dun14'];
		$productRows[] = $product['volume'];
		$productRows[] = $product['product_weight'];
		$productRows[] = $product['netweight'];
		$productRows[] = $product['grossweight'];



		//$productRows[] = '<button type="button" name="update" href="edit_product.php?id='.$product['id'].'" class="btn btn-info btn-xs update"  title="Edit">Update </button>';
		$productRows[] ='<div class="btn-group"><a href="" name="editproduct" id="'.$product['pid'].'" class="btn btn-info btn-xs update editproduct"><span class="glyphicon glyphicon-edit"></span></a>';


		/* '<div class="btn-group">
                    <type=button class="btn btn-info btn-xs" id="editproduct" name"editproduct" title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a> </div>';
*/
		$productRows[] = '<div class="btn-group">
                    <a href="delete_product.php?id='.$product['pid'].'" class="btn btn-danger btn-xs"  title="Close" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a> </div>';
    
		$productData[] = $productRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$productData
	);
	
	echo json_encode($output);
}




else{

	global $db;		
	$sqlQuery = "SELECT *, P.id AS pid FROM products P JOIN clientcode C ON P.id = C.products_id JOIN packaging PA ON P.packaging_id = PA.id JOIN price_type PRT ON P.price_type_id = PRT.id JOIN units U ON P.units_id = U.id JOIN moneys M ON P.moneys_id = M.id WHERE C.clients_id = '".$_POST['filter']."' AND P.openclose='1' AND C.openclose= '1' ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND P.desc_english LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR P.id LIKE "%'.$_POST["search"]["value"].'%" ';			
		$sqlQuery .= ' OR C.clientcode LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY P.id DESC ';
	}
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT *, P.id AS pid FROM products P JOIN clientcode C ON P.id = C.products_id JOIN packaging PA ON P.packaging_id = PA.id JOIN price_type PRT ON P.price_type_id = PRT.id JOIN units U ON P.units_id = U.id JOIN moneys M ON P.moneys_id = M.id WHERE C.clients_id = '".$_POST['filter']."' AND P.openclose='1' AND C.openclose= '1'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$productData = array();	


	
	while($product = $db->fetch_assoc($result) ) {
		
		$productRows = array();
		$catsub = select_cat_sub($product['pid']);
		$image = name_image($product['media_id']);	
		//$codecliRows[] = $codecli['id'];
		$productRows[] = $product['id'];
		//$productRows[] = '<img class="img-thumbnail" src="uploads/products/BA-20167.JPG" alt="">';
		$photo = "'{$image}'";
		if ($product['media_id'] === '0'){
			$productRows[] = '<img class="img-thumbnail" src="uploads/products/no-image.jpg" alt="" id="main">';
		}
		else{
			$productRows[] = '<img value="'.$image.'" id="'.$image.'" class="img-thumbnail" onClick ="imagen(id)" src="uploads/products/'.$image.'">' ;
		}
		
		$productRows[] = $product['desc_english']; 
		$productRows[] = $product['price'];
		$productRows[] = $product['clientcode'];
		$productRows[] = $product['price_type'];
		$productRows[] = $product['moneytype'];
		$productRows[] = $product['color'];
		$productRows[] = $product['material'];
		$productRows[] = $product['size'];
		$productRows[] = $product['cbm'];
		$productRows[] = $product['uxb'];
		$productRows[] = $catsub['cname'];
		$productRows[] = $catsub['sname'];
		$productRows[] = $product['inners'];
		$productRows[] = $product['unittype'];
		$productRows[] = $product['packagingtype'];
		$productRows[] = $product['ean13'];
		$productRows[] = $product['dun14'];
		$productRows[] = $product['volume'];
		$productRows[] = $product['product_weight'];
		$productRows[] = $product['netweight'];
		$productRows[] = $product['grossweight'];


		//$productRows[] = '<button type="button" name="update" href="edit_product.php?id='.$product['id'].'" class="btn btn-info btn-xs update"  title="Edit">Update </button>';
		$productRows[] ='<div class="btn-group"><a href="" name="editproduct" id="'.$product['pid'].'" class="btn btn-info btn-xs update editproduct"><span class="glyphicon glyphicon-edit"></span></a>';


		 

		$productRows[] = '<div class="btn-group">
                    <a href="delete_product.php?id='.$product['pid'].'" class="btn btn-danger btn-xs"  title="Close" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a> </div>';

		$productData[] = $productRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$productData
	);
	
	echo json_encode($output);




}





	}
}
?>