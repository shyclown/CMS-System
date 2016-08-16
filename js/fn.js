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


var getParentInRoot = function(oElement,oRoot){
  while(oElement.parentNode != oRoot ){
    oElement = oElement.parentNode;
  }
  return oElement;
}

var inArray = function(oArray,oString){
  return oArray.indexOf(oString) > -1;
}

var getElementsInSelection = function(range, set_root){

  var root = range.commonAncestorContainer;
  if(set_root){ root = set_root; }

  var start = getParentInRoot(range.startContainer, root);
  var end = getParentInRoot(range.endContainer, root);

  var rootNodes = root.children;
  var storeNode = false;
  var nodeArray = [];

  for( var i = 0, len = rootNodes.length; i < len ; i++)
  {
    var oNode = rootNodes[i];

    if( oNode == start )
    {
      storeNode = true;
      nodeArray.push(oNode);
    }
    else if( oNode == end )
    {
      storeNode = false;
      nodeArray.push(oNode);
    }
    else if( storeNode )
    {
      nodeArray.push(oNode);
    }
  }
  return nodeArray;
};



var deleteAnchorRange = function( oElement,oOffset ){
  var range = document.createRange();
  range.setStart(oElement, oOffset);
  range.setEnd(oElement, oElement.length);
  range.deleteContents();
}
var deleteFocusRange = function( oElement,oOffset ){
  var range = document.createRange();
  range.setStart(oElement, 0);
  range.setEnd(oElement, oOffset);
  range.deleteContents();
}

var getPreviousTextSibling = function(oElement,oRoot)
{
  var oElement = oElement;
  while(oElement.previousSibling == null && oRoot.firstChild != oElement){
    oElement = oElement.parentNode;
  }
  if(oRoot.firstChild == oElement){ return false; }
  return getLastTextNode(oElement.previousSibling);
}

var getFirstTextNode = function(oElement){
  while(oElement.firstChild != null){
    oElement = oElement.firstChild;
  }
  return oElement;
}
var getLastTextNode = function(oElement){
  while(oElement.lastChild != null){
    oElement = oElement.lastChild;
  }
  return oElement;
}

var getParentOfEmpty = function(oElement){

}

// return: BOOL
var hasTextInside = function(oElement){
  var foundText = false;

  function findText( node ){
    if( node.nodeType === 3 ) {
      if( !node.textContent == ''){ foundText = true; }
    }
    else{
      var oChildren = node.childNodes;
      var nrChildren = oChildren.length;
      var i = 0;
      while( !foundText && i < nrChildren ){
        findText( oChildren[i] );
      }
    }
  }
  findText(oElement); return foundText;
}

var deleteRangeElements = function(oSelection,oRoot)
{
  var oRange = oSelection.getRangeAt(0);

  if(oRange.startContainer == oRange.endContainer){
    oRange.deleteContents();
  }
  else
  {
    var oElements = getElementsInSelection(oRange,oRoot);

    for( var i = 0, len = oElements.length; i < len ; i++ )
    {
      var oElement = oElements[i];
      if( oElement.className && oElement.className == 'code'){ }
      else
      {
        if( i == 0 ){
          deleteAnchorRange(oRange.startContainer, oRange.startOffset);
        }
        else if( i == oElements.length - 1 ){
          deleteFocusRange(oRange.endContainer, oRange.endOffset);
        }
        else{
          // remove whole element
          oRoot.removeChild(oElement);
        }
      }
    }
  }
}
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
