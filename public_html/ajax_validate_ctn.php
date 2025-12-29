<?php

$post_ctn = $_POST['qty'] / $_POST['uxb'];

if (is_int($post_ctn)) {
    echo json_encode($post_ctn);
  }


?>
