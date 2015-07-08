

odooApp.controller('CreateDriverCtrl', function($scope, vModelService, $routeParams, $location) {
    $scope.status = null;
    $scope.driver_form_submit = function() {
        $scope.status = vModelService.saveDriver($scope.driver);
        $location.path('/driver');
        console.log($scope.status);
    };

    if ($routeParams.driverID) {
        vModelService.getDriverById($routeParams.driverID).then(function(resource) {
            $scope.driver = resource.data;
        });
    }
    $scope.cancel_new_driver = function() {
        $location.path('/driver');
    };
});


odooApp.controller('ViewDriverCtrl', function($scope, vModelService, $routeParams, $location) {
    $scope.btn_view_driver = true;
    if ($routeParams.driverID) {
        vModelService.getDriverById($routeParams.driverID).then(function(resource) {
            $scope.driver = resource.data;
        });
    }
    $scope.cancel_view_driver = function() {
        $location.path('/driver');
    };
});


odooApp.controller('driverListCtrl', function($scope, $location, Vehicles) {
    $scope.list_driver_flag = true;
    $scope.btn_create_driver = true;
    $scope.create_driver = function() {
        $location.path('/driver/create');
    };

    $scope.drivers = {};
    Vehicles.loadDrivers().then(function(response) {
        $scope.drivers = response.data;
    });

    $scope.edit_driver = function(driver_id) {
        $location.path('/driver/edit/' + driver_id);
    };

    $scope.view_driver = function(driver_id) {
        $location.path('/driver/view/' + driver_id);
    };

    $scope.delete_driver = function(driver_id) {
        Vehicles.deleteDriver(driver_id).then(function(data) {
            if (data.status === 200) {
                Vehicles.loadDrivers().then(function(response) {
                    $scope.drivers = response.data;
                });
                $location.path('/driver');
            }
        });
    };
});

odooApp.controller('driverEditCtrl', function($scope, $location, $routeParams, vModelService) {
    $scope.btn_edit_driver = true;
    if ($routeParams.driverID) {
        vModelService.getDriverById($routeParams.driverID).then(function(resource) {
            $scope.driver = resource.data;
        });
    }

    $scope.save_edit_driver = function(driver) {
        if (driver.name && driver.mobileno) {
            $scope.status = vModelService.saveDriver(driver);
            $location.path('/driver');
        }
        console.log(driver);
    };
    $scope.cancel_edit_driver = function() {
        $location.path('/driver');
    };
});