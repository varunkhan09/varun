'use strict';

odooApp.controller('homeCtrl', function($scope) {
    $scope.message = 'Welcome To Flaberry Fleet Dashboard!';
});

odooApp.controller('fbOdooMainController', function($scope) {
    $scope.message = 'Everyone come and see how good I look!';
});

/*
 * Controller for vehicles.
 * Add new vehicles , show add new vehicles page , hide new vehicles
 */
odooApp.controller("vehiclesController", function($scope, $sce, $rootScope) {
    $scope.screens = ["Create"];
    $scope.current = $scope.screens[0];
    $scope.showAddNewVehicles = false;
    $scope.vehicleslistsdisplay = true;



    $scope.setScreenPage = function(index) {
        $scope.current = $scope.screens[index];
        $scope.showAddNewVehicles = true;
        $scope.vehicleslistsdisplay = !$scope.vehicleslistsdisplay;
        // broadcast to call service in child controller
        $rootScope.$broadcast('loadDataToChildCtrlEvent', index, $scope.screens);
    };

    /**
     * 
     * @returns {String}
     */
    $scope.getScreenPage = function() {
        return $scope.current === 'Create' ? 'views/create-new-vehicles.html' : '';
    };

    $scope.cancelAddNewVehicles = function() {
        $scope.showAddNewVehicles = false;
        $scope.vehicleslistsdisplay = !$scope.vehicleslistsdisplay;
    };

    $scope.getDefaultVehiclesListPage = function() {
        if ($scope.vehicleslistsdisplay) {
            return 'views/vehicles-list.html';
        }
    };

   // $scope.html = '<span>some text</span>';
   // $scope.trustedHtml = $sce.trustAsHtml($scope.html);
});


/**
 * Load All vehicles and display on page 
 */

odooApp.controller('vehiclesListCtrl', function($scope, Vehicles) {
    $scope.vehiclesList = {};
    Vehicles.loadVehicles().then(function(data) {
        $scope.vehiclesList = data.data;
    });
    $scope.drivers = {};
    Vehicles.loadDrivers().then(function(response) {
        $scope.drivers = response.data;
    });

    $scope.getDriver = function(driver_id) {
        $scope.dname = '';
        for (var i = 0; i < $scope.drivers.length; i++) {
            if ($scope.drivers[i].did === driver_id) {
                $scope.dname = $scope.drivers[i].name;
                //console.log($scope.drivers[i].did);
                break;
            }
        }
        return  $scope.dname;
    }
});


odooApp.controller('viewVehicleCtrl',function($scope, $routeParams, vModelService) {
    $scope.vehicleimag = 'images/default-car-pic.png';
    $scope.vehicle_details_found = false;
    $scope.details_not_found = false;
    
    
    vModelService.getVehicleById($routeParams.vehicleID).then(function(response) {
     //   console.log(response);
        if(response.status === 204){
            $scope.details_not_found =  !$scope.details_not_found ;
            $scope.vehicle_details_found = false;
        }
        else if(response.status === 200){
            $scope.details_not_found = false;
            $scope.vehicle_details_found  = true;
          
              $scope.img_name =  response.data.model_brand;
             
                $scope.vehicleimag = 'images/'+ $scope.img_name.toLowerCase()+'.png';
                $scope.model = response.data.model_brand + '/' + response.data.model_name;
                $scope.licence_plate = response.data.licence_plate;
                $scope.tags = response.data.tags;
                $scope.id = response.data.vid;

                vModelService.getDriverById(response.data.driver_id).then(function(resp){
                     $scope.drivername = resp.data.name;
                });

                $scope.driverlocation = response.data.driver_location;
                $scope.chassis_no = response.data.chassis_no;
                $scope.last_odometer = response.data.last_odometer;
                $scope.acquisition_date = response.data.acquisition_date;
                $scope.car_value = response.data.car_value;
                $scope.seat_no = response.data.seat_no;
                $scope.door_no = response.data.door_no;
                $scope.color = response.data.color;

                 vModelService.getTransmissionById(response.data.transmission).then(function(resp){
                     $scope.transmission = resp.data.type;
                });

                  vModelService.fuelTypeById(response.data.fueltype).then(function(resp){
                     $scope.fueltype = resp.data.type;
                });
                $scope.ccarbon_dioxide = response.data.ccarbon_dioxide;
                $scope.horsepower = response.data.horsepower;
                $scope.horsepower_taxation = response.data.horsepower_taxation;
                $scope.power = response.data.power;
         }
    });
    
});



odooApp.controller('cnvController', function($scope, vModelService, $routeParams,$location,Odometer,VFuel,VService,VCost) {
    $scope.vehicleimag = 'images/default-car-pic.png';
    $scope.message = '';
  //  $scope.selected_vehicle_obj = {};
   // $scope.selected_driver_obj = {};
    $scope.models = {};
    vModelService.getVehiclesModels().then(function(data) {
        $scope.models = data.data;
    });

    vModelService.getTransmissions().then(function(data) {
        $scope.transmissions = data.data;
    });

    vModelService.getFuels().then(function(data) {
        $scope.fuels = data.data;
    });  

    vModelService.loadDrivers().then(function(response) {
       $scope.drivers = response.data;
    });
    
    $scope.transmissionUpdateOnChange = function(tid) {
        $scope.transmission = tid;
    };

    $scope.fuelUpdateOnChange = function(fid) {
        $scope.fuel = fid;
    };

        
    if ($routeParams.vehicleID) {
        vModelService.getVehicleById($routeParams.vehicleID).then(function(response) {
        ///   console.log(response);
            $scope.id = $routeParams.vehicleID;
            $scope.color = response.data.color;
            $scope.driverlocation = response.data.driver_location;
            $scope.seatno = response.data.seat_no;
            $scope.cotwo = response.data.ccarbon_dioxide;
            $scope.doorno = response.data.door_no;
            $scope.power = response.data.power;
            $scope.horsepower = response.data.horsepower;
            $scope.date = response.data.acquisition_date;
            $scope.hptaxation = response.data.horsepower_taxation;
            $scope.chassisno = response.data.chassis_no;
            $scope.tags = response.data.tags;
            $scope.transmission =  response.data.transmission;
            $scope.fuel =  response.data.fueltype;
            
            vModelService.getDriverById(response.data.driver_id).then(function(response){
                 $scope.driver = response.data;
                // $scope.selected_driver_obj = $scope.driver;
                //  console.log($scope.selected_driver_obj);
                  
            });
          
           vModelService.getModelById(response.data.model_id).then(function(response){
                 $scope.model = response.data; 
               //  $scope.selected_vehicle_obj = $scope.model;
                // console.log($scope.selected_vehicle_obj);
                 
            });
    
            $scope.licenceplate = response.data.licence_plate;
            $scope.carvalue = response.data.car_value;
            $scope.odometer = response.data.last_odometer;    
        });
    }

        $scope.setDriver = function(driver) {
           // $scope.selected_driver_obj = driver;
            $scope.driver = driver;
            // console.log($scope.selected_driver_obj);
            vModelService.setDriver(driver);
        };
        
        $scope.setModel = function(modelItem) {
           // $scope.selected_vehicle_obj = modelItem;
            vModelService.setVehiclesModel(modelItem);
            $scope.model = modelItem;
         //   console.log($scope.selected_vehicle_obj);
        };
        
        
        
        $scope.add_driver = function(){
            $location.path('/driver/create');
        };
        
        
        
        
    $scope.$on('loadDataToChildCtrlEvent', function(event, index, action) {
    });

    
    // To save new vehicle
    $scope.new_vehicles_form_submit = function() {
        var new_vehicles_data = {
            id: $scope.id,
            model_id: $scope.model.id,
            licence_plate: $scope.licenceplate,
            tags: $scope.tags,
            driver_id: $scope.driver.did,
            driver_location: $scope.driverlocation,
            chassis_no: $scope.chassisno,
            last_odometer: $scope.odometer,
            acquisition_date: $scope.date,
            car_value: $scope.carvalue,
            seat_no: $scope.seatno,
            door_no: $scope.doorno,
            color: $scope.color,
            transmission: $scope.transmission,
            fueltype: $scope.fuel,
            ccarbon_dioxide: $scope.cotwo,
            horsepower: $scope.horsepower,
            horsepower_taxation: $scope.hptaxation,
            power: $scope.power
        };

       // console.log(new_vehicles_data);
        
        vModelService.saveNewVehicles(new_vehicles_data).then(function(response) {
             if (response.status === 200) {
                 if(new_vehicles_data.id === undefined){
                     $scope.$parent.cancelAddNewVehicles();
                 }
             }
         });

    };
    
     if ($routeParams.vehicleID) {
        $scope.cancelAddNewVehicles = function(){
            $location.path('/vehicles');
        };
    }
    
    
    
    $scope.fuel_count = 0;
    $scope.cost_count = 0;
    $scope.service_count = 0;
    $scope.odometer_count = 0;
    
    
    // cost 
    if($routeParams.vehicleID){
       VCost.get_vehicle_cost_detail_by_id($routeParams.vehicleID).then(function(response){
       // console.log(response.data);
              $scope.cost_count = response.data.length;       
       });
    }
    
    // service
    if($routeParams.vehicleID){
       VService.get_vehicle_service_byId($routeParams.vehicleID).then(function(response){
       // console.log(response.data);
              $scope.service_count = response.data.length;       
       });
    }
    
    // fuel log
    
      if($routeParams.vehicleID){
       VFuel.get_vehicle_fuel_details_by_id($routeParams.vehicleID).then(function(response){
       // console.log(response.data);
              $scope.fuel_count = response.data.length;       
       });
    }
    
    //odometer
    
     if($routeParams.vehicleID){
       Odometer.loadOdometerByVid($routeParams.vehicleID).then(function(response){
       // console.log(response.data);
              $scope.odometer_count = response.data.length;       
       });
    }
    
    if($routeParams.vehicleID){
        $scope.go_to_cost = function(id){
            if(id === $routeParams.vehicleID){
               $location.path('/vehicle-cost/'+id); 
            }
        };
        
        $scope.go_to_fuel = function(id){
            if(id === $routeParams.vehicleID){
               $location.path('/vehicle-fuel/'+id); 
            }
        };
        
        $scope.go_to_service = function(id){
            if(id === $routeParams.vehicleID){
               $location.path('/vehicle-service/'+id); 
            }
        };
         $scope.go_to_odometer = function(id){
            if(id === $routeParams.vehicleID){
               $location.path('/odometer/'+id); 
            }
        };
    };
    
    
});




odooApp.controller("vehiclesStatusController", function($scope, vehiclesStatService) {
    /*
     *  this is screen name on vehicles status page .
     *  for create and other for list all vehicles
     */
    $scope.screenIndex = 0;
    $scope.screens = [{name: 'Add Vehicles Status', url: ''}, {name: 'List', url: ''}];

    /*
     *  To set default current screen = Create
     */
    $scope.current = $scope.screens[0].name;

    /*
     *  Flag to set screen 
     */
    $scope.showVehiclesStatusScreen = true;

    /*
     */
    $scope.selected = 0;

    /**
     * @param {type} index
     * @returns {undefined}
     * 
     */
    $scope.setVehicleStatusScreen = function(index) {
        $scope.screenIndex = index;
        $scope.current = $scope.screens[index].name;
        $scope.selection = $scope.screens[index].name;
        $scope.selected = index;
    };

    /**
     * 
     * @returns {String}
     * To set screen page on click on tab.
     */
    $scope.getVehicleStatusScreen = function() {
        switch ($scope.screenIndex) {
            case 0 :
                return 'views/create-vehicles-status.html';
                break;
            case 1 :
                return 'views/list-vehicles-status.html';
                break;
            default:
                return 'views/create-vehicles-status.html';
        }
        //  return $scope.current === 'Create' ? 'views/create-vehicles-status.html' : 'views/list-vehicles-status.html';
    };

    // to store rendor on page



    vehiclesStatService.getAllVehiclesStatus().then(function(data) {
        $scope.vehicles_status = data.data;
        vehiclesStatService.setTovehicles_status($scope.vehicles_status);
    });


    /*
     * To store more action value , It will show when any checkbox is open, else will hide,
     * default hidden. 
     */
    $scope.selectOptionOnCheckBox = [{id: '1', action: 'Share'}, {id: '2', action: 'Export'}, {id: '3', action: 'Delete'}];

    /*
     * Flag variable to show/hide more action button. 
     */
    $scope.more_action_active = false;

    /*
     * 
     * @returns {undefined}
     *  To check , is all vehicles state is checked , 
     *  if found false- make all checked and make active more action button. 
     *  If found true - it means is checked earlier so alter more action button and make all checked to unchecked.
     *  
     */
    $scope.checkUncheckAll = function() {
        var checkState = vehiclesStatService.isAllChecked();
        if (!checkState) {
            $scope.allChecked = true;
            vehiclesStatService.doCheckedAllCheckBox($scope.allChecked);
            vehiclesStatService.setAllCheckedVehiclesStatus();
            $scope.more_action_active = vehiclesStatService.moreActionStatus();
            console.log(vehiclesStatService.getChkUnchkIndexRef());
        } else {
            $scope.allChecked = false;
            vehiclesStatService.doUnCheckedAllCheckBox($scope.allChecked);
            vehiclesStatService.setAllUnCheckedVehiclesStatus();
            $scope.more_action_active = vehiclesStatService.moreActionStatus();
            console.log(vehiclesStatService.getChkUnchkIndexRef());
        }
    };

    /**
     * 
     * @param {type} vstatus
     * @returns {undefined}
     */
    $scope.checkUncheck = function(vstatus) {

        /* for Individual checkbox logic here*/
        var indvcheckboxstatus = vehiclesStatService.getcheckUncheckStatus();

        /*
         * If all checkbox = true by master check box, but checked by individual
         *  
         */
        if (!indvcheckboxstatus && $scope.allChecked) {
            // not empty statusRef array
            if (!vehiclesStatService.isEmptyChkUnchkRef()) {
                if (vehiclesStatService.isExistSidInChkUnchkRef(vstatus.sid)) {
                    vehiclesStatService.removeStatusIdToChkUnchkRef(vstatus.sid);
                } else {
                    vehiclesStatService.addStatusIdToChkUnchkRef(vstatus.sid);
                }
                $scope.more_action_active = vehiclesStatService.moreActionStatus();
            }
            console.log(vehiclesStatService.getChkUnchkIndexRef());
        } else if (!indvcheckboxstatus && !$scope.allChecked) {
            if (vehiclesStatService.isEmptyChkUnchkRef()) {
                vehiclesStatService.addStatusIdToChkUnchkRef(vstatus.sid);
                $scope.more_action_active = vehiclesStatService.moreActionStatus();
            } else {
                if (vehiclesStatService.isExistSidInChkUnchkRef(vstatus.sid)) {
                    vehiclesStatService.removeStatusIdToChkUnchkRef(vstatus.sid);
                } else {
                    vehiclesStatService.addStatusIdToChkUnchkRef(vstatus.sid);
                }
                $scope.more_action_active = vehiclesStatService.moreActionStatus();
            }
            console.log(vehiclesStatService.getChkUnchkIndexRef());
        }
    };



    $scope.editVehiclesStatus = function(vstate) {
        $scope.selectedvstate = angular.copy(vstate);
        console.log($scope.selectedvstate);
    };
});
