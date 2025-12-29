<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Itempo {
private $itempoTable = 'detail_po';

//'".$_POST['clientcodeProductid']."'";
public function listItempo($poid){
	global $db;		
	$sqlQuery = "SELECT * FROM detail_po WHERE purchaseorder_id = '".$poid."'";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND clientcode_po LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= ' OR desc_pr_po LIKE "%'.$_POST["search"]["value"].'%" ';			
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
    $sqlQuery1 = "SELECT * FROM detail_po WHERE purchaseorder_id = '".$poid."'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$itempoData = array();
	//$status = obtaing_po_status($poid);
//".$poid."


//'<button name="status-item-po" id="'.$itempo["id"].'" class="btn-success btn-xs" title="Change status" data-toggle="tooltip">
    //                    		<span class="glyphicon glyphicon-arrow-right"></span>'
	while($itempo = $db->fetch_assoc($result) ) {
		
		$itempoRows = array();
		//$names = joinsqlname('vendorcode','vendors','vendors_id','name',$itempo['vendors_id']);
		$itempoRows[] = $itempo['products_id'];
		$itempoRows[] = $itempo['clientcode_po'];
		$itempoRows[] = $itempo['desc_pr_po'];
		$itempoRows[] = $itempo['uxb_po'];
		$itempoRows[] = $itempo['qty_po'];
		$itempoRows[] = $itempo['price_po'];
		$itempoRows[] = $itempo['ctn'];
		$itempoRows[] = $itempo['cbm_po'];
		$itempoRows[] = $itempo['nw'];
		$itempoRows[] = $itempo['gw'];
		$itempoRows[] = $itempo['volume'];
		$itempoRows[] = $itempo['cbm_total'];
		$itempoRows[] = $itempo['nw_total'];
		$itempoRows[] = $itempo['gw_total'];
		$itempoRows[] = $itempo['price_total'];
		$itempoRows[] = $itempo['pendent'];
		$itempoRows[] = $itempo['eta'];
		if($itempo['finalized'] === '1') {
		$itempoRows[] = 'Disable';
		$itempoRows[] = 'Disable';
		$itempoRows[] = 'Disable';
		}
		if($itempo['pendent'] < $itempo['qty_po'] && $itempo['finalized'] === '0'){
			$itempoRows[] = 'Disable';
			$itempoRows[] = 'Disable';
			$itempoRows[] = '<button type="button" name="status-item-po" id="'.$itempo["id"].'" class="btn btn-success btn-xs status-item-po">Change Status</button>';
		}
		if($itempo['finalized'] === '0'){
		$itempoRows[] = '<button type="button" name="update" id="'.$itempo["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		$itempoRows[] = '<button type="button" name="delete" id="'.$itempo["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
		$itempoRows[] = 'Disable';			
		}
		
		$itempoData[] = $itempoRows;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$itempoData
	);
	
	echo json_encode($output);
}



//".$_POST["clientcodeId"]."
	public function getItempo(){
		global $db;	
		if($_POST["item1"]) {
			$sqlQuery = "SELECT * FROM detail_po WHERE id = '".$_POST["item1"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addItempo(){
	global $db;
	$total_cbm = $_POST["po-code-cbm"] * $_POST["po-code-ctn"];
	$total_nw = $_POST["po-code-nw"] * $_POST["po-code-ctn"];
	$total_gw = $_POST["po-code-gw"] * $_POST["po-code-ctn"];
	$total_price = $_POST["po-code-qty"] * $_POST["po-code-fob"];
	$insertQuery = "INSERT INTO ".$this->itempoTable." (clientcode_po, desc_pr_po,uxb_po,price_po,cbm_po,qty_po,gw,nw, ctn,cbm_total,gw_total,nw_total,price_total,eta,purchaseorder_id,products_id,finalized,pendent,volume) 
	VALUES ('".$_POST["po-code-name"]."', '".$_POST["po-code-description"]."', '".$_POST["po-code-uxb"]."', '".$_POST["po-code-fob"]."', '".$_POST["po-code-cbm"]."', '".$_POST["po-code-qty"]."', '".$_POST["po-code-gw"]."', '".$_POST["po-code-nw"]."', '".$_POST["po-code-ctn"]."', '".$total_cbm."', '".$total_gw."', '".$total_nw."','".$total_price."', '".$_POST["po-eta"]."', '".$_POST["poid-id"]."', '".$_POST["po-productid"]."','0','".$_POST["po-code-qty"]."','".$_POST["po-code-volume"]."')";
	
		
	$db->query($insertQuery);
		$insertQuery1 = "UPDATE products SET price = '".$_POST["po-code-fob"]."', uxb = '".$_POST["po-code-uxb"]."' WHERE id = '".$_POST["po-productid"]."'";
		$db->query($insertQuery1);

}

public function updateItempo(){
	global $db;	
	$total_cbm = $_POST["po-code-cbm"] * $_POST["po-code-ctn"];
	$total_nw = $_POST["po-code-nw"] * $_POST["po-code-ctn"];
	$total_gw = $_POST["po-code-gw"] * $_POST["po-code-ctn"];
	$total_price = $_POST["po-code-qty"] * $_POST["po-code-fob"];
		
		$updateQuery = "UPDATE detail_po SET uxb_po = '".$_POST["po-code-uxb"]."', price_po = '".$_POST["po-code-fob"]."', cbm_po = '".$_POST["po-code-cbm"]."', qty_po = '".$_POST["po-code-qty"]."', desc_pr_po = '".$_POST["po-code-description"]."', gw = '".$_POST["po-code-gw"]."', nw = '".$_POST["po-code-nw"]."', ctn = '".$_POST["po-code-ctn"]."', cbm_total = '".$total_cbm."', gw_total = '".$total_gw."', nw_total = '".$total_nw."', price_total = '".$total_price."' , products_id = '".$_POST["po-productid"]."', eta = '".$_POST["po-eta"]."',pendent = '".$_POST["po-code-qty"]."', volume = '".$_POST["po-code-volume"]."' WHERE id ='".$_POST["po-id"]."'";
		$isUpdated = $db->query($updateQuery);		

		$insertQuery2 = "UPDATE products SET price = '".$_POST["po-code-fob"]."', uxb = '".$_POST["po-code-uxb"]."' WHERE id = '".$_POST["po-productid"]."'";
		$db->query($insertQuery2);
	
}

public function ItempoDelete(){
	global $db;
	if($_POST["item"]) {
		$sqlDelete = "DELETE FROM detail_po WHERE id = '".$_POST["item"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	}
  }
  public function changeStatusItempo(){
	global $db;
	
		$sql = "UPDATE detail_po SET finalized = 1 WHERE id = '".$_POST["item_change"]."'"; 
		$db->query($sql);
		
		$po = select_po_status_change($_POST["item_change"]);

		$status_po = select_change_status_item_po($po);
		if($status_po === '0'){
			update_status_po($po);

			
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
