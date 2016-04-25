angular.module('starter.controllers', [])

.controller('SettingsCtrl', function($scope, $state, $stateParams, $http, UserInfo, Debug) {

  $scope.isProfessor = false;
  $scope.email = "";

  $scope.addMember = function () 
  {

    var payload = {
      ProjectID: $stateParams.groupID,
      user: {
        email: $scope.email, 
        isProfessor: $scope.isProfessor
      }
    };

    console.log(payload);

    $http({
      method: "POST",
      url: Debug.getURL("/projects/add"),
      data: payload,
      headers: {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }
    }).then(function successCallback(response) {
      console.log("Add member success: ");
      console.log(response);

    }, function errorCallback(response) {
      console.log("Failed adding member: ");
      console.log(response);

    }).then(function (response) {

      $scope.isProfessor = false;
      $scope.email = "";
      document.getElementById('email_input').value = "";

    });
    
  }

  $scope.autoCompleteMeetingUpdate = function(input)
  {
    if(input && input.length >= 3) 
    {
      this.show_suggestions = true;
      //document.getElementById('autocomplete_list').style.visibility = "visible";
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
        console.log(response);
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
        console.log("redirecting...");
        console.log(response);
        $scope.input_suggestions = response.data.suggestions;
        console.log("Input suggestions: " , $scope.input_suggestions);
      });
    }
    else
    {
      this.show_suggestions = false;
    }
  }

  $scope.selectEmail = function(selected_email)
  {
    $scope.email = selected_email;
    document.getElementById('email_input').value = selected_email.suggestion;
    $scope.email = selected_email.suggestion;
  }

})

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
    $scope.isProf = false;
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

.controller('StatsCtrl', function ($http, $scope, $stateParams, $filter, UserInfo, Debug) {

  $scope.stats  = {};
  $scope.showMemberStats = false;
  $scope.isProf = UserInfo.isProf();

  $scope.sort = "Asc";
  $scope.sortby = "attendanceRate";


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

        console.log($scope.stats);

        $http({
          method: "GET",
          url : Debug.getURL("/statistics/attendanceRate/" + $stateParams.groupID),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': UserInfo.getAuthToken()
          }
        }).then(function successCallback(response) {

          console.log(response);
          return response.data;

        }, function failureCallback(response) {
          //alert("Could not get member statistics");
        }).then(function (response) {

          if (response.attendanceRate.length > 0) {
            $scope.stats.attRate = response.attendanceRate;

            for (var i = 0; i < $scope.stats.attRate.length; i++) {
              $scope.stats.attRate[i].attendanceRate = Math.floor($scope.stats.attRate[i].attendanceRate);
            }
            $scope.showMemberStats = true;
          }

          console.log($scope.stats.attRate);
        });

      });

    $scope.toggleSort = function() {
      if ($scope.sort == "Asc") {
        $filter('orderBy')($scope.stats.attRate, $scope.sortby, false);

        $scope.sort = "Desc";
      }
      else {
        $filter('orderBy')($scope.stats.attRate, $scope.sortby, true);
        $scope.sort = "Asc";
      }
    };

  });
})


.controller('ChatsCtrl', function ($scope, $http, $stateParams, $interval, $animate, 
  UserInfo, Chats, GroupID, Debug) {

  GroupID.set($stateParams.groupID);
  $scope.chatsctrl = {};
  $scope.messages = [];
  $scope.readReceipts = {};
  $scope.isProf = UserInfo.isProf();
  $scope.chatsctrl.anonymous = false;

  $scope.message = {};

  var chatRefresh = $interval(function getMessages() {

    Chats.getGroupMessages($stateParams.groupID).then(function success(response) {

      $animate.enabled(false);
      console.log("Messages: ");
      console.log(response);
      $scope.messages = response.messages;
      $scope.readReceipts = response.readReceipts;

      $animate.enabled(true);

    }, function error(response) {
      console.log("Error");
    });

  }, 3000);

  $scope.$on("$ionicView.enter", function() {

    Chats.getGroupMessages($stateParams.groupID).then(function successCallback(response) {
      console.log(response);
      $scope.messages = response.messages;
      $scope.readReceipts = response.readReceipts;
      
    }, function errorCallback(response) {

      console.log(Debug.getURL("/chat/" + $stateParams.groupID));
      console.log(response);

      alert("Failed to get chat messages, please try again. " + response);

    });
  });

  $scope.$on("$ionicView.leave", function() {

    console.log("canceling");
    $interval.cancel(chatRefresh);

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

    console.log($scope.message.text);
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
      $scope.chatsctrl.text = "";
    }, function() {
      console.log("Error in sending message");
    });
  };
})

.controller('LoginCtrl', function ($scope, $state, $http, UserInfo, Debug, $location) {

  $scope.logInfo = {};

  $scope.loginFail = false;

  $scope.login = function () {
    var data = $scope.logInfo;

    $http({
      method: "POST",
      url:Debug.getURL("/login"),
      data: data
    }).then(function successCallback(response) {
      return response;
    }, function errorCallback(response) {
      alert("Can't Login" +  JSON.stringify(response));
    }).then(function redirect(response) {

      if (UserInfo.login(response.data)) {

        $scope.go("/groups");
      }
      else {
        $scope.loginFail = true;
      }
    });

  }
  $scope.go = function (path) {
    $location.path(path);
  };
})


.controller('MeetingsCtrl', function($scope, $state, $http, $stateParams, UserInfo, TempEditStorage, Meetings, GroupID, RevertTime, Debug, ionicDatePicker, CalculateTime) {

  $scope.RevertTime = RevertTime;



  $scope.meetings = [];
  $scope.isProf = UserInfo.isProf();




  var datePickerObj = {
      callback: function (val) {  //Mandatory
        console.log('Return value from the datepicker popup is : ' + val, new Date(val));
        $scope.meetingDate = new Date(val);
      },
      from: new Date(), //Optional
      to: new Date(2022, 10, 30), //Optional
      inputDate: new Date(),      //Optional
      mondayFirst: false,          //Optional
      closeOnSelect: false,       //Optional
      templateType: 'popup'       //Optional
    };

    $scope.openPicker = function() {

      ionicDatePicker.openDatePicker(datePickerObj);
    }

    $scope.$on("$ionicView.enter", function() {
      console.log("YOOOOOOOOOOŒ");
      Meetings.setEdit(false);
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

        //console.log("ERROR CALLBACK");
        //console.log(Debug.getURL("/meetings"));
        //console.log(response);

        alert("Failed to load groups, please try again.");

      }).then(function(response) {

        Meetings.set(response);
        $scope.meetings = Meetings.all();
        console.log("THIS IS THE RESPONSE DATA WHEN GROUP MEETINGS STATE IS INITIATED: ");
        console.log(response);
      });

      $http({
        method: "PUT",
        url: Debug.getURL("/notifications/" + GroupID.get() + "/Meeting"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": UserInfo.getAuthToken()
        }
      }).then(function successCallback(response) {

        console.log("Updated Meetings notifications.");
        console.log(response);

      }, function errorCallback(response) {

        console.log("Failed to update Meetings notifications.");
        console.log(response);

      });

      $scope.meetings = Meetings.all();

    });

$scope.setEdit = function(indexToSave)
{
  Meetings.setCurr(indexToSave);
  console.log("index of meeting to edit from group.meetings setEdit function v, getCurr() vv");
  console.log(indexToSave);
  console.log(Meetings.getCurr());
  TempEditStorage.setMeetingIndex(indexToSave);
  $state.go("editmeeting");
}

$scope.meetingDetails = function(index)
{
  Meetings.setCurr(index);
}

$scope.currentMeeting = function()
{
  return Meetings.get(Meetings.getCurr());
}

})

/*************************************************/
/*************************************************/

.controller('AddMeetingCtrl', function($scope, $state, $http, $stateParams, UserInfo, TempEditStorage, Meetings, GroupID, RevertTime, Debug, ionicDatePicker, CalculateTime) {

  $scope.RevertTime = RevertTime;
  $scope.meetings = [];


  $scope.$on("$ionicView.enter", function() 
  {
    Meetings.setEdit(false);
    console.log("Scope on init of AddGroupCtrl:");
    console.log($scope);
    //$scope.meetings = Meetings.all();
  })

  $scope.currentMeeting = function()
  {
    return Meetings.get(Meetings.getCurr());
  }

  var datePickerObj = {
      callback: function (val) {  //Mandatory
        console.log('Return value from the datepicker popup is : ' + val, new Date(val));
        $scope.meetingDate = new Date(val);
      },
      from: new Date(2016, 1, 1), //Optional
      to: new Date(2020, 10, 30), //Optional
      inputDate: new Date(),      //Optional
      mondayFirst: true,          //Optional
      disableWeekdays: [0],       //Optional
      closeOnSelect: false,       //Optional
      templateType: 'popup'       //Optional
    };

    $scope.openPicker = function() {
      ionicDatePicker.openDatePicker(datePickerObj);
    }

    $scope.confirmMeeting = function()
    {  
      console.log("confirmMeeting Called");
      if ($scope.meetingDate != "" && $scope.meetingDescription != "")
      {
        var start_time = CalculateTime.calcNewTime(this.startTimeHour,this.startTimeCycle);
        var end_time = CalculateTime.calcNewTime(this.endTimeHour,this.endTimeCycle);
        $scope.startTime = start_time;
        this.startTime = start_time;
        $scope.endTime = end_time;
        this.endTime = end_time;

        var url_string = "/meetings";
        console.log("$scope.meetings: ");
        console.log($scope.meetings);
        console.log("Meetings: ");
        console.log(Meetings.all());
        /*
        $scope.meetings.push({'MeetingDate':$scope.meetingDate,
          'StartTime':start_time,'MeetingDescription':$scope.meetingDescription,
          'ProjectID':GroupID.get(), 'EndTime': end_time, 'LocationName': $scope.locationName});
        Meetings.set($scope.meetings);
        */
        url_string = "/meetings";
        var new_meeting = 
        {
          ProjectID : GroupID.get(),
          MeetingDate : this.meetingDate,
          LocationName : this.locationName,
          EndTime : end_time,
          StartTime : start_time,
          MeetingDescription : this.meetingDescription,
          MeetingID : Meetings.get(Meetings.getCurr()).MeetingID
        }
        $scope.meetingDate = "";
        $scope.startTime = "";
        $scope.startTimeHour = "";
        $scope.startTimeCycle = "";
        $scope.startTimeCycle = "";
        $scope.endTime = "";
        $scope.endTimeHour = "";
        $scope.endTimeCycle = "";
        $scope.meetingDescription = "";
        $scope.locationName = "";
        
        end_time = "";
        start_time = "";
        console.log("next is meeting object: ");
        console.log(new_meeting);
        $http({
          method: "POST",
          url: Debug.getURL(url_string),
          data: new_meeting,
          headers: {
            "Content-Type": "application/json",
            "Authorization": UserInfo.getAuthToken()
          }
        }).then(function successCallback(response) {

          return response;
        }, function errorCallback(response) {
      //console.log("Add meeting 'fail': ");
      //console.log(response);
      alert("Failed to add meeting");
      return null;
    }).then(function redirect(response) {
      console.log("redirecting RESPONSE:...");
      console.log(response);
      Meetings.set($scope.meetings);
      $state.go("group.meetings");
      //$state.go("group.meetings(UserInfo.getActiveGroup())");
    });  
  }
}


})

/*************************************************/
/*************************************************/


.controller('EditMeetingCtrl', function($scope, $state, $http, $stateParams, UserInfo, TempEditStorage, Meetings, GroupID, RevertTime, Debug, ionicDatePicker, CalculateTime) {

  //$scope.Meetings = Meetings;

  //TempEditStorage.setMeetingIndex(Meetings.getCurr());

  $scope.$on("$ionicView.enter", function() 
  {
    Meetings.setEdit(true);
    //$scope.currentMeeting = Meetings.get(Meetings.getCurr());
    //TempEditStorage.setMeetingIndex(Meetings.getCurr());
    this.meetingDescription = $scope.getCurrentMeeting().MeetingDescription;
    console.log("current meeting index v, current meeting at index");
    console.log(Meetings.getCurr());
    console.log(Meetings.get(Meetings.getCurr()));

    $scope.meetings = [];
    $scope.RevertTime = RevertTime;
    $scope.meetingDescription = Meetings.get(Meetings.getCurr()).MeetingDescription;
    $scope.meetingDate = Meetings.get(Meetings.getCurr()).MeetingDate;
    $scope.locationName = Meetings.get(Meetings.getCurr()).LocationName;
    $scope.startTimeHour = RevertTime.getHour(Meetings.get(Meetings.getCurr()).StartTime);
    $scope.startTimeCycle = RevertTime.getCycle(Meetings.get(Meetings.getCurr()).StartTime);
    $scope.endTimeHour = RevertTime.getHour(Meetings.get(Meetings.getCurr()).EndTime);
    $scope.endTimeCycle = RevertTime.getCycle(Meetings.get(Meetings.getCurr()).EndTime);
    $scope.meetings = Meetings.all();
  })

$scope.getCurrentMeeting = function()
{
  return Meetings.get(Meetings.getCurr());
}

var datePickerObj = {
      callback: function (val) {  //Mandatory
        console.log('Return value from the datepicker popup is : ' + val, new Date(val));
        $scope.meetingDate = new Date(val);
      },
      from: new Date(2016, 1, 1), //Optional
      to: new Date(2020, 10, 30), //Optional
      inputDate: new Date(),      //Optional
      mondayFirst: true,          //Optional
      disableWeekdays: [0],       //Optional
      closeOnSelect: false,       //Optional
      templateType: 'popup'       //Optional
    };

    $scope.openPicker = function() {
      ionicDatePicker.openDatePicker(datePickerObj);
    }

    $scope.confirmMeeting = function()
    {  
      console.log("confirmMeeting Called");
      if ($scope.meetingDate != "" && $scope.meetingDescription != "")
      {
        console.log("v startTimeHour, vv startTimeCycle");
        console.log(this.startTimeHour);
        console.log(this.startTimeCycle);
        console.log("v endTimeHour, vv endTimeCycle");
        console.log(this.endTimeHour);
        console.log(this.endTimeCycle);

        var start_time = CalculateTime.calcNewTime(this.startTimeHour,this.startTimeCycle);
        var end_time = CalculateTime.calcNewTime(this.endTimeHour,this.endTimeCycle);
        console.log("v start_time, vv end_time");
        $scope.startTime = start_time;
        this.startTime = start_time;
        $scope.endTime = end_time;
        this.endTime = end_time;
        console.log(start_time);
        console.log(end_time);

        var saved_index = TempEditStorage.getMeetingIndex();
        var url_string = "/meetings/";
        console.log("SAVED INDEX: (index of meeting being changed: ");
        console.log(saved_index);
        var new_meeting = 
        {
          MeetingDate : this.meetingDate,
          LocationName : this.locationName,
          EndTime : end_time,
          StartTime : start_time,
          MeetingDescription : this.meetingDescription,
        }
        console.log("OBJECT BEING PASSED TO UPDATE: ");
        console.log(new_meeting);
        
        /*
        console.log("AT POINT CONFIRM MEETING:");
        console.log("v getCurr()");
        console.log(Meetings.get(Meetings.getCurr()));
        console.log("v TempEditStorage getMeetingIndex");
        console.log(TempEditStorage.getMeetingIndex());
        */

        $scope.meetings[saved_index].MeetingDate = this.meetingDate;
        $scope.meetings[saved_index].StartTime = start_time;
        $scope.meetings[saved_index].MeetingDescription = this.meetingDescription;
        $scope.meetings[saved_index].EndTime = end_time;
        Meetings.set($scope.meetings);
        url_string = "/meetings/";
        url_string += $scope.meetings[saved_index].MeetingID;
        new_meeting = 
        {
          ProjectID : GroupID.get(),
          MeetingDate : $scope.meetings[saved_index].MeetingDate,
          LocationName : $scope.meetings[saved_index].LocationName,
          EndTime : end_time,
          StartTime : start_time,
          MeetingDescription : $scope.meetings[saved_index].MeetingDescription,
          MeetingID : $scope.meetings[saved_index].MeetingID
        }
        console.log("next is meeting object: ");
        console.log(new_meeting);
        $http({
          method: "POST",
          url: Debug.getURL(url_string),
          data: new_meeting,
          headers: {
            "Content-Type": "application/json",
            "Authorization": UserInfo.getAuthToken()
          }
        }).then(function successCallback(response) {
          console.log("success passing this v");
          console.log(response);
          return response;
        }, function errorCallback(response) {

          alert("Failed to add meeting");
          return null;
        }).then(function redirect(response) {
          //console.log("redirecting RESPONSE:...");
          //console.log(response);
          console.log("redirecting to go to group/meetings/groupID");
          $state.go("group.meetings");
          //$state.go("group.meetings(UserInfo.getActiveGroup())");
        });
    /*
    $scope.meetingDate = "";
    $scope.startTime = "";
    $scope.endTime = "";
    $scope.meetingDescription = "";
    $scope.locationName = "";
    */
    //Meetings.set($scope.meetings);
    //$state.go("meetings");
    
  }
}


})

.controller('GroupsCtrl', function ($scope, $http, UserInfo, Groups, Debug) {

  $scope.userName = UserInfo.get().userName;

  $scope.activeMeeting = {active: false, id: null};

  $scope.$on("$ionicView.enter", function() {

    console.log(UserInfo.getAuthToken());
    
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

    $scope.activeMeeting = Groups.activeMeeting();

  }, function errorCallback(response) {

    console.log(Debug.getURL("/projects"));
    console.log(response);

    alert("Failed to load groups, please try again.");

    return null;

  });

}); 

    $scope.setGroup = function(id, role) {
      console.log("Setting group ID: " + id);
      UserInfo.setActiveGroup(id);

      if (role == "Student") {
        UserInfo.setProf(false);
      }
      else if (role == "Teacher") {
        UserInfo.setProf(true);
      }
      else {
        UserInfo.setProf(null);
      }

  }

  $scope.checkin = function() {

    $http({
      method: "POST",
      url: Debug.getURL("/attendance/" + $scope.activeMeeting.id),
      headers: {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }
    }).then(function successCallback(response) {
      console.log("Checking response:");
      console.log(response);
      return response;
    }, function errorCallback(response) {
      alert("Checkin failed");
      return null;
    }).then(function() {
      $scope.activeMeeting = {active: false, id: null};
    });

  };
  
  $scope.groups = Groups.all();
})

.controller('AddGroupCtrl', function ($scope, $ionicConfig, $state, $http, UserInfo, Debug, $ionicPopup) {

  $scope.show_suggestions = false;
  $scope.group = {};

  $scope.search = '';
  $scope.orderByAttribute = '';
  $scope.members = [];
  $scope.email = "";

  $scope.addMember = function () 
  {
    if (document.getElementById('email_input').value != ' ') {
      console.log("Adding email: " + document.getElementById('email_input').value);
      $scope.members.push({
        'email': document.getElementById('email_input').value,
        'isProfessor': this.isProfessor
      });
        
      $scope.email = "";
      $scope.isProfessor = false;
      document.getElementById('email_input').value = "";
      this.show_suggestions = false;
      //document.getElementById('autocomplete_list').style.visibility = "hidden";
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

    group.users.push({email:UserInfo.get().email, isProfessor:false});

    console.log(JSON.stringify(group));

    $http({
      method: "POST",
      url: Debug.getURL("/projects"),
      data: group,
      headers: {
        "Content-Type": "application/json",
        "Authorization": UserInfo.getAuthToken()
      }
    }).then(function successCallback(response) {
      console.log("Adding Group...");
      console.log(response);
      return response;

    }, function errorCallback(response) {
      console.log("Add group 'fail': ");
      console.log(response);
      alert("Failed to add group");
      return null;
    }).then(function redirect(response) {

      $state.go("groups");
    });
  }
  $scope.autoCompleteMeetingUpdate = function(input)
  {
    if(input && input.length >= 3) 
    {
      this.show_suggestions = true;
      //document.getElementById('autocomplete_list').style.visibility = "visible";
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
        //console.log(response);
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
        //console.log("redirecting...");
        //console.log(response);
        $scope.input_suggestions = response.data.suggestions;
        //console.log("Input suggestions: " , $scope.input_suggestions);
      });
    }
    else
    {
      this.show_suggestions = false;
    }
  }

  $scope.selectEmail = function(selected_email)
  {
    $scope.email = selected_email;
    document.getElementById('email_input').value = selected_email.suggestion;
    $scope.email = selected_email.suggestion;
  }
  
   // When button is clicked, the popup will be shown...
   $scope.showConfirm = function() {
	
      var confirmPopup = $ionicPopup.confirm({
         title: 'Professor',
         template: 'A professor is able to view the group activities, including anonymous chats. However, they cannot interact with the group.'
      });

      confirmPopup.then(function(res) {
         if(res) {
            console.log('Sure!');
         } else {
            console.log('Not sure!');
         }
      });
		
   };
})


.controller('RegisterCtrl', function ($scope, $state, $http, Debug) {

  $scope.regInfo = {};

  $scope.register = function () {

    if ($scope.regInfo.password == $scope.regInfo.confPassword && $scope.regInfo.password.length > 5) {

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

