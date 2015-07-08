
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
                        <form class="form-horizontal" role="form" name="new_shop" ng-submit="shop_submit()">
                                
                                
                                 <div class="form-group">
                                  
                                    <div class="col-md-6">
                                        <label class="control-label" for="ns_1" >Shop Name</label>
                                        <input text-only id="ns_1" ng-model="Shop.shop_name" type="text" class="form-control" name="shop_name"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label" for="ns_2" >Website</label>
                                        <input ng-model="Shop.website_url" id="ns_2" type="text" class="form-control" name="website_url"/>
                                    </div>
                                 </div> 



                                 <div class="form-group">
                                   
                                    <div class="col-md-12">
                                         <label class="control-label" for="ns_3" >Address</label>
                                        <input address-only ng-model="Shop.address" id="ns_3" type="text" class="form-control" name="address"/>
                                    </div>                                   
                                 </div> 
                                
                                
                               <div class="form-group">
                                  
                                    <div class="col-md-6">
                                        <label class="control-label" for="ns_4" >City</label>
                                        <input text-only ng-model="Shop.city" id="ns_4" type="text" class="form-control" name="city"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label" for="ns_5" >Pincode</label>
                                        <input numbers-only minlength="6" id="ns_5" maxlength="6" ng-model="Shop.pincode" type="text" class="form-control" name="pincode"/>
                                    </div>
                                 </div> 
                                
                                <div class="form-group">
                                  
                                    <div class="col-md-6">
                                        <label class="control-label" for="ns_6" >Phone Number</label>
                                        <input phone-number ng-model="Shop.phone_number" id="ns_6"  type="text" class="form-control" name="phone_number"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label" for="ns_7" >Alternate Phone Number</label>
                                        <input ng-model="Shop.alt_phone_number" id="ns_7" type="text" class="form-control" name="alt_phone_number"/>
                                    </div>
                                 </div> 
                                
                                 <div class="form-group">
                                  
                                    <div class="col-md-6" ng-class="{ 'has-error': new_shop.email.$invalid && new_shop.email.$dirty}">
                                        <label class="control-label" for="ns_8" >Email</label>
                                        <input ng-model="Shop.email" type="email" id="ns_8" class="form-control" name="email" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label" for="ns_9" >Contact Person</label>
                                        <input readonly stext-only ng-model="Shop.contact_person" id="ns_9" type="text" class="form-control" name="contact_person" />
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
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.sunday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
                                                
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input  ng-model="Shop.monday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    

                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.tuesday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.wednesday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.thrusday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.friday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.saturday_open" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                        </tr>

                                        <tr class="danger">
                                            <td><strong>Closing</strong></td>
    
                                                                                       <td>
    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.sunday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
                                                
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.monday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    

                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.tuesday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.wednesday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.thrusday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.friday_close" type="text" class="form-control" placeholder="00:00">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
    
                                            </td>
                                            <td>
<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
    <input ng-model="Shop.saturday_close" type="text" class="form-control" placeholder="00:00">
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
                                        <input ng-model="Shop.home_delivery" type="checkbox"/> <strong>Home Delivery</strong>
                                    </center>
                                </div>  

                                 <div class="form-group">

                                <center>
                                    <div style="width:40%;">
                                        <input type="submit" name="new_shop_submit" value="Submit" class="btn btn-default buttons">
                                    </div>
                                    </center>
                                </div>  
                            </form>     
                        </div>
                    </div>
                </div>

</div>

<script type="text/javascript">
    $('.clockpicker').clockpicker();
</script>
