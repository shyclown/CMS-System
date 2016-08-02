<?php
// requires mysqli class
class FileManager
{
  protected $db;
  public $errors;
  protected $user_id;

  function __construct()
  {
    $this->db = new Database;
    $this->errors = [];
    // create tables
    $this->create_elFolders_table();
    $this->create_elFiles_table();
    $this->create_elUserFolder_table();
    $this->create_elUserFile_table();
  }
  //-----------------------------------------------------
  // Create Tables
  //-----------------------------------------------------

  private function create_elFolders_table()
  {
      $sql = "CREATE TABLE IF NOT EXISTS `el_folders` (
              `id` int(8) NOT NULL AUTO_INCREMENT,
              `name` varchar(64) COLLATE utf8_bin NOT NULL,
              `parent_id` int(8) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
      $this->db->query($sql);
  }
  private function create_elUserFolder_table()
  {
      $sql = "CREATE TABLE IF NOT EXISTS `el_user_folder` (
              `user_id` int(8) NOT NULL,
              `folder_id` int(8) NOT NULL
              ) ENGINE=InnoDB
              DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
      $this->db->query($sql);
  }
  private function create_elFiles_table()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `el_files` (
            `id` int(8) NOT NULL AUTO_INCREMENT,
            `name` varchar(64) COLLATE utf8_bin NOT NULL,
            `parent_id` int(8) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
    $this->db->query($sql);
  }
  private function create_elUserFile_table()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `el_user_file` (
            `user_id` int(8) NOT NULL,
            `file_id` int(8) NOT NULL
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
    $this->db->query($sql);
  }
  /*
  *
  */
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
  protected function user_owns_file($file_id)
  {
    $sql = "SELECT * FROM  `el_user_file` WHERE  `user_id` = ? AND `file_id` = ? LIMIT 1";
    $params = array('ii',$this->user_id, $file_id);
    if($result = $this->db->query($sql, $params))
    { return true; }
    else
    { return false; }
  }

  protected function user_owns_folder($folder_id)
  {
      $sql = "SELECT * FROM  `el_user_folder` WHERE  `user_id` = ? AND `folder_id` = ? LIMIT 1";
      $params = array('ii',$this->user_id, $folder_id);
      if($result = $this->db->query($sql, $params))
      { return true; }
      else
      { return false; }
  }

//-----------------------------------------------------
// Folders functions
//-----------------------------------------------------

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
    $this->update_files_parent_byParent( $folder_id, 0 );

    echo 'deleted: '.$folder_id;
  }
}

public function create_new_folder($data)
{
  $folder_name = $data->folder_name;
  $parent_id = $data->parent_id;

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
    if($this->db->query($sql_user_folder, $params_user_folder)){
      return true;
    };
  }
  return false;
}

//-----------------------------------------------------
// Files Functions
//-----------------------------------------------------

public function select_all_files()
{
  $sql = "SELECT * FROM el_user_file INNER JOIN el_files
          ON el_user_file.`file_id` = el_files.id
          WHERE `user_id` = ?" ;
  $params = array( 'i', $this->user_id );
  return $this->db->query($sql,$params);
}

public function select_files_byParent($parent_id)
{
  $sql = "SELECT * FROM el_user_file INNER JOIN el_files
          ON el_user_file.`file_id` = el_files.id
          WHERE `user_id` = ? AND `parent_id` = ?" ;
  $params = array( 'i', $this->user_id, $parent_id );
  return $this->db->query($sql,$params);
}

public function store_new_file($file_name, $parent_id)
{
  $sql_insert_file = "INSERT INTO  `cms`.`el_files` ( `id` , `name` , `parent_id` )
                      VALUES ( NULL ,  ?,  ? )";
  $params = array('si', $file_name ,$parent_id);

  if($new_file_id = $this->db->query($sql_insert_file, $params, 'get_id'))
  {
    $sql_user_file = "INSERT INTO `cms`.`el_user_folder` (`user_id`, `folder_id`)
                      VALUES (?, ?)";
    $params_user_file = array('ii', $this->user_id, $new_file_id);
    $this->db->query($sql_user_file, $params_user_file);
  }
}

public function update_file_name($new_file_name,$file_id)
{
  if($this->user_owns_file($file_id))
  {
    $sql_update_name = "UPDATE  `cms`.`el_files` SET  `name` = ? WHERE  `el_files`.`id` =?";
    $params_update_name = array( 'si', $new_file_name, $file_id);
    $this->db->query($sql_update_name,$params_update_name);
  }
}

public function remove_file($file_id)
{
  if($this->user_owns_file($file_id))
  {
    $sql_user_file = "DELETE FROM  `el_user_file` WHERE `user_id` = ? AND `file_id` = ?";
    $params_user_file = array( 'ii', $this->user_id, $file_id );
    $this->db->query($sql_user_file, $params_user_file);

    $sql_files ="DELETE FROM `cms`.`el_files` WHERE `el_folders`.`id` = ?";
    $params_files = array('i', $file_id );
    $this->db->query($sql_files, $params_files);

    echo 'deleted file: '.$file_id;
  }
}

public function change_file_parent($file_id,$parent_id)
{
  if($this->user_owns_file($file_id))
  {
    if($this->user_owns_folder($parent_id))
    {
      $sql_update_parent = "UPDATE  `cms`.`el_files` SET  `parent_id` = ? WHERE  `el_files`.`id` =?";
      $params_update_parent = array( 'ii', $parent_id, $file_id);
      $this->db->query($sql_update_parent,$params_update_parent);
    }
  }
}

public function update_files_parent_byParent($old_parent_id, $new_parent_id){
    if($this->user_owns_folder($new_parent_id))
    {
      $sql_update_parents = "UPDATE  `cms`.`el_files` SET  `parent_id` = ? WHERE  `el_files`.`parent_id` =?";
      $params_update_parents = array( 'ii', $new_parent_id, $old_parent_id);
      $this->db->query($sql_update_parents,$params_update_parents);
    }
}

}//end of class
 ?>
