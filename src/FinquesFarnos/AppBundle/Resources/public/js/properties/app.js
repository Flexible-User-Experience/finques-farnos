'use strict';

angular.module('propertiesApp', [
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngRoute',
    'vr.directives.slider'
]).config(['$interpolateProvider', function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
}]);


