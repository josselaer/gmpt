angular.module('starter.services', [])

.factory("UserInfo", function() {

  user = {
    "userName": "aakashpatel1",
    "email": "helloworld@gmail.com",
    "firstName": "Aakash",
    "lastName": "Patel",
    "auth": 0
  }

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
    }

  }

})


.factory('Chats', function($http, Debug) {
  // Might use a resource here that returns a JSON array

  return {
    getGroupMessages: function(groupID) {

      return $http({

        method: "GET",
        url: Debug.getURL("/chat/1"),
        responseType: "json",
        headers: {
          'Content-Type': "json"
        }
      }).then(function successCallback(response) {

        console.log(response.data.messages);
        return response.data.messages;
    
      }, function errorCallback(response) {

        console.log(Debug.getURL("/chat/" + groupID));
        console.log(response);

        alert("Failed to get chat messages, please try again. " + response);

        return null;

      });
    },

    sendMessage: function(messageData) {

      return $http({
          method: "POST",
          url: Debug.getURL("/chat/1"),
          data: messageData,
          contentType: "application/json"
        }).then(function successCallback(response) {
          console.log("You sent a message!");
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

  groups = [{
    id: 1,
    name: "GMPT",
    nextMeeting: "Sunday 12:00 PM",
    notifications: 9
  }, {
    id: 2,
    name: "Software Engineering",
    nextMeeting: "Thursday 12:00 PM",
    notifications: 2
  }];

  return {
    all: function() {

      return groups;
    },
    set: function(g) {
      groups = g;

      console.log("Set()" + groups);
    }
  };
})

.factory("Debug", function() {

  debug = true;

  apiaryURL = "http://private-f963fa-gmpt.apiary-mock.com";

  prodURL = null;

  return {
    getURL: function(endpoint) {

      if (debug == true)
        return apiaryURL + endpoint;
      else 
        return prodURL + endpoint;
    }
  }

});


