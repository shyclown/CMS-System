<?php
class FileManager
{
  var $db;
  var $errors;
  var $user_id;

  function __construct()
  {
    $this->db = new Database;
    $this->errors = [];
  }

  public function is_logged_in()
  {
    if(isset($_SESSION)){
      if(isset($_SESSION['loged_in']) && $_SESSION['user_id']){
        if($_SESSION['loged_in']){
          $this->user_id = $_SESSION['user_id'];
          return true;
        }
      }
    }
    $this->errors[] = 'User is not logged in.';
    return false;
  }

  private function user_owns_folder($folder_id)
  {
      $sql = "SELECT * FROM  `el_user_folder` WHERE  `user-id` = ? AND `folder-id` = ? LIMIT 1";
      $params = array('ii',$this->user_id, $folder_id);
      if($result = $this->db->query($sql, $params))
      { return true; }
      else
      { return false; }
  }

  public function load_all_folders()
  {
    $sql = "SELECT * FROM el_user_folder INNER JOIN el_folders
            ON el_user_folder.`folder-id` = el_folders.id
            WHERE `user-id` = ?" ;
    $params = array( 'i', $this->user_id );
    return $this->db->query($sql,$params);
  }

  public function load_folders_inFolder($in_folder_id)
  {
    $sql = "SELECT * FROM el_user_folder INNER JOIN el_folders
            ON el_user_folder.`folder-id` = el_folders.id
            WHERE `user-id` = ? AND `parent-id` = ?" ;
    $params = array( 'ii', $this->user_id, $in_folder_id );
    return $this->db->query($sql,$params);
  }

  public function update_folder_name($data)
  {
    $folder_id = $data['folder_id'];
    $new_folder_name = $data['folder_name'];

    if($this->user_owns_folder($folder_id))
    {
      $sql_update_name = "UPDATE  `cms`.`el_folders` SET  `name` = ? WHERE  `el_folders`.`id` =?";
      $params_update_name = array( 'si', $new_folder_name, $folder_id);
      $this->db->query($sql_update_name,$params_update_name);
    }

  }

  public function update_folder_parent_byID($data)
  {
    $folder_id = $data['folder_id'];
    $parent_id = $data['parent_id'];
/**/
    if($this->user_owns_folder($folder_id))
    {
      if($this->user_owns_folder($parent_id))
      {
        $sql_update_parent = "UPDATE  `cms`.`el_folders` SET  `parent-id` = ? WHERE  `el_folders`.`id` =?";
        $params_update_parent = array( 'si', $parent_id, $folder_id);
        $this->db->query($sql_update_parent,$params_update_parent);
      }
    }
  }

  private function update_folders_parent_byParent($parent_id, $new_parent_id)
  {
    $sql_update_parents = "UPDATE  `cms`.`el_folders` SET  `parent-id` = ? WHERE  `el_folders`.`parent-id` =?";
    $params_update_parents = array( 'si', $new_parent_id, $parent_id);
    $this->db->query($sql_update_parents,$params_update_parents);
  }

  public function remove_folder($data)
  {

    $folder_id = $data['folder_id'];

    if($this->user_owns_folder($folder_id))
    {
      $sql_user_folder = "DELETE FROM  `el_user_folder` WHERE `user-id` = ? AND `folder-id` = ?";
      $params_user_folder = array( 'ii', $this->user_id, $folder_id );
      $this->db->query($sql_user_folder, $params_user_folder);

      $sql_folder ="DELETE FROM `cms`.`el_folders` WHERE `el_folders`.`id` = ?";
      $params_folder = array('i', $folder_id );
      $this->db->query($sql_folder, $params_folder);

          // set affected folders parent to root;
      $this->update_folders_parent_byParent( $folder_id , 0 );
          // set affected files parent to root;
      $this->update_files_parent_byParent( $folder_id , 0 );
    }
  }

  public function create_new_folder($data)
  {
    $sending_user = $data['user'];
    $folder_name = $data['folder_name'];
    $parent_id = $data['parent_id'];

    if(!$this->user_owns_folder($parent_id) && $parent_id != 0)
    {
      $parent_id = 0;
      $this->errors[] = 'Specific Error: Requesting User doesn\'t own Parent Folder';
    }

    $sql_insert_folder = "INSERT INTO  `cms`.`el_folders` ( `id` , `name` , `parent-id` )
                          VALUES ( NULL ,  ?,  ? )";
    $params = array('si', $folder_name ,$parent_id);

    if($new_folder_id = $this->db->query($sql_insert_folder, $params, 'get_id'))
    {
      $sql_user_folder = "INSERT INTO `cms`.`el_user_folder` (`user-id`, `folder-id`)
                          VALUES (?, ?)";
      $params_user_folder = array('ii', $this->user_id, $new_folder_id);
      $this->db->query($sql_user_folder, $params_user_folder);
    }
  }
}
 ?>
