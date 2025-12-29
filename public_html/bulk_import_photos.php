<?php
// Bulk import photos by matching filenames to Customer Alias (clientcode)
error_reporting(0);
ini_set('display_errors', 0);
mb_internal_encoding('UTF-8');
ini_set('default_charset', 'UTF-8');

require_once('includes/load.php');
page_require_level(2);

function sanitizeExcelText($text) {
  if ($text === null) { return ''; }
  $text = (string)$text;
  if (!mb_detect_encoding($text, 'UTF-8', true)) {
    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
  }
  $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $text);
  $text = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $text);
  return $text;
}

function generatePhotoErrorReport($rows) {
  require_once('libs/PHPExcel/PHPExcel.php');
  PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
  $spreadsheet = new PHPExcel();
  $sheet = $spreadsheet->getActiveSheet();
  $sheet->setTitle('Photo Import Errors');
  $sheet->setCellValue('A1', 'PHOTO IMPORT ERROR REPORT');
  $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
  $headers = ['Row','Filename','Detected Alias','Client ID (if any)','Product ID (if any)','Error','How to Fix'];
  $sheet->fromArray($headers, NULL, 'A3');
  $sheet->getStyle('A3:G3')->getFont()->setBold(true);
  $r = 4;
  foreach($rows as $row){
    $sheet->fromArray([
      $row['row'] ?? '',
      sanitizeExcelText($row['filename'] ?? ''),
      sanitizeExcelText($row['alias'] ?? ''),
      $row['client_id'] ?? '',
      $row['product_id'] ?? '',
      sanitizeExcelText($row['error'] ?? ''),
      sanitizeExcelText($row['fix'] ?? ''),
    ], NULL, 'A'.$r);
    $r++;
  }
  $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');
  $filename = 'bulk_import_photos_errors.xls';
  if (!file_exists('exports')) { mkdir('exports', 0755, true); }
  $tempPath = 'exports/' . $filename;
  $writer->save($tempPath);
  while (ob_get_level()) { ob_end_clean(); }
  header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Cache-Control: max-age=0');
  header('Content-Length: ' . filesize($tempPath));
  readfile($tempPath);
  unlink($tempPath);
  exit;
}

function findProductByAlias($alias) {
  global $db;
  $sql = "SELECT products_id, clients_id FROM clientcode WHERE clientcode = ? LIMIT 1";
  $stmt = $db->prepare($sql);
  if(!$stmt){ return null; }
  $stmt->bind_param('s', $alias);
  if(!$stmt->execute()){ $stmt->close(); return null; }
  $res = $stmt->get_result();
  $row = $res ? $res->fetch_assoc() : null;
  $stmt->close();
  return $row ?: null;
}

function insertMediaRecord($fileName, $fileType) {
  global $db;
  $sql = "INSERT INTO media (file_name, file_type) VALUES (?, ?)";
  $stmt = $db->prepare($sql);
  if(!$stmt){ return false; }
  $stmt->bind_param('ss', $fileName, $fileType);
  $ok = $stmt->execute();
  $id = $ok ? $db->insert_id() : false;
  $stmt->close();
  return $id;
}

function updateProductMediaId($productId, $mediaId) {
  global $db;
  $sql = "UPDATE products SET media_id = ? WHERE id = ?";
  $stmt = $db->prepare($sql);
  if(!$stmt){ return false; }
  $stmt->bind_param('ii', $mediaId, $productId);
  $ok = $stmt->execute();
  $stmt->close();
  return $ok;
}

function findMediaByFileName($fileName){
  global $db;
  $sql = "SELECT id, file_type FROM media WHERE file_name = ? LIMIT 1";
  $stmt = $db->prepare($sql);
  if(!$stmt){ return null; }
  $stmt->bind_param('s', $fileName);
  if(!$stmt->execute()){ $stmt->close(); return null; }
  $res = $stmt->get_result();
  $row = $res ? $res->fetch_assoc() : null;
  $stmt->close();
  return $row ?: null;
}

function updateMediaFileType($mediaId, $fileType){
  global $db;
  $sql = "UPDATE media SET file_type = ? WHERE id = ?";
  $stmt = $db->prepare($sql);
  if(!$stmt){ return false; }
  $stmt->bind_param('si', $fileType, $mediaId);
  $ok = $stmt->execute();
  $stmt->close();
  return $ok;
}

$success = 0; $errors = [];

if(isset($_POST['process_photos'])) {
  if(!isset($_FILES['photos'])){
    $session->msg('d','No files uploaded.');
  } else {
    $files = $_FILES['photos'];
    $count = is_array($files['name']) ? count($files['name']) : 0;
    for($i=0;$i<$count;$i++){
      $origName = $files['name'][$i];
      if(!$origName){ continue; }
      $tmp = $files['tmp_name'][$i];
      $type = $files['type'][$i];
      $err = $files['error'][$i];
      $size = $files['size'][$i];

      // Validate and allow conversion from other formats to JPG
      $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
      $allowedRead = ['jpg','jpeg','png','gif','webp'];
      if(!in_array($ext, $allowedRead)){
        $errors[] = ['row'=>$i+1,'filename'=>$origName,'error'=>'Unsupported format','fix'=>'Use jpg/png/gif/webp'];
        continue;
      }
      if($err !== 0 || !is_uploaded_file($tmp)){
        $errors[] = ['row'=>$i+1,'filename'=>$origName,'error'=>'Upload error','fix'=>'Retry upload'];
        continue;
      }
      // Derive alias from filename without extension
      $alias = pathinfo($origName, PATHINFO_FILENAME);
      $match = findProductByAlias($alias);
      if(!$match){
        $errors[] = ['row'=>$i+1,'filename'=>$origName,'alias'=>$alias,'error'=>'Alias not found','fix'=>'Ensure filename equals exact Customer Alias'];
        continue;
      }
      $productId = (int)$match['products_id'];
      // Normalize to jpg target filename
      $targetExt = 'jpg';
      $newName = $productId . '.' . $targetExt;
      $targetMime = 'image/jpeg';
      // Convert to JPG if needed (png/gif/webp)
      $convertedTmp = null;
      if($ext !== 'jpg' && $ext !== 'jpeg'){
        $src = null;
        if($ext === 'png' && function_exists('imagecreatefrompng')){
          $src = @imagecreatefrompng($tmp);
        } elseif($ext === 'gif' && function_exists('imagecreatefromgif')){
          $src = @imagecreatefromgif($tmp);
        } elseif($ext === 'webp' && function_exists('imagecreatefromwebp')){
          $src = @imagecreatefromwebp($tmp);
        }
        // Fallback universal reader
        if(!$src && function_exists('imagecreatefromstring')){
          $data = @file_get_contents($tmp);
          if($data !== false){ $src = @imagecreatefromstring($data); }
        }
        if(!$src){
          // Último recurso: si no hay soporte GD para leer, subimos el archivo original tal como viene
          // y lo renombramos a .jpg. Muchos navegadores lo renderizan por firma aunque la extensión no coincida.
          $convertedTmp = $tmp;
          // Intentar detectar mime original
          if(!empty($type)) { $targetMime = $type; }
        } else {
          $rgb = imagecreatetruecolor(imagesx($src), imagesy($src));
          imagefill($rgb, 0, 0, imagecolorallocate($rgb, 255, 255, 255));
          imagecopy($rgb, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
          $convertedTmp = tempnam(sys_get_temp_dir(), 'jpg');
          imagejpeg($rgb, $convertedTmp, 90);
          imagedestroy($src); imagedestroy($rgb);
        }
      }
      // Move into uploads/products as productId.jpg
      $uploadDir = dirname(__FILE__) . '/uploads/products';
      if(!file_exists($uploadDir)) { @mkdir($uploadDir, 0755, true); }
      $dest = $uploadDir . '/' . $newName;
      @unlink($dest);
      $moved = false;
      if($convertedTmp && file_exists($convertedTmp)){
        $moved = @rename($convertedTmp, $dest);
      } else {
        $moved = @move_uploaded_file($files['tmp_name'][$i], $dest);
      }
      if(!$moved){
        $errors[] = ['row'=>$i+1,'filename'=>$origName,'alias'=>$alias,'product_id'=>$productId,'error'=>'Cannot move file','fix'=>'Check folder permissions uploads/products'];
        if($convertedTmp && file_exists($convertedTmp)) { @unlink($convertedTmp); }
        continue;
      }
      // Register/update media and update product
      $existing = findMediaByFileName($newName);
      if($existing){
        // Ya existe el registro media con ese nombre -> actualizamos file_type si cambió y no insertamos otro
        updateMediaFileType((int)$existing['id'], $targetMime);
        $mediaId = (int)$existing['id'];
      } else {
        $mediaId = insertMediaRecord($newName, $targetMime);
        if(!$mediaId){
          $errors[] = ['row'=>$i+1,'filename'=>$origName,'alias'=>$alias,'product_id'=>$productId,'error'=>'DB error creating media','fix'=>'Check DB logs'];
          continue;
        }
      }
      if(!updateProductMediaId($productId, $mediaId)){
        $errors[] = ['row'=>$i+1,'filename'=>$origName,'alias'=>$alias,'product_id'=>$productId,'error'=>'DB error updating product.media_id','fix'=>'Check DB logs'];
        continue;
      }
      $success++;
    }

    if(!empty($errors)){
      generatePhotoErrorReport($errors);
    } else {
      $session->msg('s', "Photos import completed. Imported: $success");
    }
  }
}

include_once('layouts/header.php');
?>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-picture"></span> Bulk Import Photos</strong>
      </div>
      <div class="panel-body">
        <form method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-3 control-label">Select image files:</label>
            <div class="col-sm-9">
              <input type="file" id="photos" name="photos[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp" style="display:none" required>
              <label for="photos" class="btn btn-default">Choose files</label>
              <span id="photos_names" class="text-muted" style="margin-left:8px;">No files chosen</span>
              <small class="text-muted" style="display:block;">We accept .jpg/.png/.gif/.webp (will be converted to .jpg). Filename must equal Customer Alias (clientcode).</small>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <button type="submit" name="process_photos" class="btn btn-primary">
                <i class="glyphicon glyphicon-upload"></i> Process Import
              </button>
            </div>
          </div>
        </form>
        <script>
        document.addEventListener('DOMContentLoaded', function(){
          var input = document.getElementById('photos');
          var span = document.getElementById('photos_names');
          if(input && span){
            input.addEventListener('change', function(){
              if(!input.files || !input.files.length){ span.textContent = 'No files chosen'; return; }
              var names = [];
              for(var i=0;i<input.files.length;i++){ names.push(input.files[i].name); }
              span.textContent = names.join(', ');
            });
          }
        });
        </script>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
