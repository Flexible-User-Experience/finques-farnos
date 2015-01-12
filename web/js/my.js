'use strict';

angular.module('propertiesApp', [
        'ngCookies',
        'ngResource',
        'ngSanitize',
        'ngRoute',
        'truncate',
        'checklist-model',
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
    .service('API', ['CFG', '$http', '$q', '$log', function(CFG, $http, $q, $log) {

        this.getProperties = function($scope) {
            var deferred = $q.defer();
            $http.get(Routing.generate('api_properties_api_filtered', {categories: $scope.getSerializedCategories(), type: $scope.type.id, city: $scope.city.id, area: $scope.area, rooms: $scope.rooms, price: $scope.price, _format: 'json'}))
                .success(function(response) {
                    $scope.properties = response;
                    deferred.resolve(response);
                })
                .error(function(data) {
                    $log.error('[API.getProperties]', data);
                    deferred.reject(data);
                });

            return deferred.promise;
        };

    }]);

'use strict';

angular.module('propertiesApp')
    .controller('PropertyDetailCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$log', function (CFG, uiGmapGoogleMapApi, $scope, $log) {

        $scope.init = function(localization) {
            $scope.localization = angular.fromJson(localization);
            $scope.map = {
                zoom: 16,
                radius: $scope.localization.radius,
                stroke: {
                    color: '#D86F24',
                    weight: 1,
                    opacity: 1
                },
                fill: {
                    color: '#D86F24',
                    opacity: 0.25
                },
                center: { latitude: $scope.localization.coords.latitude, longitude: $scope.localization.coords.longitude }
            };
            $scope.map.options = { scrollwheel: false, draggable: true, maxZoom: 20 };
            $scope.map.control = {};
        };

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log(maps);
        });

    }]);

'use strict';

angular.module('propertiesApp')
    .controller('MainCtrl', ['CFG', 'API', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$log', function (CFG, API, uiGmapGoogleMapApi, $scope, $timeout, $log) {

        var timerArea, timerRooms, timerPrice = false;
        numeral.language('es');

        $scope.init = function(propertiesFormFilter, selectedPropertiesFormFilter, filteredProperties) {
            $scope.map = { center: { latitude: 41, longitude: 0 }, zoom: 4, bounds: {}, clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false } };
            $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 15 };
            $scope.map.control = {};

            $scope.form = angular.fromJson(propertiesFormFilter);
            $scope.selectedPropertiesFormFilter = angular.fromJson(selectedPropertiesFormFilter);
            $scope.properties = angular.fromJson(filteredProperties);

            $scope.form.area.min = Math.ceil($scope.form.area.min / 10) * 10;
            $scope.form.area.max = Math.floor($scope.form.area.max / 10) * 10;
            $scope.form.area.step = Math.round(($scope.form.area.max - $scope.form.area.min) / CFG.RANGE_STEPS);
            $scope.form.price.min = Math.ceil($scope.form.price.min / 1000) * 1000;
            $scope.form.price.max = Math.floor($scope.form.price.max / 1000) * 1000;
            $scope.form.price.step = Math.round(($scope.form.price.max - $scope.form.price.min) / CFG.RANGE_STEPS);
            $scope.categories = [];
            if ($scope.selectedPropertiesFormFilter[0].length === 0) {
                $scope.categories = [];
            } else {
                angular.forEach($scope.selectedPropertiesFormFilter[0], function(value) {
                    $scope.categories.push(parseInt(value));
                });
            }
            if ($scope.selectedPropertiesFormFilter[1] === -1) {
                $scope.type = $scope.form.types[0];
            } else {
                $scope.type = $scope.form.types[$scope.selectedPropertiesFormFilter[1]];
            }
            if ($scope.selectedPropertiesFormFilter[2] === -1) {
                $scope.city = $scope.form.cities[0];
            } else {
                $scope.city = $scope.form.cities[$scope.selectedPropertiesFormFilter[2]];
            }
            $scope.area = $scope.selectedPropertiesFormFilter[3];
            $scope.rooms = $scope.selectedPropertiesFormFilter[4];
            $scope.price = $scope.selectedPropertiesFormFilter[5];
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
                if (newValue !== undefined && oldValue !== undefined && newValue !== oldValue) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('rooms', function(newValue, oldValue) {
            if (timerRooms) {
                $timeout.cancel(timerRooms);
            }
            timerRooms = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined && newValue !== oldValue) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.$watch('price', function(newValue, oldValue) {
            if (timerPrice) {
                $timeout.cancel(timerPrice);
            }
            timerPrice = $timeout(function() {
                if (newValue !== undefined && oldValue !== undefined && newValue !== oldValue) { API.getProperties($scope); }
            }, CFG.DELAY);
        });

        $scope.getCurrency = function(value) {
            return value !== undefined ? numeral(value).format('0,0') : '';
        };

        $scope.getUrlPropertyDetail = function(property) {
            return Routing.generate('front_property', {type: property.type_name_slug, city: property.city_name_slug, name: property.name_slug, reference: property.reference});
        };

        $scope.getSerializedCategories = function() {
            var ss = '';
            angular.forEach($scope.categories, function(value) {
                ss = ss + value + '-';
            }, ss);
            if (ss === '') {
                ss = '-1';
            } else {
                ss = ss.slice(0, -1); // remove last '-' char from serialized categories
            }

            return ss;
        };

    }]);
