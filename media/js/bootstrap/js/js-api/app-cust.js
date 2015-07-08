"use strict";
myshop.controller('ShopCustomerCtrl', function($scope,$rootScope) {
	console.log("OK");
	$scope.datepickerOptions ={
		 	format: 'yyyy-mm-dd',
		  	autoclose: true,
    		weekStart: 0
	};
	 $scope.date = '2000-03-12'
});
