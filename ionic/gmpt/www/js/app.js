// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers', 'starter.services'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider
  
    .state('login', {
    url: '/login',
    templateUrl: 'templates/login.html',
    controller: 'LoginCtrl'
  })

//  .state('groups', {
//    url: '/groups',
//    templateUrl: 'templates/groups.html',
//    controller: 'GroupsCtrl'
//  })
  
  // setup an abstract state for the tabs directive
    .state('groups', {
    parent: 'menu',
    url: '/groups',
    abstract: true,
    templateUrl: 'templates/groups.html'
  })


  .state('addgroup', {
    url: '/addgroup',
    templateUrl: 'templates/add-group.html',
    controller: 'AddGroupCtrl'
  })

  // setup an abstract state for the tabs directive
    .state('group', {
    url: '/group',
    abstract: true,
    templateUrl: 'templates/group.html'
  })

  // Each tab has its own nav history stack:

  .state('group.stats', {
    url: '/stats',
    views: {
      'group-stats': {
        templateUrl: 'templates/group-stats.html',
        controller: 'StatsCtrl'
      }
    }
  })

  .state('group.chat', {
      url: '/chat/:groupID',
      views: {
        'group-chat': {
          templateUrl: 'templates/group-chat.html',
          controller: 'ChatsCtrl'
        }
      }
    })

  .state('addmeeting', {
    url: '/addmeeting',
    templateUrl: 'templates/new-meeting.html',
    controller: 'MeetingsCtrl'

  })
  .state('meeting-details', {
    url: '/meeting-details',
    templateUrl: 'templates/meeting-details.html',
    controller: 'MeetingsCtrl'

  })
  .state('group.meetings', {
    url: '/meetings',
    views: {
      'group-meetings': {
        templateUrl: 'templates/group-meetings.html',
        controller: 'MeetingsCtrl'
      }
    }
  })
  
     .state('register', {
    url: '/register',
    templateUrl: 'templates/register.html',
    controller: 'RegisterCtrl'
  })
  
    .state('menu', {
    url: '/menu',
    abstract: true,
    templateUrl: 'templates/menu.html',
    controller: 'MenuCtrl'
  });

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/login');

});
