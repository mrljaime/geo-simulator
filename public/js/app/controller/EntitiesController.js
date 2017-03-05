/**
 * Created by jaime on 04/03/17.
 */
app.controller("EntitiesController", function($scope, $rootScope, $http) {
    $rootScope.section = "entities";
    $scope.entities = {};

    console.log("EntitiesController instance");

    $http({
        method: "GET",
        url: $rootScope.baseUrl + "/entities"
    }).then(function(response) {
        var data = response.data.data;
        $scope.entities = data;
    }, function(response) {
        Materialize.toast("Some error ocurred trying to load entities", 2000);
    });

});
