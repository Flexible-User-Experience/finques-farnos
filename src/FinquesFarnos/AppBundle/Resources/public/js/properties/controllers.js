'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['$scope', '$http', '$routeParams', '$log', function ($scope, $http, $routeParams, $log) {

    $scope.name = 'hit';
    $scope.area = 5;
    $scope.rooms = 4;
    $scope.price = 3;
    $scope.search = 2;

}]);
