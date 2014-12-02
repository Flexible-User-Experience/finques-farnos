'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', '$scope', '$timeout', '$routeParams', '$log', function (CFG, API, $scope, $timeout, $routeParams, $log) {

        numeral.language('es');
        $scope.firstCallFinished = false;
        $scope.type = {};
        var timerType = false;
        var timerArea = false;
        var timerRooms = false;
        var timerPrice = false;

        var getPropertiesFormFiltersPromise = API.getPropertiesFormFilters($scope);
        getPropertiesFormFiltersPromise.then(
            function(response) {
                $scope.form = response;
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
                $log.log('type', $scope.type);
                API.getProperties($scope);
                $scope.firstCallFinished = true;
            },
            function(reason) {
                $log.error('promise error', reason);
            }
        );



        $scope.formListener = function() {
            API.getProperties($scope);
        };

//        $scope.$watch('type', function(newValue, oldValue) {
//            if (timerType) {
//                $timeout.cancel(timerType)
//            }
//            timerType = $timeout(function() {
//                $log.log('n', newValue);
//                $log.log('o', oldValue);
//                if (newValue !== undefined) API.getProperties($scope);
//            }, CFG.DELAY)
//        });
//
//        $scope.$watch('area', function(newValue, oldValue) {
//            if (timerArea) {
//                $timeout.cancel(timerArea)
//            }
//            timerArea = $timeout(function() {
//                if (newValue !== undefined) API.getProperties($scope);
//            }, CFG.DELAY)
//        });
//
//        $scope.$watch('rooms', function(newValue, oldValue) {
//            if (timerRooms) {
//                $timeout.cancel(timerRooms)
//            }
//            timerRooms = $timeout(function() {
//                if (newValue !== undefined) API.getProperties($scope);
//            }, CFG.DELAY)
//        });
//
//        $scope.$watch('price', function(newValue, oldValue) {
//            if (timerPrice) {
//                $timeout.cancel(timerPrice)
//            }
//            timerPrice = $timeout(function() {
//                if (newValue !== undefined) API.getProperties($scope);
//            }, CFG.DELAY)
//        });

        $scope.getCurrency = function(value) {
            return value !== undefined ? numeral(value).format('0,0') : '';
        };

        $scope.getUrlPropertyDetail = function(property) {
            return Routing.generate('front_property', {type: property.type_name_slug, name: property.name_slug});
        };

    }]);
