<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Desaprob {

//'".$_POST['clientcodeProductid']."'";
public function desaprobar(){
	

 
    $sales_id_1 = '4';

    $select_items_sales = select_sales_items($sales_id_1); 
   

foreach ($select_items_sales as $select) {

    $rgitem=select_rg_items($select['tp'],$select['products_id']);
    
    if($rgitem['invoiced'] === '1'){

        $qty_rg4 = $rgitem['qty_rg'] - $select['qty_sales'];

        if ($qty_rg4 <= '0'){

        update_rg_items_sales_0($select['tp'],$select['products_id']);

        }
        
        else{

     update_rg_items_sales_3($select['tp'],$select['products_id'],$qty_rg4);

    }

    }

    

    if ($rgitem['invoiced'] === '3'){

        $result = $rgitem['partial_invoice'] + $select['qty_sales']; 
        					
        if ($result >= $rgitem['qty_rg']){

         update_rg_items_sales_0($select['tp'],$select['products_id']);

        }
        else
        	{
            
            update_rg_items_sales_3($select['tp'],$select['products_id'],$result);

            }

    
    
    }
      

  

    		$qty_stock = select_stock('1',$select['products_id']);
    		$qty_stock_final = $qty_stock['qty'] + $select['qty_sales'];
          
    		update_stock($qty_stock_final,$qty_stock['id']);

  
  }
        
        finalized_sales_0($sales_id_1);

        delete_currentacount_sales($sales_id_1);


}
}