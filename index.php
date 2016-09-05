<!doctype html>
<?php
include_once 'system/class_mysqli.php';
include_once 'system/class_log.php';
// Session
include_once 'system/class_session.php';
include_once 'system/class_account.php';
$log = new Log;
$log->insert('page_loaded');
new Session;
if(!isset($_SESSION['user_id'])){
  header('Location: http://'.$_SERVER['SERVER_NAME'].'/login.php');
  die();
}
$account = new Account;
$session_id = session_id();
$log->insert('session init: '. $session_id);
?>
<html lang="en">
<?php
// CMS 2016
// version 0.01
include '/templates/main/head.php';
include '/templates/main/body.php';
 ?>


</html>
