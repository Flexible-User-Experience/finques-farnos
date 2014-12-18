'use strict';

angular.module('propertiesApp', [
        'ngCookies',
        'ngResource',
        'ngSanitize',
        'ngRoute',
        'uiGmapgoogle-maps'
    ]).config(['$interpolateProvider', function ($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]).config(function(uiGmapGoogleMapApiProvider) {
        uiGmapGoogleMapApiProvider.configure({
            key: 'AIzaSyB332MhD5g142kIo79ZagVcXUidQwHbWwk',
            v: '3.17',
            language: 'es',
            sensor: false,
            libraries: 'drawing,geometry,visualization'
        });
    })
    .constant('CFG', {
        DELAY: 600,
        RANGE_STEPS: 20,
        SHOW_MAP_ALL: 0,
        SHOW_MAP_STREET: 1,
        SHOW_MAP_AREA: 2
    })
;
