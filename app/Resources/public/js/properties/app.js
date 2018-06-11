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
            key: 'AIzaSyB332MhD5g142kIo79ZagVcXUidQwHbWwk',
            v: '3.31',
            language: 'es',
            libraries: ''
        });
    })
    .constant('CFG', {
        DELAY: 600,
        RANGE_STEPS: 20
    })
;
