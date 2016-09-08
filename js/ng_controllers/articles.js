ngApp.controller('articles', function($scope, $http){

  var targetPHP = '/system/ng/articles.php';
  $scope.info = 'info';

  $scope.articles = [];
  $scope.page = 0;
  $scope.total_articles = 0;
  $scope.per_page = 10;

  function load_many(page, per_page){
    var data = {
      'action': 'load',
      'page' : $scope.page,
      'per_page' : $scope.per_page
    };
    var success = function(response){
      $scope.articles = response.data;

    };
    var error = function(response){ console.log('error: load_many') };
  $http.post(targetPHP,data).then(success,error);
  }
  load_many();

  $scope.delete_article = function(id){
    var data = {'action':'delete','article_id':id }
    var success = function(response){ $scope.info = response.data; load_many();}
    var error = function(response){ console.log('error: delete_article');}
    $http.post(targetPHP,data).then(success,error);

  }

  $scope.create_article = function(){
    var data = {'action':'create_new' }
    var success = function(response){ $scope.info = response.data; load_many();}
    var error = function(response){ console.log('error: delete_article');}
    $http.post(targetPHP,data).then(success,error);

  }


});
