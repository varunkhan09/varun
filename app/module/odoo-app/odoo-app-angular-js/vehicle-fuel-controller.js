
odooApp.controller('vehicleFuelController', function($scope, $routeParams, VFuel, vModelService) {
    $scope.btn_vehicle_fuel_create = true;
    $scope.vehicle_fuel_list_action = true;
    $scope.save_cancel_div_toggle = false;
    $scope.add_new_vehicle_fuel = false;
    $scope.fuel_vehicles = {};
    $scope.drivers = {};
    $scope.driver_by_vid = 0;
    $scope.vehicle_fuel_record = {};
    $scope.vehicleFuel = {
        rate: '',
        liter: '',
        totalcost: '',
        driver: '',
        date: currentDate()
    };


    function currentDate() {
        var d = new Date();
        var month = d.getMonth() + 1;
        if (month < 10) {
            month = "0" + month;
        }
        return d.getFullYear() + "-" + month + "-" + d.getDate();
    }




    $scope.totalcost = 0;

    if (!Object.keys($scope.drivers).length) {
        vModelService.loadDrivers().then(function(response) {
            $scope.drivers = response.data;
        });
    }
    ;

    $scope.set_purchaser = function(driver) {
        $scope.vehicleFuel.driver = driver.name;
        $scope.vehicleFuel.purchaser = driver.did;
    };


   
        if ($routeParams.vehicleID) {
            doLoadingTask($routeParams.vehicleID);
        }
    

    function doLoadingTask(vehicleID) {
        VFuel.get_vehicle_fuel_details_by_id(vehicleID).then(function(response) {
            $scope.vehicle_fuel_record = response.data;
            angular.forEach($scope.vehicle_fuel_record, function(v_item) {
                $scope.totalcost += v_item.rate * v_item.liter;
            });
        });
    }
    ;

    $scope.vehicle_fuel_create = function() {
        $scope.vehicle_fuel_list_action = !$scope.vehicle_fuel_list_action;
        $scope.save_cancel_div_toggle = !$scope.save_cancel_div_toggle;
        $scope.btn_vehicle_fuel_create = !$scope.btn_vehicle_fuel_create;
        $scope.add_new_vehicle_fuel = !$scope.add_new_vehicle_fuel;

        if (!Object.keys($scope.fuel_vehicles).length) {
            VFuel.load_vehicles().then(function(response) {
                $scope.fuel_vehicles = response.data;
            });
        }
    };


    $scope.$watch('vehicleFuel.rate', function(rate) {
        if (!rate) {
            $scope.vehicleFuel.totalcost = '';
        }
        if (rate > 0) {
            $scope.vehicleFuel.rate = rate;
            $scope.vehicleFuel.totalcost = $scope.vehicleFuel.liter * $scope.vehicleFuel.rate;
        }
    });
    $scope.$watch('vehicleFuel.liter', function(liter) {
        if (!liter) {
            $scope.vehicleFuel.totalcost = '';
        }
        if (liter > 0) {
            $scope.vehicleFuel.liter = liter;
            $scope.vehicleFuel.totalcost = $scope.vehicleFuel.liter * $scope.vehicleFuel.rate;
        }

    });


    if ($routeParams.vehicleID) {
        $scope.vehicleFuel.vehicle_id = $routeParams.vehicleID;
        VFuel.get_vehicle_by_Id($routeParams.vehicleID).then(function(response) {
            $scope.vehicleFuel.vehicle = response.data.model_brand + '/' + response.data.model_name + '/' + response.data.licence_plate;
            $scope.driver_by_vid = response.data.driver_id;
            vModelService.getDriverById($scope.driver_by_vid).then(function(response) {
                $scope.set_purchaser(response.data);
            });
        });
    }

    $scope.set_vehicle = function(vehicle) {
        $scope.vehicleFuel.vehicle_id = vehicle.vid;
        $scope.vehicleFuel.vehicle = vehicle.model_brand + '/' + vehicle.model_name + '/' + vehicle.licence_plate;
    };



    $scope.vehicle_fuel_save = function() {
        VFuel.save_vehicle_fuel_log($scope.vehicleFuel).then(function(status) {
            if (status === 200) {
                 if ($routeParams.vehicleID) {
                        doLoadingTask($routeParams.vehicleID);
                  }
                $scope.vehicle_fuel_cancel();
                console.log("Inserted..");
            }
        });
    };

    $scope.vehicle_fuel_cancel = function() {
        $scope.vehicle_fuel_list_action = !$scope.vehicle_fuel_list_action;
        $scope.save_cancel_div_toggle = !$scope.save_cancel_div_toggle;
        $scope.btn_vehicle_fuel_create = !$scope.btn_vehicle_fuel_create;
        $scope.add_new_vehicle_fuel = !$scope.add_new_vehicle_fuel;
    };

});

odooApp.controller('vehicleFuelLog', function($scope, $location,VFuel, vModelService) {

    $scope.vehicles = {};
    $scope.drivers = {};
    $scope.vehicleFuelLog = {
        rate: '',
        liter: '',
        totalcost: '',
        driver: '',
        date: currentDate()
    };
    vModelService.loadDrivers().then(function(response) {
        $scope.drivers = response.data;
    });

    function currentDate() {
        var d = new Date();
        var month = d.getMonth() + 1;
        if (month < 10) {
            month = "0" + month;
        }
        return d.getFullYear() + "-" + month + "-" + d.getDate();
    }

    VFuel.load_vehicles().then(function(response) {
        $scope.vehicles = response.data;
    });

     $scope.set_purchaser = function(driver) {
        $scope.vehicleFuelLog.driver = driver.name;
        $scope.vehicleFuelLog.purchaser = driver.did;
    };
    

    $scope.$watch('vehicleFuelLog.rate', function(rate) {
        if (!rate) {
            $scope.vehicleFuelLog.totalcost = '';
        }
        if (rate > 0) {
            $scope.vehicleFuelLog.rate = rate;
            $scope.vehicleFuelLog.totalcost = $scope.vehicleFuelLog.liter * $scope.vehicleFuelLog.rate;
        }
    });
    $scope.$watch('vehicleFuelLog.liter', function(liter) {
        if (!liter) {
            $scope.vehicleFuelLog.totalcost = '';
        }
        if (liter > 0) {
            $scope.vehicleFuelLog.liter = liter;
            $scope.vehicleFuelLog.totalcost = $scope.vehicleFuelLog.liter * $scope.vehicleFuelLog.rate;
        }

    });


    $scope.set_vehicle = function(vehicle) {
        $scope.vehicleFuelLog.vehicle_id = vehicle.vid;
        $scope.vehicleFuelLog.vehicle = vehicle.model_brand + '/' + vehicle.model_name + '/' + vehicle.licence_plate;
        vModelService.getDriverByVid(vehicle.vid).then(function(response) {
            $scope.vehicleFuelLog.driver = response.data.name;
            $scope.vehicleFuelLog.purchaser = response.data.did;
        });
    };

    $scope.submit = function() {
        VFuel.save_vehicle_fuel_log($scope.vehicleFuelLog).then(function(status) {
            if (status === 200) {
                $location.path('/vehicle-flogs-list');
                console.log("Inserted");
                $scope.vehicleFuelLog = {
                    rate: '',
                    liter: '',
                    totalcost: '',
                    driver: '',
                    notes: '',
                    purchaser : '',
                    date: currentDate()
                };
                
            }
        });
        
    };
    
    $scope.cancel = function(){
         $scope.vehicleFuelLog = {
                    rate: '',
                    liter: '',
                    totalcost: '',
                    driver: '',
                    notes: '',
                    purchaser : '',
                    date: currentDate()
                };
    };
});




odooApp.controller('vehicleFuelLogList', function($scope, $location, VFuel) {
    $scope.showList = true;
    $scope.dataByVehicles = [];
    VFuel.get_vehicle_fuel_logToList().then(function(response) {
        for (var i = 0; i < response.data.length; i++) {
            var title;
            var titleData = {};
            var recordData = {};
            var total_cost= 0.0;
            
            titleData = response.data[i].titleData;
            if (response.data[i].titleData === undefined) {
                title = " ";
            } else {
                title = titleData[0].model_brand + '/' + titleData[0].model_name + '/' + titleData[0].licence_plate;
            }

            if (response.data[i].fuelLogData === undefined) {
                recordData = {};
            } else {
                recordData = response.data[i].fuelLogData;
                for(var k = 0 ; k < recordData.length ; k++){
                    total_cost = total_cost + parseFloat(recordData[k].totalcost);
                }
            }

            var finalData = {
                vid: response.data[i].vid,
                title: title,
                content: recordData.length ? recordData.length+' Record Found' : "0 Record Found",
                record: recordData,
                totalCostOfEachRecord : total_cost
            };

            $scope.dataByVehicles.push(finalData);
        }
       // console.log($scope.dataByVehicles);
    });

    
    $scope.create_vfuel_log = function(path) {
        $location.path(path);
    };

});