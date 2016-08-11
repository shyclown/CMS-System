<style>

</style>
<div class="card">
  <div class="card_top">
  <h2 class="form">New Article</h2>
  </div>
  <div class="card_content">

  <form class="el-regular" id="form_new_article" name="newArticle" method="post" action="/system/ng/articleService.php" autocomplete="off">
    <input name="action" type="hidden"  value="save_new_article" />
    <p>
    <label for="header">Header:</label>
    <input name="header" type="text" placeholder="Header" autocomplete="false" value="" />
    </p>
    <p>
    <label for="content">Content:</label>
    <input id="manipulate" name="content" type="text" placeholder="Content" autocomplete="false" value="" />
    </p>

  </div>
  <div class="card_footer_btn">
      <input type="submit" value="Save New"/>
  </div>
  </form>
</div>

<script src="/wysiwyg/txt_area.js"></script>
<script>new txtArea('form_new_article','manipulate');</script>
