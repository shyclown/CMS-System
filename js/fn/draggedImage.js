console.log('JS FILE LOADED: draggedImage.js');

var mousePositionOnPage = function(event)
{
  var position = {
    x : event.pageX,
    y : event.pageY
  }
  return position;
}

var oMouse = function(){
  console.log( mousePositionOnPage(event) );
}

var reportMousePosition = function()
{
  document.body.addEventListener('click',function(){ oMouse(event); }, false);
}
runAfterLoad(reportMousePosition);
