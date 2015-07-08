
odooApp.controller("AdditionalCtrl", function($scope, $location,Vehicles, AdditionalService) {
    $scope.additionalLog = {};
    $scope.drivers = {};
    $scope.vehicles = {};
    $scope.location = "";

    Vehicles.loadVehicles().then(function(response) {
        $scope.vehicles = response.data;
    });

    Vehicles.loadDrivers().then(function(response) {
        $scope.drivers = response.data;
    });


    $scope.setDriver = function(item) {
        $scope.additionalLog.driver_id = item.did;
        $scope.additionalLog.driver = item.name;

    };

    $scope.setVehicle = function(item) {
        $scope.additionalLog.driver_id = item.driver_id;
        for (var i = 0; i < $scope.drivers.length; i++) {
            if ($scope.drivers[i].did === item.driver_id) {
                $scope.additionalLog.driver = $scope.drivers[i].name;
                break;
            }
        }
        $scope.additionalLog.vehicle_id = item.vid;
        $scope.additionalLog.vehicle = item.model_brand + "/" + item.model_name + "/" + item.licence_plate;
    };



    AdditionalService.deliveryTypes().then(function(response) {
        $scope.deliveryTypes = response.data;
    });

    AdditionalService.areas().then(function(response) {
        $scope.areas = response.data;
    });



    $scope.deliveryTypeChanged = function(selected) {
        for (var i = 0; i < $scope.deliveryTypes.length; i++) {
            if ($scope.deliveryTypes[i].id === selected) {
                $scope.DType = $scope.deliveryTypes[i];
                $scope.additionalLog.delivery_type = $scope.deliveryTypes[i].type;
                $scope.deliveryTypeCost = $scope.deliveryTypes[i];
                break;
            }
        }
    };

    $scope.locationChanged = function(selected) {
        for (var i = 0; i < $scope.areas.length; i++) {
            if ($scope.areas[i].id === selected) {
                $scope.location = $scope.areas[i].location;
                $scope.additionalLog.location = $scope.location;
                if ($scope.location.toLowerCase() === 'delhi' && $scope.deliveryTypeCost.type.toLowerCase() === 'midnight delivery') {
                    $scope.additionalLog.delivery_rate = $scope.deliveryTypeCost.rate - 50;
                } else {
                    $scope.additionalLog.delivery_rate = $scope.deliveryTypeCost.rate;
                }
                break;
            }
        }
    };

    $scope.$watch('additionalLog.delivery_type_id', function(newVal, oldVal) {
        if (newVal) {
            if ($scope.DType.type.toLowerCase() === 'midnight delivery' && $scope.location) {
                if ($scope.location.toLowerCase() === 'delhi') {
                    $scope.additionalLog.delivery_rate = $scope.deliveryTypeCost.rate - 50;
                } else {
                    $scope.additionalLog.delivery_rate = $scope.deliveryTypeCost.rate;
                }
            } else if ($scope.DType.type.toLowerCase() === 'fix rate' && $scope.location) {
                $scope.additionalLog.delivery_rate = $scope.deliveryTypeCost.rate;
            }
            else if ($scope.DType.type.toLowerCase() === 'regular delivery' && $scope.location) {
                $scope.additionalLog.delivery_rate = $scope.deliveryTypeCost.rate;
            }
        }
    });


    $scope.submit = function() {
        AdditionalService.saveAdditionalLog($scope.additionalLog).then(function(response) {
            if (response.status === 200) {
                $location.path('/vehicles-alogs-list');
                $scope.additionalLog = {};
                
            }
        });
    };
    
    $scope.cancel = function(){
        $scope.additionalLog = {};
    };

});






odooApp.controller("AddLogCtrlList", function($scope,$location, AdditionalService) {
     $scope.showList = true;
     
    $scope.addLogsToList = [];
    AdditionalService.getAdditionalLogs().then(function(response){
      
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
            
             if (response.data[i].addLogData === undefined) {
                recordData = {};
            } else {
                recordData = response.data[i].addLogData;
                for(var k = 0 ; k < recordData.length ; k++){
                    total_cost = total_cost + parseFloat(recordData[k].delivery_rate);
                }
            }
            
              var finalData = {
                vid: response.data[i].vid,
                title: title,
                content: recordData.length ? recordData.length+' Record Found' : "0 Record Found",
                record: recordData,
                totalCostOfEachRecord : total_cost
            };

            $scope.addLogsToList.push(finalData);
        
        }
    });
    
    
    $scope.create_vehiclesalogs = function(path){
        $location.path(path);
    };
    
});