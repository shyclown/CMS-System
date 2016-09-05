<!doctype html>
<?php
include_once 'system/class_mysqli.php';
include_once 'system/class_log.php';
// Session
include_once 'system/class_session.php';
include_once 'system/class_account.php';
$log = new Log;
$log->insert('login_page');
new Session;
$account = new Account;

$session_id = session_id();
$log->insert('session init: '. $session_id);
?>
<html lang="en">
<?php
// CMS 2016
// version 0.01
include '/templates/main/login_head.php';
var_dump($_SESSION);
var_dump($_COOKIE);
 ?>
<body>
  <section>
  <h1>Welcome to elephant page.</h1>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in pulvinar mi. Donec ut neque leo. In hac habitasse platea dictumst. Mauris sit amet viverra odio. Morbi varius mauris eget diam lobortis feugiat. Fusce aliquam id risus a efficitur. Nunc ante leo, blandit id quam et, rhoncus pulvinar nulla.</p>
</section>
<?php if(isset($_SESSION['user_id'])):?>
<form action="/system/el/log_out.php" method="post">
  <p>You are still signed in as a user: <b><?php echo $account->nicename; ?></b></p>
  <button type="sybmit">Log out.</button>
</form>
<?php
else:
?>
<div class="flex">
  <section class="flex-item">
  <h1>Log In</h1>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in pulvinar mi. Donec ut neque leo. In hac habitasse platea dictumst. Mauris sit amet viverra odio. Morbi varius mauris eget diam lobortis feugiat. Fusce aliquam id risus a efficitur. Nunc ante leo, blandit id quam et, rhoncus pulvinar nulla.</p>

    <form action="/system/el/log_in.php" method="post">
      <input name="user_login" type="text" autocomplete="false" placeholder="username or email"></input>
      <input name="user_pass" type="password" placeholder="password"></input>
      <label for="remember_me">Remember me:
      <input name="remember_me" type="checkbox" autocomplete="false" value="true" />
      </label>
      <button type="submit" >Log In</button>
    </form>

  </section>
  <section class="flex-item">
  <h1>Sign In</h1>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in pulvinar mi. Donec ut neque leo. In hac habitasse platea dictumst. Mauris sit amet viverra odio. Morbi varius mauris eget diam lobortis feugiat. Fusce aliquam id risus a efficitur. Nunc ante leo, blandit id quam et, rhoncus pulvinar nulla.</p>

  <form action="/system/el/login.php" method="post">
    <input name="username" type="text" placeholder="username">
    <input name="email" type="text" placeholder="email">
    <input name="password" type="password" placeholder="password">
    <button name="submit" type="submit">Sign In</button>
  </form>

  </section>
</div>
<?php endif; ?>
<section class="about">
<h1>About</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in pulvinar mi. Donec ut neque leo. In hac habitasse platea dictumst. Mauris sit amet viverra odio. Morbi varius mauris eget diam lobortis feugiat. Fusce aliquam id risus a efficitur. Nunc ante leo, blandit id quam et, rhoncus pulvinar nulla.</p>
</section>
</body>

</html>
