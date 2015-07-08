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

    .shadow {
  -moz-box-shadow:    0px #ccc;
  -webkit-box-shadow: 0px #ccc;
  box-shadow:         0px #ccc;
}
</style>


<div class="container">
  <!--   <br><br><br>

    <div class="message div-center">
        <alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)"><strong>{{alert.msgtag}}</strong>{{alert.msg}}</alert>
    </div>
 -->
    <div class="col-xs-6 col-sm-4 div-center form-item">
        <div class="panel panel-default">
            <div class="panel-heading text-center"><strong class="headingname">Employee Information</strong></div>
            <div class="panel-body">

                <form class="form-horizontal" role="form" name="new_emp_form" ng-submit="submit()">
<!-- 
                    <div ng-hide="true" class="form-group">

                        <div class="col-md-6">
                            <input numbers-only ng-model="Emp.user_id" id="new_emp_info_1" class="form-control" type="text" placeholder="User ID">
                        </div>   
                    </div>
 -->
                    <div class="form-group">

                        <div class="col-md-6">
                            <label class="control-label" for="ne_1" >First Name</label>
                            <input text-only id="ne_1" ng-model="Emp.firstname" type="text" class="form-control" name="firstname"/>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="ne_2" >Last Name</label>
                            <input text-only id="ne_2" ng-model="Emp.lastname"  type="text" class="form-control" name="lastname"/>
                        </div>
                    </div> 
                    <div class="form-group">

                        <div class="col-md-12">
                            <label class="control-label" for="ne_3" >Address</label>
                            <input address-only id="ne_3" ng-model="Emp.address" type="text" class="form-control" name="address"/>
                        </div>                                   
                    </div> 


                    <div class="form-group">

                        <div class="col-md-6">
                            <label class="control-label" for="ne_4" >City</label>
                            <input text-only id="ne_4" ng-model="Emp.city" type="text" class="form-control" name="city"/>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="ne_5" >Pincode</label>
                            <input numbers-only id="ne_5" minlength="6" maxlength="6" ng-model="Emp.pincode" type="text" class="form-control" name="pincode"/>
                        </div>
                    </div> 

                    <div class="form-group">

                        <div class="col-md-6">
                            <label class="control-label" for="ne_6" >Phone Number</label>
                            <input phone-number id="ne_6" ng-model="Emp.phone_number"  type="text" class="form-control" name="contact1"/>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="ne_7" >Alternate Phone Number</label>
                            <input phone-number id="ne_7" ng-model="Emp.alt_phone_number" type="text" class="form-control" name="contact2" />
                        </div>
                    </div> 


                            <div class="form-group">
                                  
                                    <div class="col-md-6" ng-class="{ 'has-error': new_emp_form.email.$invalid && new_emp_form.email.$dirty}">
                                        <label class="control-label" for="ne_8" >Email</label>
                                        <input id="ne_8" ng-model="Emp.email" type="email" class="form-control" name="email"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label" for="ne_9" >Contact Person</label>
                                        <input readonly stext-only id="ne_9" ng-model="Emp.contact_person" type="text" class="form-control shadow" name="contact_person" />
                                    </div>
                            </div> 

                             <div class="form-group">
                                  
                                    <div class="col-md-6">
                                        <label class="control-label" for="ne_10" >Role</label>
                                           <select class="form-control" id="ne_10"
                                                ng-change="selectRole()"
                                                ng-model="role_id"
                                                ng-options="role.role_id as role.role_name for role in Roles | orderBy:'role_name'">
        
                                            </select>             
                                        </div>

                                    <div class="col-md-6">
                                        <label class="control-label" for="ne_11" >Salary( In INR )</label>
                                        <input valid-amount id="ne_11" ng-model="Emp.salary" type="text" class="form-control" name="salary"/>
                 
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
                                <td><strong>In</strong></td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.sunday_open" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input  ng-model="Emp.monday_open" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>


                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.tuesday_open" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.wednesday_open" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.thrusday_open" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.friday_open" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.saturday_open" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                            </tr>

                            <tr class="danger">
                                <td><strong>Out</strong></td>

                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.sunday_close" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.monday_close"  type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>


                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.tuesday_close" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.wednesday_close" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.thrusday_close" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.friday_close" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                        <input ng-model="Emp.saturday_close" type="text" class="form-control" placeholder="00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>

                                </td>
                            </tr>
                        </table>
                    </div>

                   <!--  <div class="form-group">
                        <center>
                            <div style="width:40%;">
                                <label style="float:left;" class="control-label" for="ne_x" >Salary ( In INR) </label>
                                          </div>
                        </center>
                    </div>  -->

                    <div class="form-group">
                        <center>
                            <div style="width:40%;">
                                <input type="submit" name="newemp_submit" value="Submit" class="btn btn-default buttons">
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


    $(document).ready(function(){
        // ("#ne_9")
    });
</script>