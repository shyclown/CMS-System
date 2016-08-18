function _id(id){
  return document.getElementById(id);
}
function _cl(className){
  return document.getElementsByClassName(className);
}

var _new = function(oTag, oClass, oPlacement)
          {
            var el = document.createElement(oTag);
            el.className = oClass;
            if(oPlacement){ oPlacement.appendChild(el); }
            return el;
          }

// return: BOOL



var splitXRange = function(oSelection)
{
    // Grab left and Right side if Exists
    // this gets complicated

    var oRange = oSelection.getRangeAt(0);
    var oElement = oSelection.anchorNode;
    var oTag = getParentInRoot(oSelection.anchorNode);

    function buildElement( oStart, oEnd )
    {
      var range = document.createRange();
      range.setStart( oElement, oStart );
      range.setEnd( oElement, oEnd );
      var content = range.cloneContents();
      var el = document.createElement(oTag);
      el.appendChild(content);
      return el;
    }

    var leftRange = buildElement( 0, oRange.startOffset );
    var rightRange = buildElement( oRange.endOffset, oElement.length );

}
