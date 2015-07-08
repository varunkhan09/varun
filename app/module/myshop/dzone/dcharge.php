<?php 
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT']."/global_variables.php"; 
?>


        <style>
            .div-center{
                width: 80%;
                position: absolute;
                top:0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;              
            }

            .form-item {
                margin-top: 10%;
            }

            input.btn.btn-default {
                width: 125px;
            }
             .message{
                  margin-top:110px;
                  padding:1px 15px;
            }
        </style>

        <div class="container">
      <!--   <br><br><br>

                <div if="showmsg" class="message div-center">
                    <alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)"><strong>{{alert.msgtag}}</strong>{{alert.msg}}</alert>
                </div>
 -->
                <div class="col-xs-6 col-sm-4 div-center form-item">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center"><strong class="headingname">Delivery Charge</strong></div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" ng-submit="submit()">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="dz_dtype_1">Regular Delivery</label>
                                    <div class="col-md-5">
                                        <input valid-amount ng-model="DeliveryCharge.regular_delivery_charge" id="dz_dtype_1" class="form-control" type="text">
                                    </div>   
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="dz_dtype_2">Mid Night Delivery</label>
                                    <div class="col-md-5">
                                        <input valid-amount ng-model="DeliveryCharge.midnight_delivery_charge" id="dz_dtype_2" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="dz_dtype_3">Fix Time Delivery</label>
                                    <div class="col-md-5">
                                        <input valid-amount ng-model="DeliveryCharge.fixedtime_delivery_charge" id="dz_dtype_3" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label"></label>
                                    <div class="col-md-5">
                                        <input type="submit" name="dz_dtype_submit" value="Submit" class="btn btn-default buttons" style="float: left;">
                                    </div>
                                </div>  
                            </form>     
                        </div>
                    </div>
                </div>
            </div>
