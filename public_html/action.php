<?php
require_once('includes/load.php');
page_require_level(2);
?>
<?php

//header('Content-Type: application/json');
// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.
//global $db;
//$connect = mysqli_connect('localhost','root','Sahara0153','oswa_inv');
$input = filter_input_array(INPUT_POST);
$clientcode = $input['clientcode'];
$clients_id = $input['clients_id'];
$idclients = $input['id'];
//mysqli_real_escape_string
// CHECK REQUEST METHOD



// PHP QUESTION TO MYSQL DB

// Connect to DB

  /*  Your code for new connection to DB*/



// Php question

  if ($input['action'] == 'edit') {
 $update_field='';
 if(isset($input['clients_id'])) {
 $update_field.= "clients_id='".$input['clients_id']."'";
 }
if($update_field && $input['id']) {
 $query = "UPDATE clientcode SET $update_field WHERE id='" .$input['id']
. "'"; 
$db->query($query);
}

}

 if ($input['action'] == 'edit') {
 $update_field='';
 if(isset($input['clientcode'])) {
 $update_field.= "clientcode='".$input['clientcode']."'";
 }
if($update_field && $input['id']) {
 $query = "UPDATE clientcode SET $update_field WHERE id='" .$input['id']
. "'"; 
$db->query($query);
}

}
/*

if ($input["action"] === 'edit') {

 $query = "UPDATE clientcode SET clientcode = '{$clientcode}' WHERE id = '{$idclients}'";

 $db->query($query);

 if($input["action"] === 'delete')
{
 $query = "DELETE FROM clientcode WHERE id = '".$db->escape($input["id"])."'";
$db->query($query);

}

echo json_encode($input);

?>

*/