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

'use strict';

angular.module('propertiesApp')
    .service('API', ['CFG', '$http', '$q', '$log', function(CFG, $http, $q, $log) {

        this.getProperties = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_filtered', {type: $scope.type.id, area: $scope.area, rooms: $scope.rooms, price: $scope.price, _format: 'json'}))
                .success(function(response) {
                    $log.log('getProperties', response.length, 'fetched');
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

'use strict';

angular.module('propertiesApp')
    .controller('PropertyDetailCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$log', function (CFG, uiGmapGoogleMapApi, $scope, $log) {

        $scope.init = function(localization) {
            $scope.map = {
                zoom: 14,
                radius: 500,
                stroke: {
                    color: '#D86F24',
                    weight: 1,
                    opacity: 1
                },
                fill: {
                    color: '#D86F24',
                    opacity: 0.25
                },
                geodesic: true,
                draggable: false,
                clickable: false,
                editable: false,
                visible: true
            };
            $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
            $scope.map.control = {};

            $scope.localization = angular.fromJson(localization);
        };

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log(maps);
        });

        $scope.isShowMapArea = function(value) {
            return value === CFG.SHOW_MAP_AREA;
        };

    }]);

'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$log', function (CFG, API, uiGmapGoogleMapApi, $scope, $timeout, $log) {

        var timerArea, timerRooms, timerPrice = false;
        numeral.language('es');

        $scope.init = function(propertiesFormFilter, filteredProperties) {
            $scope.type = {};
            $scope.map = { center: { latitude: 41, longitude: 0 }, zoom: 4, bounds: {}, clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false } };
            $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
            $scope.map.control = {};

            $scope.form = angular.fromJson(propertiesFormFilter);
            $scope.properties = angular.fromJson(filteredProperties);

            $scope.form.area.min = Math.ceil($scope.form.area.min / 10) * 10;
            $scope.form.area.max = Math.floor($scope.form.area.max / 10) * 10;
            $scope.form.area.step = Math.round(($scope.form.area.max - $scope.form.area.min) / CFG.RANGE_STEPS);
            $scope.form.price.min = Math.ceil($scope.form.price.min / 1000) * 1000;
            $scope.form.price.max = Math.floor($scope.form.price.max / 1000) * 1000;
            $scope.form.price.step = Math.round(($scope.form.price.max - $scope.form.price.min) / CFG.RANGE_STEPS);
            $scope.type = $scope.form.types[0];
            $scope.area = 180; // $scope.form.area.min + Math.round(($scope.form.area.max - $scope.form.area.min) / 2);
            $scope.rooms = 5; // $scope.form.rooms.min + Math.round(($scope.form.rooms.max - $scope.form.rooms.min) / 2);
            $scope.price = 60000; //$scope.form.price.min + Math.round(($scope.form.price.max - $scope.form.price.min) / 2);

//            $log.log('init propertiesFormFilter', $scope.form);
//            $log.log('init filteredProperties', $scope.properties);
//            $log.log('init type', $scope.type);
//            $log.log('init area', $scope.area);
//            $log.log('init rooms', $scope.rooms);
//            $log.log('init price', $scope.price);
        };

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log(maps);
        });

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
