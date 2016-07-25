<style>
#panel-top{
  display: block;
  box-sizing: border-box;
  position: fixed;
  top: 0px;
  z-index: 9999;
  width: 100%;
  height: 48px;
  overflow: hidden;
  background-color: #FFF;
  border-bottom: 1px solid #999;
}
#panel-top ul,li{
  padding: 0px 0px;
  margin: 0px 0px;
}
#panel-top li{
  border-bottom: 1px solid #777;
}
#panel-top a{
  display: block;
  padding: 8px 16px;
  line-height: 16px;
}
#panel-top a:hover{
  background-color: #07F;
}

#userTopBtn{
  position: absolute;
  top: 0px;
  right: 0px;
}
#userTopBtn > button{
  box-sizing: border-box;
  border: none;
  height: 48px;
  width: 100px;
  background-color: #666;
  color: #FFF;
  text-transform: uppercase;


}
.menuBtn{
  height: 48px;
  width: 48px;
  background-color: #666;
  border: none;
}
</style>


<div id="panel-top">
  <header>
    <button class="menuBtn" ng-click="leftPanelExpanded = !leftPanelExpanded"><i class="fa fa-navicon fa-lg"></i></button>
  <h1>CMS</h1>
  <span>This is CMS Page</span>
  </header>

  <div id="userTopBtn"><button ng-click="userPanelExpanded = !userPanelExpanded"><i class="fa fa-user"></i> Login</button></div>
</div>
