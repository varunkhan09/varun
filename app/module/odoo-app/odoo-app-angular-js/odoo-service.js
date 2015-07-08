
odooApp.service('vModelService', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.getTransmissions = function() {
            return $http.get(serviceBase + 'transmissions');
        };
        this.getVehiclesModels = function() {
            return $http.get(serviceBase + 'vehiclesmodels');
        };

        this.getTransmissionById = function(id) {
            return $http.get(serviceBase + 'transmissionById&id=' + id);
        };

        this.fuelTypeById = function(id) {
            return $http.get(serviceBase + 'fueltypeById&id=' + id);
        };


        this.getModelById = function(model_id) {
            return $http.get(serviceBase + 'vmodel&id=' + model_id);
        };

        this.getVehicleById = function(vid) {
            return $http.get(serviceBase + 'vehiclebyId&id=' + vid);
        };


        /**
         * selected_vehicles_models variable being used to store 
         * @type type
         */

        var selected_vehicle_model = null;
        this.getVehiclesModel = function() {
            if (selected_vehicle_model !== null) {
                return selected_vehicle_model;
            }
            return null;
        };

        this.setVehiclesModel = function(model) {
            selected_vehicle_model = model.model_brand + ' / ' + model.model_name;
            // console.log(selected_vehicle_model);
        };

        this.getFuels = function() {
            return $http.get(serviceBase + 'fuels');
        };

        this.saveNewVehicles = function(vehicle) {
            return $http.post(serviceBase + 'savevehicle', vehicle).then(function(response) {
                return response;
            });

        };


        this.editVehicles = function(vehicle) {
            return $http.post(serviceBase + 'editvehicle', vehicle).then(function(results) {
                return results;
            });
        };


        this.saveDriver = function(driver) {
            return $http.post(serviceBase + 'savedriver', driver)
                    /* success */
                    .then(function(response) {
                        return response;
                        /* failure */
                    }, function(response) {
                        console.log(response);
                    });
        };

        var driverList = [];
        this.setDriverList = function(driver) {
            driverList.push(driver);
        };

        this.loadDrivers = function() {
            return $http.get(serviceBase + 'drivers');
        };

        this.getDrivers = function() {
            return driverList;
        };

        this.getDriverById = function(driverId) {
            return $http.get(serviceBase + 'driver&id=' + driverId);
        };

        this.getDriverByVid = function(vid) {
            return $http.get(serviceBase + 'vehicle_driver&id=' + vid);
        };

        var selected_drivers = null;
        this.setDriver = function(driver) {
            selected_drivers = driver.name;
        };

        this.getDriver = function() {
            if (selected_drivers !== null) {
                return selected_drivers;
            }
            return null;
        };
    }]);


odooApp.service('Vehicles', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.loadVehicles = function() {
            return $http.get(serviceBase + 'vehicles');
        };

        this.loadDrivers = function() {
            return $http.get(serviceBase + 'drivers');
        };

        this.deleteDriver = function(driver_id) {
            return $http({url: serviceBase + 'delete_driver', method: 'DELETE', data: {id: driver_id}}).then(function(response) {
                return response;
            }, function(error) {
                console.log(error);
            });
        };

    }]);


odooApp.service('AdditionalService', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.deliveryTypes = function() {
            return $http.get(serviceBase + 'deliveryTypes');
        };

        this.areas = function() {
            return $http.get(serviceBase + 'areas');
        };

        this.saveAdditionalLog = function(additionalLog) {
            return $http.post(serviceBase + 'additional_log', additionalLog).then(function(response) {
                return response;
            });
        };

        this.getAdditionalLogs = function() {
            return $http.get(serviceBase + 'fetch_additional_logs');
        };


    }]);

odooApp.service('Odometer', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.loadOdometerByVid = function(vid) {
            return $http.get(serviceBase + 'odometerbyvid&id=' + vid);
        };

        this.get_vehicle_odometer_logToList = function() {
            return $http.get(serviceBase + 'vehicleOdometerLogToList');
        };

        this.loadVehiclesToOdometerLog = function() {
            return $http.get(serviceBase + 'getVehiclesToOdometer');
        };
        this.saveOdometerLog = function(odometer) {
            return $http.post(serviceBase + 'saveOdometer', odometer).then(function(response) {
                return response.status;
            });
        };

        this.updateOdometerEntryByVehicleId = function(updatedOdooMeterEntry) {
            return $http.post(serviceBase + 'updateOdometer', updatedOdooMeterEntry).then(function(response) {
                return response;
            });
        };

        this.getOdometerEntryById = function(oid) {
            return $http.get(serviceBase + 'odometerEntry&id=' + oid);
        };
    }]);

odooApp.service('Model', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.saveModel = function(model) {
            return $http.post(serviceBase + 'savemodel', model).then(function(response) {
                return response.status;
            });
        };

//    this.loadModel = function(){
//         return $http.get(serviceBase + 'vehiclesmodels');
//    };

        this.loadModel = function() {
            return $http.get(serviceBase + 'vehiclesmodels');
        };

        this.deleteModel = function(model) {
            return $http({url: serviceBase + 'deletemodel', method: 'DELETE', data: {id: model.id}}).then(function(response) {
                return response;
            }, function(error) {
                console.log(error);
            });
        };
    }]);


odooApp.service('VCost', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.load_vehicle_cost_type = function() {
            return $http.get(serviceBase + 'get_vehicle_cost_type');
        };

        this.get_vehicle_cost_LogToList = function() {
            return $http.get(serviceBase + 'vehicleCostLogToList');
        };


        this.save_vehicle_cost = function(vehiclecost) {
            return $http.post(serviceBase + 'save_vehicle_cost', vehiclecost).then(function(response) {
                return response.status;
            });
        };

        this.get_vehicle_by_Id = function(vid) {
            return $http.get(serviceBase + 'vehiclebyId&id=' + vid);
        };


        this.get_cost_type_byId = function(cost_id) {
            return $http.get(serviceBase + 'cost_type_by_id&id=' + cost_id);
        }

        // No content found error is here 
        this.get_vehicle_cost_detail_by_id = function(vid) {
            return $http.get(serviceBase + 'vehicle_cost_detail_by_id&id=' + vid);
        }

        this.save_vehicle_cost_type = function(cost) {
            return $http.post(serviceBase + 'save_vehicle_cost_type', cost).then(function(response) {
                return response.status;
            });
        }
    }]);


odooApp.service('VFuel', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.get_vehicle_fuel_details_by_id = function(vehicle_id) {
            return $http.get(serviceBase + 'vehicle_fuel_detail_by_id&id=' + vehicle_id);
        };

        this.load_vehicles = function() {
            return $http.get(serviceBase + 'vehicle_fuels');
        };

        this.get_vehicle_by_Id = function(vid) {
            return $http.get(serviceBase + 'vehiclebyId&id=' + vid);
        };

        this.save_vehicle_fuel_log = function(vehicle_fuel) {
            return $http.post(serviceBase + 'save_vehicle_fuels', vehicle_fuel).then(function(response) {
                return response.status;
            });
        };

        this.get_vehicle_fuel_logToList = function() {
            return $http.get(serviceBase + 'vehicleFuelLogToList');
        };

//    this.saveVehicleFuelLog= function(vehiclefuelLog){
//        return $http.post(serviceBase + 'save_vehicle_fuels', vehiclefuelLog).then(function(response) {
//          return response;
//        });
//    };

    }]);


odooApp.service('VService', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.get_vehicle_service_byId = function(vehicle_id) {
            return $http.get(serviceBase + 'vehicle_service_byId&id=' + vehicle_id);
        };

        this.save_vehicle_service_log = function(service_log) {
            return $http.post(serviceBase + 'save_vehicle_service', service_log).then(function(response) {
                return response.status;
            });
        };

        this.get_vehicle_service_logToList = function() {
            return $http.get(serviceBase + 'vehicleServiceLogToList');
        };

    }]);


odooApp.service('vehiclesStatService', ['$http', function($http) {
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?';
        this.getAllVehiclesStatus = function() {
            return $http.get(serviceBase + 'vehicles_status');
        };

        var vehicles_status = [];
        this.setTovehicles_status = function(data) {
            for (var index = 0; index < data.length; index++) {
                vehicles_status.push(data[index]);
            }
        };

        var chk_unchk_index_ref = [];
        this.setAllCheckedVehiclesStatus = function() {
            chk_unchk_index_ref.splice(0, chk_unchk_index_ref.length);
            for (var i = 0; i < vehicles_status.length; i++) {
                vehicles_status[i].checked = true;
                chk_unchk_index_ref.push(vehicles_status[i].sid);
            }
        };

        this.setAllUnCheckedVehiclesStatus = function() {
            for (var i = 0; i < vehicles_status.length; i++) {
                vehicles_status[i].checked = false;
            }
            // make empty array
            chk_unchk_index_ref.splice(0, chk_unchk_index_ref.length);
        };


        this.getChkUnchkIndexRef = function() {
            return chk_unchk_index_ref;
        };

        var checkboxstatus = false;

        /*
         * @type Boolean|status|status
         * To check all checked or unchecked checkbox status.
         */
        var allcheckboxstat = false;
        this.isAllChecked = function() {
            return allcheckboxstat;
        };

        this.doCheckedAllCheckBox = function(status) {
            allcheckboxstat = status;
        };

        this.doUnCheckedAllCheckBox = function(status) {
            allcheckboxstat = status;
        };

        this.isEmptyChkUnchkRef = function() {
            return chk_unchk_index_ref.length === 0 ? true : false;
        };


        this.addStatusIdToChkUnchkRef = function(sid) {
            chk_unchk_index_ref.push(sid);
        };

        this.removeStatusIdToChkUnchkRef = function(sid) {
            var index = chk_unchk_index_ref.indexOf(sid);
            if (index < 0) {
            } else {
                chk_unchk_index_ref.splice(index, 1);
            }
        };

        this.isExistSidInChkUnchkRef = function(sid) {
            var index = chk_unchk_index_ref.indexOf(sid);
            if (index < 0) {
                return false;
            } else {
                return true;
            }
        };

        this.moreActionStatus = function() {
            return chk_unchk_index_ref.length === 0 ? false : true;
        };

        this.getcheckUncheckStatus = function() {
            return checkboxstatus;
        };
    }]);

odooApp.service('Report', ['$http', function($http) {  
        var serviceBase = 'http://varun.floshowers.com:8882/app/module/odoo-app/server/api.php/q?'; 
        this.getReport = function(search) {
            return $http.post(serviceBase + 'report_data', search).then(function(response) {
                return response;
            });
        };
        
          this.getDriversReport = function(search) {
            return $http.post(serviceBase + 'all_drivers_report_data', search).then(function(response) {
                return response;
            });
        };
}]);

