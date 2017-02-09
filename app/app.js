var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'toaster']);

//configuration of the route depending on the url {ng-route} - angularjs

app.config(['$routeProvider',
  function ($routeProvider) {
        $routeProvider
            .when('/signup', {
                title: 'New Reg',
                templateUrl: 'partials/register.html',
                controller: 'authCtrl'
            })
            .when('/dashboard', {
                title: 'Dashboard',
                templateUrl: 'partials/dashboard.html',
                controller: 'authCtrl'
            })
            .when('/', {
                title: 'New Reg',
                templateUrl: 'partials/register.html',
                controller: 'authCtrl',
                role: '0'
            })
            .otherwise({
                redirectTo: '/signup'
            });
  }])
    .run(function ($rootScope, $location, Data) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            $rootScope.authenticated = false;
            Data.get('session').then(function (results) { //makes the api/v1/session get request to preload all the contacts storaged on the database
                if (results.uid) {
                    $rootScope.authenticated = true;
                    $rootScope.uid = results.uid;
                    $rootScope.name = results.name;
                    $rootScope.email = results.email;
                    $rootScope.contacts = JSON.parse(results.email);
                    $rootScope.retain = $rootScope.contacts[0];
                } else {
                    var nextUrl = next.$$route.originalPath;
                    if (nextUrl == '/signup' || nextUrl == '/login') {
                        //$location.path("/dashboard");
                    } else {
                        $location.path("/signup");
                    }
                }
            });
        });
    });