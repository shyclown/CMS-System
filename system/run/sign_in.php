<?php
ini_set('display_errors',1); error_reporting(E_ALL);
include_once '../class_mysqli.php';

include_once '../class_log.php';
// Session
include_once '../class_session.php';
include_once '../class_account.php';

new Session;
$account = new Account;
if(isset($_POST)
&& isset($_POST["user_email"])
&& isset($_POST["user_pass"])
&& isset($_POST["user_name"])){
  $account->create_new_account();
  var_dump($_SESSION);
  var_dump($_COOKIE);
}
 ?>
