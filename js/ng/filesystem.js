
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

      scope.openFolder;
      scope.openPath = [];

      // @ pseudo
      var find_path = function(){

        var folder = scope.openFolder;
        var parent = folder.parent_id;
        do{
          scope.openPath.push(parent);
          folder = select(parent);
        }
        while(parent = 0);
      }
      var find_children = function(data)
      {
        var folders = data;

        var len = folders.length;
        for (var i=0; i<len ; i++){

          var folder = folders[i];
          var parent = folder.parent_id;

          // search for parent if not root
          if(folder.parent_id != 0){
            for (var j=0; j<len ; j++)
            {
              var parent_f = folders[j];
              parent_f.child_folders = parent_f.child_folders || [];
              if(parent_f.folder_id == parent){
                  parent_f.child_folders.push(folder);
              }
            }
          }
        }
        return folders;
      }
      var find_root = function(){
        var folders = scope.folders;
        var new_folders = [];
        for(i=0;i<folders.length;i++){
          if(folders[i].parent_id==0){
            new_folders.push(folders[i]);
          }
        }
        return new_folders;
      }

      var update = function(data)
      {
        scope.folders = data;
        scope.folders = find_children(scope.folders);
        scope.folders = find_root();
      }
      folderService.loadAllFolders(update);

      var callback_create_folder = function(data)
      {
        if(data){
          scope.new_folder_name = "";
          folderService.loadAllFolders(update);
        }
      }
      scope.create_new_folder = function()
      {
        var folder_name = scope.new_folder_name;
        var parent_id = scope.parent_id;
        folderService.createNewFolder(folder_name,parent_id, callback_create_folder);
      }
    }
  }
}]);

ngApp.directive('folder',['folderService','$compile',function(folderService,$compile){
  return {
  //  scope: {
  //    mainFolders:'=mainData',
  //    folders:'=foldersData',
  //    folder: '=folderData'
  //  },
    templateUrl: '/templates/directives/folder.html',
    link: function(scope, element)
    {
      scope.isOpen = true;
      scope.nextFolders = scope.folders[scope.folders.indexOf(scope.folder)];
      console.log(scope.folders);
      scope.toogleIt = function()
      {
        scope.isOpen = !scope.isOpen;
      }
      scope.remove = function()
      {
        if(folderService.removeFolder(scope.folder.id))
        {
          console.log(scope.folder.id);
          console.log('folder was removed');
        }
        var index = scope.folders.indexOf(scope.folder);
        var children = scope.folder.child_folders;
        if(children.length > 0)
        {
          for( var i = 0; i < children.length; i++ )
          {
            scope.mainFolders.push(children[i]);
          }
        }
        scope.folders.splice(index, 1);
      }
      var child_temp = "<div ng-show='isOpen' class='thechild'><folder  ng-repeat='folder in folder.child_folders' folder-data ='folder' folders-data = 'nextFolders.child_folders' main-data = 'folders'></folder></div>";
      if (angular.isArray(scope.folder.child_folders) && scope.folder.child_folders.length > 0)
      {
        $compile(child_temp)(scope, function(cloned, scope)
        {
          element.append(cloned);
        });
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
