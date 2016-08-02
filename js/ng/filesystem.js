
ngApp.service(
  'folderService',
  ['$http', function( $http )
    {
      var folderService = this;
      var targetPHP = '/system/ng/folderService.php';

      folderService.loadAllFolders = function(update){
        $http.post(targetPHP,
          { action:'loadAllFolders' })
          .then(function(response){
            console.log(response.data);
            update(response.data);
          }, function(response){
            return false;
          });
      }
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
      scope.files = folderService.loadAllFolders(update);
    }
  }
}]);

ngApp.directive('folders',function(){
  return {
    scope: { folders: '=foldersData'},
    link: function(scope){

    }
  }
});

ngApp.directive('fileitem',function(){
  return {
    scope:{ file: '=fileData' },
    templateUrl: '/templates/directives/fileitem.html',
    link: function(scope){

    }
  }
});
