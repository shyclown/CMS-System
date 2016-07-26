<!doctype html>
<?php


include_once 'system/class_mysqli.php';
include_once 'system/class_log.php';
// Session
include_once 'system/class_session.php';
$log = new Log;
$log->insert('page_loaded');
new Session;
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
