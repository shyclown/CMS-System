<?php
/**
 *
 */
class Articles
{
  private $db;
  public $errors;

  public $id;
  public $user_id;
  public $header;
  public $content;
  public $date_created;
  public $date_edited;
  public $state;

  function __construct()
  {
    $this->db = new Database;
    $this->errors = [];
    // create tables
    $this->create_table_el_articles();
    $this->create_table_el_user_article();
  }
  //-----------------------------------------------------
  // Create Tables
  //-----------------------------------------------------
  private function create_table_el_articles()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `el_articles` (
            `id` int(8) NOT NULL AUTO_INCREMENT,
            `header` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
            `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
            `state` int(1) NOT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `date_edited` datetime NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
    $this->db->query($sql);
  }
  private function create_table_el_user_article()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `el_user_article` (
            `user_id` int(8) NOT NULL,
            `article_id` int(8) NOT NULL,
            PRIMARY KEY (`user_id`)
          ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
    $this->db->query($sql);
  }

  public function is_logged_in(){
    if(isset($_SESSION) && isset($_SESSION['user_id'])){
      $this->user_id = $_SESSION['user_id']; return true;
    }
    else {
      $this->errors[] = 'User is not logged in.';
      return false;
    }
  }
  //-----------------------------------------------------
  // Select Article
  //-----------------------------------------------------

  public function select_articles_of_user($limit_min = 0, $limit_max = 30)
  {
    $sql = "SELECT *
            FROM el_user_article ua
            INNER JOIN el_articles a ON ua.article_id = a.id
            WHERE ua.user_id = ?
            LIMIT ? , ?";
    $params = array( 'iii', $this->user_id, $limit_min, $limit_max );
    return $this->db->query($sql,$params);
  }

  public function select_by_id($article_id)
  {
    $sql = "SELECT user_id, header, content, date_created, date_edited, state
            FROM el_user_article ua
            INNER JOIN el_articles a ON ua.article_id = a.id
            WHERE ua.user_id = ? AND a.id = ?
            LIMIT 1";
    //$sql = "SELECT * FROM el_articles WHERE id = ?";
    $params = array( 'ii', $_SESSION['user_id'], $article_id );
    $result = $this->db->query($sql,$params);
    $this->load_data($result[0]);
    return $result[0];
  }

  public function select_public_articles($limit_min = 0, $limit_max = 30)
  {
    // state 3 will mean public
    $sql = "SELECT * FROM el_articles WHERE state = 3 LIMIT ?,? ";
    $params = array( 'ii', $limit_min, $limit_max );
    return $this->db->query($sql,$params);
  }

  private function load_data($data)
  {
    $this->user_id = $data['user_id'];
    $this->header = $data['header'];
    $this->content = $data['content'];
    $this->date_created = $data['date_created'];
    $this->date_edited = $data['date_edited'];
    $this->state = $data['state'];
  }
  //-----------------------------------------------------
  // Create Article
  //-----------------------------------------------------

  public function create_new()
  {
    if($new_article_id = $this->insert_draft()){
      $this->insert_user_article($new_article_id);
      $this->id = $new_article_id;
      $this->select_by_id($new_article_id);
      return $new_article_id;
    }
    return false;
  }

  private function insert_draft()
  {
    $sql = "INSERT INTO `el_articles` (`id`, `header`, `content`, `state`, `date_created`, `date_edited`)
            VALUES (NULL, ?, ?, '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    $params = array( 'ss', '', '' );
    // returns ID of inserted draft
    return $this->db->query($sql, $params , 'get_id');
  }
  private function insert_user_article($id)
  {
    $sql = "INSERT INTO  `cms`.`el_user_article` (`user_id` ,`article_id`)VALUES (?,  ?)";
    $params = array( 'ii', $_SESSION['user_id'], $id );
    // returns ID of inserted draft
    return $this->db->query($sql,$params);
  }

  //-----------------------------------------------------
  // Update Article
  //-----------------------------------------------------

  public function update_article($data)
  {
    $sql = "UPDATE `el_articles` SET `content` = ? WHERE `el_articles`.`id` = ?";
    $params = array('si', $data->content, $data->article_id);
    return $this->db->query($sql,$params);
  }

  //-----------------------------------------------------
  // Delete Article
  //-----------------------------------------------------

  public function delete_article()
  {
    $sql = "DELETE FROM `el_articles` WHERE `el_articles`.`id` = ?";
    $params = array('i', $data->article_id);
    return $this->db->query($sql,$params);
  }
}
 ?>
