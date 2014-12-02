'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', '$scope', '$timeout', '$routeParams', '$log', function (CFG, API, $scope, $timeout, $routeParams, $log) {

        numeral.language('es');
        var timerType = false;
        var timerArea = false;
        var timerRooms = false;
        var timerPrice = false;
        var r = API.getPropertiesFormFilters($scope);
        $log.log(r);

        $scope.firstCallFinished = false;

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

    }]);
