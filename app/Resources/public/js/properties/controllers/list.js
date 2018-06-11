'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', '$scope', '$timeout', function (CFG, API, $scope, $timeout) {

        var timerArea, timerRooms, timerPrice = false;
        numeral.language('es');

        $scope.init = function(propertiesFormFilter, selectedPropertiesFormFilter, filteredProperties) {

            $scope.form = angular.fromJson(propertiesFormFilter);
            $scope.selectedPropertiesFormFilter = angular.fromJson(selectedPropertiesFormFilter);
            $scope.properties = angular.fromJson(filteredProperties);

            $scope.form.area.max = Math.floor($scope.form.area.max / 10) * 10;
            $scope.form.price.max = Math.floor($scope.form.price.max / 1000) * 1000;
            $scope.categories = [];
            if ($scope.selectedPropertiesFormFilter[0].length === 0) {
                $scope.categories = [];
            } else {
                angular.forEach($scope.selectedPropertiesFormFilter[0], function(value) {
                    $scope.categories.push(parseInt(value));
                });
            }
            $scope.type = $scope.selectedPropertiesFormFilter[1];
            $scope.city = $scope.selectedPropertiesFormFilter[2];
            $scope.area = $scope.form.area.max;
            $scope.rooms = $scope.form.rooms.max;
            $scope.price = $scope.form.price.max;
        };

        $scope.formListener = function() {
            API.getProperties($scope);
        };

        $scope.$watch('area', function(newValue, oldValue) {
            if (timerArea) {
                $timeout.cancel(timerArea);
            }
            timerArea = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined && newValue !== oldValue) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('rooms', function(newValue, oldValue) {
            if (timerRooms) {
                $timeout.cancel(timerRooms);
            }
            timerRooms = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined && newValue !== oldValue) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('price', function(newValue, oldValue) {
            if (timerPrice) {
                $timeout.cancel(timerPrice);
            }
            timerPrice = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined && newValue !== oldValue) { API.getProperties($scope); }
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
                ss = ss + value + '-';
            }, ss);
            if (ss === '') {
                ss = '-1';
            } else {
                ss = ss.slice(0, -1); // remove last '-' char from serialized categories
            }

            return ss;
        };

    }]);
