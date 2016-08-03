'use strict';

angular.module('propertiesApp')
    .service('API', ['CFG', '$http', '$q', '$log', function(CFG, $http, $q, $log) {

        this.getProperties = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_filtered', {categories: $scope.getSerializedCategories(), type: $scope.type, city: $scope.city, area: $scope.area, rooms: $scope.rooms, price: $scope.price, _format: 'json'}))
                .success(function(response) {
                    $scope.properties = response;
                    deferred.resolve(response);
                })
                .error(function(data) {
                    $log.error('[API.getProperties]', data);
                    deferred.reject(data);
                });

            if ($scope.type > 0) {
                // on selected type, update filtered cities
                $http.get(Routing.generate('api_get_api_cities_by_type', {type: $scope.type, _format: 'json'}))
                    .success(function(response) {
                        $log.log(response);
                        deferred.resolve(response);
                    })
                    .error(function(data) {
                        $log.error('[API.getProperties - on selected type changed]', data);
                        deferred.reject(data);
                    });
            }

            return deferred.promise;
        };

    }]);
