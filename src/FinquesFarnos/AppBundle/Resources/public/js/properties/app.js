'use strict';

angular.module('propertiesApp', [
        'ngCookies',
        'ngResource',
        'ngSanitize',
        'ngRoute',
        'uiGmapgoogle-maps',
        'vr.directives.slider'
    ]).config(['$interpolateProvider', function ($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]).config(function(uiGmapGoogleMapApiProvider) {
        uiGmapGoogleMapApiProvider.configure({
            //    key: 'your api key',
            v: '3.17',
            libraries: 'geometry,visualization'
        });
    })
    .constant('CFG', {
        DELAY: 600,
        RANGE_STEPS: 20
    })
;
