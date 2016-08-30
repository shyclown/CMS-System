<?php
require_once('../class_account.php');
require_once('../class_mysqli.php');
// create account
$test = new stdClass();
$test->username = 'testName';
$test->email = 'test@test.php';
$test->password = 'passwordforTest';


$account = new Account;
$account->create_test_account($test->username, $test->email, $test->password);
$login_user = $account->login_account($test->username, $test->password);

var_dump($login_user);


?>
