<?php
require_once('includes/load.php');
$output = '';
global $db;

if(isset($_POST["exp_products_excel"]))
{
  if($_POST["exp_customer_products"] === '' || 0)
  {
$sqlQuery = "SELECT * FROM products WHERE openclose = '1'";
$result = $db->query($sqlQuery);

$output .= '
   <table class="table" bordered="1">  
              <tr>  
                 <th>Id</th>  
                 <th>Description English</th>  
                 <th>Description Spanish</th>  
       					 <th>F.O.B</th>
       					 <th>Money</th>
       					 <th>Color</th>
       					 <th>Material</th>
       					 <th>Size</th>
       					 <th>CBM</th>
       					 <th>Packing</th>
       					 <th>Inner</th>
       					 <th>Unit</th>
       					 <th>Packaging</th>
       					 <th>EAN13</th>
       					 <th>DUN14</th>
       				</tr>
  ';

while($product = $db->fetch_assoc($result) ) {

$money = joinsqlname('products','moneys','moneys_id','moneytype',$product['moneys_id']);
$unit = joinsqlname('products','units','units_id','unittype',$product['units_id']);
$packaging = joinsqlname('products','packaging','packaging_id','packagingtype',$product['packaging_id']);

$output .= '
    <tr> 
    	<td>'.$product["id"].'</td>
    	<td>'.$product["desc_english"].'</td>
    	<td>'.$product["desc_spanish"].'</td>
    	<td>'.$product["price"].'</td>
    	<td>'.$money.'</td>
    	<td>'.$product["color"].'</td>
    	<td>'.$product["material"].'</td>
    	<td>'.$product["size"].'</td>
    	<td>'.$product["cbm"].'</td>
    	<td>'.$product["packing"].'</td>
    	<td>'.$product["inners"].'</td>
    	<td>'.$unit.'</td>
    	<td>'.$packaging.'</td>
    	<td>'.$product["ean13"].'</td>
    	<td>'.$product["dun14"].'</td>
   </tr>
   ';

}

$output .= '</table>';
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=Product list.xls');

echo $output;
}
else{
  $sqlQuery = "SELECT * FROM products P JOIN clientcode C ON clients_id = ".$_POST["exp_customer_products"]." WHERE P.id = C.products_id AND P.openclose = 1";
$result = $db->query($sqlQuery);

$output .= '
   <table class="table" bordered="1">  
              <tr>  
                 <th>Id</th>
                 <th>Customer Alias<th>
                 <th>Description English</th>  
                 <th>Description Spanish</th>  
                 <th>F.O.B</th>
                 <th>Money</th>
                 <th>Color</th>
                 <th>Material</th>
                 <th>Size</th>
                 <th>CBM</th>
                 <th>Packing</th>
                 <th>Inner</th>
                 <th>Unit</th>
                 <th>Packaging</th>
                 <th>EAN13</th>
                 <th>DUN14</th>
                 <th>Image</th>
              </tr>
  ';

while($product = $db->fetch_assoc($result) ) {

$money = joinsqlname('products','moneys','moneys_id','moneytype',$product['moneys_id']);
$unit = joinsqlname('products','units','units_id','unittype',$product['units_id']);
$packaging = joinsqlname('products','packaging','packaging_id','packagingtype',$product['packaging_id']);
$cus_alias = $product['clientcode'];
$image = $cus_alias.'.jpg';
$output .= '
    <tr> 
      <td>'.$product["products_id"].'</td>
      <td>'.$cus_alias.'<td>
      <td>'.$product["desc_english"].'</td>
      <td>'.$product["desc_spanish"].'</td>
      <td>'.$product["price"].'</td>
      <td>'.$money.'</td>
      <td>'.$product["color"].'</td>
      <td>'.$product["material"].'</td>
      <td>'.$product["size"].'</td>
      <td>'.$product["cbm"].'</td>
      <td>'.$product["packing"].'</td>
      <td>'.$product["inners"].'</td>
      <td>'.$unit.'</td>
      <td>'.$packaging.'</td>
      <td>'.$product["ean13"].'</td>
      <td>'.$product["dun14"].'</td>
      <td>'.$image.'</td>
   </tr>
   ';

}

$output .= '</table>';

header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=Product list.xls');

echo $output;

}
}


?>
