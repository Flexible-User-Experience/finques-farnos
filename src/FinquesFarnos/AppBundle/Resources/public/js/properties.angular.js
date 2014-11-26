var propertiesApp = angular.module('propertiesApp', []);

propertiesApp.controller('MainCtrl', function ($scope) {
    $scope.name = 'hit';
});

propertiesApp.config(['$interpolateProvider', function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
}]);
