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
<!-- Angular JS -->
<script src="/js/angular/angular.min.js"></script>
<script src="/js/ng_controllers/myapp.js"></script>
<!-- Google Font Raleway -->
<link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600&subset=latin,latin-ext' rel='stylesheet' type='text/css'>


<style>
html,body{font-family: 'Raleway', sans-serif;}


</style>
</head>
