<?php
  require_once('class_mysqli.php');
  /**
   * Makes note to log table fore debuging purposes
   */
  class Log
  {
    var $db;

    function __construct()
    {
      $this->db = new Database;
    }
    public function insert($text){
      $sql_query = "INSERT INTO `cms_log` (`id`, `log`) VALUES (NULL, '".$text."')";
      $this->db->query($sql_query);
    }
    public function read(){
      $sql_query = "SELECT * FROM cms_log ORDER BY id DESC";
      $result = $this->db->query($sql_query);
      return $result;
    }
  }


 ?>
