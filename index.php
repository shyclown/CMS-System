<!doctype html>
<?php
include_once 'class_mysqli.php';
include_once 'system/log.php';
$log = new Log;
$log->insert('page_loaded');

?>
<html lang="en">
<?php
// CMS 2016
// version 0.01
include '/templates/main/head.php';
include '/templates/main/body.php';
 ?>


 <script>
 class LogPanel{
   constructor()
   {
     this.log_panel = document.getElementById('log-panel');
     this.panel_width = this.log_panel.offsetWidth;
     this.log_is_open = false;
     this.log_panel.style.right = "-"+this.panel_width+"px";

     this.log_panel_btn = document.getElementById('log-panel-btn');
     this.log_panel_btn.addEventListener('click', this.toogle.bind(this),false);
     this.log_panel_close_btn = document.getElementById('log-panel-close-btn');
     this.log_panel_close_btn.addEventListener('click', this.toogle.bind(this),false);
   }
   log_open(){ this.log_panel.style.right = "0px";}
   log_close(){ this.log_panel.style.right = "-"+this.panel_width+"px";}
   toogle(){
     if(this.log_is_open){ this.log_close() } else { this.log_open() }
     this.log_is_open = !this.log_is_open;
   }
 }
 new LogPanel();
 </script>
</html>
