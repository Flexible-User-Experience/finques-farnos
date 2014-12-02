'use strict';

angular.module('propertiesApp')
    .service('API', ['CFG', '$http', '$q', '$log', function(CFG, $http, $q, $log) {

        this.getPropertiesFormFilters = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_form_filter', {_format: 'json'}))
                .success(function(response) {
                    $log.log('getPropertiesFormFilters', response);
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
                })
                .error(function(data) {
                    $log.error('getProperties', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

    }]);
