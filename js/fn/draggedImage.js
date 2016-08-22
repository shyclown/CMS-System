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
runAfterLoad.add(oMouse);

var oDragged = {};

class dragItem {

  constructor(editor) {
      this.editor = editor;
      this.addListeners();

  }

  addListeners(){
    this.editor.addEventListener('click',this.leftClickEv.bind(this),false);
    this.editor.addEventListener('contextmenu',this.rightClickEv.bind(this),false);
    this.editor.addEventListener('dragenter',this.dragEnterEv.bind(this),false);
    this.editor.addEventListener('drop',this.dropEv.bind(this),false);
  }
  leftClickEv()
  {
    var position = mousePositionOnPage(event);
    console.log('leftClickEv \n x : '  + position.x +'\n y : '+ position.y);
  }
  rightClickEv()
  {
    //event.preventDefault();
    //alert('rightClickEv');
  }
  dragEnterEv()
  {
    event.preventDefault();
    event.stopPropagation();
  }
  dropEv()
  {
    var self = this;
    event.preventDefault();
    event.stopPropagation();

    var data = event.dataTransfer.files;
    var reader = new FileReader();

    reader.onload = function(readerEvent)
    {
      var dataUrl = self.attachDroppedFile(readerEvent, callback);


      function callback(resultUrl)
      {
      var res_image = new Image();
      res_image.src = resultUrl;
      self.editor.appendChild(res_image);
      console.log(resultUrl);
      }

    }
    reader.onprogress = function(ev){
      console.log(ev.loaded / (ev.total / 100));
    }
    reader.readAsDataURL(data[0]);
  }

  attachDroppedFile(readerEvent, callback)
  {
    // http://www.benknowscode.com/2014/01/resizing-images-in-browser-using-canvas.html
    var callback = callback;
    var dataUrl = false;
    var image = new Image();
    image.src = readerEvent.target.result;

    image.onload = function(imageEvent)
    {
      var canvas = document.createElement('canvas'),
          max_size = 450,
          width = image.width,
          height = image.height;
          console.dir(image);
      if(width > height){
        if (width > max_size) {
          height *= max_size / width;
          width = max_size;
        }
      } else {
          if(height > max_size){
            width *= max_size / height;
            height = max_size;
          }
      }
      canvas.width = width;
      canvas.height = height;
      canvas.getContext('2d').drawImage(image, 0, 0, width, height);
      dataUrl = canvas.toDataURL('image/jpeg');
      callback(dataUrl);
    }
  }
}
