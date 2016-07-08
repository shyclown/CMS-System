<?php
/**
 *
 */
 class Article
 {
   var $db; // connect to database
   function __construct()
   {
     $this->db = new Database;
   }

     public function load_by_id()
     {
       $sql = "SELECT * FROM `elephant`.`el_post` WHERE `user_id` = ? AND `id` = ? LIMIT 1";
       $params = array("ii",$_SESSION["user_id"],$_POST['id']);
       return $this->db->query($sql,$params);
     }

      public function save_new()
     {
       $sql = "INSERT INTO `elephant`.`el_post` (`id`, `user_id`, `header`, `content`, `active`, `created`)
       VALUES (NULL, ?, ?, ?, ?, NULL)";
       $params = array('issi', $_SESSION["user_id"], $_POST["header"], $_POST["content"], 1);
       return $this->db->query($sql,$params);
     }

      public function update()
     {
       $sql = "UPDATE  `elephant`.`el_post`
       SET  `header` =  ? , `content` =  ?
       WHERE `el_post`.`id` = ? AND `user_id` = ?";
       $params = array('ssii',$_POST['header'],$_POST['content'],$_POST['id'],$_SESSION["user_id"]);
       return $this->db->query($sql,$params);
     }
 }
