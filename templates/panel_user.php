<div class="el-panel-user" ng-show="userPanelExpanded">
  <div class="content">
    <div class="image">image</div>
    <div class="username"><b><?php echo $account->nicename ?></b></div>

    <div>
      <button class="el-panel-btn">
        <i class="fa fa-edit fa-fw"></i>edit account</button>

      <form action="/system/el/log_out.php" method="post">
        <button class="el-panel-btn" type="submit">
          <i class="fa fa-sign-out fa-fw"></i>Log out</button>
      </form>
      </div>

  </div>
</div>
