<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php 
$saleid = $_POST['saleid'];
$html = '';


$html.="<div class=\"modal-dialog\">
    <form method=\"post\" id=\"containerForm\">
      <div class=\"modal-content\" id=\"contModal\">
        <div class=\"modal-header\">
          <button type=\"button\" class=\"close\" data-dismiss=\"modal\">×</button>
          <h4 class=\"modal-title\"><i class=\"fa fa-plus\"></i>Add Container</h4>     
        </div>
        <div class=\"modal-body\">
           <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">Container N°</label>
            <input type=\"text\" class=\"form-control\" id=\"ContainerName\" name=\"ContainerName\" placeholder=\"Insert container\">      
          </div>
        <div class=\"modal-footer\">         
        <div><input type=\"hidden\" name=\"ContainerSalesid\" id=\"ContainerSalesid\" value=\"".$saleid."\"/></div> 
          <input type=\"hidden\" name=\"action\" id=\"action\" value=\"\"/>
          <input type=\"submit\" name=\"save\" id=\"save\" class=\"btn btn-info\" value=\"Save\" />
          <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
        </div>
      </div>
   </form>
</div>";

echo json_encode($html);

?>
