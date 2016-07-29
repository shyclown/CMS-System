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
          };
