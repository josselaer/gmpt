angular.module('starter.controllers', [])

.controller('DashCtrl', function ($scope) {})

.controller('ChatsCtrl', function ($scope, Chats) {
    // With the new view caching in Ionic, Controllers are only called
    // when they are recreated or on app start, instead of every page change.
    // To listen for when this page is active (for example, to refresh data),
    // listen for the $ionicView.enter event:
    //
    //$scope.$on('$ionicView.enter', function(e) {
    //});

    $scope.chats = Chats.all();
    $scope.remove = function (chat) {
        Chats.remove(chat);
    };
})

.controller('ChatDetailCtrl', function ($scope, $stateParams, Chats) {
    $scope.chat = Chats.get($stateParams.chatId);
})

.controller('LoginCtrl', function ($scope, $state, $http, Debug) {

    $scope.logInfo = {};

    $scope.login = function () {

        console.log("LOGIN user: " + $scope.logInfo.userName + " - PW: " + $scope.logInfo.password);

        $http({
            method: "POST",
            url: Debug.getURL("/login"),
            data: $scope.logInfo
        }).then(function successCallback(response) {
            console.log("You logged in!")
            $state.go("tab.dash");
        }, function errorCallback(response) {
            alert.log("Can't Login");
        });

    }


})

.controller('AccountCtrl', function ($scope) {
    $scope.settings = {
        enableFriends: true
    };
});
