<?php
class Account
{

  private $logname;
  private $id;
  private $username;
  private $email;
  private $password;

  private $errors;

  function __construct()
  {
    $this->db = new Database;
    $this->create_table_if_not_exist();
    $this->errors = array();
  }

  private function create_table_if_not_exist(){

    $sql_create =    'CREATE TABLE IF NOT EXISTS `el_users` (
                      `id` bigint(32) NOT NULL AUTO_INCREMENT,
                      `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `user_pass` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `salt` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `id` (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
    //$sql_check = 'SHOW TABLES LIKE `el_users`';
    $this->db->query($sql_create);
  }

  public function load_signed(){
    if(isset($_SESSION['user_id'])){
      $id = $_SESSION['user_id'];
      $sql = "SELECT * FROM `el_users` WHERE `id` = ?";
      $params = array("i",$id);
      $result = $this->db->query($sql,$params);
      if(!empty($result)){
        $this->id = $result[0]['id'];
        $this->username = $result[0]['user_login'];
        $this->email = $result[0]['user_email'];
        }
    }
  }
  public function get_name(){ echo $this->username; }
  public function get_email(){ echo $this->email; }
  public function get_id(){ echo $this->id; }

  private function load_post_values()
  {
    $this->username = $_POST['user_name'];
    $this->email = $_POST['user_email'];
    $this->password = $this->generate_hash($_POST['user_pass']);
    $this->salt = $_POST['user_pass']; // just for testing
  }

  public function create_new_account()
  {
    $this->load_post_values();
    $this->check_values();
    if(empty($this->errors)){
      $this->create_account();
      var_dump($this->errors);
      echo 'user created';
    }
    else{ var_dump($this->errors); }
  }

  public function delete_current_account()
  {
    $this->user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO `el_users` (`id`, `user_login`, `user_pass`, `user_email`, `salt`) VALUES (NULL, ?, ?, ?, ?);";
    $params = array('ssss', $this->username , $this->password , $this->email, $this->salt);
    $result = $db->query($sql, $params);

  }

  public function login_account()
  {
    $this->logname = $_POST['user_login'];
    $this->password = $_POST['user_pass'];
    if($this->find_account()){
      $this->make_session();
      if($_POST['remember_me']){
        $this->make_cookie();
      }
    }
    else{ echo "not valid username or password";}

  }

  public function check_email(){
    if(isset($_POST) && $_POST != ''){
      $this->email = $_POST['email'];
      if($this->is_valid('email')){
        if($this->is_free('email')){
          echo 'email is ok';
        }
        else{ echo 'email is not free'; }
      }
      else{
        echo 'not valid email';
      }
    }
  }

  public function is_valid($column)
  {
    if($column == 'email'){return filter_var(  $this->email, FILTER_VALIDATE_EMAIL);}
    if($column == 'username'){return preg_match('/^[A-Za-z][A-Za-z\d_.-]{5,31}$/i', $this->username);}
  }

  public function is_free($column)
  {
    if($column == 'email'){ $value = $this->email; }
    if($column == 'username'){ $value = $this->username; }
    $sql = "SELECT * FROM `users` WHERE ? = ?";
    $params = array('ss', $column, $value);
    $result = $this->db->query($sql, $params);
    return $result === NULL;
  }

  public function list_all()
  {
    $sql = "SELECT * FROM `el_users`";
    $result = $this->db->select($sql);
    return $result;
  }

  private function valid_password($input, $hashed)
  {
    return crypt($input, $hashed) == $hashed;
  }

  private function generate_hash($password, $cost = 11)
  {
    $salt= substr(base64_encode(openssl_random_pseudo_bytes(17)),0,22);
    $salt= str_replace("+",".",$salt);
    $param='$'.implode('$',array("2y", str_pad($cost,2,"0",STR_PAD_LEFT), $salt));
    return crypt($password,$param);
  }

  private function check_values()
  {
    if(!isset($this->email) || $this->email == ''){
      array_push($this->errors,'email not set');}
    if(!$this->is_valid('email')){
      array_push($this->errors,'email writen wrong');}
    if(!$this->is_free('email')){
      array_push($this->errors,'email already used by someone else');}

    if(!isset($this->username) || $this->username == ''){
      array_push($this->errors,'username not set');}
    if(!$this->is_valid('username')){
      array_push($this->errors,'username is not valid');}
    if(!$this->is_free('username')){
      array_push($this->errors,'username already used by someone else');}
  }

  private function create_account()
  {
    $sql = "INSERT INTO `cms`.`el_users` (`id`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `salt`) VALUES (NULL, ?, ?, 'Nicename' , ?, ?);";
    $params = array('ssss', $this->username , $this->password , $this->email, $this->salt);
    $this->db->query($sql, $params);
  }

  private function find_account()
  {
    $sql = "SELECT * FROM `el_users` WHERE `user_login` = ? OR `user_email` = ?";
    $params = array("ss", $this->logname ,$this->logname);
    $result = $this->db->query($sql, $params);
    if(!empty($result))
    {
      if($this->valid_password($this->password, $result[0]['user_pass']))
      {
        $this->id = $result[0]['id'];
        $this->username = $result[0]['user_login'];
        $this->email = $result[0]['user_email'];
        return true;
      }
      else
      {
        return false;
      }
    }
  }

  private function make_cookie()
  {
    echo 'inside make_cookie<br>';
    if(!setcookie('elephant-id',session_id(),strtotime( '+30 days' ), "/", NULL)){
      echo 'cookie not made';
    };
  }
  private function make_session()
  {
    $_SESSION["user_id"] = $this->id;
    $_SESSION["user_name"] = $this->username;
    $_SESSION["loged_in"] = true;
  }

  public function is_valid_cookie()
  {
    $sql = "SELECT * FROM `el_sessions` WHERE `id` = ? LIMIT 1";
    $params = array('s', $_COOKIE['elephant-id']);
    $result = $this->db->query($sql, $params);
    $data = $result[0]['data'];
    if(session_decode ( $data ))
    {
    return true;
    }
    return false;
  }
}
