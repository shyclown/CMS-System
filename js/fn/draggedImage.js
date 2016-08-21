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
