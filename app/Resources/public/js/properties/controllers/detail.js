'use strict';

angular.module('propertiesApp')
    .controller('PropertyDetailCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$log', function (CFG, uiGmapGoogleMapApi, $scope, $log) {

        $scope.init = function(propertiesFormFilter, filteredProperties) {
            $scope.firstCallFinished = true;
            $scope.type = {};
            $scope.map = { center: { latitude: 41, longitude: 0 }, zoom: 4, bounds: {}, clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false } };
            $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
            $scope.map.control = {};

            $scope.form = angular.fromJson(propertiesFormFilter);
            $scope.properties = angular.fromJson(filteredProperties);

        };

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log(maps);
        });

    }]);
