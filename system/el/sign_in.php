<?php
$root = $_SERVER["DOCUMENT_ROOT"];
$system = $root.'/system/';

ini_set('display_errors',1); error_reporting(E_ALL);
include_once $system.'class_mysqli.php';
include_once $system.'class_log.php';
// Session
include_once $system.'class_session.php';
include_once $system.'class_account.php';

new Session;
$account = new Account;
if(isset($_POST)
&& isset($_POST["user_email"])
&& isset($_POST["user_pass"])
&& isset($_POST["user_name"]))
{
  $account->create_new();
}
?>
