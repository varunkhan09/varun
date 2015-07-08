'use strict';
var odooApp = angular.module('fb-odoo-app', ['ngRoute', 'ui.bootstrap', 'ui.directives']);
odooApp.config(function($routeProvider) {
    $routeProvider
            .when('/', {
                templateUrl: 'views/vehicles.html'
                //controller: 'fbOdooMainController'
            })
            .when('/vehicles', {
                templateUrl: 'views/vehicles.html'
                        //controller: 'fbOdooMainController',
            })
            .when('/vehicles-status', {
                templateUrl: 'views/vehicles-status.html'
                        // controller: 'fbOdooMainController'
            })
            .when('/vehicle/edit/:vehicleID', {
                templateUrl: 'views/create-new-vehicles.html',
                //  controller: 'editVehicleController'
            })
            .when('/vehicle/view/:vehicleID', {
                templateUrl: 'views/vehicle-view.html'
            })

            .when('/driver', {
                templateUrl: 'views/driver.html',
                //controller: 'CreateDriverCtrl'
            })

            .when('/driver/create', {
                templateUrl: 'views/driver-create.html',
                //controller: 'CreateDriverCtrl'
            })

            .when('/driver/edit/:driverID', {
                templateUrl: 'views/driver-edit.html',
                //controller: 'editDriverCtrl'
            })
            .when('/driver/view/:driverID', {
                templateUrl: 'views/driver-view.html',
                //controller: 'ViewDriverCtrl'
            })
            /*
             .when('/model/create',{
             templateUrl: 'views/model.html',
             //controller: 'ViewDriverCtrl'
             })
             */
            .when('/model', {
                templateUrl: 'views/model-list.html'
            })
            .when('/odometer/:vehicleID', {
                templateUrl: 'views/vehicle-odometer.html'
            })

            .when('/vehicles-cost-logs', {
                templateUrl: 'views/vehicles-cost-log.html',
                controller: 'vehicleCostLog'
            })


            .when('/vehicles-clogs-list', {
                templateUrl: 'views/vehicle-cost-log-list.html',
                controller: 'vehicleCostLogList'
            })

            .when('/vehicles-odometer-logs', {
                templateUrl: 'views/vehicles-odometer-log.html',
                controller: 'vehicleOdometerLog'
            })

            .when('/vehicles-ologs-list', {
                templateUrl: 'views/vehicle-odometer-log-list.html',
                controller: 'vehicleOdometerLogList'
            })

            .when('/vehicles-alogs', {
                templateUrl: 'views/vehicle-additional-log.html',
            })
            .when('/vehicles-alogs-list', {
                templateUrl: 'views/vehicle-additional-log-list.html',
            })

            .when('/vehicle-cost/:vehicleID', {
                templateUrl: 'views/vehicle-cost.html'
            })
            .when('/vehicle-fuel/:vehicleID', {
                templateUrl: 'views/vehicle-fuel.html'
            })

            .when('/vehicle-fuel-logs', {
                templateUrl: 'views/vehicle-fuel-logs.html',
                controller: 'vehicleFuelLog'
            })

            .when('/vehicle-flogs-list', {
                templateUrl: 'views/vehicle-fuel-log-list.html',
                controller: 'vehicleFuelLogList'
            })

            .when('/vehicle-fservice-list', {
                templateUrl: 'views/vehicle-service-log-list.html',
                controller: 'vehicleServiceLogList'
            })

            .when('/vehicle-service-logs', {
                templateUrl: 'views/vehicle-service-log.html',
                controller: 'vehicleServiceLog'
            })
            .when('/vehicle-service/:vehicleID', {
                templateUrl: 'views/vehicle-service.html',
                //controller: 'ViewDriverCtrl'
            })
            .when('/report', {
                templateUrl: 'views/report.html'
            })
            .otherwise({redirectTo: '/'});
});
