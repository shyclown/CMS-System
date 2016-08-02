<?php
class FileManager
{
  var $db;
  var $errors;
  var $user_id;

  function __construct()
  {
    $this->db = new Database;
    $this->create_elFolders_table();
    $this->create_elUserFolder_table();
    $this->errors = [];
  }

  private function create_elFolders_table(){
    $sql = "CREATE TABLE IF NOT EXISTS `el_folders` (
            `id` int(8) NOT NULL AUTO_INCREMENT,
            `name` varchar(64) COLLATE utf8_bin NOT NULL,
            `parent_id` int(8) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=71
          DEFAULT CHARSET=utf8 COLLATE=utf8_bi";
    $this->db->query($sql);
  }
  private function create_elUserFolder_table(){
    $sql = "CREATE TABLE IF NOT EXISTS `el_user_folder` (
            `user_id` int(8) NOT NULL,
            `folder_id` int(8) NOT NULL
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
    $this->db->query($sql);
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
      $sql = "SELECT * FROM  `el_user_folder` WHERE  `user_id` = ? AND `folder_id` = ? LIMIT 1";
      $params = array('ii',$this->user_id, $folder_id);
      if($result = $this->db->query($sql, $params))
      { return true; }
      else
      { return false; }
  }

  public function load_all_folders()
  {
    $sql = "SELECT * FROM el_user_folder INNER JOIN el_folders
            ON el_user_folder.`folder_id` = el_folders.id
            WHERE `user_id` = ?" ;
    $params = array( 'i', $this->user_id );
    return $this->db->query($sql,$params);
  }

  public function load_folders_inFolder($in_folder_id)
  {
    $sql = "SELECT * FROM el_user_folder INNER JOIN el_folders
            ON el_user_folder.`folder_id` = el_folders.id
            WHERE `user_id` = ? AND `parent_id` = ?" ;
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
        $sql_update_parent = "UPDATE  `cms`.`el_folders` SET  `parent_id` = ? WHERE  `el_folders`.`id` =?";
        $params_update_parent = array( 'si', $parent_id, $folder_id);
        $this->db->query($sql_update_parent,$params_update_parent);
      }
    }
  }

  private function update_folders_parent_byParent($parent_id, $new_parent_id)
  {
    $sql_update_parents = "UPDATE  `cms`.`el_folders` SET  `parent_id` = ? WHERE  `el_folders`.`parent_id` =?";
    $params_update_parents = array( 'si', $new_parent_id, $parent_id);
    $this->db->query($sql_update_parents,$params_update_parents);
  }

  public function remove_folder($folder_id)
  {
    if($this->user_owns_folder($folder_id))
    {
      $sql_user_folder = "DELETE FROM  `el_user_folder` WHERE `user_id` = ? AND `folder_id` = ?";
      $params_user_folder = array( 'ii', $this->user_id, $folder_id );
      $this->db->query($sql_user_folder, $params_user_folder);

      $sql_folder ="DELETE FROM `cms`.`el_folders` WHERE `el_folders`.`id` = ?";
      $params_folder = array('i', $folder_id );
      $this->db->query($sql_folder, $params_folder);

          // set affected folders parent to root;
      $this->update_folders_parent_byParent( $folder_id , 0 );
          // set affected files parent to root;
      //$this->update_files_parent_byParent( $folder_id , 0 );
      echo 'deleted: '.$folder_id;
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

    $sql_insert_folder = "INSERT INTO  `cms`.`el_folders` ( `id` , `name` , `parent_id` )
                          VALUES ( NULL ,  ?,  ? )";
    $params = array('si', $folder_name ,$parent_id);

    if($new_folder_id = $this->db->query($sql_insert_folder, $params, 'get_id'))
    {
      $sql_user_folder = "INSERT INTO `cms`.`el_user_folder` (`user_id`, `folder_id`)
                          VALUES (?, ?)";
      $params_user_folder = array('ii', $this->user_id, $new_folder_id);
      $this->db->query($sql_user_folder, $params_user_folder);
    }
  }
}
 ?>
