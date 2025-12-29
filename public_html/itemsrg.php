<?php
require_once('includes/load.php');
$all_codevendors = find_all('vendorcode');

class Itemrg {
private $itemrgTable = 'detail_rg';

//'".$_POST['clientcodeProductid']."'";
public function listItemrg($rgid){
	global $db;		
	$sqlQuery = "SELECT * FROM detail_rg WHERE receivedgoods_id = '".$rgid."'";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'AND clientcode_rg LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= ' OR desc_pr_rg LIKE "%'.$_POST["search"]["value"].'%" ';			
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
    $sqlQuery1 = "SELECT * FROM detail_rg WHERE receivedgoods_id = '".$rgid."'";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$itemrgData = array();

//".$poid."

	while($itemrg = $db->fetch_assoc($result) ) {
		$tp =obtaing_tp_name($itemrg['purchaseorder_id']);
		$itemrgRows = array();
		//$names = joinsqlname('vendorcode','vendors','vendors_id','name',$itempo['vendors_id']);
		$itemrgRows[] = $itemrg['products_id'];
		$itemrgRows[] = $itemrg['clientcode_rg'];
		$itemrgRows[] = $itemrg['desc_pr_rg'];
		$itemrgRows[] = $itemrg['uxb_rg'];
		$itemrgRows[] = $itemrg['qty_rg'];
		$itemrgRows[] = $itemrg['price_rg'];
		$itemrgRows[] = $itemrg['ctn'];
		$itemrgRows[] = $itemrg['cbm_rg'];
		$itemrgRows[] = $itemrg['nw'];
		$itemrgRows[] = $itemrg['gw'];
		$itemrgRows[] = $itemrg['cbm_total'];
		$itemrgRows[] = $itemrg['nw_total'];
		$itemrgRows[] = $itemrg['gw_total'];
		$itemrgRows[] = $itemrg['price_total'];
		$itemrgRows[] = $tp;
		if ($itemrg['finalized'] === '1'){
			$itemrgRows[] = 'Disabled';
			$itemrgRows[] = 'Disabled';

		}
		else{
		$itemrgRows[] = '<button type="button" name="update" id="'.$itemrg["id"].'" class="btn btn-warning btn-xs update">Update</button>';
		
		$itemrgRows[] = '<button type="button" name="delete" id="'.$itemrg["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
		}
		$itemrgData[] = $itemrgRows;
	}
	$output = array(
		"draw"			=>  intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=>  $numRows,
		"data"    		=>  $itemrgData
	);
	
	echo json_encode($output);
}



//".$_POST["clientcodeId"]."
	public function getItemrg(){
		global $db;	
		if($_POST["item2"]) {
			$sqlQuery = "SELECT * FROM detail_rg WHERE id = '".$_POST["item2"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}

	public function addItemrg(){
	global $db;
	$total_cbm = $_POST["rg-code-cbm"] * $_POST["rg-code-ctn"];
	$total_nw = $_POST["rg-code-nw"] * $_POST["rg-code-ctn"];
	$total_gw = $_POST["rg-code-gw"] * $_POST["rg-code-ctn"];
	$total_price = $_POST["rg-code-qty"] * $_POST["rg-code-fob"];
	$insertQuery = "INSERT INTO ".$this->itemrgTable." (clientcode_rg, desc_pr_rg,uxb_rg,price_rg,cbm_rg,qty_rg,gw,nw, ctn,cbm_total,gw_total,nw_total,price_total,purchaseorder_id,products_id,finalized,receivedgoods_id,invoiced) 
	VALUES ('".$_POST["rg-code-name"]."', '".$_POST["rg-code-description"]."', '".$_POST["rg-code-uxb"]."', '".$_POST["rg-code-fob"]."', '".$_POST["rg-code-cbm"]."', '".$_POST["rg-code-qty"]."', '".$_POST["rg-code-gw"]."', '".$_POST["rg-code-nw"]."', '".$_POST["rg-code-ctn"]."', '".$total_cbm."', '".$total_gw."', '".$total_nw."','".$total_price."', '".$_POST["rg-tp"]."', '".$_POST["rg-productid"]."','0','".$_POST["rgid-id"]."','0')";
	
		//$insertQuery = "INSERT INTO clientcode (clientcode, clients_id, products_id) 
		//VALUES ('asd456', '1', '29')";
		//".$_POST["clientcodeClient"]."
	$db->query($insertQuery);
}

public function updateItemrg(){
	global $db;	
	$total_cbm = $_POST["rg-code-cbm"] * $_POST["rg-code-ctn"];
	$total_nw = $_POST["rg-code-nw"] * $_POST["rg-code-ctn"];
	$total_gw = $_POST["rg-code-gw"] * $_POST["rg-code-ctn"];
	$total_price = $_POST["rg-code-qty"] * $_POST["rg-code-fob"];
		
		$updateQuery = "UPDATE detail_rg SET uxb_rg = '".$_POST["rg-code-uxb"]."', price_rg = '".$_POST["rg-code-fob"]."', cbm_rg = '".$_POST["rg-code-cbm"]."', qty_rg = '".$_POST["rg-code-qty"]."', desc_pr_rg = '".$_POST["rg-code-description"]."', gw = '".$_POST["rg-code-gw"]."', nw = '".$_POST["rg-code-nw"]."', ctn = '".$_POST["rg-code-ctn"]."', cbm_total = '".$total_cbm."', gw_total = '".$total_gw."', nw_total = '".$total_nw."', price_total = '".$total_price."' , products_id = '".$_POST["rg-productid"]."', purchaseorder_id = '".$_POST["rg-tp"]."' WHERE id ='".$_POST["rg-id"]."'";
		$isUpdated = $db->query($updateQuery);
	

	
}

public function ItemrgDelete(){
	global $db;
	if($_POST["item"]) {
		$sqlDelete = "DELETE FROM detail_rg WHERE id = '".$_POST["item"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
	}
  }



public function processpo($updateItem){


$totalpo1 = pendent_items_po($_POST["rg-tp"],$_POST["rg-productid"]);
$totalrg1 = $_POST["rg-code-qty"];
if ($totalrg1 >= $totalpo1['qty_po'] || $updateItem >= $totalpo1['qty_po'] ){

       finalized_items_po($_POST["rg-tp"],$_POST["rg-productid"]);

		}
		else{
			$pendent = $totalpo1['pendent'] - $totalrg1;
			pendent_po_update($pendent,$totalpo1['id']);


		}

	}


	public function processpoUpdate($updateItem){
		
		
		$totalpo1 = pendent_items_po($_POST["rg-tp"],$_POST["rg-productid"]);
		$cantrg = $_POST['rg-qty-hidden'];
		$totalrg1 = $_POST["rg-code-qty"];
		
		$totrg = $totalrg1 - $cantrg;
	
		var_dump($totalpo1);
		var_dump($cantrg);
		var_dump($totalrg1);
		var_dump($totrg);
		if ($totrg >= $totalpo1['qty_po']){
			
       		finalized_items_po($_POST["rg-tp"],$_POST["rg-productid"]);

		}
		else{
			$pendent = $totalpo1['pendent'] - $totrg;
		
			if ($pendent <= '0'){
				 		
				finalized_items_po($_POST["rg-tp"],$_POST["rg-productid"]);
			}
			else{
			pendent_po_update($pendent,$totalpo1['id']);
				}

			}





	}
}

?>

