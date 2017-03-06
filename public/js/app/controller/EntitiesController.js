/**
 * Created by jaime on 04/03/17.
 */
app.controller("EntitiesController", function($scope, $rootScope, $http, $window) {
    $rootScope.section = "entities";
    $scope.entities = {};
    $scope.routes = {};
    $scope.entity = {};
    $scope.entityType = null;
    $scope.entityTypeOptions = [
        {id: 0, name: "Driver"},
        {id: 1, name: "Car"}
    ];

    $scope.showEdit = false;

    console.log("EntitiesController instance");

    /**
     * Get dependencies
     */
    getDependencies($http, true, true);

    $scope.getEntity = function(id) {
        $http({
            method: "GET",
            url: $rootScope.baseUrl + "/entities/" + id
        }).then(function(response) {
            if (response.data.code != 200) {
                $scope.showEdit = false;
                Materialize.toast(response.data.msg, 2000);
                return false;
            }

            $scope.routes = $scope.routes;

            $scope.entity = response.data.data;
            $scope.showEdit = true;
            Materialize.updateTextFields();

            $("html,body").animate({
                    scrollTop: $("#editForm").offset().top},
                "slow");

            /**
             * Reload map
             */
            $window.lat = $scope.entity.lat; $window.lng = $scope.entity.lng;
            $window.handleMap();
        }, function(response) {
            Materialize.toast("Some error ocurred trying to load entity", 2000);
        });
    };

    $scope.entityUpdate = function() {
        if ($scope.entity.name === undefined || 0 == $scope.entity.name.trim().length) {
            Materialize.toast("Name can't be empty", 2000);
            return;
        }
        if ($scope.entity.entity_type === undefined) {
            Materialize.toast("Type (Car, Driver) can't be empty", 2000);
            return;
        }
        if ($scope.entity.route_id === undefined || "new" == $scope.entity.route_id) {
            Materialize.toast("Route can't be empty", 2000);
            return;
        }

        $http({
            method: "PUT",
            url: $rootScope.baseUrl + "/entities/" + $scope.entity.id + "/update",
            data: $scope.entity,
        }).then(function(response) {
            Materialize.toast(response.data.msg, 2000);
            /**
             * Reload entities and routes
             */
            getDependencies($http, true, false);
        }, function(response) {
            Materialize.toast("Some error ocurred trying to update entity", 2000);
            console.log(response);
        });
    };

    $scope.changeRoute = function() {
        if ("new" == $scope.entity.route_id) {
            console.log("Show modal");
        }
    };

    function getDependencies($http, entities, routes) {
        /**
         * Entities
         */
        if (entities) {
            $http({
                method: "GET",
                url: $rootScope.baseUrl + "/entities"
            }).then(function(response) {
                var data = response.data.data;
                $scope.entities = data;
            }, function(response) {
                Materialize.toast("Some error ocurred trying to load entities", 2000);
            });
        }


        /**
         * Routes
         */
        if (routes) {
            $http({
                method: "GET",
                url: $rootScope.baseUrl + "/routes"
            }).then(function(response) {
                $scope.routes = response.data.data;
            }, function(response) {
                Materialize.toast("Some error ocurred trying to load routes", 2000);
            });
        }
    }


});
