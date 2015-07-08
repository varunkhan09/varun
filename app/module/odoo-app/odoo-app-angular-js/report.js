
odooApp.controller("ReportCtrl", function($scope, $filter, $rootScope, Vehicles, Report) {
    $scope.pageTitle = "Report";
    $scope.Report = {};
    $scope.get_all = false;
    $scope.get_by = false;

    /*Date validation and filter */
    $scope.$watch('Report.fromDate', function(newValue) {
        $scope.Report.fromDate = $filter('date')(newValue, 'yyyy-MM-dd');
        if ($scope.Report.toDate < $scope.Report.fromDate) {
            $scope.Report.toDate = '';
        }
    });

    $scope.$watch('Report.toDate', function(newValue) {
        $scope.Report.toDate = $filter('date')(newValue, 'yyyy-MM-dd');
        if ($scope.Report.toDate < $scope.Report.fromDate) {
            $scope.Report.toDate = '';
        }
    });


    /*
     * Load Driver list
     */
    Vehicles.loadDrivers().then(function(response) {
        $scope.Report.drivers = response.data;
    });

    /* 
     * Set Driver on selection
     */

    $scope.set_driver = function(driver) {
        $scope.Report.driver = driver.name;
        $scope.Report.did = driver.did;
    };

    /*
     * Load Vehicles List 
     */
    Vehicles.loadVehicles().then(function(response) {
        $scope.Report.vehicles = response.data;
    });

    /* 
     * Set Vehicles on selection
     */
    $scope.set_vehicle = function(vehicle) {
//        Vehicles.getDriverById(vehicle.driver_id).then(function(response) {
//            $scope.set_driver(response.data);
//        });

        $scope.Report.vid = vehicle.vid;
        $scope.Report.vehicle = vehicle.model_brand + '/' + vehicle.model_name + '/' + vehicle.licence_plate;
    };
    $scope.refresh = function() {
        $scope.Report.vid ="";
        $scope.Report.vehicle="";
        $scope.Report.driver = "";
        $scope.Report.did = "";
        $scope.Report.toDate ="";
        $scope.Report.fromDate="";
        $rootScope.$broadcast('clear_page',1);
    };

$scope.get_all_drivers_report = function(param){

        if(param === 'get_all'){
            
             $scope.get_all = true;
             $scope.get_by = false;
             
            var search = {
                vid: $scope.Report.vid ? $scope.Report.vid : "",
                did: $scope.Report.did ? $scope.Report.did : "",
                from_date: $scope.Report.fromDate ? $scope.Report.fromDate : "",
                to_date: $scope.Report.toDate ? $scope.Report.toDate : ""
            };
            Report.getDriversReport(search).then(function(response) {
                $rootScope.$broadcast('_child_view_data_active_all_drivers', response.data, search);
            });
        }
          
     };
     

    $scope.submitToGenerateReport = function(param) {
        
          if(param === 'get_by'){
              $scope.get_by = true;
              $scope.get_all = false;
              
              var search = {
            vid: $scope.Report.vid ? $scope.Report.vid : "",
            did: $scope.Report.did ? $scope.Report.did : "",
            from_date: $scope.Report.fromDate ? $scope.Report.fromDate : "",
            to_date: $scope.Report.toDate ? $scope.Report.toDate : ""
        };
       
        Report.getReport(search).then(function(response) {
            $rootScope.$broadcast('child_view_data_active', response.data, search);
        });
          }
        
    };
});

odooApp.controller("AllDriversReportViewCtrl", function($scope) {
    $scope.cReportTemplates = {};
    $scope.aReport = {};
    $scope.odometerData = {};
    $scope.additionalData = {};
    $scope.fuelData = {};
    $scope.serviceData = {};
    $scope.driver = {};
    $scope.rate = 2.25;
   
    $scope.date = new Date();
    $scope.cReportTemplates.url = 'views/all_drivers_report_data.html';
    
    
    
     $scope.$on('_child_view_data_active_all_drivers', function(event, data, search) {
          if (data.hasOwnProperty('odometer')
                || data.hasOwnProperty('fuel')
                || data.hasOwnProperty('additional')
                || data.hasOwnProperty('service')) {
            $scope.aReport.status = true;
            $scope.odometer = false;
        $scope.additional = false;
        $scope.fuel = false;
        $scope.service = false;

        $scope.show_odometer = false;
        $scope.show_additional = false;
        $scope.show_fuel = false;
        $scope.show_service = false;
        
        $scope.atotal_cost = 0.0;
        $scope.ftotal_cost = 0.0;
        $scope.stotal_cost = 0.0;
        $scope.ototal_cost = 0.0;

        
        if (data.hasOwnProperty('odometer')) {
            $scope.odometer = true;
            $scope.odometerData = data.odometer;
             if ($scope.odometerData.length > 0) {
                $scope.show_odometer = true;
                for(var k = 0 ; k < $scope.odometerData.length ; k++){
                    $scope.ototal_cost = $scope.ototal_cost + parseFloat(($scope.odometerData[k].cls_odometer -$scope.odometerData[k].op_odometer)* $scope.rate );
                }
            }
        }
        
        
        if (data.hasOwnProperty('additional')) {
            
            $scope.additional = true;
            $scope.additionalData = data.additional;
            if ($scope.additionalData.length > 0) {
                $scope.show_additional = true;
                for(var k = 0 ; k < $scope.additionalData.length ; k++){
                    for(var k = 0 ; k < $scope.additionalData.length ; k++){
                    $scope.atotal_cost = $scope.atotal_cost + parseFloat($scope.additionalData[k].delivery_rate);
                }
                }
              
            }
        }
        
        if (data.hasOwnProperty('fuel')) {
            $scope.fuel = true;
            $scope.fuelData = data.fuel;
            if ($scope.fuelData.length > 0) {
                $scope.show_fuel = true;
                 for(var k = 0 ; k < $scope.fuelData.length ; k++){
                    $scope.ftotal_cost = $scope.ftotal_cost + parseFloat($scope.fuelData[k].totalcost);
                }
            }

        }
        if (data.hasOwnProperty('service')) {
            $scope.service = true;
            $scope.serviceData = data.service;
            if ($scope.serviceData.length > 0) {
                $scope.show_service = true;
                  for(var k = 0 ; k < $scope.serviceData.length ; k++){
                    $scope.stotal_cost = $scope.stotal_cost + parseFloat($scope.serviceData[k].totalprice);
                }
            }
        }


        }
        $scope.gross_total = 0.0;
        $scope.gross_total =  $scope.gross_total + parseFloat($scope.stotal_cost);
         $scope.gross_total =  $scope.gross_total + parseFloat($scope.ftotal_cost);
          $scope.gross_total =  $scope.gross_total + parseFloat($scope.atotal_cost);
           $scope.gross_total =  $scope.gross_total + parseFloat($scope.ototal_cost);
          
        
        
           event.preventDefault();
     });
    
});



odooApp.controller("ReportViewCtrl", function($scope) {
    $scope.cReportTemplates = {};
    $scope.cReport = {};
    $scope.odometerData = {};
    $scope.additionalData = {};
    $scope.fuelData = {};
    $scope.serviceData = {};
    $scope.driver = {};
    $scope.rate = 2.25;

    $scope.date = new Date();

    $scope.cReportTemplates.url = 'views/report_data.html';
    // 'received data from parent controller'
    $scope.$on('child_view_data_active', function(event, data, search) {
        event.preventDefault();
        $scope.cReport.status = false;
        if (data.hasOwnProperty('odometer')
                || data.hasOwnProperty('fuel')
                || data.hasOwnProperty('additional')
                || data.hasOwnProperty('service')) {
            $scope.cReport.status = true;
        }


        $scope.odometer = false;
        $scope.additional = false;
        $scope.fuel = false;
        $scope.service = false;

        $scope.show_odometer = false;
        $scope.show_additional = false;
        $scope.show_fuel = false;
        $scope.show_service = false;
        
        $scope.atotal_cost = 0.0;
        $scope.ftotal_cost = 0.0;
        $scope.stotal_cost = 0.0;
        $scope.ototal_cost = 0.0;
       
        if (data.hasOwnProperty('odometer')) {
            $scope.odometer = true;
            $scope.odometerData = data.odometer;
             if ($scope.odometerData.length > 0) {
                $scope.show_odometer = true;
                for(var k = 0 ; k < $scope.odometerData.length ; k++){
                    $scope.ototal_cost = $scope.ototal_cost + parseFloat(($scope.odometerData[k].cls_odometer -$scope.odometerData[k].op_odometer)* $scope.rate );
                }
            }
        }
        
        
        if (data.hasOwnProperty('additional')) {
            $scope.additional = true;
            $scope.additionalData = data.additional;
            if ($scope.additionalData.length > 0) {
                $scope.show_additional = true;
                for(var k = 0 ; k < $scope.additionalData.length ; k++){
                    $scope.atotal_cost = $scope.atotal_cost + parseFloat($scope.additionalData[k].delivery_rate);
                }
            }
        }
        
        if (data.hasOwnProperty('fuel')) {
            $scope.fuel = true;
            $scope.fuelData = data.fuel;
            if ($scope.fuelData.length > 0) {
                $scope.show_fuel = true;
                for(var k = 0 ; k < $scope.fuelData.length ; k++){
                    $scope.ftotal_cost = $scope.ftotal_cost + parseFloat($scope.fuelData[k].totalcost);
                }
            }

        }
        if (data.hasOwnProperty('service')) {
            $scope.service = true;
            $scope.serviceData = data.service;
            if ($scope.serviceData.length > 0) {
                $scope.show_service = true;
                for(var k = 0 ; k < $scope.serviceData.length ; k++){
                    $scope.stotal_cost = $scope.stotal_cost + parseFloat($scope.serviceData[k].totalprice);
                }
            }
        }
        
         $scope.gross_total = 0.0;
         $scope.gross_total =  $scope.gross_total + parseFloat($scope.stotal_cost);
         $scope.gross_total =  $scope.gross_total + parseFloat($scope.ftotal_cost);
         $scope.gross_total =  $scope.gross_total + parseFloat($scope.atotal_cost);
         $scope.gross_total =  $scope.gross_total + parseFloat($scope.ototal_cost);
          
    });
    
    
    $scope.$on('clear_page', function(event, data) { 
        event.preventDefault();
        $scope.cReport.status = false;
        $scope.odometer = false;
        $scope.additional = false;
        $scope.fuel = false;
        $scope.service = false;
        $scope.show_odometer = false;
        $scope.show_additional = false;
        $scope.show_fuel = false;
        $scope.show_service = false;
        $scope.additionalData={};
        $scope.odometerData={};
        $scope.serviceData={};
        $scope.fuelData={};
        console.log(data);
    });
    
   
});