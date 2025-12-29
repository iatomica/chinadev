<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<?php
//insert_clientscodes($client,$clientcode);

//$client = $_POST['client'];
//$clientcode = $_POST['codeclient'];

$all_codeclients = find_all('clientcode');
//insert_clientscodes($client,$clientcode);

$html = '';
//$html .= "<table class=\"table table-bordered\" name=\"code-client\" id=\"code-client\">"
$html .=        "<thead>";
$html .=               "<tr>";
$html .=                 "<th class=\"text-center\" style=\"width: 50px;\">Id</th>";
$html .=                 "<th class=\"text-center\" style=\"width: 50px;\">Client</th>";
$html .=                 "<th class=\"text-center\" style=\"width: 50px;\">Client Code</th>";
//$html .=                 "<th class=\"text-center\" style=\"width: 50px;\"> Accion </th>";
$html .=               "</tr>";
$html .=             "</thead>";
$html .=             "<tbody>";
foreach ($all_codeclients as $codeclient){
$html .=               "<tr>";
$html .=       "<td class=\"text-center\">".$codeclient['id']."</td>";
$html .=       "<td class=\"text-center\">".$codeclient['clients_id']."</td>";
$html .=       "<td class=\"text-center\">".$codeclient['clientcode']."</td>";
//$html .=       "<td class=\"text-center\"> peperupo</td>";
$html .=               "</tr>";
}
$html .=             "</tbody>";
//$html .= "</table>";


//.$codeclient['clients_id'].
//.$codeclient['clientcode'].



echo json_encode($html);
?>
