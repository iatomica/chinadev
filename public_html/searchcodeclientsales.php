<?php
require_once('includes/load.php');

 if(isset($_POST["query"]))  
 {   

     
      $output = '';  
      $query = "SELECT d.id,d.clientcode_rg,d.products_id,d.uxb_rg FROM detail_rg d JOIN receivedgoods r ON r.id = d.receivedgoods_id AND r.clients_id = ".$_POST["idcli"]."  WHERE d.clientcode_rg LIKE'%".$_POST["query"]."%' AND (d.invoiced = 0 OR d.invoiced = 3)";  
/*
      $query = "SELECT distinct d.id,d.clientcode_rg,d.products_id FROM detail_rg d JOIN receivedgoods r WHERE d.clientcode_rg LIKE '%".$_POST["query"]."%' AND r.clients_id = ".$_POST["idcli"]." AND d.receivedgoods_id = r.id AND d.invoiced = 0 OR d.invoiced = 3";  

      */
     $result = $db->query($query);  
      $output = '<ul class="list-unstyled">';  
      if($db->num_rows($result) > 0)  
      {  
           while($row = $db->fetch_assoc($result))  
           {  
                $output .= '<li class="li-first" id="li-first" uxb="'.$row["uxb_rg"].'" clientcode="'.$row["clientcode_rg"].'" name="li-first" value="'.$row["products_id"].'">'.$row["clientcode_rg"].'         UXB='.$row["uxb_rg"].'</li>';

           }  
      }  
      else  
      {  
           $output .= '<li>Client code Not Found</li>';  
      }  
      $output .= '</ul>';




      echo $output;  
 }  
 ?>  
