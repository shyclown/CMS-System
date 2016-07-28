<?php
$fileData = file_get_contents("php://input");
$ng_data = json_decode($fileData);
if($ng_data->data == 'loadContent'){
$dir    = $_SERVER['DOCUMENT_ROOT'];
$files = [];
if($handle = opendir($_SERVER['DOCUMENT_ROOT'])){
  while (($file = readdir($handle)) !== false)
  {
      if ($file == '.' || $file == '..')
      {
         continue;
      }
      $filepath = $dir == '.' ? $file : $dir . '/' . $file;
      if (is_link($filepath))
      {
          continue;
      }
      if (is_file($filepath))
      {
          $f = new stdClass();
            $f->type = 'file';
            $f->name = $file;
          $files[] = $f;
      }
      else if (is_dir($filepath)){
          $f = new stdClass();
            $f->type = 'dir';
            $f->name = $file;
          $files[] = $f;
      }
  }
    closedir($handle);
  
}
$filesJSON = json_encode($files);
echo $filesJSON;
}
?>
