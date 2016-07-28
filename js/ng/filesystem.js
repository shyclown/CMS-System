
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
    }
  ]
);

ngApp.directive('filesystem',['folderService',function(folderService){
  return {
    scope: {},
    templateUrl: '/templates/directives/filesystem.html',
    link: function(scope){
      scope.folder = [];
      var update = function(data){ scope.folder = data; };
      scope.files = folderService.loadContent(update);
    }
  }
}]);
ngApp.directive('fileitem',function(){
  return {
    scope:{ file: '=fileData' },
    templateUrl: '/templates/directives/fileitem.html',
    link: function(scope){
    }
  }
});
