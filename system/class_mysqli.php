<?php
require_once('/../define.php');
// debug
mysqli_report(MYSQLI_REPORT_ALL);
class Database{

  // from define.php file
  private $hostname = DB_HOST;
  private $username = DB_USER;
  private $password = DB_PASS;
  private $database = DB_NAME;

  private $_mysqli;
  private $error;

  public function __construct(){

    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
      $this->_mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
    }
    catch(Exception $e){
      echo "Service unavailable";
      echo "message: " . $e->message;
      $error = $e->message;
      exit;
    }
  }
  public function connect(){
  }

  public function select($sql_string){
    $result = $this->_mysqli->query($sql_string);
    if($result){
      $_array = array();
      while($row = $result->fetch_array(MYSQLI_ASSOC))
        { $_array[] = $row; }
      return $_array;
    }
  }

  public function query($sql_string = null, $values = null, $returning = 'array')
  {
    $return_value = false;
    if($stmt = $this->_mysqli->prepare($sql_string)){

      if($values){
        if(is_array($values)){

          if(call_user_func_array(array($stmt, 'bind_param'), $this->refValues($values))){
          };
        }
      }
      if($stmt->execute())
      {
        if($result = $stmt->get_result())
        {
          $_array = array();
          while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $_array[]= $row;
          }
          if($returning == 'array'){
            $return_value = $_array;
          }
        }
        else {
          if($returning == 'get_id'){
            $return_value = $stmt->insert_id;
          }else{
            $return_value = true;
          }
        }
      } else {
        var_dump($this->_mysqli->error);
      }
      $stmt->close();
    }
    else
    {
      var_dump($this->_mysqli->error);
    }
    return $return_value;
  } // end of query

  private function refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
  }

  public function destroy(){
    $thread_id = $this->_mysqli->thread_id;
    $this->_mysqli->kill($thread_id);
    $this->_mysqli->close();
  }
}
