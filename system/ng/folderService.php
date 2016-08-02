<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/ng/class_filemanager.php');
new Session;
$fileData = file_get_contents("php://input");
$ng_data = json_decode($fileData);
var_dump($_SESSION);
$fm = new FileManager;
$result;

if($fm->is_logged_in())
{
  $action = $ng_data->action;
  $data = $ng_data;

  switch($data->action)
  {
      case 'loadAllFolders':
        $result = json_encode($fm->load_all_folders());
        var_dump($result);
        break;
      case 'loadFoldersInFolder':
        $result = json_encode($fm->load_folders_inFolder($data));
        break;
      case 'removeFolder':
        $result = $fm->remove_folder($data->folder_id);
        break;
      case 'updateParent':
        $result = $fm->update_folder_parent_byID($data);
        break;
  }
  if($result == ''){ $result = $fm->errors;}
}
else{
  echo $fm->errors[0];
}
/*
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
            $f->type = 'file-o';
            $f->name = $file;
          $files[] = $f;
      }
      else if (is_dir($filepath))
      {
          $f = new stdClass();
            $f->type = 'folder';
            $f->name = $file;
          $files[] = $f;
      }
  }
    closedir($handle);

}*/
?>
