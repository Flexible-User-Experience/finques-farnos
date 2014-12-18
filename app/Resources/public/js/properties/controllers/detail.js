'use strict';

angular.module('propertiesApp')
    .controller('PropertyDetailCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$log', function (CFG, uiGmapGoogleMapApi, $scope, $log) {

        $scope.init = function(localization) {
            $scope.map = {
                zoom: 14,
                radius: 500,
                stroke: {
                    color: '#D86F24',
                    weight: 1,
                    opacity: 0.25
                },
                fill: {
                    color: '#D86F24',
                    opacity: 0.25
                },
                geodesic: true,
                draggable: false,
                clickable: false,
                editable: false,
                visible: true,
                clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false }
            };
            $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
            $scope.map.control = {};

            $scope.localization = angular.fromJson(localization);
            $log.log($scope.localization);
        };

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log(maps);
        });

        $scope.isShowMapArea = function(value) {
            return value === CFG.SHOW_MAP_AREA;
        }

    }]);
