<?php
require_once('../class_account.php');
require_once('../class_session.php');
require_once('../class_mysqli.php');
// article class
require_once('../class_articles.php');
new Session;
$user = new Account;
echo session_id();
$user->logout();
var_dump($user);

if(!$user->login()){ var_dump($user->errors);};


$article = new Articles;

 ?>
