<?php
class Account
{
  public $user_id;
  public $username;
  public $nicename;
  public $email;


  public $user_folder;

  // logname is either username or email
  private $logname;
  private $password;

  public $errors;
  private $db;

  function __construct()
  {
    $this->db = new Database;
    $this->create_table_if_not_exist();
    $this->errors = array();

    if(isset($_SESSION['user_id'])){ $this->load_signed(); }
    else if(isset($_COOKIE['elephant-id'])){
      if($this->is_valid_cookie()){ $this->load_signed();
      }
    }
  }

  private function add_error($string){
    array_push($this->errors, $string);
  }
  private function create_table_if_not_exist()
  {
    $sql_create =    'CREATE TABLE IF NOT EXISTS `el_users` (
                      `id` bigint(32) NOT NULL AUTO_INCREMENT,
                      `user_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `user_pass` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `salt` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `id` (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
    $this->db->query($sql_create);
  }
  private function set_user_folder()
  {
    $path = $_SERVER["DOCUMENT_ROOT"].'/files/'.$this->user_id;
    if (!file_exists($path)){ mkdir($path, 0777, true); }
    return $path;
  }

  public function load_signed()
  {
      $id = $_SESSION['user_id'];
      $sql = "SELECT * FROM `el_users` WHERE `id` = ? LIMIT 1";
      $params = array("i",$id);
      $result = $this->db->query($sql,$params);
      if(!empty($result))
      {
        $this->user_id = $result[0]['id'];
        $this->username = $result[0]['user_name'];
        $this->email = $result[0]['user_email'];
        $this->nicename = $result[0]['user_nicename'];
      }
  }

  public function create_new()
  {
    $this->username = $_POST['user_name'];
    $this->email = $_POST['user_email'];
    $this->password = $this->generate_hash($_POST['user_pass']);
    $this->salt = $_POST['user_pass']; // just for testing

    $this->check_email($this->email);
    $this->check_username($this->username);
    if(empty($this->errors))
    {
      $this->user_id = $this->insert_account();
      $this->set_user_folder();
      return true;
    }
    else{
      var_dump($this->errors);
      return false;
    }
  }

  public function delete()
  {
    $this->user_id = $_SESSION['user_id'];
    $sql = "DELETE FROM `cms`.`el_users` WHERE `el_users`.`id` = ?";
    $params = array('i', $this->user_id);
    return $this->db->query($sql, $params);
  }

  public function login()
  {
    if(!$_POST
    && !isset($_POST['user_login'])
    && !isset($_POST['user_pass'])){
      $this->add_error('Function:'.__FUNCTION__.' -> Correct POST data is missing. Login requires log name and password to be sent.');
      return false;
    }else{
    $this->logname  = $_POST['user_login'];
    $this->password = $_POST['user_pass'];
    }
    if($this->find_account()){
      $this->make_session();
      if(isset($_POST['remember_me'])){ $this->make_cookie(); }
      return true;
    }
    else{
      return false;
    }
  }

  public function is_valid($column, $value)
  {
    if($column == 'user_email'){return filter_var(  $value, FILTER_VALIDATE_EMAIL);}
    if($column == 'user_name'){return preg_match('/^[A-Za-z][A-Za-z\d_.-]{5,31}$/i', $value);}
  }

  public function is_free_email($value)
  {
    $sql = "SELECT * FROM `el_users` WHERE `user_email` = ?";
    $params = array('s', $value);
    $result = $this->db->query($sql, $params);
    return empty($result);
  }
  public function is_free_username($value)
  {
    $sql = "SELECT * FROM `el_users` WHERE `user_name` = ?";
    $params = array('s', $value);
    $result = $this->db->query($sql, $params);
    return empty($result);
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

  private function check_email($email)
  {
    if(!isset($email) || $email == ''){
      $this->add_error('email not set');}
    if(!$this->is_valid('user_email', $email)){
      $this->add_error('email writen wrong');}
    if(!$this->is_free_email($email)){
      $this->add_error('email already used by someone else');}
    return empty($this->errors);
  }

  private function check_username($username)
  {
    if(!isset($username) || $username == ''){
      $this->add_error('username not set');}
    if(!$this->is_valid('user_name', $username)){
      $this->add_error('username is not valid');}
    if(!$this->is_free_username($username)){
      $this->add_error('username already used by someone else');}
    return empty($this->errors);
  }

  private function insert_account()
  {
    $sql = "INSERT INTO `cms`.`el_users` (`id`, `user_name`, `user_pass`, `user_nicename`, `user_email`, `salt`) VALUES (NULL, ?, ?, 'Nicename' , ?, ?)";
    $params = array('ssss', $this->username , $this->password , $this->email, $this->salt);
    return $this->db->query($sql, $params, 'get_id');
  }

  // UPDATES
  public function change_email($new_email)
  {
    if(!$this->check_email( $new_email )){ return false; }
    else
    {
      $sql = "UPDATE `el_users` SET `user_email` = ? WHERE `el_users`.`id` = ?";
      $params = array('si', $new_email, $_SESSION['user_id']);
      if($this->db->query($sql, $params)){
        $this->email = $new_email;
        return true;
      }
      $this->add_error('change_email query failed');
      return false;
    }
  }
  public function change_nicename($new_nicename){
    $sql = "UPDATE `el_users` SET `user_nicename` = ? WHERE `el_users`.`id` = ?";
    $params = array('si', $new_nicename, $_SESSION['user_id']);
    if($this->db->query($sql, $params)){
      $this->nicename = $new_nicename;
      return true;
    }
    $this->add_error('change_nicename query failed');
    return false;
  }

  public function logout()
  {
    if(session_destroy())
    {
      echo 'destroyed';
      unset($_SESSION);
      if(isset($_COOKIE['elephant-id']))
      {
        unset($_COOKIE['elephant-id']);
        setcookie('elephant-id',null,-1,'/');
      }
      return true;
    }
    return false;
    //header("");
  }

  public function change_password($new_pass, $old_pass)
  {
    if($this->check_password($old_pass))
    {
      // send email
      //$this->send_email('Header','Content');
      // replace old password
      $sql = "UPDATE `el_users` SET `user_pass` = ? WHERE `el_users`.`id` = ?";
      $params = array('ss', $new_pass, $_SESSION['user_id'], );
      return $this->db->query($sql, $params);
    }

  }
  private function check_password($password)
  {
    $sql = "SELECT `user_pass` FROM `el_users` WHERE `id` = ? LIMIT 1";
    $params = array("i", $_SESSION['user_id']);
    $result = $this->db->query($sql,$params);
    return $this->valid_password($password, $result[0]['user_pass']);
  }

  private function find_account()
  {
    $sql = "SELECT * FROM `el_users` WHERE `user_name` = ? OR `user_email` = ? LIMIT 1";
    $params = array("ss", $this->logname ,$this->logname);
    $result = $this->db->query($sql, $params);
    if(!empty($result))
    {
      if($this->valid_password($this->password, $result[0]['user_pass']))
      {
        $this->user_id = $result[0]['id'];
        $this->username = $result[0]['user_name'];
        $this->email = $result[0]['user_email'];
        $this->nicename = $result[0]['user_nicename'];
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
    $_SESSION["user_id"] = $this->user_id;
    $_SESSION["user_name"] = $this->username;
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
