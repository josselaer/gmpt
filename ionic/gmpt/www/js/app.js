// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers', 'starter.services', 'ionic-datepicker'])

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
<<<<<<< HEAD
  
  .state('login', {
    url: '/login',
    templateUrl: 'templates/login.html',
    controller: 'LoginCtrl'
  })
=======
>>>>>>> 49418717eeb0872c668b38bc9db82a491ecc75a8

  .state('account', {
    url: '/account',
    templateUrl: 'templates/accounts.html',
    controller: 'AccountCtrl' 
  })
  
  .state('groups', {
    url: '/groups',
    templateUrl: 'templates/groups.html',
    controller: 'GroupsCtrl'
  })
  
  .state('addgroup', {
    url: '/addgroup',
    templateUrl: 'templates/add-group.html',
    controller: 'AddGroupCtrl'
  })

  // setup an abstract state for the tabs directive
<<<<<<< HEAD
  .state('group', {
    url: '/group',
    cache: false,
    abstract: true,
    templateUrl: 'templates/group.html',
    controller: 'TabCtrl'
  })
  
  .state('group.stats', {
    url: '/stats/:groupID',
    views: {
        'group-stats': {
          templateUrl: 'templates/group-stats.html',
          controller: 'StatsCtrl'
        }
=======
    .state('tab', {
    url: '/tab',
    abstract: true,
    templateUrl: 'templates/tabs.html'
  })

  // Each tab has its own nav history stack:

  .state('tab.dash', {
    url: '/dash',
    views: {
      'tab-dash': {
        templateUrl: 'templates/tab-dash.html',
        controller: 'DashCtrl'
>>>>>>> 49418717eeb0872c668b38bc9db82a491ecc75a8
      }
  })

  .state('tab.chats', {
      url: '/chats/',
      views: {
        'tab-chats': {
          templateUrl: 'templates/tab-chats.html',
          controller: 'ChatsCtrl'
        }
      }
    })
<<<<<<< HEAD
  
  .state('addmeeting', {
    url: '/addmeeting',
    templateUrl: 'templates/new-meeting.html',
    controller: 'MeetingsCtrl'

  })

  .state('editmeeting', {
    url: '/editmeeting',
    templateUrl: 'templates/edit-meeting.html',
    controller: 'MeetingsCtrl'

  })

  .state('meeting-details', {
    url: '/meeting-details',
    templateUrl: 'templates/meeting-details.html',
    controller: 'MeetingsCtrl'

  })

  .state('group.meetings', {
    url: '/meetings/:groupID',
=======
    .state('tab.chat-detail', {
      url: '/chats/:chatId',
      views: {
        'tab-chats': {
          templateUrl: 'templates/chat-detail.html',
          controller: 'ChatDetailCtrl'
        }
      }
    })

  .state('tab.account', {
    url: '/account',
>>>>>>> 49418717eeb0872c668b38bc9db82a491ecc75a8
    views: {
      'tab-account': {
        templateUrl: 'templates/tab-account.html',
        controller: 'AccountCtrl'
      }
    }
<<<<<<< HEAD
  })
  
  .state('register', {
    url: '/register',
    templateUrl: 'templates/register.html',
    controller: 'RegisterCtrl'
  })
=======
  });
>>>>>>> 49418717eeb0872c668b38bc9db82a491ecc75a8

  .state('group.settings', {
      url: '/settings/:groupID',
      views: {
        'group-settings': {
          templateUrl: 'templates/group-settings.html',
          controller: 'SettingsCtrl'
        }
      }
  });
  
  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/groups');

});
