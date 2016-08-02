<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/ng/class_filemanager.php');

new Session;

$fm = new FileManager;


$fileData = file_get_contents("php://input");
$ng_data = json_decode($fileData);
$result = '';

if($fm->is_logged_in())
{
  $action = $ng_data->action;
  $data = $ng_data;

  switch($data->action)
  {
      case 'loadAllFolders':
        $result = json_encode($fm->load_all_folders());
        break;
      case 'loadFoldersInFolder':
        $result = json_encode($fm->load_folders_inFolder($data));
        break;
      case 'createNewFolder':
        $result = $fm->create_new_folder($data) ? true : false;
        break;
      case 'removeFolder':
        $result = $fm->remove_folder($data->folder_id);
        break;
      case 'updateParent':
        $result = $fm->update_folder_parent_byID($data);
        break;
  }
  if($result == ''){ $result = $fm->errors;}
  else{
    echo $result;
  }
}
else{
  echo $fm->errors[0];
}

?>
