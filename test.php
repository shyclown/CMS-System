<?php
var_dump($_POST);

require_once('define.php');
require_once('class_session.php');
require_once('class_mysqli.php');

new Session();
$remeber;

if(isset($_POST['remember_me'])){ $remember = true; }
else{ $remember = false; }


echo $remember;
 ?>
