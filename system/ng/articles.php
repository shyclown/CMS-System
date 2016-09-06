<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_articles.php');
new Session;
$articles = new Articles;
$articles->is_logged_in();

$fileData = file_get_contents("php://input");
$ng_data = json_decode($fileData, true);

$limit_min = $ng_data['page'] * $ng_data['per_page'];
$limit_max = $limit_min + $ng_data['per_page'];

$result = $articles->select_articles_of_user($limit_min,$limit_max);
echo json_encode($result);

?>
