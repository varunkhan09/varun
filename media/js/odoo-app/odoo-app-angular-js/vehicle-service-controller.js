

odooApp.controller('vehicleServiceController', function($scope, $location, $routeParams, VService, vModelService, VFuel, VCost) {
    $scope.btn_vehicle_service_create = true;
    $scope.save_cancel_div_toggle = false;
    $scope.vehicle_service_list_action = true;
    $scope.add_new_vehicle_service = false;
    $scope.vservice = {};
    

    $scope.totalprice = 0;
    $scope.service_vehicles = {};
    $scope.service_costType = {};


    $scope.vehicleService = {
        cost_type_id : 0
    };

   
    VFuel.load_vehicles().then(function(response) {
        $scope.service_vehicles = response.data;
    });


    $scope.set_vehicle = function(vehicle) {
        $scope.vehicleService.vehicle_id = vehicle.vid;
        $scope.vehicleService.vehicle = vehicle.model_brand + '/' + vehicle.model_name + '/' + vehicle.licence_plate;
    };

    vModelService.loadDrivers().then(function(response) {
        $scope.purchasers = response.data;
    });

    VCost.load_vehicle_cost_type().then(function(response) {
        $scope.service_costType = response.data;
    });

    $scope.set_purchaser = function(purchaser) { // purchaser = driver
        $scope.vehicleService.purchaser = purchaser.name;
        $scope.vehicleService.purchaser_id = purchaser.did;
    };

    $scope.set_serviceType = function(service) {
        $scope.vehicleService.cost_type_id = service.id;
        $scope.vehicleService.type = service.type;
    };

    $scope.vehicle_service_create = function() {
        $scope.btn_vehicle_service_create = !$scope.btn_vehicle_service_create;
        $scope.save_cancel_div_toggle = !$scope.save_cancel_div_toggle;
        $scope.vehicle_service_list_action = !$scope.vehicle_service_list_action;
        $scope.add_new_vehicle_service = !$scope.add_new_vehicle_service;

        VFuel.get_vehicle_by_Id($routeParams.vehicleID).then(function(response) {

            $scope.vehicleService.vehicle_id = response.data.vid;
            $scope.vehicleService.vehicle = response.data.model_brand + '/' + response.data.model_name + '/' + response.data.licence_plate;
        });

    };

    $scope.vehicle_service_cancel = function() {
        $scope.btn_vehicle_service_create = !$scope.btn_vehicle_service_create;
        $scope.save_cancel_div_toggle = !$scope.save_cancel_div_toggle;
        $scope.vehicle_service_list_action = !$scope.vehicle_service_list_action;
        $scope.add_new_vehicle_service = !$scope.add_new_vehicle_service;

    };

    $scope.parseFloat = function(value) {
        return parseFloat(value);
    };


    if ($routeParams.vehicleID) {
        VService.get_vehicle_service_byId($routeParams.vehicleID).then(function(response) {
            $scope.vservice = response.data;
            angular.forEach($scope.vservice, function(v_item) {
                $scope.totalprice += parseFloat(v_item.totalprice);
            });
        });
    }



    $scope.vehicle_service_save = function() {
        
        if($scope.vehicleService.totalprice && $scope.vehicleService.cost_type_id && $scope.vehicleService.date && $scope.vehicleService.purchaser_id){
            VService.save_vehicle_service_log($scope.vehicleService).then(function(status) {
            if (status === 200) {
                alert("Sucessfully submitted");
                $scope.vehicleService= {};
                VService.get_vehicle_service_byId($routeParams.vehicleID).then(function(response) {
                    $scope.vservice = response.data;
                    angular.forEach($scope.vservice, function(v_item) {
                        $scope.totalprice += parseFloat(v_item.totalprice);
                    });
                });

                $scope.vehicle_service_cancel();
                if ($routeParams.vehicleID) {
                    $location.path('/vehicle-service/' + $routeParams.vehicleID);
                }
            }
        });
        
        }else{
        
        
        
        }
        
    };

    $scope.getCostType = function(cost_id) {
        $scope.costType = '';
        for (var i = 0; i < $scope.service_costType.length; i++) {
            if ($scope.service_costType[i].id === cost_id) {
                $scope.costType = $scope.service_costType[i].type;
                break;
            }
        }
        return  $scope.costType;
    };

    $scope.getPurchaser = function(id) {
        $scope.purchaser_name = '';
        for (var i = 0; i < $scope.purchasers.length; i++) {
            if ($scope.purchasers[i].did === id) {
                $scope.purchaser_name = $scope.purchasers[i].name;
                break;
            }
        }
        return  $scope.purchaser_name;
    };

    $scope.getVehicle = function(vid) {
        $scope.vehicle_name = '';
        for (var i = 0; i < $scope.service_vehicles.length; i++) {
            if ($scope.service_vehicles[i].vid === vid) {
                $scope.vehicle_name = $scope.service_vehicles[i].model_brand + '/' + $scope.service_vehicles[i].model_name + "/" + $scope.service_vehicles[i].licence_plate;
                break;
            }
        }
        return  $scope.vehicle_name;
    };
});





odooApp.controller('vehicleServiceLog', function($scope, $modal,VFuel, VCost, vModelService, VService,$location) {
    $scope.purchasers = {};
    $scope.service_costType = {};
    $scope.service_vehicles = {};

    vModelService.loadDrivers().then(function(response) {
        $scope.purchasers = response.data;
    });

    VFuel.load_vehicles().then(function(response) {
        $scope.service_vehicles = response.data;
    });

 VCost.load_vehicle_cost_type().then(function(response) {
        $scope.service_costType = response.data;
    });
    
    $scope.vehicleService = {
        date: ''
    };

    $scope.set_vehicle = function(vehicle) {
        $scope.vehicleService.vehicle_id = vehicle.vid;
        $scope.vehicleService.vehicle = vehicle.model_brand + '/' + vehicle.model_name + '/' + vehicle.licence_plate;
        for (var i = 0; i < $scope.purchasers.length; i++) {
            if (vehicle.driver_id === $scope.purchasers[i].did) {
                $scope.vehicleService.purchaser_id = $scope.purchasers[i].did;
                $scope.vehicleService.purchaser = $scope.purchasers[i].name;
            }
        }
    };

    $scope.set_purchaser = function(item) {
        $scope.vehicleService.purchaser_id = item.did;
        $scope.vehicleService.purchaser = item.name;
    };

    $scope.set_serviceType = function(service) {
        $scope.vehicleService.cost_type_id = service.id;
        $scope.vehicleService.type = service.type;
    };

    $scope.submit = function() {
        if (!$scope.vehicleService.vehicle_id || !$scope.vehicleService.cost_type_id) {
        } else {
            VService.save_vehicle_service_log($scope.vehicleService).then(function(status) {
                if (status === 200) {
                    $location.path('/vehicle-fservice-list');
                    console.log("submitted");
                    $scope.vehicleService = {
                        vehicle_id: '',
                        cost_type_id: '',
                        date: currentDate(),
                        type: '',
                        purchaser:'',
                        purchaser_id:''
                    };
                     
                }
            });
        }
    };
    
    $scope.cancel = function(){
         $scope.vehicleService = {
                        vehicle_id: '',
                        cost_type_id: '',
                        date: currentDate(),
                        type: '',
                        purchaser:'',
                        purchaser_id:''
                    };
    };


loadServiceType = function (){
  VCost.load_vehicle_cost_type().then(function(response) {
        $scope.service_costType = response.data;
    });
};
    // To add service type on request , if required
    $scope.addServiceType = function(){
      $modal.open({
            templateUrl: 'scost_type.html',
            backdrop: true,
            windowClass: 'modal',
            size: 'sm',
            controller: function($scope, $modalInstance) {
                $scope.submit = function() {
                  if($scope.cost){  
                        VCost.save_vehicle_cost_type($scope.cost).then(function(status) {
                            if (status === 200) {
                                loadServiceType();
                                $modalInstance.dismiss('cancel');
                            }
                        });
                    }
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




odooApp.controller('vehicleServiceLogList', function($scope, $location, VService) {
    $scope.showList = true;
    $scope.dataByVehicles = [];

    $scope.dataByVehicles = [];
    VService.get_vehicle_service_logToList().then(function(response) {
        for (var i = 0; i < response.data.length; i++) {
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

            if (response.data[i].serviceLogData === undefined) {
                recordData = {};
            } else {
                recordData = response.data[i].serviceLogData;
                for (var k = 0; k < recordData.length; k++) {
                    total_cost = total_cost + parseFloat(recordData[k].totalprice);
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

    $scope.create_vservice_log = function(service_log_path) {
        $location.path(service_log_path);
    };

});

