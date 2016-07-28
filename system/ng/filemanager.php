<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_session.php');
/**
 *
 */
 new Session;

class FileManager
{
  var $db;
  var $errors;
  var $user_id;
  var $file_id;
  var $folder_id;

  var $file_name;
  var $folder_name;

  function __construct()
  {
    $this->db = new Database;
    $this->errors = [];
  }

  private function is_logged_in()
  {
    if(isset($_SESSION)){
      if(isset($_SESSION['loged_in']) && $_SESSION['user_id']){
        if($_SESSION['loged_in']){
          $this->user_id = $_SESSION['user_id'];
          return true;
        }
      }
    }
    $this->errors[] = 'Error: User is not Logged In /b';
    return false;
  }

  private function user_owns_folder($folder_id)
  {
      $sql = "SELECT * FROM  `el_user_folder` WHERE  `user-id` = ? AND `folder-id` = ? LIMIT 0 , 30";
      $params = array('ii',$this->user_id, $folder_id);
      if($result = $this->db->query($sql, $params))
      { return true; }
      else
      { return false; }
  }

  private function remove_folder($data)
  {
    if($this->is_logged_in()){
      $user_id = $data['user_id'];
      $folder_id = $data['folder_id'];

      if($this->user_owns_folder($folder_id)){
        if($user_id = $this->user_id){

          $sql_user_folder = "DELETE FROM  `el_user_folder` WHERE `user-id` = ? AND `folder-id` = ?";
          $params_user_folder = array( 'ii', $user_id, $folder_id );
          $this->db->query($sql_user_folder, $params_user_folder);

          $sql_folder ="DELETE FROM `cms`.`el_folders` WHERE `el_folders`.`id` = ?";
          $params_folder = array('i', $folder_id );

          // update all files and folder where parent
          $sql_select_owned = "SELECT * FROM  `el_user_folder` WHERE  `user-id` = ? AND `folder-id` = ? LIMIT 0 , 30";
          $sql_update_files = "UPDATE  `el_folders` SET  `id` =8, `name` =8, `parent-id` = 0
                                WHERE parent-id = ? "
        }
        else{
          $this->errors[] = 'Specific Error: Requesting User is not Logged User!';
        }
      }
    }
  }

  public function create_new_folder($data)
  {
    if($this->is_logged_in())
    {

      $sending_user = $data['user'];
      $folder_name = $data['folder_name'];
      $parent_id = $data['parent_id'];
      if(!$this->user_owns_folder($parent_id) && $parent_id != 0)
      {
        $parent_id = 0;
        $this->errors[] = 'Specific Error: Requesting User doesn\'t own Parent Folder';
      }
      if($sending_user == $this->user_id)
      {
        $sql_insert_folder = "INSERT INTO  `cms`.`el_folders` ( `id` , `name` , `parent-id` )
                              VALUES ( NULL ,  '?',  '?' )";
        $params = array('si',$folder_name,$parent_id);
        if($new_folder_id = $this->db->query($sql_insert_folder, $params, 'get_id')){
            $sql_user_folder = "INSERT INTO `cms`.`el_user_folder` (`user-id`, `folder-id`)
                                VALUES ('?', '?')";
            $params_user_folder = array('ii', $this->user_id, $new_folder_id);
            $this->db->query($sql_user_folder, $params_user_folder);
        }
      }
      else{
        $this->errors[] = 'Specific Error: Requesting User is not Logged User!';
      }
    }
  }
}
$fm = new FileManager;


 ?>
