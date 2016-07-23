<style>

form,h1,h2{
  font-family: sans-serif;
  font-weight: 100;
}

div.card{
  display: block;
  margin: 4px;
  border: 1px solid #999;
  width: 300px;
}
div.card_top{
  padding: 8px;
}
div.card_content{
  padding: 0px 16px;
}
div.card_footer_btn{
  padding: 8px;
  text-align: right;
}

h2.form{
  font-size: 24px;
  font-weight: 100;
  margin: 0px 0px;
  padding: 8px 8px;

}
form.regular{
  box-sizing: border-box;
  display: block;
  width: 100%;
  margin-bottom: 0px;
}
input[type="submit"]{
  padding: 8px 16px;
  border: none;
  background-color: #09F;
}
form.regular > p > label{
  font-size: 12px;
}
form.regular > p > input[type="text"],
form.regular > p > input[type="password"]{
  box-sizing: border-box;
  display: block;
  margin: 4px 0px 8px 0px;
  padding: 8px 4px;
  clear: both;
  width: 100%;
}
form.regular > p > input[type="checkbox"]{

}

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
