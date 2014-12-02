'use strict';

angular.module('propertiesApp')
    .service('API', ['$http', '$q', '$log', function($http, $q, $log) {

        this.getPropertiesFormFilters = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_form_filter', {_format: 'json'}))
                .success(function(response) {
                    $log.log('api_get_properties_filters', response);
                    $scope.form = response;
                    $scope.area = $scope.form.area.min + Math.round(($scope.form.area.max - $scope.form.area.min) / 2);
                    $scope.rooms = $scope.form.rooms.min + Math.round(($scope.form.rooms.max - $scope.form.rooms.min) / 2);
                    $scope.price = $scope.form.price.min + Math.round(($scope.form.price.max - $scope.form.price.min) / 2);
                    $scope.firstCallFinished = true;
                })
                .error(function(data) {
                    $log.error('error', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

        this.getProperties = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_filtered', {type: $scope.type, area: $scope.area, rooms: $scope.rooms, price: $scope.price, _format: 'json'}))
                .success(function(response) {
                    $log.log('api_get_properties', response.length, response);
                    $scope.properties = response;
                })
                .error(function(data) {
                    $log.error('error', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

    }]);
