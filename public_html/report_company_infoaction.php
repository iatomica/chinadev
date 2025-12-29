<?php
include('report_company_info.php');
$companyinfo = new itemCompany();
if(!empty($_POST['action']) && $_POST['action'] == 'listCompany') {
	$companyinfo->listCompany();
}

?>
