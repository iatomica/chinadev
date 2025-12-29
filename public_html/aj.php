<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php 
$html = '';
$categorie = $_POST['categorye'];
$subcategorye = $_POST['subcategorye'];
 
 $all_subcategories = find_subcat('subcategories',$categorie);

$html.="<label>Subcategorie</label>";
$html.="<select id=\"product-subcategorie\" class=\"form-control\" name=\"product-subcategorie\">";	
$html.= "<option value=\"0\"></option>";
	foreach ($all_subcategories as $subcat){
    
       
      if ($subcategorye === $subcat['id'] ){
        $html .= "<option value=\"{$subcat['id']}\" selected>".$subcat['name']."</option>";

       }  

       else{

          $html.= "<option value=\"{$subcat['id']}\">" .$subcat['name']."</option>";
         }
   
}
 

    $html.="</select>";
    echo json_encode($html);

?>