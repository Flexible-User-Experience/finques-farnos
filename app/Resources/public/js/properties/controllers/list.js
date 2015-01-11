'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$log', function (CFG, API, uiGmapGoogleMapApi, $scope, $timeout, $log) {

        var timerArea, timerRooms, timerPrice = false;
        numeral.language('es');

        $scope.init = function(propertiesFormFilter, selectedPropertiesFormFilter, filteredProperties) {
            $scope.categories = {};
            $scope.type = {};
            $scope.city = {};
            $scope.map = { center: { latitude: 41, longitude: 0 }, zoom: 4, bounds: {}, clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false } };
            $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
            $scope.map.control = {};

            $scope.form = angular.fromJson(propertiesFormFilter);
            $scope.selectedPropertiesFormFilter = angular.fromJson(selectedPropertiesFormFilter);
            $scope.properties = angular.fromJson(filteredProperties);
//            $log.log(filteredProperties);
            $log.log('[selectedPropertiesFormFilter]', selectedPropertiesFormFilter);
//            $log.log($scope.properties);

            $scope.form.area.min = Math.ceil($scope.form.area.min / 10) * 10;
            $scope.form.area.max = Math.floor($scope.form.area.max / 10) * 10;
            $scope.form.area.step = Math.round(($scope.form.area.max - $scope.form.area.min) / CFG.RANGE_STEPS);
            $scope.form.price.min = Math.ceil($scope.form.price.min / 1000) * 1000;
            $scope.form.price.max = Math.floor($scope.form.price.max / 1000) * 1000;
            $scope.form.price.step = Math.round(($scope.form.price.max - $scope.form.price.min) / CFG.RANGE_STEPS);
            if ($scope.selectedPropertiesFormFilter[1] === -1) {
                $scope.type = $scope.form.types[0];
            } else {
                $scope.type = $scope.form.types[$scope.selectedPropertiesFormFilter[1]];
            }
            if ($scope.selectedPropertiesFormFilter[2] === -1) {
                $scope.city = $scope.form.cities[0];
            } else {
                $scope.city = $scope.form.cities[$scope.selectedPropertiesFormFilter[2]];
            }
            $scope.area = $scope.selectedPropertiesFormFilter[3]; //180; // $scope.form.area.min + Math.round(($scope.form.area.max - $scope.form.area.min) / 2);
            $scope.rooms = $scope.selectedPropertiesFormFilter[4]; //5; // $scope.form.rooms.min + Math.round(($scope.form.rooms.max - $scope.form.rooms.min) / 2);
            $scope.price = $scope.selectedPropertiesFormFilter[5]; //60000; //$scope.form.price.min + Math.round(($scope.form.price.max - $scope.form.price.min) / 2);

//            $log.log('init propertiesFormFilter', $scope.form);
//            $log.log('init filteredProperties', $scope.properties);
//            $log.log('init type', $scope.type);
//            $log.log('init city', $scope.city);
//            $log.log('init area', $scope.area);
//            $log.log('init rooms', $scope.rooms);
//            $log.log('init price', $scope.price);
        };

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log(maps);
        });

        $scope.formListener = function() {
            API.getProperties($scope);
        };

        $scope.$watch('area', function(newValue, oldValue) {
            if (timerArea) {
                $timeout.cancel(timerArea);
            }
            timerArea = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined && newValue !== 0) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('rooms', function(newValue, oldValue) {
            if (timerRooms) {
                $timeout.cancel(timerRooms);
            }
            timerRooms = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined && newValue !== 0) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('price', function(newValue, oldValue) {
            if (timerPrice) {
                $timeout.cancel(timerPrice);
            }
            timerPrice = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined && newValue !== 0) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.getCurrency = function(value) {
            return value !== undefined ? numeral(value).format('0,0') : '';
        };

        $scope.getUrlPropertyDetail = function(property) {
            return Routing.generate('front_property', {type: property.type_name_slug, city: property.city_name_slug, name: property.name_slug, reference: property.reference});
        };

        $scope.getSerializedCategories = function() {
            var ss = '';
            angular.forEach($scope.categories, function(value) {
                ss = ss + value.id + '-';
            }, ss);

            if (ss === '') {
                ss = '-1';
            } else {
                ss = ss.slice(0, -1); // remove last '-' char from serialized categories
            }
            $log.log($scope.categories);
            $log.log(ss);

            return ss;
        };

    }]);
