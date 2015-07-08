"use strict";

// myshop.constant('ROOT', (function() {
//     var resource = 'http://pos.flaberry.com';
//     return {
//         DOMAIN: resource,
//         IMAGES: resource + '/media/images',
//         JS: resource + '/media/js/bootstrap/js',
//         CSS: resource + '/media/css/bootstrap/css',
//     }
// })());


myshop.constant('ROOT', (function() {
    var resource = 'http://varun.floshowers.com:8882';
    return {
        DOMAIN: resource,
        IMAGES: resource + '/media/images',
        JS: resource + '/media/js/bootstrap/js',
        CSS: resource + '/media/css/bootstrap/css',
    }
})());




/**************************************************************/
/*          DELIVERY ZONE SERVICE 
/*
/***************************************************************/
myshop.service('DELIVERY_ZONE', function(ROOT,$http){
	var basePath = ROOT.DOMAIN+'/app/module/myshop/dzone/delivery_charge_action.php?';
	this.save_delivery_charge = function(object) {
        return $http.post(basePath + 'save_delivery_charge', object).then(function(response) {
            return response;
        });
    };

    // new added on 24-05-2015
    this.getShopDeliveryCharge = function(shop_id) {
        return $http.get(basePath + 'get_shop_delivery_charge_info&shop_id=' + shop_id);
    };

    this.update_delivery_charge = function(object) {
        return $http.post(basePath + 'update_delivery_charge', object).then(function(response) {
            return response;
        });
    };

});



/*********************************************/
/*       SHOP SERVICE                        */
/*                                           */
/*********************************************/

myshop.service('ShopService', function(ROOT,$http){
    var basePath = ROOT.DOMAIN+'/app/module/myshop/shop/shop_operation.php?';
    this.saveShop = function(object) {
        return $http.post(basePath + 'save_shop', object).then(function(response) {
            return response;
        });
    };

    this.saveUserShop = function(object) {
        return $http.post(basePath + 'save_user_shop', object).then(function(response) {
            return response;
        });
    };


    this.getShopInfo = function(shop_id) {
        return $http.get(basePath + 'get_shop_info&shop_id=' + shop_id);
    };

    this.updateShop = function(object) {
        return $http.post(basePath + 'update_shop_info', object).then(function(response) {
            return response;
        });
    };

});




/*********************************************/
/*       EMPLOYEE SERVICE                        */
/*                                           */
/*********************************************/

myshop.service('EmpService', function(ROOT,$http){
    var basePath = ROOT.DOMAIN+'/app/module/myshop/employee/employee_operation.php?';
    this.saveEmployee = function(object) {
        return $http.post(basePath + 'save_employee', object).then(function(response) {
            return response;
        });
    };

    this.getEmployees = function() {
        return $http.get(basePath + 'get_employees_list');
    };

    this.getRoles = function() {
        return $http.get(basePath + 'get_roles');
    };


    this.getEmployeeInfo = function(emp_id) {
        return $http.get(basePath + 'get_employee_info&emp_id=' + emp_id);
    };

    this.updateEmployee = function(object) {
        return $http.post(basePath + 'update_employee_info', object).then(function(response) {
            return response;
        });
    };

    this.deleteEmployee = function(emp_id) {
        return $http.get(basePath + 'deactivate_employee&emp_id=' + emp_id);
    };

});


/*********************************************/
/*       PINCODE SERVICE                        */
/*                                           */
/*********************************************/

myshop.service('PincodeService', function(ROOT,$http){
    var basePath = ROOT.DOMAIN+'/app/module/myshop/dzone/pincode_list.php?';
    this.getShopPincode = function(shop_id) {
        return $http.get(basePath + 'get_listed_pincode&shop_id=' + shop_id);
    };
});
