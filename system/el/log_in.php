<?php
$root = $_SERVER["DOCUMENT_ROOT"];
$system = $root.'/system/';

ini_set('display_errors',1); error_reporting(E_ALL);
include_once $system.'class_mysqli.php';

include_once $system.'class_log.php';
// Session
include_once $system.'class_session.php';
include_once $system.'class_account.php';
//&& isset($_POST["remember_me"])
new Session;

$account = new Account;
if($account->login()){
  header('Location: http://'.$_SERVER['SERVER_NAME'].'/index.php');
  die();
}





?>
