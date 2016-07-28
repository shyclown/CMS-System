var ngApp = angular.module("page",[]);

ngApp.controller('cms', function($scope, $http){
 $scope.leftPanelExpanded = true;
 $scope.userPanelExpanded = false;
 $scope.logPanelExpanded = false;
});

ngApp.service(
  'folderService',
  ['$http', function( $http )
    {
      var folderService = this;
      folderService.loadContent =  function(update){
        $http.post('/system/ng/folderService.php',
        { data:'loadContent'})
        .then(function(response){
          var data = response.data;
          update(data);
          return response.data;
        }, function(response){
          return response.statusText;
        });
      }
      folderService.something = "something in folder Service";
    }
  ]
);


ngApp.directive('card',function(){
  return {
    transclude: true,
    templateUrl: '/templates/directives/card.html'
  }
});
ngApp.directive('filesystem',['folderService',function(folderService){
  return {
    scope: {},
    templateUrl: '/templates/directives/filesystem.html',
    link: function(scope){
      scope.folder = [
        {name: '1stFile',type:'1stType'},
        {name: '2ndFile',type:'2ndType'}
      ];
      var update = function(data){ scope.folder = data; };
      scope.files = folderService.loadContent(update);
      scope.something = folderService.something;
    }
  }
}]);
ngApp.directive('fileitem',function(){
  return {
    scope:{
      file: '=fileData',
    },
    templateUrl: '/templates/directives/fileitem.html',
    link: function(scope){
    }
  }
});
ngApp.directive('droparea',function(){
  return {
    transclude: true,
    templateUrl: '/templates/directives/droparea.html',
    link: function(scope, element, attrs)
    {
      scope.roman = 'romanVariable';
      scope.tmp_file_array = {};

      var droparea = element[0];
      var btn = angular.element(droparea.querySelector('.custom-file-upload'));

      element.on('dragover',function(){
        event.preventDefault();
        event.stopPropagation();
      });
      element.on('dragenter',function(){
        event.preventDefault();
        event.stopPropagation();
      });
      element.on('dragleave',function(){
        event.preventDefault();
        event.stopPropagation();
      });

      /* --- Drop --- */
      element.on('drop',function()
      {
        event.preventDefault();
        event.stopPropagation();

        // droped files
        var self = this;
        var files = event.dataTransfer.files;

        var FileLine = function(file)
        {
          this.exec = file.name.split('.').pop();
          this.file = file;
          var cls;

          switch(this.exec)
          {
            case 'jpg': cls = 'file-image-o'; break;
            case 'gif': cls = 'file-image-o'; break;
            case 'png': cls = 'file-image-o'; break;
            case 'pdf': cls = 'file-pdf-o'; break;
            default: cls = 'file-o';
          }

          var _new = function(oTag, oClass, oPlacement)
          {
            var el = document.createElement(oTag);
            el.className = oClass;
            if(oPlacement){ oPlacement.appendChild(el); }
            return el;
          };

          this.wrap = _new('div','file-line',false);
          this.type_wrap = _new('span','file-type-wrap',this.wrap);
          this.type_icon = _new('i','fa fa-'+cls+' fa-fw',this.type_wrap);
          this.file_name = _new('span','file-name',this.wrap);
          this.file_name.innerHTML = file.name;
          this.remove_wrap = _new('span','file-remove-wrap',this.wrap);
          this.remove_icon = _new('i','fa fa-close fa-fw',this.remove_wrap);

        //this.remove_wrap.addEventListener('click',removeFromUpload.bind(this),false);

        } // end of FileLine

        function listFiles()
        {
          var results = droparea.getElementsByClassName('results')[0];
          results.innerHTML = '';
          for(var i = 0; i < files.length; i++ )
          {
              var file = files[i];
              file.position = i;
              var file_div = new FileLine(file);
              results.appendChild(file_div.wrap);
          }
        }
        function tmpArrayToFileInput(){
          el = browseInput;
          fileInput.files = scope.tmp_file_array;
        }
        listFiles();
      });
    }
  }
});
        /*

        }
        is_image_type(file){return true}
        is_allowed_size(file){return file.size < max_size }

        uploadFile(file, progress, callback){
            ajax_request(xhr_file_upload,
            { file : file }
            , callback, progress);
        }

        wrap(file){
          var wrap_element = _el('DIV','imagewrap');
          var wrap_message = 'loading image';
          return wrap_element;
        }

        setupReader(file, self)
        {
          var reader = new FileReader();
          var done;

          console.log(file.size);
          var self = this;
          var file = file;

          var wrap_el;
          var progress_bar;
          var image_object;

          wrap_el = self.wrap();
          // create wrap and display status bar
          //wrap_el = _el('DIV','imagewrap');
          progress_bar = _el('DIV','progress');
          wrap_el.appendChild(progress_bar);
          self.log_area.appendChild(wrap_el);

          reader.onloadstart = function()
          {

          }
          reader.onload = function(event)
          {
            var image = self.createImage(event.target.result);
            wrap_el.appendChild(image);
            // after reading
            function callback(msg)
            {
              wrap_el.removeChild(progress_bar);
              var doc = _('php_log');
              doc.innerHTML = msg;

            }
            function progress(percent)
            {
              var percent = Math.floor(percent)*100;
              progress_bar.innerHTML = 'uploaded: '+ percent +'%';
              progress_bar.style.width = percent+'%';

            }
            self.uploadFile(file, progress, callback);
          }

          reader.onprogress = function(event)
          {
            if (event.lengthComputable)
            {
              var percent = (event.loaded/event.total)*100;
              progress_bar.innerHTML = Math.floor(percent) + '%';
              progress_bar.style.width = percent+'%';
              console.log(percent + "%");
            }
          }
          reader.readAsDataURL(file);
          return false;
        }

        createImage(src)
        {
          var img = new Image;
          img.src = src;
          return img;
        }
      }*/
