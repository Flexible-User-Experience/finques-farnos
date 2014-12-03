'use strict';

angular.module('propertiesApp')
    .service('API', ['CFG', '$http', '$q', '$log', function(CFG, $http, $q, $log) {

        this.getPropertiesFormFilters = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_form_filter', {_format: 'json'}))
                .success(function(response) {
                    $log.log('getPropertiesFormFilters', response);
                    $scope.form = response;
                    $scope.form.area.min = Math.ceil($scope.form.area.min / 10) * 10;
                    $scope.form.area.max = Math.floor($scope.form.area.max / 10) * 10;
                    $scope.form.area.step = Math.round(($scope.form.area.max - $scope.form.area.min) / CFG.RANGE_STEPS);
                    $scope.form.price.min = Math.ceil($scope.form.price.min / 1000) * 1000;
                    $scope.form.price.max = Math.floor($scope.form.price.max / 1000) * 1000;
                    $scope.form.price.step = Math.round(($scope.form.price.max - $scope.form.price.min) / CFG.RANGE_STEPS);
                    $scope.area = $scope.form.area.max; // + Math.round(($scope.form.area.max - $scope.form.area.min) / 2);
                    $scope.rooms = $scope.form.rooms.min; // + Math.round(($scope.form.rooms.max - $scope.form.rooms.min) / 2);
                    $scope.price = $scope.form.price.min; // + Math.round(($scope.form.price.max - $scope.form.price.min) / 2);
                    $scope.type = $scope.form.types[0];
                    deferred.resolve(response);
                })
                .error(function(data) {
                    $log.error('getPropertiesFormFilters', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

        this.getProperties = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_filtered', {type: $scope.type.id, area: $scope.area, rooms: $scope.rooms, price: $scope.price, _format: 'json'}))
                .success(function(response) {
                    $log.log('getProperties', response.length, response);
                    $scope.properties = response;
                    deferred.resolve(response);
                })
                .error(function(data) {
                    $log.error('getProperties', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

    }]);
