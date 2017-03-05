/**
 * Created by jaime on 04/03/17.
 */
var app = angular.module("SimulatorApp", ["ngRoute"]);

app.factory("httpInterceptor", ["$q", "$location", function($q, $location) {
    return {
        request: function(request) {
            $("#loader").show();
            return request;
        },
        response: function(response) {
            $("#loader").hide();
            return response;
        }
    }
}]);

app.config(function($routeProvider, $httpProvider) {
    $routeProvider
        .when("/", {
            templateUrl: "views/index.html",
        })
        .when("/entities", {
            templateUrl: "views/entities.html",
            controller: "EntitiesController"
        })
        .otherwise({
            redirectTo: '/'
        });

    $httpProvider.interceptors.push("httpInterceptor");
});



app.run(function($rootScope) {
    $rootScope.baseUrl = location.protocol +
        '//' +
        location.hostname +
        (location.port ? ':'+location.port: '') +
        "/simulator/api";

    $rootScope.section = "map";
});