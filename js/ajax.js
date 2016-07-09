var ajax_request = function(target, data, callback){

  var request = new XMLHttpRequest();
  request.addEventListener('load',function(){
    callback(request.responseText);
  });
  request.open("POST", target);
  request.send(create_form(data));
}

var create_form = function(oArray){

  var oForm = new FormData();
  for(oKey in oArray){
    oForm.append(oKey,oArray[oKey]);
  }
  return oForm
}

var ajax_request_form = function(target, form, callback){

  var request = new XMLHttpRequest();
  request.addEventListener('load',function(){
    callback(request.responseText);
  });
  data = new FormData(form);
  request.open("POST", target);
  request.send(data);
}


var array_from_form = function(id){

  var oArray = {};
  var oForm = document.getElementById(id);
  var oInputs = oForm.getElementsByTagName('INPUT');

  for(i=0,len = oInputs.length; i < len; i++)
  {
    var oInput = oInputs[i];

    if( oInput.type != 'submit' || oInput.type == 'button')
    {
      oArray[oInput.name] = oInput.value;
    }
  }
  return oArray;
}
