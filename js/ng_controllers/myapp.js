var ngApp = angular.module("page",[]);

ngApp.controller('cms', function($scope, $http){
 $scope.leftPanelExpanded = true;
 $scope.userPanelExpanded = false;
 $scope.logPanelExpanded = false;
});

ngApp.directive('card',function(){
  return {
    transclude: true,
    templateUrl: '/templates/directives/card.html'
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
