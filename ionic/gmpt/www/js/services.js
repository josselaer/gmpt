angular.module('starter.services', [])

.factory("Notifications", function(UserInfo, Debug) {

  var notifications = [{
    'groupID': 0, 
    'number': 5
  }, {
    'groupID': 1, 
    'number': 9
  }, {
    'groupID': 2, 
    'number': 5
  }];

  return {

    get: function() {
      return notifications;
    },
    
    set: function(not) {
      notifications = not;
    },

    getNumberOfNotifications: function() {
      return notifications.length;
    },

    getNumberOfGroupNotifications: function(gID) {

      for (var i = 0; i < notifications.length; i++) {
        if (notifications[i].groupID == gID) {
          return notifications[i];
        }
      }

      return null;
    },

    getNotifications: function() {

      $http({
        method: "GET",
        url: Debug.getURL("/notifications"),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': UserInfo.getAuthToken()
        }
      }).then(function successCallback(response) {

        notifications = response.data.notifications;

      }, function failureCallback(response) {

        console.log("Failed to get notifications.");
        console.log(response);

      });
    }

  };

})

.factory("UserInfo", function() {

  user = {
    "userName": "aakashpatel1",
    "email": "helloworld@gmail.com",
    "firstName": "Aakash",
    "lastName": "Patel",
    "auth": 0,
    "userid": 0
  }

  activeGroup = null;

  return {
    get: function() {
      return user;
    },
    set: function(userData) {
      user.userName = userData.userName;
      user.email = userData.email;
      user.firstName = userData.firstName;
      user.lastName = userData.lastName;
    },
    setAuthToken: function(authToken) {
      user.auth = authToken;

    },
    getAuthToken: function() {
      return user.auth;
    },
    login: function(userData) {
      user.userName = userData.username;
      user.email = userData.email;
      user.firstName = userData.firstName;
      user.lastName = userData.lastName;
      user.auth = userData.token;
      user.userid = userData.userID;  

      if (user.auth != undefined && user.auth != null && user.auth != 0) {
        return true;
      }
      else {
        return false;
      }
    },

    setActiveGroup: function(id) {
      activeGroup = id;

      console.log(activeGroup);
    },
    getActiveGroup: function() {
        return activeGroup;
    }

  }

})


.factory('Chats', function($http, UserInfo, Debug) {

  return {

    getGroupMessages: function(groupID) {

      return $http({

        method: "GET",
        url: Debug.getURL("/chat/" + groupID),
        headers: {
          "Content-Type": "application/json",
          "Authorization": UserInfo.getAuthToken()
        }
      }).then(function successCallback(response) {

        return response.data.messages;
    
      }, function errorCallback(response) {

        console.log(Debug.getURL("/chat/" + groupID));
        console.log(response);

        alert("Failed to get chat messages, please try again. " + JSON.stringify(response));

        return response;

      }).then(function receivedMessage(response) {
        
        return response;
      });
      
    },

    sendMessage: function(messageData, groupID) {

      return $http({
          method: "POST",
          url: Debug.getURL("/chat/" + groupID),
          data: messageData,
          headers: {
            "Content-Type": "application/json",
            "Authorization": UserInfo.getAuthToken()
          }
        }).then(function successCallback(response) {
          return;
        }, function errorCallback(response) {
          console.log(response);
          console.log(messageData);
          alert("Message failed. " + response);
        });

    },

    getDefaultMessages: function() {
      return chats;
    }
  };
})

.factory("Groups", function($http) {

  groups = [];

  return {
    all: function() {

      return groups;
    },
    set: function(g) {
      groups = g;
    },
    activeMeeting: function() {

      var now = new Date();

      for (var i = 0; i < groups.length; i++) {

        if (groups[i].NextMeetingDate != null) {

          var meetingDate = new Date(groups[i].NextMeetingDate);

          if (now.getDay() === meetingDate.getDay() && now.getMonth() === now.getMonth()) {
            console.log("There is a meeting today!");

            return {active: true, id: groups[i].MeetingID, desc: groups[i].NextMeetingDescription};
          }
        }
      }
      return false;
    }
  };
})

.factory("Meetings", function($http) {

  meetings = [];
  /*
    {
    date: "12/3/12",
    startTime: "6:30PM",
    topic: "Mock Ups",
    meetingDescription: "lorum ipsum random description"
  }, {
    date: "12/7/9",
    startTime: "8:30PM",
    topic: "Front End Design",
    meetingDescription: "lorum ipsum random description"
  }
  ];
  */

  editing = false;
  currInd = 0;
  //console.log("[1]currInd = ", currInd);
  return {
    all: function() {
      return meetings;
    },
    set: function(g) {
      meetings = g;
    },
    get: function(index)
    {
      return meetings[index];
    },
    getCurr: function()
    {
      return currInd;
    },
    setCurr: function(index)
    {
      currInd = index;
    },
    getEdit: function()
    {
      return editing;
    },
    setEdit: function(bool)
    {
      editing = bool;
    }
  };
})

.factory("GroupID", function($http) {

  ID = 0;

  return {
    set: function(g) {
      ID = g;
    },
    get: function()
    {
      return ID;
    }
  };
})

.factory("Debug", function() {

  debug = false;

  apiaryURL = "http://private-f963fa-gmpt.apiary-mock.com";

  prodURL = "http://52.37.56.8/gmpt/api/public";

  return {
    getURL: function(endpoint) {

      if (debug == true)
        return apiaryURL + endpoint;
      else 
        return prodURL + endpoint;
    }
  }

});


