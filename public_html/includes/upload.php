<?php

class  Media {

  public $imageInfo;
  public $fileName;
  public $fileType;
  public $fileTempPath;
  //Set destination for upload
  public $userPath = SITE_ROOT.DS.'..'.DS.'uploads/users';
  public $productPath = SITE_ROOT.DS.'..'.DS.'uploads/products';
  public $filecode;
  
  public $errors = array();
  public $upload_errors = array(
    0 => 'There is no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'Ningun archivo fue subido',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.'
  );
  public $upload_extensions = array(
   'gif',
   'jpg',
   'jpeg',
   'png',
  );
  public function file_ext($filename){
     $ext = strtolower(substr( $filename, strrpos( $filename, '.' ) + 1 ) );
     if(in_array($ext, $this->upload_extensions)){
       return true;
     }
   }


  public function upload($file,$codeproduct)
  {
    if(!$file || empty($file) || !is_array($file)):
      $this->errors[] = "Ningún archivo subido.";
      return false;
    elseif($file['error'] != 0):
      $this->errors[] = $this->upload_errors[$file['error']];
      return false;
    elseif(!$this->file_ext($file['name'])):
      $this->errors[] = 'Formato de archivo incorrecto ';
      return false;
    else:
      $ext = strtolower(substr( $file['name'], strrpos( $file['name'], '.' ) + 1 ) );
      $this->imageInfo = getimagesize($file['tmp_name']);
      $this->fileName  = basename($file['name']);
      $this->fileType  = $this->imageInfo['mime'];
      $this->fileTempPath = $file['tmp_name'];
      $this->filecode = $codeproduct.".".$ext;
     
     return true;
    endif;

  }

  public function upload_users($file)
  {
    if(!$file || empty($file) || !is_array($file)):
      $this->errors[] = "Ningún archivo subido.";
      return false;
    elseif($file['error'] != 0):
      $this->errors[] = $this->upload_errors[$file['error']];
      return false;
    elseif(!$this->file_ext($file['name'])):
      $this->errors[] = 'Formato de archivo incorrecto ';
      return false;
    else:
      $ext = strtolower(substr( $file['name'], strrpos( $file['name'], '.' ) + 1 ) );
      $this->imageInfo = getimagesize($file['tmp_name']);
      $this->fileName  = basename($file['name']);
      $this->fileType  = $this->imageInfo['mime'];
      $this->fileTempPath = $file['tmp_name'];
      $this->filecode = $codeproduct.".".$ext;
      $this->idimage = $id_image_pr;

     return true;
    endif;

  }

 public function process(){

    if(!empty($this->errors)):
      return false;
    elseif(empty($this->fileName) || empty($this->fileTempPath)):
      $this->errors[] = "La ubicación del archivo no esta disponible.";
      return false;
    elseif(!is_writable($this->productPath)):
      $this->errors[] = $this->productPath." Debe tener permisos de escritura!!!.";
      return false;
    elseif(file_exists($this->productPath."/".$this->fileName)):
      $this->errors[] = "El archivo {$this->fileName} realmente existe.";
      return false;
    else:
     return true;
    endif;
 }
 /*--------------------------------------------------------------*/
 /* Function for Process media file
 /*--------------------------------------------------------------*/
  public function process_media($media_id){
    if(!empty($this->errors)){
        return false;
      }
    if(empty($this->fileName) || empty($this->fileTempPath)){
        $this->errors[] = "La ubicación del archivo no se encuenta disponible.";
        return false;
      }

    if(!is_writable($this->productPath)){
        $this->errors[] = $this->productPath." Debe tener permisos de escritura!!!.";
        return false;
      }

    if(file_exists($this->productPath."/".$this->fileName)){
      $this->errors[] = "El archivo {$this->fileName} Realmente existe.";
      return false;
    }
      if($media_id === '0'){
      if(move_uploaded_file($this->fileTempPath,$this->productPath.'/'.$this->filecode))
    {

      if($this->insert_media()){
        unset($this->fileTempPath);
        return true;
      }

    } else {

      $this->errors[] = "Error en la carga del archivo, posiblemente debido a permisos incorrectos en la carpeta de carga.";
      return false;
    }

      }

      else{
        unlink($this->productPath.'/'.$this->filecode);
        if(move_uploaded_file($this->fileTempPath,$this->productPath.'/'.$this->filecode))
    {

      if($this->insert_media()){
        unset($this->fileTempPath);
        return true;
      }

    } else {

      $this->errors[] = "Error en la carga del archivo, posiblemente debido a permisos incorrectos en la carpeta de carga.";
      return false;
    }


      }
   

  }
  public function return_filename($files,$codeproducts){
    $ext = strtolower(substr( $files['name'], strrpos( $files['name'], '.' ) + 1 ) );
    $filecodes = $codeproducts.".".$ext;
    return $filecodes;
  }
  /*--------------------------------------------------------------*/
  /* Function for Process user image
  /*--------------------------------------------------------------*/
 public function process_user($id){

    if(!empty($this->errors)){
        return false;
      }
    if(empty($this->fileName) || empty($this->fileTempPath)){
        $this->errors[] = "La ubicación del archivo no estaba disponible.";
        return false;
      }
    if(!is_writable($this->userPath)){
        $this->errors[] = $this->userPath." Debe tener permisos de escritura";
        return false;
      }
    if(!$id){
      $this->errors[] = " ID de usuario ausente.";
      return false;
    }
    $ext = explode(".",$this->fileName);
    $new_name = randString(8).$id.'.' . end($ext);
    $this->fileName = $new_name;
    if($this->user_image_destroy($id))
    {
    if(move_uploaded_file($this->fileTempPath,$this->userPath.'/'.$this->fileName))
       {

         if($this->update_userImg($id)){
           unset($this->fileTempPath);
           return true;
         }

       } else {
         $this->errors[] = "Error en la carga del archivo, posiblemente debido a permisos incorrectos en la carpeta de carga.";
         return false;
       }
    }
 }
 /*--------------------------------------------------------------*/
 /* Function for Update user image
 /*--------------------------------------------------------------*/
  private function update_userImg($id){
     global $db;
      $sql = "UPDATE users SET";
      $sql .=" image='{$db->escape($this->fileName)}'";
      $sql .=" WHERE id='{$db->escape($id)}'";
      $result = $db->query($sql);
      return ($result && $db->affected_rows() === 1 ? true : false);

   }
 /*--------------------------------------------------------------*/
 /* Function for Delete old image
 /*--------------------------------------------------------------*/
  public function user_image_destroy($id){
     $image = find_by_id('users',$id);
     if($image['image'] === 'no_image.jpg')
     {
       return true;
     } else {
       unlink($this->userPath.'/'.$image['image']);
       return true;
     }

   }
/*--------------------------------------------------------------*/
/* Function for insert media image
/*--------------------------------------------------------------*/
  private function insert_media(){
          global $db;
          $imgselect = select_image_($this->filecode);
         
          if($imgselect['file_name'] === $this->filecode){
            $sql  = "UPDATE media SET file_name = '".$this->filecode."' WHERE id = '".$imgselect['id']."'";
            $db->query($sql);

            return ($db->query($sql) ? true : false);
          }
          else{

         global $db;
         $sql  = "INSERT INTO media (file_name,file_type)";
         $sql .=" VALUES ";
         $sql .="(
                  '{$db->escape($this->filecode)}',
                  '{$db->escape($this->fileType)}'
                  )";
       return ($db->query($sql) ? true : false);
     }

  }
/*--------------------------------------------------------------*/
/* Function for Delete media by id
/*--------------------------------------------------------------*/
   public function media_destroy($id,$file_name){
     $this->fileName = $file_name;
     if(empty($this->fileName)){
         $this->errors[] = "Falta el archivo de foto.";
         return false;
       }
     if(!$id){
       $this->errors[] = "ID de foto ausente.";
       return false;
     }
     if(delete_by_id('media',$id)){
         unlink($this->productPath.'/'.$this->fileName);
         return true;
     } else {
       $this->error[] = "Se ha producido un error en la eliminación de fotografías.";
       return false;
     }

   }



}


?>
