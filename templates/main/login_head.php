<head>
<title>CMS</title>
<meta name="description" content="CMS-System">
<meta name="author" content="rm">
<!-- Styles from Style Folder -->
<!--
All CSS files from folder are loaded
-->
<script src="/js/_afterLoad.js"></script>
<!-- Angular JS -->
<script src="/js/angular/angular.min.js"></script>
<script src="/js/angular/angular-sanitize.min.js"></script>
<script src="/js/ng_controllers/myapp.js"></script>
<!-- Google Font Raleway -->
<link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Roboto+Slab&subset=latin-ext" rel="stylesheet">

<style>
html,body{font-family: 'Raleway', sans-serif;}
body{
  width: 640px;
  margin: 0px auto;
}
h1{
    padding-top: 8px;
  font-family: 'Roboto Slab', serif;
  font-size: 34px;
  -webkit-font-smoothing: antialiased;
  border-top: 2px solid #09F;
  font-weight: 500;
}
.flex{
  display: -webkit-flex;
  display: flex;
  justify-content: space-between;

}
.flex-item{
  width: 45%;

}
.flex-item > h1{
  padding-top: 8px;
  font-size: 24px;
}
form{
  padding: 16px;
  border: 1px solid #09F;
}
input{
  box-sizing: border-box;
  width: 100%;
  margin-bottom: 4px;
  padding: 8px 8px;
}
label[for="remember_me"]{
  line-height: 16px;
  font-size: 14px;
}
input[type="checkbox"]{
  box-sizing: border-box;
  width: 12px;
  line-height: 16px;
  vertical-align: middle;
  margin-bottom: 4px;
  padding: 8px 8px;
}
button{
  box-sizing: border-box;
  width: 100%;
  padding: 8px 8px;
  margin-top: 16px;
  background-color: #09F;
  color:white;
  border: none;
}
button[disabled="true"]{
  background-color: #EAEAEA;
  color:#888;
}
.about{

  margin-top: 48px;
}
.about h1{
    border-top: 1px solid #09F;
  padding-top: 8px;
  font-size: 18px;
}
</style>
</head>
