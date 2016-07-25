<style>

</style>
<div class="card">
  <div class="card_top">
  <h2 class="form">Log In</h2>
  </div>
  <div class="card_content">

  <form class="regular" name="Log_In" method="post" action="/test.php" autocomplete="off">
    <p>
    <label for="user_email">Email:</label>
    <input name="user_email" type="text" placeholder="email" autocomplete="false" value="" />
    </p>
    <p>
    <label for="user_pass">Password:</label>
    <input name="user_pass" type="password" placeholder="password" autocomplete="false" value="" />
    </p>
    <p>
    <label for="remerber_me">Remember me</label>
    <input name="remember_me" type="checkbox" autocomplete="false" value="true" />
    </p>

  </div>
  <div class="card_footer_btn">
      <input type="submit" value="Log In"/>
  </div>
  </form>
</div>

<div class="card">
  <div class="card_top">
  <h2 class="form">Sign In</h2>
  </div>
  <div class="card_content">
  <form class="regular" name="Sign_In" method="post" action="http://localhost/CMS-System/test.php" autocomplete="off">
    <p>
    <label for="user_email">email:</label>
    <input name="user_email" type="text" placeholder="email" autocomplete="false" value="" />
    </p>
    <p>
    <label for="user_pass">password:</label>
    <input name="user_pass" type="password" placeholder="password" autocomplete="false" value="" />
    </p>
    <p>
    <label for="user_pass_two">repeat password:</label>
    <input name="user_pass_two" type="password" placeholder="password" autocomplete="false" value="" />
    </p>
  </div>
  <div class="card_footer_btn">
      <input type="submit" value="Sign In"/>
    </div>
  </form>
</div>
