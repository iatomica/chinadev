<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
$all_clients = find_all('clients');
$html = '';
$tp_id = $_POST['tp_ids'];
$tpid = obtaing_tp_rg($_POST['idproduct']);
$idcli = $_POST['client'];

$html.="<label>TP</label>";
$html.="<select id=\"rg-tp\" class=\"form-control\" name=\"rg-tp\" required>";	
$html.=" <option value=\"0\"></option>";
	foreach ($tpid as $tp){
		
		if ($tp['clients_id'] === $idcli){
		
		if($tp['id'] === $tp_id){

		$html .= "<option value=\"{$tp['id']}\" selected>".$tp['tp']."</option>";
		}
		else{
        $html.= "<option value=\"{$tp['id']}\">" .$tp['tp']."</option>";
         }
   		}

}
 

    $html.="</select>"; 
    echo json_encode($html); 
