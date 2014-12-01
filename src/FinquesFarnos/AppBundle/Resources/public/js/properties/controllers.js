'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['API', '$scope', '$http', '$routeParams', '$log', function (API, $scope, $http, $routeParams, $log) {

        API.getPropertiesFormFilters($scope);

        $scope.formListener = function () {
        }
    }]);
