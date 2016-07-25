<body ng-app="page" ng-controller="cms">

<?php
include "/templates/panel_top.php";
include "/templates/panel_user.php";
include "/templates/nav_top.php";
?>
<?php
include "/templates/panel_left.php";
include "/templates/panel_right.php";

?>
<div id="page-content">



<?php
if(isset($_GET['page'])){
  $page = $_GET['page'];
  include '/templates/page-'.$page.'.php';
  $log->insert('page included: /templates/page-'.$page.'.php');
}else{
  include '/templates/page-home.php';
}

?>



</div>


</body>
