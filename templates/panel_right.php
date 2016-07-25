<style>
#log-panel{
  display: block;
  position: fixed;
  top: 48px;
  right: 0px;
  z-index: 9999;
  width: 400px;
  height: calc(100% - 48px);
  background-color: #fff;
  border-left: 1px solid  #999;
}
.animate {
  -webkit-transition: all 700ms cubic-bezier(0.470, 0.025, 0.910, 0); /* older webkit */
  -webkit-transition: all 700ms cubic-bezier(0.470, 0.025, 0.910, -0.365);
     -moz-transition: all 700ms cubic-bezier(0.470, 0.025, 0.910, -0.365);
       -o-transition: all 700ms cubic-bezier(0.470, 0.025, 0.910, -0.365);
          transition: all 700ms cubic-bezier(0.470, 0.025, 0.910, -0.365); /* custom */
}
#log{
  position: relative;
  font-size: 12px;
  background-color: #efefef;
  padding: 0px;
}
#log > .one_log{
  padding: 4px 16px;
}
#log > header{
  box-sizing: border-box;
  background-color: #09F;
  padding: 8px 16px;
  height: 32px;
}
#log > header > h2{
  font-size: 16px;
  background-color: #09F;
  padding: 0px 0px;
  margin: 0px 0px;
}
#log > .close{
  position: absolute;
  box-sizing: border-box;
  height: 32px;
  line-height: 16px;
  top: 0px;
  right: 0px;
  font-size: 16px;
  background-color: #999;
  padding: 8px 8px;
  margin: 0px 0px;
}
</style>

<div id="log-panel">
  <div id="log-panel-content">
    <div id="log">
      <header>
      <h2>Log:</h2>
      </header>
      <div class="close" id="log-panel-close-btn">close</div>
    <?php
      $logs = $log->read();
      $logs_size = count($logs);
      for($i = 0;$i < $logs_size; $i++){
        ?>
        <div class="one_log">
        <?php
        echo $logs[$i]['log'];
        ?>
        </div>
        <?php
      }
    ?>
    </div>
  </div>
</div>
