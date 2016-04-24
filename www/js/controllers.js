angular.module('app', [])

.controller('RegCtrl', ['$scope', '$location', '$http', function ($scope, $location, $http) {
  $scope.userInfo = {
  	firstName: null,
  	lastName: null,
  	username: null,
  	password: null,
  	email: null,
  	token: null
  }

  console.log($scope.userInfo.token);

  $scope.register = function () {

	$scope.userInfo.token = $location.search().token;

    console.log($scope.userInfo);

    $http({
      method: "POST",
      url: "http://52.37.56.8/gmpt/api/public/update/user",
      data: $scope.userInfo
    }).then(function successCallback(response) {
      console.log(response);
      console.log("Successful Registration. Welcome to gmpt!")
      
    }, function errorCallback(response) {
      alert("Couldn't Register");
    });

  };
}]);