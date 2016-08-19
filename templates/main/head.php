<head>
<title>CMS</title>
<meta name="description" content="CMS-System">
<meta name="author" content="rm">
<!-- Styles from Style Folder -->
<!--
All CSS files from folder are loaded
-->
<?php
foreach (glob("style/*.css") as $filename){ echo '<link href="'.$filename.'" rel="stylesheet">';}
?>
<script src="/js/_afterLoad.js"></script>
<?php
foreach (glob("js/fn/*.js") as $filename){ echo '<script src="'.$filename.'"></script>';}
?>
<script src="/js/fn.js"></script>
<script src="/js/fn_editor.js"></script>
<script src="/js/fn_images.js"></script>
<!-- Angular JS -->
<script src="/js/angular/angular.min.js"></script>
<script src="/js/angular/angular-sanitize.min.js"></script>
<script src="/js/ng_controllers/myapp.js"></script>

<?php
foreach (glob("js/ng/*.js") as $filename){ echo '<script src="'.$filename.'" ></script>';}
?>
<!-- Google Font Raleway -->
<link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600&subset=latin,latin-ext' rel='stylesheet' type='text/css'>


<style>
html,body{font-family: 'Raleway', sans-serif;}


</style>
</head>
