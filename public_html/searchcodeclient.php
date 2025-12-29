<?php
require_once('includes/load.php'); 

 if(isset($_POST["query"]))  
 {  
      $output = '';  
     
     $query = "SELECT * FROM clientcode WHERE clients_id = ".$_POST["idcli"]." AND clientcode LIKE '%".$_POST["query"]."%'";  
     $result = $db->query($query);  
      $output = '<ul class="list-unstyled">';  
      if($db->num_rows($result) > 0)  
      {  
           while($row = $db->fetch_assoc($result))  
           {  
                $output .= '<li class="li-first" id="li-first" name="li-first" value="'.$row["products_id"].'">'.$row["clientcode"].'</li>';

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
