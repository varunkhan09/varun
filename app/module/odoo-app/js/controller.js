'use strict';
feetApp.controller('feetController', function($scope) {
    $scope.message = 'Everyone come and see how good I look!';
});

feetApp.controller('aboutController', function($scope) {
    $scope.message = 'Look! I am an about page.';
});

feetApp.controller('contactController', function($scope) {
    $scope.message = 'Contact us! JK. This is just a demo.';
});




feetApp.controller('gardnerSalaryController', function($scope, $filter,services,gardnersalaryservice,gardnerSalaryCommService) {
    /*Date validation and filter */
    $scope.$watch('from_month', function(newValue) {
        $scope.from_month = $filter('date')(newValue, 'yyyy-MM-dd');
        if ($scope.to_month < $scope.from_month) {
            $scope.to_month = '';
        };
    });

    $scope.$watch('to_month', function(newValue) {
        $scope.to_month = $filter('date')(newValue, 'yyyy-MM-dd');
        if ($scope.to_month < $scope.from_month) {
            $scope.to_month = '';
        };
    });

    /* To get all gardner list*/
    services.getGardners().then(function(data) {
        $scope.gardners = data.data;
       // console.log(data);
    });
       
    /* To get particular gardner data */
    $scope.gardnerSalaryUpdateOnChange = function(gardner) {
            /* calculate salary of partcular gardner */ 
             gardnersalaryservice.getGardnerSalary(gardner).then(function(data){
                 /*Call Salary Service of Gardener and save it for processing*/
                 gardnerSalaryCommService.doSaveSalary(data);
                 /*Broadcast salary details to display to its child controllers*/
                 $scope.$broadcast ('broadcastSalaryDetails');
            });
      
    };
});

feetApp.controller('individualGardnerSalaryController', function($scope,gardnerSalaryCommService) {
    $scope.message = 'Salary Details:';
    // hidden value in html page, being used to calculate
    $scope.currentdate = new Date();
    var gardner_salary_obj = {};
    $scope.gardnersal ={
            gdsaltbl_id:'',
            user_type:'',
            monthly_salary: 0,
            working_hour:0,
            user_id: 0,
            name:'----',
            phone_no:0,
            is_active:0,
            max_credit_amount: 0
        };
        
    $scope.$on('broadcastSalaryDetails', function() {
        gardner_salary_obj = gardnerSalaryCommService.getSalary();
        if (typeof gardner_salary_obj === 'object' && gardner_salary_obj !== null) { 
               for(var index = 0 ; index < $scope.gardners.length; index++){
                   if(gardner_salary_obj.user_id === $scope.gardners[index].id ){
                        //console.log($scope.gardners[index]);
                        $scope.gardnersal ={
                            gdsaltbl_id:gardner_salary_obj.id,
                            user_type:gardner_salary_obj.user_type,
                            monthly_salary: gardner_salary_obj.monthly_salary,
                            working_hour:gardner_salary_obj.working_hour,
                            user_id: $scope.gardners[index].id,
                            name:$scope.gardners[index].name,
                            phone_no:$scope.gardners[index].phone_no,
                            is_active:$scope.gardners[index].is_active,
                            max_credit_amount: $scope.gardners[index].max_credit_amount,
                            num_days_in_current_month : new Date($scope.currentdate.getFullYear(),$scope.currentdate.getMonth() + 1,0).getDate()
                       }; 
                   }
               }
        }else{
             $scope.gardnersal = {
                    gdsaltbl_id:'',
                    user_type:'',
                    monthly_salary: 0,
                    working_hour:0,
                    user_id: 0,
                    name:'----',
                    phone_no:0,
                    is_active:0,
                    max_credit_amount: 0
            };
        }   
//        var start = Date.now();
//        console.log("Page load took " + (Date.now() - start) + "milliseconds");
//        
    });
});






feetApp.controller('gardnerAdvancePaymentController', ['$scope', '$filter', '$http', function($scope, $filter, $http) {
      
        // To format date time to page
        $scope.$watch('date', function(newValue) {
            $scope.date = $filter('date')(newValue, 'yyyy-MM-dd');
        });

        // To set default date in calander
        $scope.date = new Date();

        /* To fill Gardner Select box */
        var gardnerslist = [];
        getGardnerList();
        function getGardnerList() {
            $http.get("../server/action.php?action=get_gardner_list").success(function(response) {
              //  console.log(response);
                for (var i = 0; i < response.length; i++) {
                    gardnerslist.push(response[i]);
                }
            });
            $scope.options = gardnerslist;
        }




        /* On submit, save data to the database.*/
        $scope.submit = function() {
            var balance_amount = $scope.balance_credit - $scope.pay_amount;
            if (balance_amount >= 0) {
            } else {
                $scope.alerts = [
                    {type: 'warning', msg: 'Advance payment cannot be exceeded more than balance amount'}
                ];
                return false;
            }

            var gardnerContact = {
                action: 'gardner_adv_payment',
                gardner_id: $scope.gardner,
                pay_amount: $scope.pay_amount,
                pay_date: $scope.date
            };
            $http.post("../server/action.php", gardnerContact)
                    .success(function(data, status, headers, config) {
                        //console.log(data);
                        if (status === 200) {
                            $scope.gardner = '';
                            $scope.pay_amount = '';
                            $scope.alerts = [
                                {type: 'success', msg: 'Your information has been successfully added.'}
                            ];

                            /*
                             * update when payment is done
                             */
                            $scope.gardner_update(gardnerContact['gardner_id']);
                        }
                    })
                    .error(function(data, status, header, config) {
                        // error handler
                        //console.log(data);
                        $scope.alerts = [
                            {type: 'error', msg: 'Oh snap! Change a few things up and try submitting again.'}
                        ];
                    });
        };

        $scope.closeAlert = function(index) {
            $scope.alerts.splice(index, 1);
        };
        /* On submit end */

        $scope.max_credit = 0;
        $scope.balance_credit = 0;
        $scope.total_paid_amount = $scope.max_credit-$scope.balance_credit;
        //  $scope.price=$filter('currency')($scope.price,"&#8377;")
        /*Update Gardner Balance Amount*/



       
        $scope.gardner_update = function(gardner_id) {

            $http.get("../server/action.php?action=get_gardner_advance_amount&gardner_id=" + gardner_id).success(function(response) {
                var obj = {};
                //console.log(response);
                obj = response[0];
                if (obj.gardner_id === gardner_id) {
                    var gar_object = {};
                    for (var i = 0; i < gardnerslist.length; i++) {
                        gar_object = gardnerslist[i];
                        if (gar_object.id === gardner_id) {
                            $scope.name = gar_object.name;
                            $scope.phone_no = gar_object.phone_no;
                            $scope.max_credit = gar_object.max_credit_amount;
                            $scope.balance_credit = gar_object.max_credit_amount - obj.total_advance;
                             $scope.total_paid_amount = $scope.max_credit-$scope.balance_credit;
                            getGardnerPaymentDetails(gardner_id);

                            break;
                        }
                    }
                } else {
                    var gar_object = {};
                    for (var i = 0; i < gardnerslist.length; i++) {
                        gar_object = gardnerslist[i];
                        if (gar_object.id === gardner_id) {
                            $scope.name = gar_object.name;
                            $scope.phone_no = gar_object.phone_no;
                            $scope.max_credit = gar_object.max_credit_amount;
                            $scope.balance_credit = gar_object.max_credit_amount;//- obj.total_advance;
                            $scope.total_paid_amount = $scope.max_credit-$scope.balance_credit;
                            getGardnerPaymentDetails(gardner_id);
                            break;
                        }
                    }
                }
            });
            
            
            
         
            
            
        };
        function getGardnerPaymentDetails(gardner_id) {
            $http.get("../server/action.php?action=get_gardner_advance_payment_details&gardner_id=" + gardner_id + "&date=" + $scope.date)
                    .success(function(data) {
                        if (typeof data === 'object' && data !== null) {
                            $scope.gardnerCollection = data;
                           
                        } else {
                            $scope.gardnerCollection = {};
                        }
                    })
                    .error(function(data, status) {
                        console.error('Repos error', status, data);
                    });
//           .finally(function() {
//                console.log("finally finished");
//            });
        }






    }]);



feetApp.controller('addGardnerController', function($scope, $http) {
    $scope.showModal = false;
    $scope.toggleModal = function() {
        $scope.showModal = !$scope.showModal;
    };
    $scope.add_gardner_on_submit = function(gardner_name, phone_number, credit_amount) {
        var gardner_info_to_add = {
            action: 'gardner_info_save_todb',
            name: gardner_name,
            max_credit_amount: credit_amount,
            phone_no: phone_number,
            is_active: 1
        };

        $http.post("../server/action.php", gardner_info_to_add)
                .success(function(data, status, headers, config) {
                    //console.log(data);
                    if (status === 200) {
                        $scope.gardner_name = '';
                        $scope.credit_amount = '';
                        $scope.phone_number = '';
                        $scope.alerts = [
                            {type: 'success', msg: 'Your information has been successfully added.'}
                        ];
                    }
                })
                .error(function(data, status, header, config) {
                    // error handler
                    //console.log(data);
                    //console.log(status);
                    $scope.alerts = [
                        {type: 'error', msg: 'Oh snap! Change a few things up and try submitting again.'}
                    ];
                });
    };
    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };
});



feetApp.controller("gardnersDetailsCtrl", function($scope, $log, $http, $location) {
    $scope.screens = ["Gardners List"];
    $scope.current = $scope.screens[0];
    $scope.setScreen = function(index) {
        $scope.current = $scope.screens[index];
    };
    $scope.getScreen = function() {
        return $scope.current === 'Gardners List' ? 'pages/gardner-list.html' : '';
    };



});

feetApp.controller("gardnerListCtrl", function($scope, $log, $http) {
    getGardnerList();
   
    function getGardnerList() {
        $http.get("../server/action.php?action=get_all_gardner_list")
                .success(function(data) {
                    if (typeof data === 'object' && data !== null) {
                        $scope.gardners = data;
                    }
                    // console.log(data);
                })
                .error(function(error) {
                    console.log(error);
                });
    }
    $scope.createGardner = function(gardner) {
        if (!gardner.name || !gardner.phone_no || !gardner.max_credit_amount) {
            return false;
        }

        var gardner_info = {
            action: 'gardner_info_save_todb',
            name: gardner.name,
            max_credit_amount: gardner.max_credit_amount,
            phone_no: gardner.phone_no,
            is_active: gardner.is_active
        };
        $http.post("../server/action.php", gardner_info)
                .success(function(data, status, headers, config) {
                    //console.log(data);
                    if (status === 200) {
                        $scope.editedGardner.name = '';
                        $scope.editedGardner.max_credit_amount = '';
                        $scope.editedGardner.phone_no = '';
                        $scope.editedGardner.is_active = '';
                        getGardnerList();
                        $scope.alerts = [
                            {type: 'success', msg: 'Your information has been successfully added.'}
                        ];
                    }
                })
                .error(function(data, status, header, config) {
                    // error handler
                    //console.log(data);
                    $scope.alerts = [
                        {type: 'error', msg: 'Oh snap! Change a few things up and try submitting again.'}
                    ];
                });
    };
    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };

    $scope.cancelEdit = function() {
        $scope.editedGardner = {};
        $scope.editedGardner = !$scope.editedGardner;
        $scope.displayMode = "gardner-info";
    };
    $scope.deleteGardner = function(gardner) {
        var gardner = {
            action: 'gardner_info_delete',
            id: gardner.id,
            is_active: gardner.is_active
        };
        $http.post("../server/action.php", gardner)
                .success(function(data, status, headers, config) {
                    //.log(data);
                    if (status === 200) {
                        getGardnerList();
                        $scope.alerts = [
                            {type: 'success', msg: 'Your information has been successfully added.'}
                        ];
                    }
                })
                .error(function(data, status, header, config) {
                    //  console.log(data);
                    $scope.alerts = [
                        {type: 'error', msg: 'Oh snap! Change a few things up and try submitting again.'}
                    ];
                });

    };
    $scope.currentGardner = null;
    $scope.editOrCreateGardner = function(gardner) {
        $scope.currentGardner = gardner ? gardner : {};
        $scope.displayMode = "gardner-edit";
        // console.log($scope.currentGardner);
    };
    $scope.saveEditGardner = function(currentGardner) {
        console.log(currentGardner);
        var gardner = {
            action: 'gardner_info_update',
            id: currentGardner.id,
            name: currentGardner.name,
            max_credit_amount: currentGardner.max_credit_amount,
            phone_no: currentGardner.phone_no,
            is_active: currentGardner.is_active
        };
        $http.post("../server/action.php", gardner)
                .success(function(data, status, headers, config) {
                    //    console.log(data);
                    if (status === 200) {
                        $scope.displayMode = 'gardner-info';
                        getGardnerList();
                        $scope.alerts = [
                            {type: 'success', msg: 'Your information has been successfully added.'}
                        ];
                    }
                })
                .error(function(data, status, header, config) {
                    // console.log(data);
                    $scope.alerts = [
                        {type: 'error', msg: 'Oh snap! Change a few things up and try submitting again.'}
                    ];
                });

    };



    $scope.showModal = false;
    $scope.toggleModal = function(gardner) {
        //console.log(gardner);
        $scope.name = gardner.name;
        $scope.phone_no = gardner.phone_no;
        $scope.max_credit_amount = gardner.max_credit_amount;
        $scope.id = gardner.id;
        $scope.is_active = gardner.is_active;
        $scope.showModal = !$scope.showModal;
    };

    $scope.add_gardner_on_edit_submit = function(is_active, id, name, phone_no, max_credit_amount) {
        var object = {
            id: id,
            is_active: is_active,
            name: name,
            phone_no: phone_no,
            max_credit_amount: max_credit_amount
        };
        $scope.saveEditGardner(object);
        $scope.showModal = !$scope.showModal;
        console.log(object);
    };

    /** Pagination start */
    if (!$scope.currentPage)
        $scope.currentPage = 1;

    if (!$scope.maxSize)
        $scope.maxSize = 5;

    if (!$scope.totalItems)
        $scope.totalItems = 15;

    var start = 0;
    var limit = 10;
    var end;

    $scope.pageChanged = function() {
        start = ($scope.currentPage * limit) - limit;
        end = start + limit;
        //$log.log('start ' + start);
        //$log.log('end ' + end);
        $http.get("../server/action.php?action=get_all_offset_gardner_record&start=" + start + "&end=" + end)
                .success(function(data) {
                    if (data !== null) {
                        $scope.gardners = data;
                        // console.log(data);
                        $scope.totalItems = data.totalcount;
                        //console.log($scope.totalItems);
                    }
                    // alert(typeof data); 
                })
                .error(function(error) {
                    console.log(error);
                });
    };
    /** Pagination end */
});



