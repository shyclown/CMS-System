/*
* Text Editor
* v. 0.01
*/
var getFirstTextNode = function(oElement){
  while(oElement.firstChild != null){  oElement = oElement.firstChild; }
  return oElement;
}

var getLastTextNode = function(oElement){
  while(oElement.lastChild != null){ oElement = oElement.lastChild; }
  return oElement;
}

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

var hasTextInside = function(oElement)
{
  var oFound = false;
  var isEmpty = function(oElement)
  {
    if(!oFound)
    {
      if( isTextNode(oElement) )
      {
        if (oElement.textContent != '')
        {
          oFound = true;
        }
      }
      else
      {
        var oChildren = oElement.childNodes;
        var nrChildren = oChildren.length;
        for( var i = 0; i < nrChildren; i++)
        {
          isEmpty(oChildren[i]);
        }
      }
    }
  }
  isEmpty(oElement);
  return oFound;
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


var getNextTextSibling = function(oElement, oRoot)
{
    var oElement = oElement;
    while(oElement.nextSibling == null && oRoot.lastChild != oElement){
      oElement = oElement.parentNode;
    }
    if(oRoot.lastChild == oElement){ return false; }
    var textElement = getFirstTextNode(oElement.nextSibling);
    return textElement;
}


var removeElement = function(oElement){
  oElement.parentNode.removeChild(oElement);
}
var removeNextSibling = function(oElement){
  oElement.parentNode.removeChild(oElement.nextSibling);
}

var hasDirectSiblingOfTag= function(oElement, oTagName){
  if( oElement.nextSibling != null && isOfTag( oElement.nextSibling, oTagName ))
  { return true; }
  else
  { return false; }
}
var isOfTag = function( oElement , oTagName){
  if( !isTextNode(oElement) &&
      oElement.tagName.toUpperCase() == oTagName.toUpperCase())
  { return true; }
  else
  { return false; }
}
var isTextNode = function(oElement){  return (oElement.nodeType == 3); }

//
var getTopEmpty = function(oElement,oRoot)
{
  var oFound = false;
  var oParent = oElement;

  while(oFound == false && oParent != oRoot)
  {
    if(!hasTextInside(oParent.parentNode))
    {
      oParent = oParent.parentNode;
    }
    else{
      oFound = true; // do not change parent
    }
  }
  if( oFound ){ return oParent; }
  else { return false; }
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

    if( oNode == start ){
      storeNode = true;
      nodeArray.push(oNode);
    }
    else if( oNode == end ){
      storeNode = false;
      nodeArray.push(oNode);
    }
    else if( storeNode ){
      nodeArray.push(oNode);
    }
  }
  return nodeArray;
};




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
