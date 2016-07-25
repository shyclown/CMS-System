var ngApp = angular.module("page",[]);
ngApp.controller('cms', function($scope){
 $scope.hey = "hi";
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
