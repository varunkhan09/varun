<?php 
session_start();
?>
  <style>
          .form-group {
                margin-bottom: 5px;
            }
            .form-horizontal .control-label {
                padding-top: 1px;
                margin-bottom: 0;
                text-align: right;
            }


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
           
                
                <div class="col-xs-6 col-sm-4 div-center form-item">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center"><strong class="headingname">Shop Information</strong></div>
                        <div class="panel-body">

                        <form class="form-horizontal" role="form" name="edit_shop_form">
                                
                                
                                 <div class="form-group">
                                  
                                    <div class="col-md-6">
                                        <label class="control-label" for="si_1" >Shop Name</label>
                                     
                                        <input id="si_1" ng-readonly="readonlyflag" text-only ng-model="Shop.shop_name" type="text" class="form-control" name="shop_name" placeholder="Name" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label" for="si_2" >Website</label>
                                        <input ng-readonly="readonlyflag" id="si_2" ng-model="Shop.website_url" type="text" class="form-control" name="website_url" placeholder="Website url" />
                                    </div>
                                 </div> 
                                 <div class="form-group">
                                   
                                    <div class="col-md-12">
                                         <label class="control-label" for="si_3" >Address</label>
                                        <input ng-readonly="readonlyflag" id="si_3" address-only ng-model="Shop.address" type="text" class="form-control" name="address"/>
                                    </div>                                   
                                 </div> 
                                
                                
                               <div class="form-group">
                                  
                                    <div class="col-md-6">
                                         <label class="control-label" for="si_4" >City</label>
                                        <input ng-readonly="readonlyflag" id="si_4" text-only ng-model="Shop.city" type="text" class="form-control" name="city"/>
                                    </div>
                                    <div class="col-md-6">
                                         <label class="control-label" for="si_5" >Pincode</label>
                                        <input ng-readonly="readonlyflag" id="si_6" numbers-only minlength="6" maxlength="6" ng-model="Shop.pincode" type="text" class="form-control" name="pincode"/>
                                    </div>
                                 </div> 
                                
                                <div class="form-group">
                                  
                                    <div class="col-md-6">
                                         <label class="control-label" for="si_7" >Phone Number</label>
                                        <input ng-readonly="readonlyflag" id="si_7" phone-number ng-model="Shop.phone_number"  type="text" class="form-control" name="phone_number"/>
                                    </div>
                                    <div class="col-md-6">
                                         <label class="control-label" for="si_8" >Alternate Phone Number</label>
                                        <input ng-readonly="readonlyflag" id="si_8" ng-model="Shop.alt_phone_number" type="text" class="form-control" name="alt_phone_number"/>
                                    </div>
                                 </div> 
                                
                                 <div class="form-group">
                                  
                                    <div class="col-md-6" ng-class="{ 'has-error': edit_shop_form.email.$invalid && edit_shop_form.email.$dirty}">
                                         <label class="control-label" for="si_9" >Email</label>
                                        <input ng-readonly="readonlyflag" id="si_9" ng-model="Shop.email" type="email" class="form-control" name="email"/>
                                    </div>
                                    <div class="col-md-6">
                                         <label class="control-label" for="si_10" >Contact Person</label>
                                        <input readonly id="si_10" stext-only ng-model="Shop.contact_person" type="text" class="form-control" name="contact_person"/>
                                    </div>
                                 </div> 
                                


                                <div class="form-group">
                                    <p style="background:#999;color:#fff;font-weight: bold;text-align: center;" class="well well-sm">Working Time</p>

                                    <table style="margin-left:0%;width:96%;text-align:left;" class="table table-responsive table-stripped" data-toggle="table">
                                        <tr>
                                            <th>Time</th>   
                                            <th style="text-align: center;">Sun</th>
                                            <th style="text-align: center;">Mon</th>
                                            <th style="text-align: center;">Tue</th>
                                            <th style="text-align: center;">Wed</th>
                                            <th style="text-align: center;">Thu</th>
                                            <th style="text-align: center;">Fri</th>
                                            <th style="text-align: center;">Sat</th>
                                        </tr>

                                        <tr class="info">
                                            <td><strong>Opening</strong></td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag"  ng-model="Shop.sunday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
                                                
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag"  ng-model="Shop.monday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    

                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.tuesday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.wednesday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.thrusday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.friday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.saturday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                        </tr>

                                        <tr class="danger">
                                            <td><strong>Closing</strong></td>
    
                                                                                       <td>
    <div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.sunday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
                                                
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.monday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    

                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.tuesday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.wednesday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.thrusday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.friday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-readonly="readonlyflag" ng-model="Shop.saturday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                        </tr>
                                    </table>
                                </div>




                                <div class="form-group">
                                    <center>
                                        <input ng-disabled="readonlyflag" value="{{Shop.home_delivery}}" ng-model="Shop.home_delivery" type="checkbox"/> <strong> Home Delivery </strong>
                                    </center>
                                </div>  

                                 <div class="form-group">
                                <center>
                                    <?php 

                                        if(isset($_SESSION['loggedin'])){
                                            $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? (int)$_SESSION['loggedin']['user_id'] : null;
                                            if(isset($_SESSION['loggedin']['user'])){
                                                $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? (int) $_SESSION['loggedin']['user']['shop_id'] : null;
                                            }   
                                        }
                                    ?>
                                    <!-- /user/:user_id/shop/:shop_id/update -->

                                     <a class="btn btn-default buttons" href="#/user/<?php echo $user_id; ?>/shop/<?php echo $shop_id; ?>/update">Edit</a>
                                 

                                  <!--   

                                    <div ng-if="editable" style="width:40%;">
                                        <input type="button" ng-click="make_editable()" name="edit_shop_form_submit" value="Edit" class="btn btn-default buttons">
                                    </div>

                                    <div ng-if="update" style="width:40%;">
                                        <input type="button" ng-click="save_shop()" name="edit_shop_form_submit" value="Update" class="btn btn-default buttons">
                                    </div> -->

                                </center>
                                </div>  
                            </form>     
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                 //   $('.clockpicker').clockpicker();
            </script>
