<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/class_articles.php');
new Session;
$articles = new Articles;
$articles->is_logged_in();

$fileData = file_get_contents("php://input");
$ng_data = json_decode($fileData, true);

if($ng_data['action'] == 'load')
{
  $limit_min = $ng_data['page'] * $ng_data['per_page'];
  $limit_max = $limit_min + $ng_data['per_page'];

  $result = $articles->select_articles_of_user($limit_min,$limit_max);
  echo json_encode($result);
}

if($ng_data['action'] == 'delete')
{
  $article_id = $ng_data['article_id'];
  $articles->select_by_id($article_id);
  $result = $articles->delete();
  //echo $result;
}

if($ng_data['action'] == 'create_new')
{
  $result = $articles->create_new();
  //echo $result;
}
?>
