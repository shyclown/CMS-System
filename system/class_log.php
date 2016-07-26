<?php
  /**
   * Makes note to log table fore debuging purposes
   */
  class Log
  {
    var $db;

    function __construct()
    {
      $this->db = new Database;
      $this->create_table_if_not_exist();
    }
    private function create_table_if_not_exist(){
      $sql_query = "CREATE TABLE IF NOT EXIST `cms_log` (
                    `id` int(4) NOT NULL AUTO_INCREMENT,
                    `log` text NOT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM AUTO_INCREMENT=267 DEFAULT CHARSET=latin1";
      $this->db->query($sql_query);
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
