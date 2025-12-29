<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<?php
$codes = '23565';
$photo = new Media();
$photo->upload($_FILES['file_upload'],$codes);
    if($photo->process_media()){
        $session->msg('s','Imagen subida al servidor.');
      
    } else{
      $session->msg('d',join($photo->errors));
      
    }

?>