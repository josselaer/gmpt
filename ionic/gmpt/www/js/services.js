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

  // Some fake testing data
  d = new Date();
  d.setTime(Date.now());
  var chats = [{
    id: 0,
    sender: 'Ben Sparrow',
    text: 'You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?',
    face: 'img/ben.png',
    timeDate: d.toLocaleString(),
    anonymous: true
  }, {
    id: 1,
    sender: 'Max Lynx',
    text: 'Hey, it\'s me',
    face: 'img/max.png',
    timeDate: d.toLocaleString(),
    anonymous: false
  }, {
    id: 2,
    sender: 'Adam Bradleyson',
    text: 'I should buy a boat',
    face: 'img/adam.jpg',
    timeDate: d.toLocaleString(),
    anonymous: false
  }, {
    id: 3,
    sender: 'Perry Governor',
    text: 'Look at my mukluks!',
    face: 'img/perry.png',
    timeDate: d.toLocaleString(),
    anonymous: false
  }, {
    id: 4,
    sender: 'Mike Harrington',
    text: 'This is wicked good ice cream.',
    face: 'img/mike.png',
    timeDate: d.toLocaleString(),
    anonymous: false
  }];

  return {
    getGroupMessages: function(groupID) {

      $http({

        method: "GET",
        url: Debug.getURL("/chat/" + groupID),
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


