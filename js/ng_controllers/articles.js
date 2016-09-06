ngApp.controller('articles', function($scope, $http){

  var targetPHP = '/system/ng/articles.php';

  $scope.articles = [];
  $scope.page = 0;
  $scope.total_articles = 0;
  $scope.per_page = 10;

  function load_many(page, per_page){
    var data = {
      'page' : $scope.page,
      'per_page' : $scope.per_page
    };
    var success = function(response){
      console.log(response.data);
      $scope.articles = response.data;

    };
    var error = function(response){ console.log('error: load_many') };
  $http.post(targetPHP,data).then(success,error);
  }
  load_many();


});
