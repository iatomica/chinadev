<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Itemsales {
private $itemsalesTable = 'detail_sales';

//'".$_POST['clientcodeProductid']."'";
public function listItemsales($salesid){
	global $db;		
	$sqlQuery = "SELECT * FROM detail_sales WHERE sales_id = '".$salesid."'";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND clientcode_sales LIKE "%'.$_POST["search"]["value"].'%" ';
		//$sqlQuery .= ' OR clientcode LIKE "%'.$_POST["search"]["value"].'%" ';			
		//$sqlQuery .= ' OR clients_id LIKE "%'.$_POST["search"]["value"].'%" ';		
	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY id DESC ';
	}
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	$result = $db->query($sqlQuery);

        // Updated for pagination	
    $sqlQuery1 = "SELECT * FROM detail_sales WHERE sales_id = '".$salesid."'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$itemsalesData = array();

//".$poid."
		$sales_s = select_sale($salesid);

	while($itemsales = $db->fetch_assoc($result) ) {
		
		$itemsalesRows = array();
		$tpname = obtaing_tp_name($itemsales['tp']);
		//$names = joinsqlname('vendorcode','vendors','vendors_id','name',$itempo['vendors_id']);
		$itemsalesRows[] = $itemsales['products_id'];
		$itemsalesRows[] = $itemsales['clientcode_sales'];
		$itemsalesRows[] = $tpname;
		$itemsalesRows[] = $itemsales['desc_pr_sales'];
		$itemsalesRows[] = $itemsales['uxb_sales'];
		$itemsalesRows[] = $itemsales['qty_sales'];
		$itemsalesRows[] = $itemsales['price_sales'];
		$itemsalesRows[] = $itemsales['ctn_sales'];
		$itemsalesRows[] = $itemsales['cbm_sales'];
		$itemsalesRows[] = $itemsales['nw_sales'];	
		$itemsalesRows[] = $itemsales['gw_sales'];
		$itemsalesRows[] = $itemsales['cbm_total_sales'];
		$itemsalesRows[] = $itemsales['nw_total_sales'];
		$itemsalesRows[] = $itemsales['gw_total_sales'];
		$itemsalesRows[] = $itemsales['price_total_sales'];
		
		if ($sales_s['finalized'] === '1'){
			$itemsalesRows[] = 'Disabled';
			$itemsalesRows[] = 'Disabled';

		}
		else{
		$itemsalesRows[] = '<button type="button" name="update" id="'.$itemsales["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		
		$itemsalesRows[] = '<button type="button" name="delete" id="'.$itemsales["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
		
		}
		
		
		
		$itemsalesData[] = $itemsalesRows;
	}
	
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$itemsalesData
	);
	
	echo json_encode($output); 
}



//".$_POST["clientcodeId"]."
	public function getItemsales(){
		global $db;	
		if($_POST["item2"]) {
			$sqlQuery = "SELECT * FROM detail_sales WHERE id = '".$_POST["item2"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addItemsales(){
	global $db;
	$total_cbm = $_POST["sales-code-cbm"] * $_POST["sales-code-ctn"];
	$total_nw = $_POST["sales-code-nw"] * $_POST["sales-code-ctn"];
	$total_gw = $_POST["sales-code-gw"] * $_POST["sales-code-ctn"];
	$total_price = $_POST["sales-code-qty"] * $_POST["sales-code-fob"];
	$sales_clientcode = 
	$insertQuery = "INSERT INTO ".$this->itemsalesTable." (clientcode_sales,desc_pr_sales,uxb_sales,price_sales,cbm_sales,qty_sales,gw_sales,nw_sales, ctn_sales,cbm_total_sales,gw_total_sales,nw_total_sales,price_total_sales,products_id,sales_id,tp) 
	VALUES ('".$_POST["sales-code-name"]."', '".$_POST["sales-code-description"]."', '".$_POST["sales-code-uxb"]."', '".$_POST["sales-code-fob"]."', '".$_POST["sales-code-cbm"]."', '".$_POST["sales-code-qty"]."', '".$_POST["sales-code-gw"]."', '".$_POST["sales-code-nw"]."', '".$_POST["sales-code-ctn"]."', '".$total_cbm."', '".$total_gw."', '".$total_nw."','".$total_price."','".$_POST["sales-productid"]."','".$_POST["salesid-id"]."','".$_POST["sales-tp"]."')";
	
		//$insertQuery = "INSERT INTO clientcode (clientcode, clients_id, products_id) 
		//VALUES ('asd456', '1', '29')";
		//".$_POST["clientcodeClient"]."
	$db->query($insertQuery);
}

public function updateItemsales(){
	global $db;	
	$total_cbm = $_POST["sales-code-cbm"] * $_POST["sales-code-ctn"];
	$total_nw = $_POST["sales-code-nw"] * $_POST["sales-code-ctn"];
	$total_gw = $_POST["sales-code-gw"] * $_POST["sales-code-ctn"];
	$total_price = $_POST["sales-code-qty"] * $_POST["sales-code-fob"];
		
		$updateQuery = "UPDATE detail_sales SET uxb_sales = '".$_POST["sales-code-uxb"]."', price_sales = '".$_POST["sales-code-fob"]."', cbm_sales = '".$_POST["sales-code-cbm"]."', qty_sales = '".$_POST["sales-code-qty"]."', desc_pr_sales = '".$_POST["sales-code-description"]."', gw_sales = '".$_POST["sales-code-gw"]."', nw_sales = '".$_POST["sales-code-nw"]."', ctn_sales = '".$_POST["sales-code-ctn"]."', cbm_total_sales = '".$total_cbm."', gw_total_sales = '".$total_gw."', nw_total_sales = '".$total_nw."', price_total_sales = '".$total_price."' , products_id = '".$_POST["sales-productid"]."' WHERE id ='".$_POST["sales-id"]."'";
		$isUpdated = $db->query($updateQuery);
	

	
}

public function ItemsalesDelete(){
	global $db;
	if($_POST["item"]) {
		$sqlDelete = "DELETE FROM detail_sales WHERE id = '".$_POST["item"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	}
  }
}