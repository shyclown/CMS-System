<?php
/**
 *
 */
class Articles
{
  private $db;
  public $errors;

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
            FROM user_articles ua
            INNER JOIN el_articles a ON ua.article_id = a.id
            WHERE ua.user_id = ?
            LIMIT ? , ?";
    $params = array( 'i', $this->user_id, $limit_min, $limit_max );
    return $this->db->query($sql,$params);
  }

  public function select_article_by_id($article_id)
  {
    $sql = "SELECT * FROM el_articles WHERE id = ?";
    $params = array( 'i', $article_id );
    return $this->db->query($sql,$params);
  }

  public function select_public_articles($limit_min = 0, $limit_max = 30)
  {
    // state 3 will mean public
    $sql = "SELECT * FROM el_articles WHERE state = 3 LIMIT ?,? ";
    $params = array( 'ii', $limit_min, $limit_max );
    return $this->db->query($sql,$params);
  }
  //-----------------------------------------------------
  // Create Article
  //-----------------------------------------------------

  public function insert_article($data)
  {
    $sql = "INSERT INTO `el_articles` (`id`, `header`, `content`, `state`, `date_created`, `date_edited`)
            VALUES (NULL, ?, ?, '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    $params = array( 'ss', $data->header, $data->content );
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
