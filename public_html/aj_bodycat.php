<?php
require_once('includes/load.php'); 
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
$all_categories = find_all('categories');



$html = '';


foreach ($all_categories as $category) {

		$subcategorie = select_subcategorie1('subcategories',$category['id']);
		
		
 		
 		$html .= '<ul>'.$category['name'];

 		/*
 		foreach ($subcategorie as $subcategory) {
 			if ($subcategory['name'] == NULL){
 				$html .='';
 			}
 			else{
 			
 			$html .= '<li>'.$subcategory['name'].'</li>';
        	}
        	
 		}
        */
        $html .= '<li></li>';
        $html .= '</ul>';
 			
   }

/*

foreach ($all_categories as $category) {

		$subcategorie = select_subcategorie1('subcategories',$category['id']);
		
		$html .= '<tr>';
		$html .= '<td class="text-center">1</td>';
		$html .= '<td>'.$category['name'].'</td>';
 		$html .= '<td>';
 		
 		$html .= '<ul>';
 		foreach ($subcategorie as $subcategory) {

 			
 			$html .= '<li>'.$subcategory['name'].'</li>';
        	
        	
 		}
        
        $html .= '</ul>';
 			$html .= '</td>';
    	
		
		$html .= '<td class="text-center">accion</td>';
		$html .= '</tr>';
   }

*/
   
    
 
echo json_encode($html);
?>

