<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Itemsm {
private $itemsmTable = 'detail_sm';

//'".$_smST['clientcodeProductid']."'";
public function listItemsm($smid){
	global $db;		
	$sqlQuery = "SELECT * FROM detail_sm WHERE stockmovements_id = '".$smid."'";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND vendorcode LIKE "%'.$_POST["search"]["value"].'%" ';
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
    $sqlQuery1 = "SELECT * FROM detail_sm WHERE stockmovements_id = '".$smid."'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$itemsmData = array();
	$status = obtaing_po_status($smid);
//".$poid."

	while($itemsm = $db->fetch_assoc($result) ) {
		
		$itemsmRows = array();
		//$names = joinsqlname('vendorcode','vendors','vendors_id','name',$itempo['vendors_id']);
		$itemsmRows[] = $itemsm['products_id'];
		$itemsmRows[] = $itemsm['clientcode_sm'];
		$itemsmRows[] = $itemsm['desc_pr_sm'];
		$itemsmRows[] = $itemsm['qty_sm'];
		if ($itemsm['finalized'] === '0'){
			$itemsmRows[] = 'Negative Stock';
			$itemsmRows[] = '<button type="button" name="update" id="'.$itemsm["id"].'" class="btn btn-warning btn-xs update">Update</button>';
			$itemsmRows[] = '<button type="button" name="delete" id="'.$itemsm["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
	}
		elseif ($itemsm['finalized'] === '3'){

			
			$itemsmRows[] = 'Pending to submit';
			$itemsmRows[] = '<button type="button" name="update" id="'.$itemsm["id"].'" class="btn btn-warning btn-xs update">Update</button>';
			$itemsmRows[] = '<button type="button" name="delete" id="'.$itemsm["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
		}

	else{

		}
		$itemsmRows[] = 'Done';
		$itemsmRows[] = 'Disable';
		$itemsmRows[] = 'Disable';
		
		$itemsmData[] = $itemsmRows;
	}

	
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$itemsmData
	);
	
	echo json_encode($output);
}



//".$_POST["clientcodeId"]."
	public function getItemsm(){
		global $db;	
		if($_POST["item1"]) {
			$sqlQuery = "SELECT * FROM detail_sm WHERE id = '".$_POST["item1"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addItemsm(){
	
	global $db;
	$inserQuery ="INSERT INTO ".$this->itemsmTable." (desc_pr_sm, clientcode_sm, qty_sm,products_id, stockmovements_id,finalized) VALUES ('".$_POST["sm-code-description"]."', '".$_POST["sm-code-name"]."', '".$_POST["sm-code-qty"]."', '".$_POST["sm-productid"]."','".$_POST["sm-id"]."',3)";
	
		//$insertQuery = "INSERT INTO clientcode (clientcode, clients_id, products_id) 
		//VALUES ('asd456', '1', '29')";
		//".$_POST["clientcodeClient"]."
	
//'".$_POST["sm-id"]."'
	$db->query($inserQuery);
}


public function updateItemsm(){
	global $db;
	
		
		$updateQuery = "UPDATE detail_sm SET desc_pr_sm = '".$_POST["sm-code-description"]."', clientcode_sm = '".$_POST["sm-code-name"]."', qty_sm = '".$_POST["sm-code-qty"]."', products_id = '".$_POST["sm-productid"]."' WHERE id ='".$_POST["sm-ids"]."'";
		
		$isUpdated = $db->query($updateQuery);		
		var_dump($isUpdated);
}

public function ItemsmDelete(){
	global $db;
	if($_POST["item"]) {
		$sqlDelete = "DELETE FROM detail_sm WHERE id = '".$_POST["item"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	}
  }
/*
public function processPendent(){

	$qtypen = pendent_po_item_update($_POST["po-id"]);
	$qty1 = $_POST['po-code-qty'];
	$qty2 = $_POST['po-code-qty-hidden'];

	$totalqty = $qty1 - $qty2;

	if($qtypen['finalized'] === 1){


	}
	}
*/








}
?>
