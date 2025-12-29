<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
$all_clients = find_all('clients');
$html = '';
//$tp_id = $_POST['tp_ids'];
//$tpid = obtaing_tp_rg($_POST['idproduct']);
//$idcli = obtaingclient_id_rg($_POST['clientcode']);
$sales_tp = obtaing_tps_sales($_POST['idproduct'],$_POST['uxb']);



$html.="<label>TP</label>";
$html.="<select id=\"sales-tp\" class=\"form-control\" name=\"sales-tp\" required>";	
$html.=" <option value=\"\" disabled selected>Select TP</option>";
	foreach ($sales_tp as $tp){
		
		if($tp['clients_id'] === $_POST['clientid']){

		 $html.= "<option value=\"{$tp['id']}\">" .$tp['tp']."</option>";

		}
		

		//$html .= "<option value=\"{$tp['id']}\" selected>".$tp['tp']."</option>";
		
		
       
        
        
   		}


 

    $html.="</select>";
    echo json_encode($html);
