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
function br(){ echo '<br/>';}

new Session;
$account = new Account;

if($account->create_new()){
  echo 'New account: id-'. $account->user_id;
  br();
}
if($account->login()){
  echo 'Loged in: id-'.$_SESSION['user_id'];
  br();
}
if($account->change_nicename('newNiceName')){
  echo 'Nicename changed';
  br();
}
$new_email = generateRandomString(8).'@'.generateRandomString(8).'.com';
if($account->change_email($new_email)){
  echo 'Email changed';
  br();
}
$new_pass = generateRandomString(12);
if($account->change_password($new_pass, $test->password)){
  echo 'Password changed';
  br();
}
if($account->delete()){
  echo 'Account deleted';
  br();
}
if($account->logout()){
  echo 'Logged out';
  br();
}



?>
