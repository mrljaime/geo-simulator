/**
 * Created by jaime on 04/03/17.
 */
app.controller("ConfigController", function($scope, $rootScope, $http) {
    console.log($rootScope.baseUrl);
    $scope.config = {
        config: {
            maxCars: 0,
            maxDrivers: 0,
            state: false,
        },
        maxCars: 0,
        maxDrivers: 0,
    };

    /**
     * Load app config
     */
    $scope.loadConfig = function() {
        $http({
            method: "GET",
            url: $rootScope.baseUrl + "/config"
        }).then(function(response) {
            var data = response.data.data;
            $scope.config.config.maxCars = data.config.max_clients;
            $scope.config.config.maxDrivers = data.config.max_users;
            $scope.config.config.state = data.config.state == 1;
            $scope.config.maxCars = data.maxCars;
            $scope.config.maxDrivers = data.maxDrivers;

            console.log($scope.config);
            $("#configModal").modal("open");
        }, function(response) {
            Materialize.toast("Ups, something ocurred trying to load config.", 2000);
        });
    };

    /**
     * After verify, save changes and reload app core
     */
    $scope.saveConfig = function() {

        if ($scope.config.config.maxCars === undefined || $scope.config.config.maxCars > $scope.config.maxCars) {
            Materialize.toast(
                "The max number cars has been exceeded (" + $scope.config.maxCars + ")",
                2000);
            return false;
        }
        if ($scope.config.config.maxDrivers === undefined || $scope.config.config.maxDrivers > $scope.config.maxDrivers) {
            Materialize.toast(
                "The max number drivers has been exceeded (" + $scope.config.maxCars + ")",
                2000);
            return false;
        }
        if ($scope.config.config.state == 1) {
            $scope.config.config.state = true;
        }
        if ($scope.config.config.state == 0) {
            $scope.config.config.state = false;
        }


        $http({
            method: "PUT",
            url: $rootScope.baseUrl + "/config/update",
            data: $scope.config
        }).then(function(response) {
            var data = response.data;
            if (data.code == 200) {
                $("#configModal").modal("close");
            }
            Materialize.toast(data.msg, 2000);
        }, function(response) {
            $("#configModal").modal("close");
        });


    }

});