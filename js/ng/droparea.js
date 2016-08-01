ngApp.directive('droparea', ['$http', function($http){
  return {
    transclude: true,
    scope:{},
    templateUrl: '/templates/directives/droparea.html',
    link: function(scope, element, attrs)
    {
      scope.upload = [];
      scope.hasResults = function(){ return scope.upload.length > 0; }

      var stopDefault = function(){
        event.preventDefault();
        event.stopPropagation();
      }
      element.on('dragover',  stopDefault );
      element.on('dragenter', stopDefault );
      element.on('dragleave', stopDefault );
      element.on('drop', function()
      {
        stopDefault();

        var files = event.dataTransfer.files;
        console.log(files);

        // -- angular doesnt have implemented progress data
        // make ajax function able to send progress data
        var data = new FormData();
        angular.forEach(files, function(value){
          data.append("files[]",value);
        });
        // create solution for old browsers not supporting Form Data 
        $http({
          method: 'POST',
          url: '/system/ng/file.php',
          data: data,
          headers: {'Content-Type': undefined},
          transformRequest: angular.identity
        }).then(
            function(response)
            {
            scope.res = response.data;
            },
            function(response){
            scope.res = response.data;
            }
          );

        var assignIcon = function(file)
        {
          switch( file.type )
          {
            case 'image/jpeg':
            case 'image/gif':
            case 'image/png': file.icon = 'file-image-o'; break;
            case 'application/pdf': file.icon = 'file-pdf-o'; break;
            default: file.icon = 'file-o';
          }
        } // end of FileLine

        for(var i = 0; i < files.length; i++ )
        {
              assignIcon( files[i] );
              scope.upload.push( files[i] );
        }
        scope.$apply();
      });
    }
  }
}]);
