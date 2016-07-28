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

  public function update_folder_name($data) = {
    $user_id = $data['user_id'];
    $folder_id = $data['folder_id'];
    $new_folder_name = $data['folder_name'];

    if($this->is_logged_in()){
      if($this->user_owns_folder($folder_id)){
        if($user_id = $this->user_id){
          $sql_update_name = "UPDATE  `cms`.`el_folders` SET  `name` = ? WHERE  `el_folders`.`id` =?";
          $params_update_name = array( 'si', $new_folder_name, $folder_id);
          $this->db->query($sql_update_name,$params_update_name);
        }
      }
    }
  }
  public function update_folder_parent_byID($data) = {
    $user_id = $data['user_id'];
    $folder_id = $data['folder_id'];
    $parent_id = $data['parent_id'];

    if($this->is_logged_in()){
      if($this->user_owns_folder($folder_id)){
        if($this->user_owns_folder($parent_id)){
          if($user_id = $this->user_id){
            $sql_update_parent = "UPDATE  `cms`.`el_folders` SET  `parent-id` = ? WHERE  `el_folders`.`id` =?";
            $params_update_parent = array( 'si', $parent_id, $folder_id);
            $this->db->query($sql_update_parent,$params_update_parent);
          }
        }
      }
    }
  }
  public function update_folders_parent_byParent($data) = {
    $user_id = $data['user_id'];
    $folder_id = $data['folder_id'];
    $parent_id = $data['parent_id'];
    $new_parent_id = $data['new_parent_id'];

    if($this->is_logged_in()){
      if($this->user_owns_folder($folder_id)){
        if($this->user_owns_folder($parent_id)){
          if($user_id = $this->user_id){
            $sql_update_parents = "UPDATE  `cms`.`el_folders` SET  `parent-id` = ? WHERE  `el_folders`.`parent-id` =?";
            $params_update_parents = array( 'si', $new_parent_id, $parent_id);
            $this->db->query($sql_update_parents,$params_update_parents);
          }
        }
      }
    }
  }
  public function remove_folder($data)
  {
    $user_id = $data['user_id'];
    $folder_id = $data['folder_id'];

    if($this->is_logged_in()){
      if($this->user_owns_folder($folder_id)){
        if($user_id = $this->user_id){

          $sql_user_folder = "DELETE FROM  `el_user_folder` WHERE `user-id` = ? AND `folder-id` = ?";
          $params_user_folder = array( 'ii', $user_id, $folder_id );
          $this->db->query($sql_user_folder, $params_user_folder);

          $sql_folder ="DELETE FROM `cms`.`el_folders` WHERE `el_folders`.`id` = ?";
          $params_folder = array('i', $folder_id );

          // update all files and folder where parent
          $sql_update_files = "UPDATE  `el_folders` SET `parent-id` = 0  WHERE parent-id = ? ";
          $params_update_files = array( 'i', $folder_id );
          $this->db->query($sql_update_files, $params_update_files);
        }
        else{
          $this->errors[] = 'Specific Error: Requesting User is not Logged User!';
        }
      }
    }
  }

  public function create_new_folder($data)
  {
    $sending_user = $data['user'];
    $folder_name = $data['folder_name'];
    $parent_id = $data['parent_id'];

    if($this->is_logged_in())
    {

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
