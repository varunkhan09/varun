
odooApp.controller('vehicleCostController', function($scope, $routeParams, $window, $modal, VCost, Vehicles) {
    $scope.btn_vehicle_cost_create = true;
    $scope.save_cancel_div_toggle = false;
    $scope.vehicle_list_action = true;
    $scope.add_new_vehicle_cost = false;
    $scope.vehiclecost = {};

    if ($routeParams.vehicleID) {
        load_vehicle_cost_record($routeParams.vehicleID);
    }

    function load_vehicle_cost_record(vehicleID) {
        VCost.get_vehicle_cost_detail_by_id(vehicleID).then(function(response) {
            $scope.vehicle_cost_record = response.data;
        });
    }

    VCost.load_vehicle_cost_type().then(function(response) {
        $scope.vehicleCostType = response.data;
    });

    Vehicles.loadVehicles().then(function(response) {
        $scope.vehicles = response.data;
    });


    $scope.setVehicleCostType = function(item) {
        $scope.vehiclecost.type = item.type;
        $scope.vehiclecost.cost_type_id = item.id;
    };

    $scope.setVehicle = function(item) {
        $scope.vehiclecost.vehicle_id = item.vid;
        $scope.vehiclecost.vehicle = item.model_brand + "/" + item.model_name + "/" + item.licence_plate;
    };

    $scope.vehicle_cost_create = function() {
        for (var i = 0; i < $scope.vehicles.length; i++) {
            if ($scope.vehicles[i].vid === $routeParams.vehicleID) {
                $scope.vehiclecost.vehicle_id = $scope.vehicles[i].vid;
                $scope.vehiclecost.vehicle = $scope.vehicles[i].model_brand + "/" + $scope.vehicles[i].model_name + "/" + $scope.vehicles[i].licence_plate;
                break;
            }
        }

        $scope.btn_vehicle_cost_create = !$scope.btn_vehicle_cost_create;
        $scope.save_cancel_div_toggle = !$scope.save_cancel_div_toggle;
        $scope.vehicle_list_action = !$scope.vehicle_list_action;
        $scope.add_new_vehicle_cost = !$scope.add_new_vehicle_cost;
    };

    $scope.vehicle_cost_cancel = function() {
        $scope.btn_vehicle_cost_create = !$scope.btn_vehicle_cost_create;
        $scope.save_cancel_div_toggle = !$scope.save_cancel_div_toggle;
        $scope.vehicle_list_action = !$scope.vehicle_list_action;
        $scope.add_new_vehicle_cost = !$scope.add_new_vehicle_cost;
        $scope.vehiclecost = {};

    };
    $scope.vehicle_cost_save = function() {
        VCost.save_vehicle_cost($scope.vehiclecost).then(function(status) {
            if (status === 200) {
                alert("Successfully Submitted.");
                if ($routeParams.vehicleID) {
                    load_vehicle_cost_record($routeParams.vehicleID);
                }
                $scope.vehicle_cost_cancel();
                console.log("Inserted..");
            }
        });
    };
    

    $scope.open_cost_type = function() {
        $modal.open({
            templateUrl: 'cost_type.html',
            backdrop: true,
            windowClass: 'modal',
            size: 'sm',
            controller: function($scope, $modalInstance) {
                $scope.submit = function() {
                    VCost.save_vehicle_cost_type($scope.cost).then(function(status) {
                        if (status == 200) {
                            $modalInstance.dismiss('cancel');
                        }
                    });
                };
                $scope.cancel = function() {
                    $modalInstance.dismiss('cancel');
                };
            },
            resolve: {
                model: function() {
                    return $scope.cost;
                }
            }
        });
    };

    $scope.vehicle_cost_cancel = function() {
        // to go back 
        $window.history.back();
    };

});



odooApp.controller('vehicleCostLog', function($scope, VCost, Vehicles,$modal,$location) {
    VCost.load_vehicle_cost_type().then(function(response) {
        $scope.vehicleCostType = response.data;
    });
    Vehicles.loadVehicles().then(function(response) {
        $scope.vehicles = response.data;
    });
    $scope.setVehicleCostType = function(item) {
        $scope.vehiclecost.type = item.type;
        $scope.vehiclecost.cost_type_id = item.id;
    };

    $scope.setVehicle = function(item) {
        $scope.vehiclecost.vehicle_id = item.vid;
        $scope.vehiclecost.vehicle = item.model_brand + "/" + item.model_name + "/" + item.licence_plate;
    };

    $scope.submit = function() {
        VCost.save_vehicle_cost($scope.vehiclecost).then(function(status) {
            if (status === 200) {
                alert("Successfully Submitted.");
                 $location.path('/vehicles-clogs-list');
                $scope.vehiclecost = {};
                console.log('Inserted');
               
            }
        });
    };

$scope.open_cost_type = function() {
    $scope.cost={};
        $modal.open({
            templateUrl: 'cost_type.html',
            backdrop: true,
            windowClass: 'modal',
            size: 'sm',
            controller: function($scope, $modalInstance) {
                $scope.submit = function() {
                    VCost.save_vehicle_cost_type($scope.cost).then(function(status) {
                        if (status === 200) {
                            $modalInstance.dismiss('cancel');
                        }
                    });
                    console.log($scope.cost);

                };
                $scope.cancel = function() {
                    $modalInstance.dismiss('cancel');
                };
            },
            resolve: {
                model: function() {
                    return $scope.cost;
                }
            }
        });
    };
});





odooApp.controller('vehicleCostLogList', function($scope, VCost, $location) {
    $scope.showList = true;
    $scope.dataByVehicles = [];
    $scope.dataByVehicles = [];
    VCost.get_vehicle_cost_LogToList().then(function(response) {
        for (var i = 0; i < response.data.length; i++) {
            console.log(response.data[i]);
            var title;
            var titleData = {};
            var recordData = {};
            var total_cost = 0.0;

            titleData = response.data[i].titleData;
            if (response.data[i].titleData === undefined) {
                title = " ";
            } else {
                title = titleData[0].model_brand + '/' + titleData[0].model_name + '/' + titleData[0].licence_plate;
            }


            if (response.data[i].costLogData === undefined) {
                recordData = {};
            } else {
                recordData = response.data[i].costLogData;
                for (var k = 0; k < recordData.length; k++) {
                    total_cost = total_cost + parseFloat(recordData[k].cost);
                }
            }

            var finalData = {
                vid: response.data[i].vid,
                title: title,
                content: recordData.length ? recordData.length + ' Record Found' : "0 Record Found",
                record: recordData,
                totalCostOfEachRecord: total_cost
            };

            $scope.dataByVehicles.push(finalData);

        }
    });
    $scope.create_vcost_log = function(cost_path) {
        $location.path(cost_path);
    };
});
