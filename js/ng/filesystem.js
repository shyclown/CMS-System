
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
            console.log(response.data)
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
      folderService.createNewFolder = function( folder_name, parent_id, callback ){
        $http.post(targetPHP,
          {
            action:'createNewFolder',
            folder_name: folder_name,
            parent_id: parent_id
          })
          .then(function(response){
            callback(response.data);
            console.log(response.data);
          }, function(response){
            console.log(response.data);
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
      scope.parent_id = 0;
      scope.addFolderInput = false;

      var update = function(data){ scope.folders = data; };
      folderService.loadAllFolders(update);

      var callback_create_folder = function(data){
        if(data){
          scope.new_folder_name = "";
          folderService.loadAllFolders(update); }
      }
      scope.create_new_folder = function(){
        var folder_name = scope.new_folder_name;
        var parent_id = scope.parent_id;
        folderService.createNewFolder(folder_name,parent_id, callback_create_folder);
      }
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
