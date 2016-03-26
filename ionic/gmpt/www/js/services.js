angular.module('starter.services', [])

.factory('Chats', function() {
  // Might use a resource here that returns a JSON array

  // Some fake testing data
  d = new Date();
  d.setTime(Date.now());
  var chats = [{
    id: 0,
    sender: 'Ben Sparrow',
    body: 'You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?You on your way?',
    face: 'img/ben.png',
    timeDate: d.toLocaleString(),
    anonymous: true
  }, {
    id: 1,
    sender: 'Max Lynx',
    body: 'Hey, it\'s me',
    face: 'img/max.png',
    timeDate: d.toLocaleString(),
    anonymous: false
  }, {
    id: 2,
    sender: 'Adam Bradleyson',
    body: 'I should buy a boat',
    face: 'img/adam.jpg',
    timeDate: d.toLocaleString(),
    anonymous: false
  }, {
    id: 3,
    sender: 'Perry Governor',
    body: 'Look at my mukluks!',
    face: 'img/perry.png',
    timeDate: d.toLocaleString(),
    anonymous: false
  }, {
    id: 4,
    sender: 'Mike Harrington',
    body: 'This is wicked good ice cream.',
    face: 'img/mike.png',
    timeDate: d.toLocaleString(),
    anonymous: false
  }];

  return {
    getGroupMessages: function(groupID) {
      return chats;
    },
    get: function(chatId) {
      for (var i = 0; i < chats.length; i++) {
        if (chats[i].id === parseInt(chatId)) {
          return chats[i];
        }
      }
      return null;
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


