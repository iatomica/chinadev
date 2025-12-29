<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
$saleid = $_POST['saleid'];
$html = '';


$html.="<div class=\"modal-dialog\">
    <form method=\"post\" id=\"salesForm\">
      <div class=\"modal-content\">
        <div class=\"modal-header\">
          <button type=\"button\" class=\"close\" data-dismiss=\"modal\">×</button>
          <h4 class=\"modal-title\"><i class=\"fa fa-plus\"></i>Add item</h4>
          
        </div>
        <div class=\"modal-body\">
          <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">Customer Alias</label>
            <input type=\"text\" class=\"form-control\" id=\"sales-code-name\" name=\"sales-code-name\" placeholder=\"Customer Alias\" autocomplete=\"off\" required>
            <div id=\"sales-clientcodename\"></div>      
          </div>
          <div><input type=\"hidden\" name=\"salesid-id\" id=\"salesid-id\" value=\"".$saleid."\"/></div>
          <div class=\"form-group\">
            <label for=\"lastname\" class=\"control-label\">Id Product</label>              
            <input type=\"text\" readonly=\"readonly\" class=\"form-control\"  id=\"sales-productid\" name=\"sales-productid\" placeholder=\"Product id\" value=\"\" >             
          </div>
          <div class=\"form-group\" id=\"sales-tps\" name=\"sales-tps\" class=\"col-md-5\"></div>
           <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">Descripcion</label>
            <input type=\"text\" class=\"form-control\" id=\"sales-code-description\" name=\"sales-code-description\" placeholder=\"Description\" readonly=\"readonly\">      
          </div>
          <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">QTY</label>
            <input type=\"number\" class=\"form-control\" id=\"sales-code-qty\" name=\"sales-code-qty\" placeholder=\"QTY\" required autocomplete=\"off\">      
          </div>
          <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">UxB</label>
            <input type=\"number\" class=\"form-control\" id=\"sales-code-uxb\" name=\"sales-code-uxb\" placeholder=\"UxB\" required autocomplete=\"off\">      
          </div>
          <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">Price</label>
            <input type=\"text\" class=\"form-control\" id=\"sales-code-fob\" name=\"sales-code-fob\" placeholder=\"F.O.B\" required autocomplete=\"off\" readonly=\"readonly\">      
          </div>
          <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">CTN</label>
            <input type=\"number\" class=\"form-control\" id=\"sales-code-ctn\" name=\"sales-code-ctn\" placeholder=\"CTN\" readonly=\"readonly\" required autocomplete=\"off\">
          </div>
          <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">CBM</label>
            <input type=\"number\" step=\"any\" class=\"form-control\" id=\"sales-code-cbm\" name=\"sales-code-cbm\" placeholder=\"CBM\" required autocomplete=\"off\">      
          </div>
           <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">GW</label>
            <input type=\"number\" step=\"any\" class=\"form-control\" id=\"sales-code-gw\" name=\"sales-code-gw\" placeholder=\"Gross weight\" required autocomplete=\"off\">      
          </div>
          <div class=\"form-group\">
            <label for=\"name\" class=\"control-label\">NW</label>
            <input type=\"number\" step=\"any\" class=\"form-control\" id=\"sales-code-nw\" name=\"sales-code-nw\" placeholder=\"Net weight\" required autocomplete=\"off\">      
          </div>
        <div class=\"modal-footer\">
          <input type=\"hidden\" name=\"sales-id\" id=\"sales-id\"/>
          
          <input type=\"hidden\" name=\"action\" id=\"action\" value=\"\" />
          <input type=\"hidden\" name=\"sales-qty-hidden\" id=\"sales-qty-hidden\" value=\"\" />
          <input type=\"hidden\" name=\"action-po\" id=\"action-po\" value=\"\" />
          <input type=\"submit\" name=\"save\" id=\"save\" class=\"btn btn-info\" value=\"Save\" />
          <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
        </div>
      </div>
   </form>
</div>";

    echo json_encode($html);

?>





