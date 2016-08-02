
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
            update(response.data);
          }, function(response){
            return false;
          });
      }
      folderService.removeFolder = function(folder_id){
        $http.post(targetPHP,
          { action:'removeFolder',
            folder_id: folder_id
          })
          .then(function(response){
            console.log(response);
            return true;
          }, function(response){
            console.log(response);
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
      scope.folders = [];
      var update = function(data){ scope.folders = data; };
      folderService.loadAllFolders(update);
    }
  }
}]);

ngApp.directive('folder',['folderService',function(folderService){
  return {
    scope: {
      folders:'=foldersData',
      folder: '=folderData'
    },
    templateUrl: '/templates/directives/folder.html',
    link: function(scope, element){
      scope.remove = function(){
        var removed = scope.folder.id;
        if(folderService.removeFolder(scope.folder.id)){
                  console.log(removed);
                  console.log('folder was removed');
        };
        var index = scope.folders.indexOf(scope.folder);
        scope.folders.splice(index, 1);
      }
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
