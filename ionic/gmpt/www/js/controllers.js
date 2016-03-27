angular.module('starter.controllers', [])

.controller('StatsCtrl', function ($scope) {})

.controller('ChatsCtrl', function ($scope, $http, $stateParams, UserInfo, Chats, Debug) {
  

    $http({

        method: "GET",
        url: Debug.getURL("/chat/" + $stateParams.groupID),
        responseType: "json",
        headers: {
          'Content-Type': "json"
        }
      }).then(function successCallback(response) {

        console.log(response.data.messages);
        $scope.messages = response.data.messages;
    
      }, function errorCallback(response) {

        console.log(Debug.getURL("/chat/" + $stateParams.groupID));
        console.log(response);

        alert("Failed to get chat messages, please try again. " + response);

      });

    $scope.remove = function (chat) {
        Chats.remove(chat);
    };

    $scope.report = function(messageID) {
        console.log("Reported message " + messageID);
    };

    $scope.send = function() {

        messageData = { 
          sender: UserInfo.get().user.userName,
          text: $scope.message.text,
          anonymous: $scope.message.anonymous,
          flag: false,
          timeDate: Date.now()
        }

        $http({
            method: "POST",
            url: Debug.getURL("/chat/1"),
            data: messageData,
            contentType:"json"
        }).then(function successCallback(response) {
            console.log("You sent a message!")
        }, function errorCallback(response) {
            alert.log("Message failed. " + response);
        });
    };
})


.controller('LoginCtrl', function ($scope, $state, $http, UserInfo, Debug) {

    $scope.logInfo = {};

    $scope.login = function () {

        console.log("LOGIN user: " + $scope.logInfo.userName + " - PW: " + $scope.logInfo.password);

        $http({
            method: "POST",
            url: Debug.getURL("/login"),
            data: $scope.logInfo
        }).then(function successCallback(response) {
            console.log("You logged in!")
            $state.go("groups");
        }, function errorCallback(response) {
            alert.log("Can't Login");
        });

    }


})


.controller('MeetingsCtrl', function($scope) {
  $scope.settings = {
    enableFriends: true
  }
})

.controller ('GroupsCtrl', function($scope, $http, Groups, Debug) {

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

.controller('AddGroupCtrl', function($scope, $state, $http, Debug) {

    $scope.group = {};

    $scope.search = '';
    $scope.orderByAttribute = '';
    $scope.members = [
    {"_username":"henrysdev","_email":"henrysdev@gmail.com","done":false,"remove":false}
    ];
    $scope.addMember = function()
    {
      console.log("Clicked");
      console.log("username: ", this._username, "email: ", this._email);
      if(this._username !=' ' && this._email !=' ')
      {
        $scope.members.push({'_username':this._username,'_email':this._email,'done':false, 'remove':false});
        this._username = ' ';
        this._email = ' ';
      }
    }
    
    $scope.removeItem = function(index)
    {
      $scope.members.splice(index,1);
      console.log("delete member");
      $scope.members = $scope.members.filter(function(item)
      {
        return !item.done;
      })
    }

  $scope.addGroup = function() {

    console.log($scope.group.groupName);
    
    var group = {
      groupName: $scope.group.groupName,
      description: $scope.group.groupDesc
    }

    $http({
      method:"POST",
      url: Debug.getURL("/groups"),
      data: group
    }).then(function successCallback(response) {
      $state.go("groups");
    }, function errorCallback(response) {
      alert.log("Failed to add group");
    });
  }

});
