'use strict';

angular.module('propertiesApp')
    .service('API', ['$http', '$q', '$log', function($http, $q, $log) {

        this.getPropertiesFormFilters = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_test'))
                .success(function (response) {
                    $log.log('response', response);
                    $scope.form = response;
                    $scope.area = $scope.form.area.min;
                    $scope.rooms = $scope.form.rooms.min;
                })
                .error(function (data) {
                    $log.error('error', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

    }]);
