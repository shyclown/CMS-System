<style>
.page-wrap {
  padding: 0px 0px;
  background-color: #EAEAEA;
}
.page-wrap header{
  padding: 16px 16px;
  margin: 0px;
  background-color: #FFF;
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
form.el-article{
  padding: 0px;
}
form.el-article input[type='text']{
  box-sizing: border-box;
  width: 100%;
}
.article_editor{
  font-family: 'Raleway', sans-serif;
  width: 100%;
}
.article_editor .left_side{
  margin: 0px;
  box-sizing: border-box;
  padding: 0px 16px;
  width: calc(100% - 300px);
  float: left;
}
.article_editor .right_side{
  box-sizing: border-box;
  width: 300px;
  float: right;
}
.article_editor .el_cont_area{
  box-sizing: border-box;
  outline: 1px solid #E0E0E0;
  min-height: 240px;
}
.el_cont_area code.code{
  display: block;
  padding: 16px;
  margin: 16px 0px;
  background-color: #EAEAEA;
  font-size: 14px;
  font-family: monospace;
  overflow-x: scroll;
  white-space: nowrap;
}
.unselectable {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}
.el_cont_area code.code div:nth-child(even){
  padding: 1px;
  background-color: #EAEAEA;
}
.el_cont_area code.code div:nth-child(odd){
    padding: 1px;
  background-color: #E0E0E0;
}
.clr{
  clear: both;
}
</style>
<div class="article_editor page-wrap">
  <header>
    <h2>New Article</h2>
    <p>here you can setup all page settings</p>
  </header>

  <form class="el-regular el-article" id="form_new_article" name="newArticle" method="post" action="/system/ng/articleService.php" autocomplete="off">
    <input name="action" type="hidden"  value="save_new_article" />

  <div class="left_side">
      <p>
      <label for="header">Header:</label>
      <input name="header" type="text" placeholder="Header" autocomplete="false" value="" />
      </p>
      <p>
      <label for="content">Content:</label>
      <input id="manipulate" name="content" type="text" placeholder="Content" autocomplete="false" value="<div>New Content</div><div>Test</div><div>Another Test</div>" />
      </p>
  </div>

  <div class="right_side">
    <label for="content">Date:</label>
    <input id="publish_date" name="publish_date" type="text" autocomplete="false" value="" />
    <div class="card_footer_btn">
        <input type="submit" value="Save New"/>
    </div>
  </div>
<div class="clr"></div>
  </form>
</div>

<script src="/wysiwyg/txt_area.js"></script>
<script src="/wysiwyg/calendar.js"></script>
<script>
new txtArea('form_new_article','manipulate');
new Calendar('publish_date');
</script>
