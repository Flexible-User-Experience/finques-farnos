'use strict';

angular.module('propertiesApp')
    .controller('PropertyDetailCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$log', function (CFG, uiGmapGoogleMapApi, $scope, $log) {

        $scope.init = function(localization) {
            $scope.localization = angular.fromJson(localization);
            $scope.map = {
                zoom: 14,
                radius: 500,
                stroke: {
                    color: '#D86F24',
                    weight: 1,
                    opacity: 1
                },
                fill: {
                    color: '#D86F24',
                    opacity: 0.25
                },
                center: { latitude: $scope.localization.coords.latitude, longitude: $scope.localization.coords.longitude }
            };
            $scope.map.options = { scrollwheel: false, draggable: true, maxZoom: 15 };
            $scope.map.control = {};
        };

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log(maps);
        });

    }]);
