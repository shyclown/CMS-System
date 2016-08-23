var Editor = Editor || {};

Editor.mouse = function(editor)
{
  this.content_area = editor;
  this.element_under;
  this.isDragging = false;
  this.draggingItem; // stored event function
  this.attachEvents();
  console.log(this);
}

Editor.mouse.prototype.attachEvents = function(){
    this.content_area.addEventListener('click', this.leftClick.bind(this),false);
    this.content_area.addEventListener('contextmenu', this.rightClick.bind(this),false);
    this.content_area.addEventListener('dragenter', this.dragEnter.bind(this),false);
    this.content_area.addEventListener('drop', this.drop.bind(this),false);

    this.content_area.addEventListener('dragstart', this.dragStart.bind(this),false);
}
Editor.mouse.prototype.removeDefault = function(event){
  event.preventDefault();
  event.stopPropagation();
}

Editor.mouse.prototype.getPosition = function(event){
    return { x : event.pageX,  y : event.pageY };
}

Editor.mouse.prototype.leftClick = function(event){
  console.log(this.getPosition(event));
  console.log(this.elementUnderMouse(event).tagName);
}

Editor.mouse.prototype.rightClick = function(event){}

Editor.mouse.prototype.dragStart = function(event){
  console.log("dragstart");
}

Editor.mouse.prototype.dragEnter = function(event){
  this.removeDefault(event);
  if(!this.isDragging){
    console.log('dragEnter');
    console.log(this);
    this.dragingItem = this.draging.bind(this);
    this.content_area.addEventListener('mousemove', this.dragingItem ,false);
    this.isDragging = true;
  }
  var oElement = this.elementUnderMouse(event);
  if(this.element_under != oElement){
    this.element_under = oElement;
    console.log(this.element_under.tagName);
  }
}

Editor.mouse.prototype.draging = function(event){
  this.removeDefault(event);
}
Editor.mouse.prototype.drop = function(event){
  var self = this;
  self.removeDefault(event);

  // prepare for next drag
  self.isDragging = false;
  self.content_area.removeEventListener('mousemove', self.dragingItem ,false);
  //

  var data = event.dataTransfer.files;
  var reader = new FileReader();

  reader.onload = function(readerEvent){
    var dataUrl = self.resizeDropped(readerEvent, callback);
    function callback(resultUrl)
    {
      oData = {
        action : 'upload',
        image : resultUrl
      }
      var uploadProgress = function(percent){ console.log(percent*100); }
      var callbackAjax = function(response){
        console.log(response);
        var res_image = new Image();
        res_image.src = response;
        self.content_area.appendChild(res_image);
      }
      new Editor.ajax('system/run/upload_image.php', oData, uploadProgress, callbackAjax);
    }
  }
  reader.onprogress = function(ev){
    console.log(ev.loaded / (ev.total / 100));
  }
  reader.readAsDataURL(data[0]);
}

Editor.mouse.prototype.resizeDropped = function(readerEvent, callback, size){
  var callback = callback;
  var dataUrl = false;
  var image = new Image();
  image.src = readerEvent.target.result;

  image.onload = function(imageEvent){
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
    dataUrl = canvas.toDataURL('image/png');
    callback(dataUrl);
  }// image.onload
}// mouse.resizeDropped

Editor.mouse.prototype.elementUnderMouse = function(event){
  var pos = this.getPosition(event);
  return document.elementFromPoint( pos.x, pos.y );
}
