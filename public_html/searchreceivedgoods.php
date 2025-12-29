<?php
require_once('includes/load.php'); 

 if(isset($_POST["query"]))  
 {  
      $output = '';  
      $query = "SELECT * FROM receivedgoods WHERE vendors_id = ".$_POST["vendor"]." AND numbers LIKE '%".$_POST["query"]."%'";  
     $result = $db->query($query);  
      $output = '<ul class="list-rg-ca-items">';  
      if($db->num_rows($result) > 0)  
      {  
           while($row = $db->fetch_assoc($result))  
           {  
                $output .= '<li class="li-first" value="'.$row["id"].'" id="li-first" name="li-first"  data-tab="'.$row["numbers"].'">'.$row["numbers"].' </li>';

           }  
      }  
      else  
      {  
           $output .= '<li>Receivedgoods Not Found</li>';  
      }  
      $output .= '</ul>';




      echo $output;  
 }   
 ?>  
