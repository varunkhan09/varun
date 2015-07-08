"use strict";
myshop.controller('PanelCtrl', function($scope,$rootScope) {
});
//////////////////////////////////////////////////////////////////
/*
 * SHOP INFORMATION
 */

myshop.controller('ShopAddByUserCtrl',function($scope,$routeParams,ShopService,EmpService,$log,$location,ROOT) {

  $scope.Shop = {};
  $scope.Shop.user_id =  $routeParams.uid;

    /* For SUNDAY */
  $scope.$watch('Shop.sunday_open', function(otime) {
    if($scope.Shop.sunday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.sunday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.sunday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.sunday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.sunday_close', function(otime) {
    if($scope.Shop.sunday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.sunday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.sunday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.sunday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


/* For MONDAY  */
  $scope.$watch('Shop.monday_open', function(otime) {
    if($scope.Shop.monday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.monday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.monday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.monday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.monday_close', function(otime) {
    if($scope.Shop.monday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.monday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.monday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.monday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

/* For TUESDAY  */
  $scope.$watch('Shop.tuesday_open', function(otime) {
    if($scope.Shop.tuesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.tuesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.tuesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.tuesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.tuesday_close', function(otime) {
    if($scope.Shop.tuesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.tuesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.tuesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.tuesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

/* For wednesday  */
  $scope.$watch('Shop.wednesday_open', function(otime) {
    if($scope.Shop.wednesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.wednesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.wednesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.wednesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.wednesday_close', function(otime) {
    if($scope.Shop.wednesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.wednesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.wednesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.wednesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });
  

  //thrusday_close

  $scope.$watch('Shop.thrusday_open', function(otime) {
    if($scope.Shop.thrusday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.thrusday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.thrusday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.thrusday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.thrusday_close', function(otime) {
    if($scope.Shop.thrusday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.thrusday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.thrusday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.thrusday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

  //friday 
  $scope.$watch('Shop.friday_open', function(otime) {
    if($scope.Shop.friday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.friday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.friday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.friday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.friday_close', function(otime) {
    if($scope.Shop.friday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.friday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.friday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.friday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

  //saturday_close 
  $scope.$watch('Shop.saturday_open', function(otime) {
    if($scope.Shop.saturday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.saturday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.saturday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.saturday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.saturday_close', function(otime) {
    if($scope.Shop.saturday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.saturday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.saturday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.saturday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


//$scope.Shop.user_id

if($scope.Shop.user_id !== undefined || $scope.Shop.user_id !== null){
    EmpService.getEmployeeInfo($scope.Shop.user_id).then(function(response){
      $scope.Shop.contact_person =   response.data.firstname ||'';
    });
}



  $scope.shop_submit = function(){
    // shop details
    $scope.Shop.shop_name  =  $scope.Shop.shop_name ? $scope.Shop.shop_name : "";
    $scope.Shop.website_url  =  $scope.Shop.website_url ? $scope.Shop.website_url : "";
    $scope.Shop.address  =  $scope.Shop.address ? $scope.Shop.address : "";
    $scope.Shop.city  =  $scope.Shop.city ? $scope.Shop.city : "";
    $scope.Shop.pincode  =  $scope.Shop.pincode ? $scope.Shop.pincode : "";
    $scope.Shop.phone_number  =  $scope.Shop.phone_number ? $scope.Shop.phone_number : "";
    $scope.Shop.alt_phone_number  =  $scope.Shop.alt_phone_number ? $scope.Shop.alt_phone_number : "";
    $scope.Shop.email  =  $scope.Shop.email ? $scope.Shop.email : "";
 //   $scope.Shop.contact_person  =  $scope.Shop.contact_person ? $scope.Shop.contact_person : "";
    // Working day
    $scope.Shop.sunday_open  =  $scope.Shop.sunday_open ? $scope.Shop.sunday_open : "";
    $scope.Shop.sunday_close  =  $scope.Shop.sunday_close ? $scope.Shop.sunday_close : "";
    $scope.Shop.monday_open  =  $scope.Shop.monday_open ? $scope.Shop.monday_open : "";
    $scope.Shop.monday_close  =  $scope.Shop.monday_close ? $scope.Shop.monday_close : "";
    $scope.Shop.tuesday_open  =  $scope.Shop.tuesday_open ? $scope.Shop.tuesday_open : "";
    $scope.Shop.tuesday_close  =  $scope.Shop.tuesday_close ? $scope.Shop.tuesday_close : "";
    $scope.Shop.wednesday_open  =  $scope.Shop.wednesday_open ? $scope.Shop.wednesday_open : "";
    $scope.Shop.wednesday_close  =  $scope.Shop.wednesday_close ? $scope.Shop.wednesday_close : "";
    $scope.Shop.thrusday_open  =  $scope.Shop.thrusday_open ? $scope.Shop.thrusday_open : "";
    $scope.Shop.thrusday_close  =  $scope.Shop.thrusday_close ? $scope.Shop.thrusday_close : "";
    $scope.Shop.friday_open  =  $scope.Shop.friday_open ? $scope.Shop.friday_open : "";
    $scope.Shop.friday_close  =  $scope.Shop.friday_close ? $scope.Shop.friday_close : "";
    $scope.Shop.saturday_open  =  $scope.Shop.saturday_open ? $scope.Shop.saturday_open : "";
    $scope.Shop.saturday_close  =  $scope.Shop.saturday_close ? $scope.Shop.saturday_close : "";

   ShopService.saveUserShop($scope.Shop).then(function(resource){
        $log.debug( resource );
        if(resource.status === 200){
          alert('Successfully submitted.');
          $location.path(ROOT.DOMAIN);
        }else{
          alert('Provide input and try it again.');
        }
    });
  }
});


/////////////////////////////////////////////////////////////////////////////////////////


myshop.controller('ShopInfoEditCtrl',function($routeParams,$scope,$log,$location,EmpService,ShopService,ROOT) {
console.log($routeParams);
$scope.update = false;
$scope.editable = true;
$scope.readonlyflag = true;
$scope.Shop = {};

$scope.edit_shop_id = 1;

/* If shop_id & user_id coming from route url then */
if($routeParams.hasOwnProperty('shop_id') &&  $routeParams.hasOwnProperty('user_id')){
    if( typeof $routeParams.shop_id === 'string'){
        $scope.edit_shop_id = parseInt($routeParams.shop_id,10);
        loadShopInfo($scope.edit_shop_id);
    }  

    if( typeof $routeParams.user_id === 'string'){
        $scope.user_id = parseInt($routeParams.user_id,10);
        EmpService.getEmployeeInfo($scope.user_id).then(function(response){
            $scope.Shop.contact_person =   response.data.firstname || " ";
        });
       
    } 
}



    function loadShopInfo(shop_id){
        ShopService.getShopInfo(shop_id).then(function(response){
            if(response.status === 200 && (response.data !== "" || response.data === undefined)){
                $scope.Shop = response.data; 
                console.log($scope.Shop);
                $scope.Shop.home_delivery = response.data.home_delivery ? true : false;
            }
        });
    }


/* For SUNDAY */
  $scope.$watch('Shop.sunday_open', function(otime) {
    if($scope.Shop.sunday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.sunday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.sunday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.sunday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.sunday_close', function(otime) {
    if($scope.Shop.sunday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.sunday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.sunday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.sunday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


/* For MONDAY  */
  $scope.$watch('Shop.monday_open', function(otime) {
    if($scope.Shop.monday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.monday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.monday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.monday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.monday_close', function(otime) {
    if($scope.Shop.monday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.monday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.monday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.monday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });



/* For TUESDAY  */
  $scope.$watch('Shop.tuesday_open', function(otime) {
    if($scope.Shop.tuesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.tuesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.tuesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.tuesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.tuesday_close', function(otime) {
    if($scope.Shop.tuesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.tuesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.tuesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.tuesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });



/* For wednesday  */
  $scope.$watch('Shop.wednesday_open', function(otime) {
    if($scope.Shop.wednesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.wednesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.wednesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.wednesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.wednesday_close', function(otime) {
    if($scope.Shop.wednesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.wednesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.wednesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.wednesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });
  

  //thrusday_close

  $scope.$watch('Shop.thrusday_open', function(otime) {
    if($scope.Shop.thrusday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.thrusday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.thrusday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.thrusday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.thrusday_close', function(otime) {
    if($scope.Shop.thrusday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.thrusday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.thrusday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.thrusday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

  //friday 

  $scope.$watch('Shop.friday_open', function(otime) {
    if($scope.Shop.friday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.friday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.friday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.friday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.friday_close', function(otime) {
    if($scope.Shop.friday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.friday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.friday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.friday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


  //saturday_close 

  $scope.$watch('Shop.saturday_open', function(otime) {
    if($scope.Shop.saturday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.saturday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.saturday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.saturday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.saturday_close', function(otime) {
    if($scope.Shop.saturday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.saturday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.saturday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.saturday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

  $scope.make_editable = function(){
      $scope.update = !$scope.update ;
      $scope.editable = !$scope.editable ;
      $scope.readonlyflag = !$scope.readonlyflag;
  }



  $scope.save_shop = function(){
    // shop details
    $scope.Shop.shop_name  =  $scope.Shop.shop_name ? $scope.Shop.shop_name : "";
    $scope.Shop.website_url  =  $scope.Shop.website_url ? $scope.Shop.website_url : "";
    $scope.Shop.address  =  $scope.Shop.address ? $scope.Shop.address : "";
    $scope.Shop.city  =  $scope.Shop.city ? $scope.Shop.city : "";
    $scope.Shop.pincode  =  $scope.Shop.pincode ? $scope.Shop.pincode : "";
    $scope.Shop.phone_number  =  $scope.Shop.phone_number ? $scope.Shop.phone_number : "";
    $scope.Shop.alt_phone_number  =  $scope.Shop.alt_phone_number ? $scope.Shop.alt_phone_number : "";
    $scope.Shop.email  =  $scope.Shop.email ? $scope.Shop.email : "";
    //$scope.Shop.contact_person  =  $scope.Shop.contact_person ? $scope.Shop.contact_person : "";

    // Working day
    $scope.Shop.sunday_open  =  $scope.Shop.sunday_open ? $scope.Shop.sunday_open : "";
    $scope.Shop.sunday_close  =  $scope.Shop.sunday_close ? $scope.Shop.sunday_close : "";
    $scope.Shop.monday_open  =  $scope.Shop.monday_open ? $scope.Shop.monday_open : "";
    $scope.Shop.monday_close  =  $scope.Shop.monday_close ? $scope.Shop.monday_close : "";
    $scope.Shop.tuesday_open  =  $scope.Shop.tuesday_open ? $scope.Shop.tuesday_open : "";
    $scope.Shop.tuesday_close  =  $scope.Shop.tuesday_close ? $scope.Shop.tuesday_close : "";
    $scope.Shop.wednesday_open  =  $scope.Shop.wednesday_open ? $scope.Shop.wednesday_open : "";
    $scope.Shop.wednesday_close  =  $scope.Shop.wednesday_close ? $scope.Shop.wednesday_close : "";
    $scope.Shop.thrusday_open  =  $scope.Shop.thrusday_open ? $scope.Shop.thrusday_open : "";
    $scope.Shop.thrusday_close  =  $scope.Shop.thrusday_close ? $scope.Shop.thrusday_close : "";
    $scope.Shop.friday_open  =  $scope.Shop.friday_open ? $scope.Shop.friday_open : "";
    $scope.Shop.friday_close  =  $scope.Shop.friday_close ? $scope.Shop.friday_close : "";
    $scope.Shop.saturday_open  =  $scope.Shop.saturday_open ? $scope.Shop.saturday_open : "";
    $scope.Shop.saturday_close  =  $scope.Shop.saturday_close ? $scope.Shop.saturday_close : "";
     
         ShopService.updateShop($scope.Shop).then(function(response){             
                if(response.status === 200){
                  // toggle action 
                  $scope.update = !$scope.update ;
                  $scope.editable = !$scope.editable ;
                  $scope.readonlyflag = !$scope.readonlyflag;
                  alert('Successfully submitted.');
                  window.history.go(-1);

                 // $location.path(ROOT.DOMAIN+"/app/module/myshop/#/user/"+$scope.user_id+"/shop/"+$scope.edit_shop_id);
                }else{
                  alert('Provide input and try it again.');
                }
            });
  }
});






myshop.controller('ShopInfoCtrl',function($scope,$log,ShopService) {
  $scope.Shop = {};


/* For SUNDAY */
  $scope.$watch('Shop.sunday_open', function(otime) {
    if($scope.Shop.sunday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.sunday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.sunday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.sunday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.sunday_close', function(otime) {
    if($scope.Shop.sunday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.sunday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.sunday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.sunday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


/* For MONDAY  */
  $scope.$watch('Shop.monday_open', function(otime) {
    if($scope.Shop.monday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.monday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.monday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.monday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.monday_close', function(otime) {
    if($scope.Shop.monday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.monday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.monday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.monday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });



/* For TUESDAY  */
  $scope.$watch('Shop.tuesday_open', function(otime) {
    if($scope.Shop.tuesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.tuesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.tuesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.tuesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.tuesday_close', function(otime) {
    if($scope.Shop.tuesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.tuesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.tuesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.tuesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });



/* For wednesday  */
  $scope.$watch('Shop.wednesday_open', function(otime) {
    if($scope.Shop.wednesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.wednesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.wednesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.wednesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.wednesday_close', function(otime) {
    if($scope.Shop.wednesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.wednesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.wednesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.wednesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });
  

  //thrusday_close

  $scope.$watch('Shop.thrusday_open', function(otime) {
    if($scope.Shop.thrusday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.thrusday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.thrusday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.thrusday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.thrusday_close', function(otime) {
    if($scope.Shop.thrusday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.thrusday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.thrusday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.thrusday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });




  //friday 

  $scope.$watch('Shop.friday_open', function(otime) {
    if($scope.Shop.friday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.friday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.friday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.friday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.friday_close', function(otime) {
    if($scope.Shop.friday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.friday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.friday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.friday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


  //saturday_close 

  $scope.$watch('Shop.saturday_open', function(otime) {
    if($scope.Shop.saturday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.saturday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Shop.saturday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Shop.saturday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Shop.saturday_close', function(otime) {
    if($scope.Shop.saturday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Shop.saturday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Shop.saturday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Shop.saturday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });




  $scope.shop_submit = function(){
    // shop details
    $scope.Shop.shop_name  =  $scope.Shop.shop_name ? $scope.Shop.shop_name : "";
    $scope.Shop.website_url  =  $scope.Shop.website_url ? $scope.Shop.website_url : "";
    $scope.Shop.address  =  $scope.Shop.address ? $scope.Shop.address : "";
    $scope.Shop.city  =  $scope.Shop.city ? $scope.Shop.city : "";
    $scope.Shop.pincode  =  $scope.Shop.pincode ? $scope.Shop.pincode : "";
    $scope.Shop.phone_number  =  $scope.Shop.phone_number ? $scope.Shop.phone_number : "";
    $scope.Shop.alt_phone_number  =  $scope.Shop.alt_phone_number ? $scope.Shop.alt_phone_number : "";
    $scope.Shop.email  =  $scope.Shop.email ? $scope.Shop.email : "";
    $scope.Shop.contact_person  =  $scope.Shop.contact_person ? $scope.Shop.contact_person : "";

    // Working day
    $scope.Shop.sunday_open  =  $scope.Shop.sunday_open ? $scope.Shop.sunday_open : "";
    $scope.Shop.sunday_close  =  $scope.Shop.sunday_close ? $scope.Shop.sunday_close : "";
    $scope.Shop.monday_open  =  $scope.Shop.monday_open ? $scope.Shop.monday_open : "";
    $scope.Shop.monday_close  =  $scope.Shop.monday_close ? $scope.Shop.monday_close : "";
    $scope.Shop.tuesday_open  =  $scope.Shop.tuesday_open ? $scope.Shop.tuesday_open : "";
    $scope.Shop.tuesday_close  =  $scope.Shop.tuesday_close ? $scope.Shop.tuesday_close : "";
    $scope.Shop.wednesday_open  =  $scope.Shop.wednesday_open ? $scope.Shop.wednesday_open : "";
    $scope.Shop.wednesday_close  =  $scope.Shop.wednesday_close ? $scope.Shop.wednesday_close : "";
    $scope.Shop.thrusday_open  =  $scope.Shop.thrusday_open ? $scope.Shop.thrusday_open : "";
    $scope.Shop.thrusday_close  =  $scope.Shop.thrusday_close ? $scope.Shop.thrusday_close : "";
    $scope.Shop.friday_open  =  $scope.Shop.friday_open ? $scope.Shop.friday_open : "";
    $scope.Shop.friday_close  =  $scope.Shop.friday_close ? $scope.Shop.friday_close : "";
    $scope.Shop.saturday_open  =  $scope.Shop.saturday_open ? $scope.Shop.saturday_open : "";
    $scope.Shop.saturday_close  =  $scope.Shop.saturday_close ? $scope.Shop.saturday_close : "";

           ShopService.saveShop($scope.Shop).then(function(resource){
                $log.debug( resource );
                if(resource.status === 200){
                  alert('Successfully submitted.');
                  $location.path('http://varun.floshowers.com:8882/');  
                }else{
                  alert('Provide input and try it again.');
                }
            });
  }
});





//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

/*
 * EMPLOYEE INFORMATION
 */

myshop.controller('EmployeeCtrl',function($scope,$log,$location,$routeParams,EmpService) {
    $scope.employeeList = {};

    /* If shop_id & user_id coming from route url then */
    if($routeParams.hasOwnProperty('shop_id') &&  $routeParams.hasOwnProperty('user_id')){
        if( typeof $routeParams.user_id === 'string'){
             $scope.user_id = parseInt($routeParams.user_id,10);
        }

        if( typeof $routeParams.shop_id === 'string'){
            $scope.shop_id = parseInt($routeParams.shop_id,10);
            // retrive employee of that shop id. both shopid and user id can pass to check 
            load_employee();
        } 
    }

    function load_employee(){
        EmpService.getEmployees().then(function(response){
            $scope.employeeList = response.data;
        })
    }

  
    $scope.empDelete = function ( emp_entity_id ) {
        // Ask for confirmation. Are You sure ? do you want to delete ??
        if (window.confirm("Do you really want to delete ?") == true) {
            EmpService.deleteEmployee(emp_entity_id).then(function(response){
                if(response.data.statusText === 'OK'){
                    load_employee();
                }    
            })
        } 
    };
});

//EmployeeViewCtrl


myshop.controller('EmployeeViewCtrl',function($scope,$log,$location,$routeParams,EmpService) {
    $scope.employee = {};
    /* If shop_id & user_id coming from route url then */
    if($routeParams.hasOwnProperty('shop_id') &&  $routeParams.hasOwnProperty('user_id') && $routeParams.hasOwnProperty('empid')){
        if( typeof $routeParams.user_id === 'string'){
             $scope.user_id = parseInt($routeParams.user_id,10);
        }

        if( typeof $routeParams.shop_id === 'string'){
            $scope.shop_id = parseInt($routeParams.shop_id,10);
        } 

        if( typeof $routeParams.empid === 'string'){
            $scope.emp_id = parseInt($routeParams.empid,10);
        } 

        EmpService.getEmployeeInfo($scope.emp_id).then(function(response){
             $scope.employee =  response.data;
        });
    }else{
        alert("Opps...! Something wrong");
    } 
});



// Employee Edit 
myshop.controller('EmployeeEditCtrl',function($scope,$routeParams,$location,$log,EmpService) {
    $scope.update = false;
    $scope.editable = true;
    $scope.readonlyflag = true;
    $scope.Emp = {};
    $scope.Roles = {};

    /* For SUNDAY */
      $scope.$watch('Emp.sunday_open', function(otime) {
        if($scope.Emp.sunday_open !== undefined){
            var opening_time =  String(otime);
            var t1 = opening_time.split(":");
            var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
            if($scope.Emp.sunday_close !== undefined){
                // close time is not empty then check
                var closing_time = String($scope.Emp.sunday_close);
                var t2 = closing_time.split(":");
                var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
                if(cminutes < ominutes) {
                    $scope.Emp.sunday_close = "";
                     alert('Sorry ! Invalid working hours.');
                }
            }
        }
      });

   $scope.$watch('Emp.sunday_close', function(otime) {
    if($scope.Emp.sunday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.sunday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.sunday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.sunday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


/* For MONDAY  */
  $scope.$watch('Emp.monday_open', function(otime) {
    if($scope.Emp.monday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.monday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.monday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.monday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.monday_close', function(otime) {
    if($scope.Emp.monday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.monday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.monday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.monday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });



/* For TUESDAY  */
  $scope.$watch('Emp.tuesday_open', function(otime) {
    if($scope.Emp.tuesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.tuesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.tuesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.tuesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.tuesday_close', function(otime) {
    if($scope.Emp.tuesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.tuesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.tuesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.tuesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });



/* For wednesday  */
  $scope.$watch('Emp.wednesday_open', function(otime) {
    if($scope.Emp.wednesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.wednesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.wednesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.wednesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.wednesday_close', function(otime) {
    if($scope.Emp.wednesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.wednesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.wednesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.wednesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });
  

  //thrusday_close

  $scope.$watch('Emp.thrusday_open', function(otime) {
    if($scope.Emp.thrusday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.thrusday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.thrusday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.thrusday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.thrusday_close', function(otime) {
    if($scope.Emp.thrusday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.thrusday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.thrusday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.thrusday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

  //friday 

  $scope.$watch('Emp.friday_open', function(otime) {
    if($scope.Emp.friday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.friday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.friday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.friday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.friday_close', function(otime) {
    if($scope.Emp.friday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.friday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.friday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.friday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


  //saturday_close 

  $scope.$watch('Emp.saturday_open', function(otime) {
    if($scope.Emp.saturday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.saturday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.saturday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.saturday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.saturday_close', function(otime) {
    if($scope.Emp.saturday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.saturday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.saturday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.saturday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

    $scope.$watch('Emp.salary', function(newValue,oldValue) {
        if(String(newValue).indexOf(',') != -1)
            $scope.Emp.salary = String(newValue).replace(',', '.');
        else {
            var index_dot,
                arr = String(newValue).split("");
            if (arr.length === 0) return;                  
            if (isNaN(newValue) || ((index_dot = String(newValue).indexOf('.')) != -1 && String(newValue).length - index_dot > 3 )) {
                $scope.Emp.salary = oldValue;
            }
        }
    });

    /* If shop_id & user_id coming from route url then */
    if($routeParams.hasOwnProperty('shop_id') &&  $routeParams.hasOwnProperty('user_id')){
        if( typeof $routeParams.shop_id === 'string'){
            $scope.shop_id = parseInt($routeParams.shop_id,10);
        } 

        if( typeof $routeParams.empid === 'string'){
            $scope.empid = parseInt($routeParams.empid,10);
            loadEmployeeInfo( $scope.empid );
        } 

        if( typeof $routeParams.user_id === 'string'){
            $scope.user_id = parseInt($routeParams.user_id,10);
            EmpService.getEmployeeInfo($scope.user_id).then(function(response){
                console.log(response.data.firstname);
                $scope.Emp.contact_person = response.data.firstname || "";
            });
        }
    }


    loadRolesType();
    function loadRolesType(){
        EmpService.getRoles().then(function(response){
            $scope.Roles = response.data;
        });
    }

    function loadEmployeeInfo(emp_entity_id){
        EmpService.getEmployeeInfo(emp_entity_id).then(function(response){
            if(response.status === 200 && (response.data !== "" || response.data === undefined)){
                $scope.Emp = response.data; 
                $scope.role_id = response.data.role_id;
            }
        });
    }

    $scope.selectRole = function() {
        $scope.Emp.role_id = $scope.role_id;
    };

    $scope.submit = function(){
        EmpService.updateEmployee($scope.Emp).then(function(response){             
            if(response.status === 200){
              // window.history.go(-1);
                alert('Successfully submitted');
            }else{
                alert('Provide input and try it again.');
            }
        });
    }  
});












myshop.controller('EmployeeAddCtrl',function($scope,$log,$routeParams,EmpService) {
    $scope.Emp = {};
    $scope.Roles = {};
    $scope.Emp.role_id = "";

     /* If shop_id & user_id coming from route url then */
    if($routeParams.hasOwnProperty('shop_id') &&  $routeParams.hasOwnProperty('user_id')){
        if( typeof $routeParams.user_id === 'string'){
             $scope.user_id = parseInt($routeParams.user_id,10);

             EmpService.getEmployeeInfo($scope.user_id).then(function(response){
                $scope.Emp.contact_person = response.data.firstname || "";
             });
        }
        if( typeof $routeParams.shop_id === 'string'){
            $scope.shop_id = parseInt($routeParams.shop_id,10);
        } 
    }

    loadRolesType();
    function loadRolesType(){
        EmpService.getRoles().then(function(response){
            $scope.Roles = response.data;
        });
    }

    /* For SUNDAY */
      $scope.$watch('Emp.sunday_open', function(otime) {
        if($scope.Emp.sunday_open !== undefined){
            var opening_time =  String(otime);
            var t1 = opening_time.split(":");
            var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
            if($scope.Emp.sunday_close !== undefined){
                // close time is not empty then check
                var closing_time = String($scope.Emp.sunday_close);
                var t2 = closing_time.split(":");
                var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
                if(cminutes < ominutes) {
                    $scope.Emp.sunday_close = "";
                     alert('Sorry ! Invalid working hours.');
                }
            }
        }
      });

   $scope.$watch('Emp.sunday_close', function(otime) {
    if($scope.Emp.sunday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.sunday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.sunday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.sunday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


/* For MONDAY  */
  $scope.$watch('Emp.monday_open', function(otime) {
    if($scope.Emp.monday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.monday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.monday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.monday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.monday_close', function(otime) {
    if($scope.Emp.monday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.monday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.monday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.monday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });



/* For TUESDAY  */
  $scope.$watch('Emp.tuesday_open', function(otime) {
    if($scope.Emp.tuesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.tuesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.tuesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.tuesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.tuesday_close', function(otime) {
    if($scope.Emp.tuesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.tuesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.tuesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.tuesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });



/* For wednesday  */
  $scope.$watch('Emp.wednesday_open', function(otime) {
    if($scope.Emp.wednesday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.wednesday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.wednesday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.wednesday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.wednesday_close', function(otime) {
    if($scope.Emp.wednesday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.wednesday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.wednesday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.wednesday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });
  

  //thrusday_close

  $scope.$watch('Emp.thrusday_open', function(otime) {
    if($scope.Emp.thrusday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.thrusday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.thrusday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.thrusday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.thrusday_close', function(otime) {
    if($scope.Emp.thrusday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.thrusday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.thrusday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.thrusday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

  //friday 

  $scope.$watch('Emp.friday_open', function(otime) {
    if($scope.Emp.friday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.friday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.friday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.friday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.friday_close', function(otime) {
    if($scope.Emp.friday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.friday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.friday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.friday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });


  //saturday_close 

  $scope.$watch('Emp.saturday_open', function(otime) {
    if($scope.Emp.saturday_open !== undefined){
        var opening_time =  String(otime);
        var t1 = opening_time.split(":");
        var ominutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.saturday_close !== undefined){
            // close time is not empty then check
            var closing_time = String($scope.Emp.saturday_close);
            var t2 = closing_time.split(":");
            var cminutes = parseInt(t2[0] * 60) + parseInt(t2[1]);
            if(cminutes < ominutes) {
                $scope.Emp.saturday_close = "";
                 alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

   $scope.$watch('Emp.saturday_close', function(otime) {
    if($scope.Emp.saturday_close !== undefined){
        var closing_time =  String(otime);var t1 = closing_time.split(":");
        var cminutes = parseInt(t1[0] * 60) + parseInt(t1[1]);
        if($scope.Emp.saturday_open !== undefined){
            // close time is not empty then check
            var opening_time = String($scope.Emp.saturday_open);
            var t2 = opening_time.split(":");
            var ominutes = parseInt(t2[0] * 60) + parseInt(t2[1]);

            if(cminutes < ominutes) {
                $scope.Emp.saturday_close = "";
                alert('Sorry ! Invalid working hours.');
            }
        }
    }
  });

    $scope.$watch('Emp.salary', function(newValue,oldValue) {
        if(String(newValue).indexOf(',') != -1)
            $scope.Emp.salary = String(newValue).replace(',', '.');
        else {
            var index_dot,
                arr = String(newValue).split("");
            if (arr.length === 0) return;                  
            if (isNaN(newValue) || ((index_dot = String(newValue).indexOf('.')) != -1 && String(newValue).length - index_dot > 3 )) {
                $scope.Emp.salary = oldValue;
            }
        }
    });

  $scope.selectRole = function() {
    $scope.Emp.role_id = $scope.role_id;
  };

$scope.submit = function(){

    if($scope.Emp.role_id === undefined || $scope.Emp.role_id === "" || $scope.Emp.role_id === null){

         alert('Please select role type and submit again.');

    }else{

        $scope.Emp.shop_id = $scope.shop_id;
        EmpService.saveEmployee($scope.Emp).then(function(response){
            $log.debug( response );
            if(response.status === 200){
                window.history.go(-1);
            }else{
              alert('Provide input and try it again.');
            }
        });

    }

   
}

});


//////////////////////////////////////////////////////////////

/*
 * DELIVERY MANAGEMENT
 */

myshop.controller('DeliveryZoneCtrl',function($scope) {

});


myshop.controller('DeliveryChargeCtrl', function($routeParams,$scope,$log,DELIVERY_ZONE) {
    $scope.DeliveryCharge = {};
    $scope.DeliveryCharge.shop_id = null;

    /* If shop_id & user_id coming from route url then */
    if($routeParams.hasOwnProperty('shop_id') &&  $routeParams.hasOwnProperty('user_id')){
        if( typeof $routeParams.user_id === 'string'){
             $scope.user_id = parseInt($routeParams.user_id,10);
        }

        if( typeof $routeParams.shop_id === 'string'){
            $scope.DeliveryCharge.shop_id = parseInt($routeParams.shop_id,10);
        }  
    }

    $scope.$watch('DeliveryCharge.fixedtime_delivery_charge', function(newValue,oldValue) {
        if(String(newValue).indexOf(',') != -1)
            $scope.DeliveryCharge.fixedtime_delivery_charge = String(newValue).replace(',', '.');
        else {
            var index_dot,arr = String(newValue).split("");

            if (arr.length === 0) return;     
            if (isNaN(newValue) || ((index_dot = String(newValue).indexOf('.')) != -1 && String(newValue).length - index_dot > 3 )) {
                $scope.DeliveryCharge.fixedtime_delivery_charge = oldValue;
            }
        }
    });

    $scope.$watch('DeliveryCharge.regular_delivery_charge', function(newValue,oldValue) {
        if(String(newValue).indexOf(',') != -1)
            $scope.DeliveryCharge.regular_delivery_charge = String(newValue).replace(',', '.');
        else {
            var index_dot,
                arr = String(newValue).split("");

            if (arr.length === 0) return;                  
            if (isNaN(newValue) || ((index_dot = String(newValue).indexOf('.')) != -1 && String(newValue).length - index_dot > 3 )) {
                $scope.DeliveryCharge.regular_delivery_charge = oldValue;
            }
        }
    });

    $scope.$watch('DeliveryCharge.midnight_delivery_charge', function(newValue,oldValue) {
        if(String(newValue).indexOf(',') != -1)
            $scope.DeliveryCharge.midnight_delivery_charge = String(newValue).replace(',', '.');
        else {
            var index_dot,
                arr = String(newValue).split("");

            if (arr.length === 0) return;                
            if (isNaN(newValue) || ((index_dot = String(newValue).indexOf('.')) != -1 && String(newValue).length - index_dot > 3 )) {
                $scope.DeliveryCharge.midnight_delivery_charge = oldValue;
            }
        }
    });

    /****** When SUMIT BUTTON PRESSED TO SAVE DELIVERY CHARGE *****/
    $scope.submit = function(){
        if($scope.DeliveryCharge.shop_id !== null){
            console.log($scope.DeliveryCharge);
            /* To check all fields are undefined or null then error message*/
            if(($scope.DeliveryCharge.midnight_delivery_charge === undefined && $scope.DeliveryCharge.regular_delivery_charge === undefined && $scope.DeliveryCharge.fixedtime_delivery_charge === undefined) ||($scope.DeliveryCharge.midnight_delivery_charge === null && $scope.DeliveryCharge.regular_delivery_charge === null && $scope.DeliveryCharge.fixedtime_delivery_charge === null) ){
                alert('Provide input and try it again.');
            }else{

                angular.forEach($scope.DeliveryCharge, function(value, key) {
                    if($scope.DeliveryCharge[key] === undefined){
                        $scope.DeliveryCharge[key] = "";
                    }
                });
               
                /* SEND HERE TO DATABASE */
                DELIVERY_ZONE.save_delivery_charge($scope.DeliveryCharge).then(function(response){
                    console.log(response);
                    if(response.data.statusCode === 200){
                        alert(response.data.statusText);
                    }else{
                        alert(response.data.statusText);
                    }
                });
            }  
        }
    }
});



myshop.controller('DZoneParentCtrl',function($scope) {
});


 /*
  * To store pincode from tags 
  */
myshop.controller('ZonePincodeCtrl',function($routeParams,$scope,$log,$http,ROOT,PincodeService) {
    
    $scope.tags = [];
    var pincodes = [];
    $scope.pincode_list = "";
    $scope.shop_id = null;


    $scope.show_pincode = false;
    $scope.show_pincode_text = "Show Pincode List";
    $scope.show_class = "glyphicon glyphicon-eye-open";


   /* If shop_id & user_id coming from route url then */
    if($routeParams.hasOwnProperty('shop_id') &&  $routeParams.hasOwnProperty('user_id')){
        if( typeof $routeParams.user_id === 'string'){
             $scope.user_id = parseInt($routeParams.user_id,10);
        }

        if( typeof $routeParams.shop_id === 'string'){
            $scope.shop_id = parseInt($routeParams.shop_id,10);
        }  
    }

    // if shop id is not null then list else , no need to call function.

    if($scope.shop_id !== null && $scope.user_id !== null){
        load_pincode_list($scope.shop_id);
    }
    
    function load_pincode_list(shop_id){
        PincodeService.getShopPincode(shop_id).then(function(response){
            $log.debug(response);
            $scope.pincode_list = response.data.pincode_list;

        });
    }

    $scope.show_pincode_list = function(){
        $scope.show_pincode = !$scope.show_pincode;
        $scope.show_pincode_text = "Show Pincode List";
        $scope.show_class = "glyphicon glyphicon-eye-open";

        if($scope.show_pincode){
           $scope.show_pincode_text = "Hide Pincode List";
           $scope.show_class = "glyphicon glyphicon-eye-close";
        }
    }


  $scope.submitPincode = function() {
        if($scope.shop_id !== null && $scope.user_id !== null){
            /* To extract pincode from tags*/
            for (var prop in $scope.tags) {
              if( $scope.tags.hasOwnProperty( prop )  && ( $scope.tags[prop].text !== undefined || $scope.tags[prop].text !== null)) {
                    var element =  $scope.tags[prop].text;
                    var index = pincodes.indexOf(element);

                    // if element is not found in pincode array , then add else ignore.
                    if(index == -1){
                       pincodes.push(element);
                    }
              } 
            }

            // To log all pincode input.
            $log.debug( pincodes );

            if(pincodes.length){
                $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
                
                /***** DATA TO BE SENT TO THE SERVER SIDE TO STORE PINCODE DATA  *******/
                
                var pincodes_data = {
                  pincode : pincodes,
                  shop_id: $scope.shop_id,
                  vendor_id: $scope.user_id
                }; 

                $http.post(ROOT.DOMAIN+'/app/module/myshop/dzone/pincode.php', pincodes_data)
                  .success(function(data, status, headers, config){
                   // console.log(status + ' - ' + data);
                    pincodes = []; 
                    if(status === 200){

                      load_pincode_list($scope.shop_id);// 
                      alert('Successfully submitted');

                     // $scope.$emit('messageEvent', ['success','Successfully submitted','Success ']); 
                    }  
                  })
                  .error(function(data, status, headers, config){
                    alert('Error!  try it again');
                  //  $scope.$emit('messageEvent', ['danger','Change a few things up and try it again.','Error! ']); 
                   // $log.debug( status );
                   // $log.debug( 'error' );
                  });
                }else{
                   alert('Enter pin codes and try it again.');
                  // $scope.$emit('messageEvent', ['warning','Enter pin codes and try it again.','Warning! ']); 
                }

            }
        }
});



//http://varun.floshowers.com:8882/vendorpanel/media
myshop.controller('DeliveryCtrl', function($routeParams,$scope,$log,$rootScope,ROOT) {
    /* If shop_id & user_id coming from route url then */
    if($routeParams.hasOwnProperty('shop_id') &&  $routeParams.hasOwnProperty('user_id')){
        if( typeof $routeParams.user_id === 'string'){
             $scope.user_id = parseInt($routeParams.user_id,10);
        }

        if( typeof $routeParams.shop_id === 'string'){
            $scope.shop_id = parseInt($routeParams.shop_id,10);
        }  
    }
    $scope.image_url =  ROOT.IMAGES;
});

myshop.controller('MainController',function($scope,$log,$rootScope,ROOT){
  $scope.image_url = ROOT.IMAGES;
  $scope.js_url =  ROOT.JS;
  $scope.css_url = ROOT.CSS;
})