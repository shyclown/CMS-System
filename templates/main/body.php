<body>
<?php
include "/templates/panel_top.php";
include "/templates/nav_top.php";
?>
<?php
include "/templates/panel_left.php";
?>
<div id="page-content">
<?php
if(isset($_GET['page'])){
  $page = $_GET['page'];
  include '/templates/page-'.$page.'.php';
}

?>



</div>


</body>
