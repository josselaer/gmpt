angular.module('starter.controllers', [])

.controller('StatsCtrl', function ($scope) {})

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

.controller('MessageCtrl', function ($scope, $stateParams, Chats) {
    $scope.chat = Chats.get($stateParams.messageId);
})

.controller('LoginCtrl', function ($scope, $state, $http, Debug, $location) {

    $scope.logInfo = {};

    $scope.login = function () {

        console.log("LOGIN user: " + $scope.logInfo.userName + " - PW: " + $scope.logInfo.password);

        $http({
            method: "POST",
            url: Debug.getURL("/login"),
            data: $scope.logInfo
        }).then(function successCallback(response) {
            console.log("You logged in!")
            console.log(response);
            $state.go("groups");
        }, function errorCallback(response) {
            alert.log("Can't Login");
        });

    }

    $scope.go = function (path) {
        $location.path(path);
    };


})


.controller('MeetingsCtrl', function ($scope) {
    $scope.settings = {
        enableFriends: true
    }
})

.controller('GroupsCtrl', function ($scope, $http, Groups, Debug) {

    $http({

        method: "GET",
        url: Debug.getURL("/groups"),
        responseType: "json"
    }).then(function successCallback(response) {

        console.log(Debug.getURL("/groups"));
        console.log(response);
        groups = response.data;
        console.log(groups);

        Groups.set(groups);
        $scope.groups = groups;

    }, function errorCallback(response) {

        console.log(Debug.getURL("/groups"));
        console.log(response);

        alert("Failed to load groups, please try again.");

        return null;

    });


    $scope.groups = Groups.all();
})

.controller('AddGroupCtrl', function ($scope, $state, $http, Debug) {

    $scope.group = {};

    $scope.search = '';
    $scope.orderByAttribute = '';
    $scope.members = [
        {
            "_username": "henrysdev",
            "_email": "henrysdev@gmail.com",
            "done": false,
            "remove": false
        }
    ];
    $scope.addMember = function () {
        console.log("Clicked");
        console.log("username: ", this._username, "email: ", this._email);
        if (this._username != ' ' && this._email != ' ') {
            $scope.members.push({
                '_username': this._username,
                '_email': this._email,
                'done': false,
                'remove': false
            });
            this._username = ' ';
            this._email = ' ';
        }
    }

    $scope.removeItem = function (index) {
        $scope.members.splice(index, 1);
        console.log("delete member");
        $scope.members = $scope.members.filter(function (item) {
            return !item.done;
        })
    }

    $scope.addGroup = function () {

        console.log($scope.group.groupName);

        var group = {
            groupName: $scope.group.groupName,
            description: $scope.group.groupDesc
        }

        $http({
            method: "POST",
            url: Debug.getURL("/groups"),
            data: group
        }).then(function successCallback(response) {
            $state.go("groups");
        }, function errorCallback(response) {
            alert.log("Failed to add group");
        });
    }
})


.controller('RegisterCtrl', function ($scope, $state, $http, Debug) {

    $scope.regInfo = {};

    $scope.register = function () {

        console.log("UserName: " + $scope.regInfo.userName + " First Name: " + $scope.regInfo.firstName + " Last Name: " + $scope.regInfo.lastName + " Password: " + $scope.regInfo.password + " E-mail: " + $scope.regInfo.email);
        
        console.log($scope.regInfo);

        $http({
            method: "POST",
            url:"http://localhost:8100/api",
            data: $scope.regInfo
        }).then(function successCallback(response) {
            console.log("Successful Registration. Welcome to gmpt!")
            $state.go("login");
        }, function errorCallback(response) {
            alert.log("Couldn't Register");
        });

    }
});
