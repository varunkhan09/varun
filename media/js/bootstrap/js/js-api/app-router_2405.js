"use strict";

var myshop = angular.module('myshop',['ui.bootstrap','ngRoute','ngTagsInput']);
myshop.config(['$routeProvider',function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: "panel.php",
      	controller: "PanelCtrl"
      }).
      when('/delivery', {
        templateUrl: "dzone/delivery.php",
      	controller: "DeliveryCtrl"
      }).
      // when('/user/:user_id/delivery', {
      //   templateUrl: "dzone/delivery.php",
      //   controller: "DeliveryCtrl"
      // }).
      when('/user/:user_id/shop/:shop_id/delivery', {
        templateUrl: "dzone/delivery.php",
        controller: "DeliveryCtrl"
      }).
      when('/dzone', {
        templateUrl: "dzone/dzone.php",
        controller: "DeliveryZoneCtrl"
      }).
      when('/user/:user_id/shop/:shop_id/dzone', {
        templateUrl: "dzone/dzone.php",
        controller: "DeliveryZoneCtrl"
      }).
      when('/dcharge', {
        templateUrl: "dzone/dcharge.php",
      	controller: "DeliveryChargeCtrl"
      }).
      when('/user/:user_id/shop/:shop_id/dcharge', {
        templateUrl: "dzone/dcharge.php",
        controller: "DeliveryChargeCtrl"
      }).
      when('/user/:user_id/shop/:shop_id/emp', {
        templateUrl: "employee/employee.php",
        controller: "EmployeeCtrl"
      }).
      when('/user/:user_id/shop/:shop_id/view-emp/:empid', {
        templateUrl: "employee/viewemp.php",
        controller: "EmployeeViewCtrl"
      }).
       when('/user/:user_id/shop/:shop_id/edit-emp/:empid', {
        templateUrl: "employee/editemp.php",
        controller: "EmployeeEditCtrl"
      }).
      when('/user/:user_id/shop/:shop_id/add-emp', {
        templateUrl: "employee/newemp.php",
        controller: "EmployeeAddCtrl"
      }).
      // when('/shop/add', {
      //   templateUrl: "shop/newshop.php",
      //   controller: "ShopInfoCtrl"
      // }).
       when('/user/:uid/shop', {
        templateUrl: "shop/newshop.php",
        controller: "ShopAddByUserCtrl"
      }).
       
      when('/user/:user_id/shop/:shop_id', {
        templateUrl: "shop/shop-info.php",
        controller: "ShopInfoEditCtrl"
      }).
     
      when('/shop/info', {
        templateUrl: "shop/shop-info.php",
        controller: "ShopInfoEditCtrl"
      }).
      when('/user/:user_id/shop/:shop_id/update', {
        templateUrl: "shop/shop-info-update.php",
        controller: "ShopInfoEditCtrl"
      }).
      // when('/shop/:shop_id/add-customer', {
      //   templateUrl: "http://varun.floshowers.com:8882/app/module/customer/new-customer.php",
      //   controller: "ShopCustomerCtrl"
      // }).
      otherwise({redirectTo: '/'});
}]);