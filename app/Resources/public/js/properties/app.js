'use strict';

angular.module('propertiesApp', [
        'ngCookies',
        'ngResource',
        'ngSanitize',
        'ngRoute',
        'truncate',
        'checklist-model',
        'nemLogging',
        'uiGmapgoogle-maps'
    ]).config(['$interpolateProvider', function ($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]).config(function(uiGmapGoogleMapApiProvider) {
        uiGmapGoogleMapApiProvider.configure({
            key: 'AIzaSyCpZkgUPZNnkE0MDxFP3Vx6WmDoiMPoJ74',
            v: '3.35',
            language: 'es',
            libraries: ''
        });
    })
    .constant('CFG', {
        DELAY: 600,
        RANGE_STEPS: 20
    })
;
