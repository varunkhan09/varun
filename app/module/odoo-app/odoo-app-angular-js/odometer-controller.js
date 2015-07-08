/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


odooApp.controller('odoMeterController', function($scope, $routeParams, $modal, Odometer, Vehicles, vModelService) {
    $scope.odometerRecord = null;
    $scope.vid = null;
    $scope.add_new_odometer_table_row = false;
    $scope.save_cancel_div_toggle = false;
    $scope.create_odometer_entry_div = true;
    $scope.vehicles = null;
    $scope.nvid = null;
    $scope.nunit = 'kilometers';
   
    
    $scope.vehicle = {};
    var d = new Date();
    var month = d.getMonth() + 1;
    if (month < 10) {
        month = "0" + month;
    }
    $scope.ndate = d.getFullYear() + "-" + month + "-" + d.getDate();
    
    if ($routeParams.vehicleID) {
        load_odometer_record($routeParams.vehicleID);
    };

    function load_odometer_record(vehicleID) {
        $scope.vid = vehicleID;
        
        Odometer.loadOdometerByVid(vehicleID).then(function(response) {
            $scope.odometerRecord = response.data;
            if ($scope.odometerRecord) {
                $scope.setVehicle($scope.odometerRecord[0]);
            } else {
                vModelService.getVehicleById(vehicleID).then(function(response) {
                    $scope.setVehicle(response.data);
                });
            }
        });
    }

    $scope.create_odometer_entry = function() {
        $scope.add_new_odometer_table_row = true;
        $scope.save_cancel_div_toggle = true;
        $scope.create_odometer_entry_div = false;
    };

    $scope.cancel_odometer_entry = function() {
        $scope.add_new_odometer_table_row = false;
        $scope.save_cancel_div_toggle = false;
        $scope.create_odometer_entry_div = true;
    };

    Odometer.loadVehiclesToOdometerLog().then(function(response) {
        $scope.vehicles = response.data;
    });

    $scope.setVehicle = function(vehicle) {
        $scope.vehicle = vehicle.model_brand + ' / ' + vehicle.model_name + ' / ' + vehicle.licence_plate;
        $scope.nvid = vehicle.vid;
        $scope.nunit = "kilometer";
    };


    $scope.add_odometer_entry = function(vid, nvid, ncheckbox, ndate, op_odometer,cls_odometer, nunit) {
        // if to add new entry selecting unlisted vehicles
        if(op_odometer && cls_odometer && ndate ){
            var odometer = {
                vid: vid,
                checkbox: ncheckbox,
                date: ndate,
                odometervalue: 0,
                op_odometer: op_odometer,
                cls_odometer: cls_odometer,
                unit: nunit
            };

        Odometer.saveOdometerLog(odometer).then(function(data) {
            if (data === 200) { // sucessfully update
                
                
                $scope.ncheckbox = "";
                $scope.nodometervalue = "";
                $scope.nop_odometer = "";
                $scope.ncls_odometer = "";
                $scope.nunit = "kilometers";    
                 
                
                $scope.add_new_odometer_table_row = false;
                $scope.save_cancel_div_toggle = false;
                $scope.create_odometer_entry_div = true;
                Odometer.loadOdometerByVid($routeParams.vehicleID).then(function(response) {
                    $scope.odometerRecord = response.data;
                });
            }
        });
        
        }else{
            alert("Sorry : Trying to submit Invalid data...!");
        }
    };








    $scope.edit_odometer_entry = function(ref_identity, odo_entry_id, vehicle_id) {
        $modal.open({
            templateUrl: 'editOdometer.html',
            backdrop: true,
            windowClass: 'modal',
            controller: function($scope, $modalInstance) {
                $scope.edited_ref = ref_identity + odo_entry_id + '/' + vehicle_id;
                $scope.ounit = "kilometers";
                vModelService.getVehicleById(vehicle_id).then(function(response) {
                    $scope.up_vehicle_id =  response.data.vid;
                    $scope.ovehicle =  response.data.model_brand + ' / ' + response.data.model_name + ' / ' + response.data.licence_plate;
                });
                
                var d = new Date();
                var month = d.getMonth() + 1;
                if (month < 10) {
                    month = "0" + month;
                }
                $scope.odate = d.getFullYear() + "-" + month + "-" + d.getDate();
                
                Odometer.getOdometerEntryById(odo_entry_id).then(function(response) {
                   $scope.cls_odometer = response.data[0].cls_odometer;
                   $scope.op_odometer = response.data[0].op_odometer;
                });
                
                
//                Vehicles.loadVehicles().then(function(response) {
//                    
//                      
//                    $scope.ovehicles = response.data;
//                    for (var i = 0; i < $scope.ovehicles.length; i++) {
//                        if ($scope.ovehicles[i].vid === vehicle_id) {
//                            break;
//                        }
//                    }
//                });


                $scope.submit = function() {
                    if($scope.op_odometer && $scope.op_odometer && $scope.ounit && vehicle_id){
                            $scope.update_vodoometer = {
                            vid: vehicle_id,
                            update_vid: $scope.up_vehicle_id,
                            edited_ref: $scope.edited_ref,
                            date: $scope.odate,
                            odometervalue: 0,
                            op_odometer: $scope.op_odometer,
                            cls_odometer: $scope.cls_odometer,
                            checkbox: 0,
                            unit: $scope.ounit,
                            comment: $scope.comment
                        };
                        Odometer.updateOdometerEntryByVehicleId($scope.update_vodoometer).then(function(response) {
                            load_odometer_record(vehicle_id);
                        });
                    }else{
                        alert("Trying to submit invalid value.");
                    }
                    $modalInstance.dismiss('cancel');
                };
                $scope.cancel = function() {
                    $modalInstance.dismiss('cancel');
                };
            },
            resolve: {
                model: function() {
                    return $scope.ovehicles;
                }
            }
        });
    };
});




odooApp.controller('vehicleOdometerLog', function($scope, $location,Odometer, Vehicles) {
    $scope.vehicleodometer = {
        checkbox: 0,
        unit: 'kilometers',
        odometervalue : 0
    };
    
    Vehicles.loadVehicles().then(function(response) {
        $scope.vehicles = response.data;
      
    });

    $scope.setVehicle = function(vehicle) {
        $scope.vehicleodometer.vid = vehicle.vid;
        $scope.vehicleodometer.vehicle = vehicle.model_brand + '/' + vehicle.model_name + '/' + vehicle.licence_plate;
    };

    $scope.submit = function() {
       Odometer.saveOdometerLog($scope.vehicleodometer).then(function(status) {
            if (status === 200) {
                $location.path('/vehicles-ologs-list');
                $scope.vehicleodometer = {
                    checkbox: 0,
                    unit: 'kilometers',
                    odometervalue: 0
                };
                console.log("Submitted...");
                
                
            }
        });
    };
});



odooApp.controller('vehicleOdometerLogList', function($scope, Odometer, $location) {
    $scope.showList = true;
    $scope.dataByVehicles = [];
    Odometer.get_vehicle_odometer_logToList().then(function(response) {
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

            if (response.data[i].odometerLogData === undefined) {
                recordData = {};
            } else {
                recordData = response.data[i].odometerLogData;
                for(var k = 0 ; k < recordData.length ; k++){
                    // 2.25 is a rate                
                    total_cost = total_cost + parseFloat((recordData[k].cls_odometer - recordData[k].op_odometer) * 2.25);                   
                }
            }

            var finalData = {
                vid: response.data[i].vid,
                title: title,
                content: recordData.length ? recordData.length + ' Record Found' : "0 Record Found",
                record: recordData,
                totalCostOfEachRecord : total_cost
            };
            $scope.dataByVehicles.push(finalData);
        }
        // console.log($scope.dataByVehicles);
    });

    $scope.create_vodoometer_log = function(path) {
        console.log(path);
        $location.path(path);
    };
});