ngApp.directive('dropfile',function(){
  return {
    scope:{ file: '=fileData' },
    templateUrl: '/templates/directives/droparea_file.html',
    link: function(scope){
    }
  }
});
