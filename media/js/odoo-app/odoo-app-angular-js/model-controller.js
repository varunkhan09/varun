
odooApp.controller('ModelListController', function($scope, $modal, Model) {
    $scope.model = {};    
    
    $scope.showModal = false;
    load_models();
    function load_models(){
    Model.loadModel().then(function(response) {
        $scope.models = response.data;
    });
}
    
    $scope.editToggleModal = function(model) {
        $scope.showModal = !$scope.showModal;
        $scope.model = model;
    };

    $scope.cancalEditModel = function() {
        $scope.model = {};    
        $scope.showModal = !$scope.showModal;   
    };

    $scope.saveEditModel = function(model) {
        Model.saveModel(model).then(function(data) {
            if (data === 200) { // sucessfully update
                $scope.showModal = !$scope.showModal;
            }
        });
    };

    $scope.createModel = function() {
        $modal.open({
            templateUrl: 'newModel.html',
            backdrop: true,
            windowClass: 'modal',
            controller: function($scope, $modalInstance, model) {
                $scope.model = model;
                $scope.submit = function() {
                    if ($scope.model.model_brand !== null && $scope.model.model_name !== null) {
                        Model.saveModel($scope.model).then(function(status){
                           if(status === 200){
                                $scope.model = {
                                    model_brand: null,
                                    model_name: null
                                };
                                console.log('saved '+status);
                                load_models();
                           }
                        });
                        
                        $scope.model = {
                            model_brand: null,
                            model_name: null
                        };
                        
                        $modalInstance.dismiss('cancel'); 
                    }
                };
                $scope.cancel = function() {
                    $scope.model = {
                            model_brand: null,
                            model_name: null
                        };
                    $modalInstance.dismiss('cancel');
                };
            },
            resolve: {
                model: function() {
                   return $scope.model;
                }
            }
        });
    
    };



    $scope.delete_model = function(model) {
        Model.deleteModel(model).then(function(data) {
            if (data.status === 200) {
                Model.loadModel().then(function(response) {
                    $scope.models = response.data;
                });
            }
        });
    };
    
});
























/*
odooApp.controller('ModalController', function($scope, $modal, $log, Model) {
    $scope.model = {
        model_brand: null,
        model_name: null
    };
    $scope.open = function() {
        $modal.open({
            templateUrl: 'createModel.html',
            backdrop: true,
            windowClass: 'modal',
            controller: function($scope, $modalInstance, $log, model) {
                $scope.model = model;
                $scope.submit = function() {
                    if ($scope.model.model_brand !== null && $scope.model.model_name !== null) {
                        // $log.log(model);
                        //  
                        Model.saveModel($scope.model);
                        $scope.model = {
                            model_brand: null,
                            model_name: null
                        };
                        $modalInstance.dismiss('cancel');
                    }
                };
                $scope.cancel = function() {
                    $modalInstance.dismiss('cancel');
                };
            },
            resolve: {
                model: function() {
                    return $scope.model;
                }
            }
        });
    };

});
*/