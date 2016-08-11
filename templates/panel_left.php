<style>
#left-panel{
  display: block;
  position: fixed;
  top: 48px;
  z-index: 9999;
  width: 200px;
  height: calc(100% - 48px);
  background-color: #fff;
    border-right: 1px solid  #999;
}
#left-panel ul,li{
  padding: 0px 0px;
  margin: 0px 0px;
}
#left-panel li{
  border-bottom: 1px solid #e2e2e2;
}
#left-panel a{
  display: block;
  padding: 8px 16px;
  line-height: 16px;
  font-size: 14px;
  text-decoration: none;
  color: #666;
}
#left-panel a:hover{
  background-color: #666;
  color: #FFF;
}
</style>

<div id="left-panel" ng-show="leftPanelExpanded">
  <div id="left-panel-content">
    <ul>
      <li><a href="?page=settings"><i class="fa fa-cog fa-fw"></i>  Settings</a></li>
      <li><a ng-click="logPanelExpanded = !logPanelExpanded" href="#"><i class="fa fa-database fa-fw"></i>  Log Panel</a></li>
      <li><a href="#"><i class="fa fa-archive fa-fw"></i>   Archive</a></li>
      <li><a href="?page=folders"><i class="fa fa-folder fa-fw"></i>  Folders</a></li>
      <li><a href="?page=login"><i class="fa fa-unlock fa-fw"></i> Login</a></li>
      <li><a href="?page=account"><i class="fa fa-user fa-fw"></i> My Account</a></li>
      <li><a href="?page=new-article"><i class="fa fa-folder fa-fw"></i>  New Article</a></li>

    </ul>
  </div>
</div>
