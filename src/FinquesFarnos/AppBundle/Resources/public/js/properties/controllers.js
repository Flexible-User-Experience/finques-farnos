'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log', function (CFG, API, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log) {

        numeral.language('es');
        var timerArea, timerRooms, timerPrice = false;
        $scope.firstCallFinished = false;
        $scope.type = {};
        $scope.map = { center: { latitude: 41, longitude: 0 }, zoom: 4, bounds: {}, clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false } };
        $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
        $scope.map.control = {};

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
        });

        var getPropertiesFormFiltersPromise = API.getPropertiesFormFilters($scope);
        getPropertiesFormFiltersPromise.then(
            function(response) {
                $scope.map.control.refresh();
                var getPropertiesPromise = API.getProperties($scope);
                getPropertiesPromise.then(
                    function(response) {
                        $scope.firstCallFinished = true;
                    },
                    function(reason) { $log.error('get properties promise error', reason); }
                );
            },
            function(reason) { $log.error('get properties form filters promise error', reason); }
        );

        $scope.formListener = function() {
            API.getProperties($scope);
        };

        $scope.$watch('area', function(newValue, oldValue) {
            if (timerArea) {
                $timeout.cancel(timerArea)
            }
            timerArea = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined) API.getProperties($scope);
            }, CFG.DELAY)
        });

        $scope.$watch('rooms', function(newValue, oldValue) {
            if (timerRooms) {
                $timeout.cancel(timerRooms)
            }
            timerRooms = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined) API.getProperties($scope);
            }, CFG.DELAY)
        });

        $scope.$watch('price', function(newValue, oldValue) {
            if (timerPrice) {
                $timeout.cancel(timerPrice)
            }
            timerPrice = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined) API.getProperties($scope);
            }, CFG.DELAY)
        });

        $scope.getCurrency = function(value) {
            return value !== undefined ? numeral(value).format('0,0') : '';
        };

        $scope.getUrlPropertyDetail = function(property) {
            return Routing.generate('front_property', {type: property.type_name_slug, name: property.name_slug});
        };

    }]);
