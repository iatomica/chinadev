<?php
require_once('includes/load.php'); 

 if(isset($_POST["query"]))  
 {  
      $output = '';  
      //$query = "SELECT * FROM detail_po WHERE pendent > 0 AND clientcode_po LIKE '%".$_POST["query"]."%'";
      //SELECT d.clientcode_po,d.products_id FROM detail_po d JOIN purchaseorder p ON d.purchaseorder_id = p.id  WHERE p.clients_id = ".$_POST["client"]." AND p.vendors_id = ".$_POST["vendor"]." AND d.pendent > 0 AND d.finalized = '0' AND d.clientcode_po LIKE '%".$_POST["query"]."%'
      	$query = "SELECT d.clientcode_po,d.products_id FROM detail_po d JOIN purchaseorder p ON d.purchaseorder_id = p.id  WHERE p.clients_id = ".$_POST["client"]." AND p.vendors_id = ".$_POST["vendor"]." AND d.pendent > 0 AND d.finalized = '0' AND d.clientcode_po LIKE '%".$_POST["query"]."%'";
      
     $result = $db->query($query);  
      $output = '<ul class="list-rg-items">';  
      if($db->num_rows($result) > 0)  
      {  
           while($row = $db->fetch_assoc($result))  
           {  
                $output .= '<li class="li-first" id="li-first" name="li-first" value="'.$row["products_id"].'" data-tab="'.$row["clientcode_po"].'">'.$row["clientcode_po"].' </li>';

           }  
      }  
      else  
      {  
           $output .= '<li>Customer alias Not Found</li>';  
      }  
      $output .= '</ul>';




      echo $output;  
 }   
 ?>  

