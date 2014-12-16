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
        RANGE_STEPS: 20
    })
;

'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log', function (CFG, API, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log) {

        numeral.language('es');
        var timerArea, timerRooms, timerPrice = false;
        $scope.firstCallFinished = false;
        $scope.type = {};
        $scope.map = { center: { latitude: 41, longitude: 0 }, zoom: 4, bounds: {}, clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false } };
        $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
        $scope.map.control = {};

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log(maps);
        });

        var getPropertiesFormFiltersPromise = API.getPropertiesFormFilters($scope);
        getPropertiesFormFiltersPromise.then(
            function() {
                $scope.map.control.refresh();
                var getPropertiesPromise = API.getProperties($scope);
                getPropertiesPromise.then(
                    function() {
                        $scope.firstCallFinished = true;
                    },
                    function(reason) { $log.error('get properties promise error', reason); }
                );
            },
            function(reason) { $log.error('get properties form filters promise error', reason); }
        );

        $scope.formListener = function() {
            API.getProperties($scope);
        };

        $scope.$watch('area', function(newValue, oldValue) {
            if (timerArea) {
                $timeout.cancel(timerArea);
            }
            timerArea = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('rooms', function(newValue, oldValue) {
            if (timerRooms) {
                $timeout.cancel(timerRooms);
            }
            timerRooms = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('price', function(newValue, oldValue) {
            if (timerPrice) {
                $timeout.cancel(timerPrice);
            }
            timerPrice = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.getCurrency = function(value) {
            return value !== undefined ? numeral(value).format('0,0') : '';
        };

        $scope.getUrlPropertyDetail = function(property) {
            return Routing.generate('front_property', {type: property.type_name_slug, name: property.name_slug});
        };

    }]);

'use strict';

angular.module('propertiesApp')
    .service('API', ['CFG', '$http', '$q', '$log', function(CFG, $http, $q, $log) {

        this.getPropertiesFormFilters = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_form_filter', {_format: 'json'}))
                .success(function(response) {
                    $log.log('getPropertiesFormFilters', response);
                    $scope.form = response;
                    $scope.form.area.min = Math.ceil($scope.form.area.min / 10) * 10;
                    $scope.form.area.max = Math.floor($scope.form.area.max / 10) * 10;
                    $scope.form.area.step = Math.round(($scope.form.area.max - $scope.form.area.min) / CFG.RANGE_STEPS);
                    $scope.form.price.min = Math.ceil($scope.form.price.min / 1000) * 1000;
                    $scope.form.price.max = Math.floor($scope.form.price.max / 1000) * 1000;
                    $scope.form.price.step = Math.round(($scope.form.price.max - $scope.form.price.min) / CFG.RANGE_STEPS);
                    $scope.area = $scope.form.area.max; // + Math.round(($scope.form.area.max - $scope.form.area.min) / 2);
                    $scope.rooms = $scope.form.rooms.min; // + Math.round(($scope.form.rooms.max - $scope.form.rooms.min) / 2);
                    $scope.price = $scope.form.price.min; // + Math.round(($scope.form.price.max - $scope.form.price.min) / 2);
                    $scope.type = $scope.form.types[0];
                    deferred.resolve(response);
                })
                .error(function(data) {
                    $log.error('getPropertiesFormFilters', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

        this.getProperties = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_filtered', {type: $scope.type.id, area: $scope.area, rooms: $scope.rooms, price: $scope.price, _format: 'json'}))
                .success(function(response) {
                    $log.log('getProperties', response.length, response);
                    $scope.properties = response;
                    deferred.resolve(response);
                })
                .error(function(data) {
                    $log.error('getProperties', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

    }]);
