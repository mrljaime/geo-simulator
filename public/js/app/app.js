/**
 * Created by jaime on 04/03/17.
 */
var app = angular.module("SimulatorApp", ["ui.router"]);

app.factory("httpInterceptor", ["$q", "$location", function($q, $location) {
    return {
        request: function(request) {
            $("#loader").show();
            return request;
        },
        response: function(response) {
            $("#loader").hide();
            return response;
        },
        responseError: function(response) {
            $("#loader").hide();
            return response;
        }
    }
}]);

app.config(function($stateProvider, $httpProvider) {

    $stateProvider
        .state("home", {
            url: "/",
            views: {
                root: {
                    templateUrl: "views/index.html",
                }
            }
        })
        .state("entities", {
            url: "/entities",
            views: {
                root: {
                    templateUrl: "views/entities.html",
                    controller: "EntitiesController"
                }
            }
        })
        .state("entities.edit", {
            url: "/entities/:id/edit",
            views: {
                iframe: {
                    templateUrl: "views/entities-edit.html",
                    controller: "EntitiesController"
                }
            }
        });

    /*
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

    */

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