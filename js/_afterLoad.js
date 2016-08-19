console.log('JS FILE LOADED: _afterLoad.js');

window.addEventListener('load',function load(){
  window.removeEventListener("load", load, false); //remove listener, no longer needed
  applyAfterLoad();
},false);

var runAfterLoadList = [];

var runAfterLoad = function(oSomething, oArguments)
{
  toList =
  {
    fn: oSomething, arg: oArguments
  }
  runAfterLoadList.push(toList);
}

var applyAfterLoad = function()
{
  var i = 0;
  var len = runAfterLoadList.length;
  while( i < len )
  {
    runAfterLoadList[i].fn.apply(this, runAfterLoadList[i].arg);
    console.log( 'RUN AFTER LOAD: '+ runAfterLoadList[i].fn.name + '()');
    i++;
  }
}

var log = function(oMessage){  console.log(oMessage); }
runAfterLoad(log,['PAGE LOADED']);
