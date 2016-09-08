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
button.article-list{
  margin: 16px;
  padding: 16px 16px;
  background-color: #009688;
  font-size: 16px;
  color: #FFF;
  border: none;
  outline: none;
  cursor: pointer;
}
button.new-btn{
  background-color: #009688;
}
button.gray-btn{
  color: #666;
  background-color: #EAEAEA;
}
button.new-btn i{
  font-size: 16px;
  margin-right: 4px;
}
button.new-btn:hover{
  background-color: #00897B;
}
.content{
  padding: 0px 16px;
}

.article-line{
  box-sizing: border-box;
  height: 32px;
  line-height: 16px;
  background-color: #FFF;
  border: 1px solid #EAEAEA;
}
  .article-line div.number{
    display: inline-block;
    text-align: center;
    width: 32px;
    padding: 8px;
  }
  .article-line div.article-name{
    display: inline-block;
    padding: 8px 16px;
  }
  .article-line div.line-btns{
    display: block;
    box-sizing: border-box;
    float: right;
    height: 32px;
    padding: 8px 16px;
  }
</style>
<div class="page-wrap">
<header>
<h2>Articles</h2>
<p>here are all created articles</p>
</header>
<button class="article-list new-btn"><i class="fa fa-plus fa-fw"></i>Create new</button>
<button class="article-list gray-btn"><i class="fa fa-th-list fa-fw"></i></button>
<button class="article-list gray-btn"><i class="fa fa-th fa-fw"></i></button>

<div ng-controller="articles" class="content">
  <!-- Loop Trough Articles -->
  <div class="article-line" ng-repeat="article in articles">

    <div class="number">  {{article.article_id}}</div>
    <div class="article-name">{{article.header}}</div>
    <div class="line-btns" ng-click="delete_article()">Delete</div>
  </div>
</div>
</div>
