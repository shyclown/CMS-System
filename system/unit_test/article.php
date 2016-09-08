<?php
require_once('../class_account.php');
require_once('../class_session.php');
require_once('../class_mysqli.php');
// article class
require_once('../class_articles.php');
new Session;
$user = new Account;
var_dump($_SESSION);

$article = new Articles;
// returns ID of created Article
$article->create_new();
var_dump($article);


$article->load($data);
//$article->save();





/*
$article->save_all($data);
$article->change_header('New Header');
$article->change_content('New Content');

// Returns bool
$article->add_content_file();
$article->remove_content_file();
// Returns array
$article->content_files();

$article->user_id;
$article->user_name;
$article->edited;
$article->created;
$article->publish_date;


$article->make_public();
$article->make_private();
$article->make_protected('password');
$article->make_draft();

$article->set_publish_date($publish_date);

$article->add_category($new_category);
$article->move_to_folder($folder_name)
*/
 ?>
