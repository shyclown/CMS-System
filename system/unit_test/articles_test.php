<?php
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

require_once('../class_account.php');
require_once('../class_session.php');
require_once('../class_mysqli.php');
// create account
$test = new stdClass();
$test->username = generateRandomString(16);
$test->email =  generateRandomString(16).'@'. generateRandomString(16).'.php';
$test->password =  generateRandomString(18);

$_POST['user_name'] = $test->username;
$_POST['user_email'] = $test->email;
$_POST['user_pass'] = $test->password;

$_POST['user_login'] = $test->username;
new Session;
$account = new Account;

$account->create_new_account();
$login_user = $account->login_account();
var_dump($_SESSION);

$account->change_nice_name('new nicer name');
$test->nw_password = generateRandomString(18);
if($account->change_password($_POST['user_pass'],'new_password_changed'));
$account->change_email('new@email.com');




?>
