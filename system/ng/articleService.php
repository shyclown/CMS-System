<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_articles.php');

new Session;

$articles = new Articles;


$fileData = file_get_contents("php://input");
$ng_data = json_decode($fileData);
$result = '';

if($articles->is_logged_in())
{

  var_dump($ng_data);
  var_dump($_POST);
  /*
  $action = $ng_data->action;
  $data = $ng_data;

  switch($data->action)
  {
      case 'loadAllFolders':
        $result = json_encode($articles->load_all_folders());
        break;
      case 'loadFoldersInFolder':
        $result = json_encode($articles->load_folders_inFolder($data));
        break;
      case 'createNewFolder':
        $result = $articles->create_new_folder($data) ? true : false;
        break;
      case 'removeFolder':
        $result = $articles->remove_folder($data->folder_id);
        break;
      case 'updateParent':
        $result = $articles->update_folder_parent_byID($data);
        break;
  }
  if($result == ''){ $result = $articles->errors;}
  else{
    echo $result;
  }*/
}
else{
  echo $articles->errors[0];
}

?>
