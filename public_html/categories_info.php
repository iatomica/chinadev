<?php
require_once('includes/load.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Categoriesinfo {


//'".$_POST['clientcodeclientid']."'";
public function listCategories(){
	global $db;		
	$sqlQuery = "SELECT * FROM categories ";	
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= 'OR id LIKE "%'.$_POST["search"]["value"].'%" ';			
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
    $sqlQuery1 = "SELECT * FROM categories ";
	$result1 = $db->query($sqlQuery1);
	$numRows = $db->num_rows($result1);       
   
	$categoriesData = array();	

	
	while($categories = $db->fetch_assoc($result) ) {
		$info = '';
		$categoriesRows = array();
		$ids = $categories['id'];
		$categoriesRows[] ='<br>'.utf8_encode($categories['name']).'  <button type="button" name="update_cat" id="'.$ids.'" class="btn btn-info btn-xs update_cat"><span class="glyphicon glyphicon-edit" pull-right></span></button>  <button type="button" name="delete_cat" id="'.$ids.'" class="btn btn-danger btn-xs delete_cat"><span class="glyphicon glyphicon-trash"></span></button>  <button type="button" name="add_subcat" id="'.$ids.'" class="btn btn-info btn-xs add_subcat">Add</span></button></br>';
		
		$subcategories = select_subcategorie($ids); 
		while($sub = $db->fetch_assoc($subcategories) ) {
		   
			$info.='<br>'.utf8_encode($sub['name']).'  <button type="button" name="update_subcat" id="'.$sub["id"].'" class="btn btn-info btn-xs update_subcat"><span class="glyphicon glyphicon-edit" pull-right></span></button>  <button type="button" name="delete_subcat" id="'.$sub["id"].'" class="btn btn-danger btn-xs delete_subcat"><span class="glyphicon glyphicon-trash"></span></button></br>';
		 }
		
		$categoriesRows[] = $info;

		$categoriesData[] = $categoriesRows;
	}

		

	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$categoriesData
	);
	
	echo json_encode($output);
	}


public function getSubcat(){
		global $db;	
		if($_POST["subcatId"]) {
			$sqlQuery = "SELECT * FROM subcategories WHERE id = '".$_POST["subcatId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}
	public function getCat(){
		global $db;	
		if($_POST["subcatId"]) {
			$sqlQuery = "SELECT * FROM categories WHERE id = '".$_POST["subcatId"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			echo json_encode($row);
		}
	}
	public function updateCat(){
	global $db;	
	if($_POST['subcatId']) {	
		$updateQuery = "UPDATE  categories SET name = '".$_POST["subcatName"]."' WHERE id ='".$_POST["subcatId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
	
	}

	public function updateSubcat(){
	global $db;	
	if($_POST['subcatId']) {	
		$updateQuery = "UPDATE  subcategories SET name = '".$_POST["subcatName"]."' WHERE id ='".$_POST["subcatId"]."'";
		$isUpdated = $db->query($updateQuery);		
	}	
	}

	public function catDelete(){
	global $db;
	
		$sqlDelete = "DELETE FROM categories WHERE id = '".$_POST["subcatId1"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);
		echo json_encode('return');
  }

	public function subcatDelete(){
	global $db;
	
		$sqlDelete = "DELETE FROM subcategories WHERE id = '".$_POST["subcatId1"]."'";		
			//$sqlDelete = "DELETE FROM ".$this->codeclientTable."
			//WHERE id = '60'";
		$db->query($sqlDelete);

		echo json_encode('return');
	
  }



	public function addSubcat($id){
	global $db;
	$insertQuery = "INSERT INTO subcategories (name,categories_id) 
	VALUES ('".$_POST["subcatName"]."', '".$id."' )";
	
	$db->query($insertQuery);

}
public function catDeleteRes(){

global $db;
$sqlQuery = "SELECT * FROM subcategories WHERE categories_id = '".$_POST["subcatId1"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			
			$return = ($db->num_rows($result) > 0) ? true : false;
			
			if($return === false){

			
			$sqlQuery1 = "SELECT distinct products_id FROM productscatsub WHERE categories_id = '".$_POST["subcatId1"]."'";
			$result1 = $db->query($sqlQuery1);
			//$row = $db->fetch_assoc($result);
			$return1 = ($db->num_rows($result1) > 0) ? true : false;
			
			if ($return1 === true){

			$info= '';

			foreach ($result1 as $ret){
				$info .= $ret['products_id']. ',';

			}
			
			$output = array(
				"info" 	=> 	$info,
				"data"  => 	$return1
			);
			echo json_encode($output);

			}
			else{
				echo json_encode($return1);
			}
			



			}
			else{

			echo json_encode($return);
			}
			
			

}

public function subcatDeleteRes(){

global $db;
$sqlQuery = "SELECT distinct products_id FROM productscatsub WHERE subcategories_id = '".$_POST["subcatId1"]."'";
			$result = $db->query($sqlQuery);
			$row = $db->fetch_assoc($result);
			$return = ($db->num_rows($result) > 0) ? true : false;
			
			$info= '';

			foreach ($result as $ret){
				$info .= $ret['products_id']. ',';

			}
			
			$output = array(
		"info" 	=> 	$info,
		"data"  => 	$return
	);
			echo json_encode($output);
			

}

}


/*
$categoriesRows = array();


		$categoriesRows[] = $categories['cname'];
		$categoriesRows[] = utf8_encode($categories['sname']);
		
		$categoriesData[] = $categoriesRows;
		//var_dump($categoriesRows);
	


	$info.='<br>'.utf8_encode($sub['names']).' <button type="button" name="update" id="'.$sub["id"].'" class="btn btn-info btn-xs" update"><span class="glyphicon glyphicon-edit"></span></button> <button type="button" name="delete_subcat" id="'.$sub["id"].'" class="btn btn-danger btn-xs delete_subcat" pull-right><span class="glyphicon glyphicon-trash"></button></br><br></br>';
		
*/