

<style>
.page-wrap {
  padding: 16px 0px;
}
.page-wrap header{
  padding: 0px 16px;
  margin: 0px;
  border-bottom: 1px solid #eaeaea;
}
.page-wrap header > h2{
font-weight: 300;
margin: 0px;
padding: 0px;
font-family: 'Raleway', sans-serif;
}
.page-wrap header > p{
font-weight: 300;
font-size: 14px;
font-family: 'Raleway', sans-serif;
}

</style>
<div class="page-wrap">
  <header>
<h2>My Folders</h2>
<p>here you can setup all page settings</p>
  </header>
<form class="el-regular" name="page-name-form">
<label for="page-name">New Folder Name:</label>
<input name="page-name" type="text" placeholder="Here write name of your page">

<label for="page-keywords">Key Words:</label>
<input name="page-keywords" type="text" placeholder="Here write key words you wish to be associated with your page-content">

<?php
include_once 'system/run/file.php';
?>
</form>
</div>
