angular.module('starter.controllers', [])

.controller('AccountCtrl', function($scope, $state, $http, UserInfo, Debug) {

  $scope.logout = function () {
    $http({
      method: "GET",
      url:Debug.getURL("/logout"),
   headers: {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }    
    }).then(function successCallback(response) {
      console.log("Logging out. See you later!")
      $state.go("login");
    }, function errorCallback(response) {
      alert.log("Can't logout. You can never leave!");
        console.error;
    });
  };
})

.controller('TabCtrl', function($scope, $http, UserInfo, Debug) {

  $scope.$on ("$ionicView.enter", function() {

    $scope.groupID = 0;
    $scope.groupID = UserInfo.getActiveGroup();
    $scope.notifications = {};

    $http({
      method: "GET",
      url: Debug.getURL("/notifications/" + $scope.groupID),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': UserInfo.getAuthToken()
      }
    }).then(function successCallback(response) {

      console.log("Tab GET Notifications:");
      console.log(response);
      return response;

    }, function errorCallback(response) {

      return null;

    }).then(function(response) {

      var not = response.data.notifications;

      if (not.Meeting > 0) {
        $scope.notifications.Meeting = not.Meeting;
      }

      if (not.Message > 0) {
        $scope.notifications.Message = not.Message;
      }

    }); 

  });

})


.controller('StatsCtrl', function ($http, $scope, $stateParams, UserInfo, Debug) {

  $scope.stats  = {};

  $scope.$on("$ionicView.enter", function() {

    $http({
        method: "GET",
        url: Debug.getURL("/statistics/totals/" + $stateParams.groupID),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': UserInfo.getAuthToken()
        }
      }).then(function successCallback(response) {

        return response.data;

      }, function failureCallback(response) {

        console.log("Failed to get statistics.");
        console.log(response);

      }).then(function(response) {

        $scope.stats = response.Totals;

      });

  });

})


.controller('ChatsCtrl', function ($scope, $http, $stateParams, $interval, $animate, 
                                    UserInfo, Chats, GroupID, Debug) {

  GroupID.set($stateParams.groupID);
  $scope.chatsctrl = {};
  $scope.messages = [];

  $scope.chatsctrl.anonymous = false;

  $interval(function getMessages() {
    
    Chats.getGroupMessages($stateParams.groupID).then(function success(response) {

        $animate.enabled(false);
        $scope.messages = [];
        $scope.messages = response;

        $animate.enabled(true);
      }, function error(response) {
        console.log("Error");
      });

  }, 3000);

  $scope.$on("$ionicView.enter", function() {

      Chats.getGroupMessages($stateParams.groupID).then(function successCallback(response) {

      $scope.messages = response;
      
    }, function errorCallback(response) {

      console.log(Debug.getURL("/chat/" + $stateParams.groupID));
      console.log(response);

      alert("Failed to get chat messages, please try again. " + response);

    });
  });

  $scope.chatsctrl.toggleAnonymous = function() {
    if ($scope.chatsctrl.anonymous == true) {
      $scope.chatsctrl.anonymous = false;
    }
    else {
      $scope.chatsctrl.anonymous = true;
    }
  }

  $scope.chatsctrl.remove = function (chat) {
    Chats.remove(chat);
  };

  $scope.chatsctrl.report = function(messageID) {
    //console.log("Reported message " + messageID);
  };

  $scope.chatsctrl.send = function() {

    var m = { 
      //sender: UserInfo.get().user.userName,
      text: $scope.message.text,
      anonymous: $scope.chatsctrl.anonymous
    };

    Chats.sendMessage(JSON.stringify(m), $stateParams.groupID).then( function() {
      Chats.getGroupMessages($stateParams.groupID).then( function(response) {
        $scope.messages = response;
      }, function(response) {
        console.log("Error");
      });
      $scope.message.text = "";
    }, function() {
      console.log("Error in sending message");
    });
  };
})

.controller('LoginCtrl', function ($scope, $state, $http, UserInfo, Debug, $location) {

  $scope.logInfo = {};

  $scope.login = function () {

    $http({
      method: "POST",
      url:Debug.getURL("/login"),
      data: $scope.logInfo
    }).then(function successCallback(response) {
      return response;
    }, function errorCallback(response) {
      alert.log("Can't Login");
    }).then(function redirect(response) {

      if (UserInfo.login(response.data)) {
        console.log("You logged in!")
      
        $state.go("menu.groups");
      }
      else {
        alert("Could not log in!" + JSON.stringify(response.data.userData));
      }
    });

  }

  $scope.go = function (path) {
    $location.path(path);
  };


})

.controller('MeetingsCtrl', function($scope, $state, $http, $stateParams, UserInfo, Meetings, GroupID, Debug) {

  $scope.meetings = [];

  $scope.$on("$ionicView.enter", function() {

    $http({

      method: "GET",
      url: Debug.getURL("/meetings/" + GroupID.get()),
      responseType: "json",
      headers: {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }
    }).then(function successCallback(response) {

      console.log("Meetings for group " + GroupID.get() +": ");
      console.log(response.data);

      return response.data;

    }, function errorCallback(response) {

      console.log("ERROR CALLBACK");
      console.log(Debug.getURL("/meetings"));
      console.log(response);

      alert("Failed to load groups, please try again.");

    }).then(function(response) {

      Meetings.set(response);
      $scope.meetings = Meetings.all();
      this.meetings = Meetings.all();

    });
    $scope.Meetings = Meetings.all();
  });

  $scope.Meetings = Meetings.all();
  $scope.meetingDetails = function(index)
  {

    Meetings.setCurr(index);
    $scope.currentMeeting = Meetings.get(Meetings.getCurr());
  }

  $scope.currentMeeting = function()
  {
    return Meetings.get(Meetings.getCurr());
  }

  $scope.confirmMeeting = function()
  {  
    if(this.date != "" && this.startTime != "" && this.topic != "" && this.meetingDescription != "")
    {
      if(Meetings.getEdit() == false)
      {
        $scope.meetings.push({'date':this.date,'startTime':this.startTime,'topic':this.topic,'meetingDescription':this.meetingDescription, 'ProjectID':GroupID.get()});
        Meetings.set($scope.meetings);
      }
      else if(Meetings.getEdit() == true)
      {     
        $scope.meetings[Meetings.getCurr()].topic = this.topic;
        $scope.meetings[Meetings.getCurr()].date = this.date;
        $scope.meetings[Meetings.getCurr()].startTime = this.startTime;
        $scope.meetings[Meetings.getCurr()].meetingDescription = this.meetingDescription;
        Meetings.set($scope.meetings);
      }
      var meeting = 
        {
          //GroupName = "TEMPORARY_VAR";
          ProjectID : GroupID.get(),
          MeetingDate : this.date,
          StartTime : this.StartTime,
          MeetingDescription : this.MeetingDescription
          //EndTime = "2:30 PM";
        }

        $http({
      method: "POST",
      url: Debug.getURL("/meetings"),
      data: meeting,
      headers: {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }
    }).then(function successCallback(response) {

      return response;
    }, function errorCallback(response) {
      console.log("Add meeting 'fail': ");
      console.log(response);
      alert("Failed to add meeting");
      return null;
    }).then(function redirect(response) {
      console.log("redirecting...");
      console.log(response);
      //$state.go("groups");
    });
      }
      this.date = "";
      this.startTime = "";
      this.topic = "";
      this.meetingDescription = "";

}

$scope.editMeeting = function(index)
{
  Meetings.setEdit(true);
  Meetings.setCurr(index);
  $scope.currentMeeting = Meetings.get(Meetings.getCurr());
}

$scope.newMeeting = function()
{
  Meetings.setEdit(false);
  Meetings.setCurr(0);
  //$scope.currentMeeting = Meetings.get(Meetings.getCurr());
}
})

.controller('AddMeetingCtrl', function($scope, $state, $http, Debug) 
{

})

.controller('GroupsCtrl', function ($scope, $http, UserInfo, Groups, Debug) {

  $scope.userName = UserInfo.get().userName;

  $scope.$on("$ionicView.enter", function() {
    $http({

        method: "GET",
        url: Debug.getURL("/projects"),
    //    responseType: "json",
        headers: {
          "Content-Type": "application/json",
          "Authorization": UserInfo.getAuthToken()
        }
      }).then(function successCallback(response) {

        console.log("Get projects with auth: " + UserInfo.getAuthToken());
        console.log(response.data);

        Groups.set(response.data.projects);
        $scope.groups = response.data.projects;

      }, function errorCallback(response) {

        console.log(Debug.getURL("/projects"));
        console.log(response);

        alert("Failed to load groups, please try again.");

        return null;

    });

  }); 

    $scope.setGroup = function(id) {
    UserInfo.setActiveGroup(id);

  }
  


  $scope.groups = Groups.all();
})

.controller('AddGroupCtrl', function ($scope, $ionicConfig, $state, $http, UserInfo, Debug) {

  $scope.group = {};

  $scope.search = '';
  $scope.orderByAttribute = '';
  $scope.members = [];



  $scope.addMember = function () {

    if ($scope.email != ' ') {
      $scope.members.push({
        'email': $scope.email,
        'isProfessor': this.isProfessor
      });
      $scope.email = ' ';
      $scope.isProfessor = false;
    }
  }

  $scope.removeItem = function (index) {
    $scope.members.splice(index, 1);
    $scope.members = $scope.members.filter(function (item) {
      return !item.done;
    })
  }

  $scope.addGroup = function () {

    var group = {
      groupName: $scope.group.groupName,
      projDescription: $scope.group.groupDesc,
      users: $scope.members
    }

    $http({
      method: "POST",
      url: Debug.getURL("/projects"),
      data: group,
      headers: {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }
    }).then(function successCallback(response) {

      return response;

    }, function errorCallback(response) {
      console.log("Add group 'fail': ");
      console.log(response);
      alert("Failed to add group");
      return null;
    }).then(function redirect(response) {

      $state.go("menu.groups");
    });
  }
  $scope.autoCompleteUpdate = function(input)
  {

    var input_data = 
    {
      term: input
    }
    var success = false;
    $scope.input_suggestions = [];
    $http(
    {
      method: "POST",
      url: Debug.getURL("/autocomplete"),
      data: input_data,
      headers: 
      {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }
    }).then(function successCallback(response) 
    {
      success = true;
      return response;
    }, function errorCallback(response) 
    {
      console.log("auto complete 'fail': ");
      console.log(response);
      alert("Failed to post autocomplete");
      return null;
    }).then(function redirect(response) 
    {

      $scope.input_suggestions = response.data.suggestions;
      console.log("Input suggestions: " , $scope.input_suggestions);
    });
  }

  $scope.selectEmail = function(selected_email)
  {
    $scope.email = selected_email;
    document.getElementById('email_input').value = selected_email.suggestion;
    $scope.email = selected_email.suggestion;
  }
})


.controller('RegisterCtrl', function ($scope, $state, $http, Debug) {

  $scope.regInfo = {};

  $scope.register = function () {

    $http({
      method: "POST",
      url:Debug.getURL("/user"),
      data: $scope.regInfo
    }).then(function successCallback(response) {
      console.log("Successful Registration. Welcome to gmpt!")
      $state.go("login");
    }, function errorCallback(response) {
      alert.log("Couldn't Register");
    });

  }
})


.controller('LogoutCtrl', function ($scope, $state, $http, UserInfo, Debug) {

  $scope.logout = function () {
    $http({
      method: "GET",
      url:Debug.getURL("/logout"),
   headers: {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }    
    }).then(function successCallback(response) {
      console.log("Logging out. See you later!")
      $state.go("login");
    }, function errorCallback(response) {
      alert.log("Can't logout. You can never leave!");
        console.error;
    });

  }
})

.controller('MenuCtrl', function ($scope, $ionicSideMenuDelegate) {
    $scope.toggleLeftSideMenu = function() {
      $ionicSideMenuDelegate.toggleLeft();
   };
})
