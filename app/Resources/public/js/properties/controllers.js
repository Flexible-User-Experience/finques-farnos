'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log', function (CFG, API, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log) {

        $scope.init = function(propertiesFormFilter, filteredProperties) {
            $scope.firstCallFinished = true;
            $scope.type = {};
            $scope.map = { center: { latitude: 41, longitude: 0 }, zoom: 4, bounds: {}, clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false } };
            $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
            $scope.map.control = {};
            $scope.form = angular.fromJson(propertiesFormFilter);
            $scope.form.area.min = Math.ceil($scope.form.area.min / 10) * 10;
            $scope.form.area.max = Math.floor($scope.form.area.max / 10) * 10;
            $scope.form.area.step = Math.round(($scope.form.area.max - $scope.form.area.min) / CFG.RANGE_STEPS);
            $scope.form.price.min = Math.ceil($scope.form.price.min / 1000) * 1000;
            $scope.form.price.max = Math.floor($scope.form.price.max / 1000) * 1000;
            $scope.form.price.step = Math.round(($scope.form.price.max - $scope.form.price.min) / CFG.RANGE_STEPS);
            $scope.area = $scope.form.area.min + Math.round(($scope.form.area.max - $scope.form.area.min) / 2);
            $scope.rooms = $scope.form.rooms.min + Math.round(($scope.form.rooms.max - $scope.form.rooms.min) / 2);
            $scope.price = $scope.form.price.min + Math.round(($scope.form.price.max - $scope.form.price.min) / 2);
            $scope.type = $scope.form.types[0];
            $scope.properties = angular.fromJson(filteredProperties);
            $log.log('init propertiesFormFilter', $scope.form);
            $log.log('init filteredProperties', $scope.properties);
        };

        numeral.language('es');
        var timerArea, timerRooms, timerPrice = false;

        uiGmapGoogleMapApi.then(function(/*maps*/) {
            // promise done
            //$log.log(maps);
        });

        $scope.formListener = function() {
            API.getProperties($scope);
        };

        $scope.$watch('area', function(newValue, oldValue) {
            if (timerArea) {
                $timeout.cancel(timerArea);
            }
            timerArea = $timeout(function() {
                $log.log('area', oldValue, newValue);
                if (newValue !== undefined && oldValue !== undefined) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('rooms', function(newValue, oldValue) {
            if (timerRooms) {
                $timeout.cancel(timerRooms);
            }
            timerRooms = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('price', function(newValue, oldValue) {
            if (timerPrice) {
                $timeout.cancel(timerPrice);
            }
            timerPrice = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.getCurrency = function(value) {
            return value !== undefined ? numeral(value).format('0,0') : '';
        };

        $scope.getUrlPropertyDetail = function(property) {
            return Routing.generate('front_property', {type: property.type_name_slug, name: property.name_slug});
        };

    }]);
