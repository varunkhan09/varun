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
</style>


<div class="container">
    <div class="col-xs-6 col-sm-4 div-center form-item">
        <div style="margin-bottom:0px;" class="panel panel-default">
            <div class="panel-heading text-center"><strong class="headingname">Employee Information</strong></div>
            <div class="panel-body">

            <div class="panel panel-info">
                <!-- <div class="panel-heading">Personal Information</div> -->
                <div class="panel-body" style="padding-top:2px;">
                    <table style="width:50%; float:left;" class="table-responsive">
                        <tr ng-if="employee.username.length > 0 ">
                            <td> <label>Username </label></td> 
                            <td> <span style="margin-left:25px;">{{employee.username}}</span></td>                 
                        </tr>

                        <tr>
                            <td> <label>First Name </label></td> 
                            <td> <span style="margin-left:25px;">{{employee.firstname}}</span></td>                 
                        </tr>

                        <tr>
                            <td> <label>Last Name </label></td> 
                            <td> <span style="margin-left:25px;">{{employee.lastname}}</span></td>                 
                        </tr>
                        <tr>
                            <td> <label>Email Id </label></td> 
                            <td> <span style="margin-left:25px;">{{employee.email}}</span></td>                 
                        </tr>

                         <tr>
                            <td> <label>Role</label></td> 
                            <td> <span style="margin-left:25px;">{{employee.role_name}}</span></td>                 
                        </tr>
                       <!--  
                        <tr ng-if="employee.is_adminpanel == 1 ">
                            <td> <label>Is Admin ? </label></td> 
                            <td> <span style="margin-left:25px;"> Yes </span></td>                 
                        </tr> -->
                    </table>


                    <table style="width:50%; float:right;" class="table-responsive">
                         <tr>
                            <td> <label>Phone number</label></td> 
                            <td> <span style="margin-left:25px;">{{employee.phone_number}}</span></td>                 
                        </tr>

                        <tr>
                            <td> <label>Alternate Phone number</label></td> 
                            <td> <span style="margin-left:25px;">{{employee.alt_phone_number}}</span></td>                 
                        </tr>

                        <tr ng-if="employee.is_adminpanel != 1 ">
                            <td> <label>Contact Person</label></td> 
                            <td> <span style="margin-left:25px;">{{employee.contact_person}}</span></td>                 
                        </tr>

                         <tr ng-if="employee.salary > 0 ">
                            <td> <label>Salary</label></td> 
            <td> <span style="margin-left:25px;">{{employee.salary | currency:"&#8377;"}}</span></td>                 
                        </tr>

                    </table>


                </div>
            </div>

            <div style="margin-bottom:0px;"  class="panel panel-info">
                <div class="panel-heading">Address </div>
                <div class="panel-body" style="padding-top:2px;">
                    <table class="table-responsive">
                        <tr>
                            <td> <label>Address </label></td> 
                            <td> <span style="margin-left:25px;">{{employee.address}}</span></td>                 
                        </tr>

                         <tr>
                            <td> <label>City </label></td> 
                            <td> <span style="margin-left:25px;">{{employee.city}}</span></td>                 
                        </tr>


                         <!-- <tr>
                            <td> <label>State </label></td> 
                            <td> <span style="margin-left:25px;">{{employee.state}}</span></td>                 
                        </tr>
 -->

                         <tr>
                            <td> <label>Pincode </label></td> 
                            <td> <span style="margin-left:25px;">{{employee.pincode}}</span></td>                 
                        </tr>

                    </table>
                </div>
            </div>


             <div style="margin-bottom:0px;"  class="panel panel-info">
                <div class="panel-heading">Working Time </div>
                <div class="panel-body" style="padding-top:2px;">

                <table class="table table-responsive" style="width:100%; margin:0px;">

                <thead>
                    <tr>
                        <th></th>
                         <th> Sunday </th>
                         <th>Monday </th>
                         <th> Tuesday </th>
                         <th>Wednesday </th>
                         <th>Thursday </th>
                         <th> Friday</th>
                         <th> Saturday</th>
                    </tr>
                </thead>

                <tbody style="text-align:left;">
                    <tr>
                        <td><label>In</label></td> 
                        <td>{{employee.sunday_open}} </td>
                        <td> {{employee.monday_open}}</td>
                        <td> {{employee.tuesday_open}}</td>
                        <td> {{employee.wednesday_open}}</td>
                        <td> {{employee.thrusday_open}}</td>
                        <td> {{employee.friday_open}}</td>
                        <td> {{employee.saturday_open}}</td>
                    </tr>

                    <tr>
                         <td><label>Out</label></td> 
                         <td>{{employee.sunday_close}} </td>
                         <td>{{employee.monday_close}} </td>
                         <td> {{employee.tuesday_close}}</td>
                         <td>{{employee.wednesday_close}} </td>
                         <td> {{employee.thrusday_close}}</td>
                         <td>{{employee.friday_close}} </td>
                         <td> {{employee.saturday_close}}</td>
                    </tr>


                </tbody>

                </table>
                       
                </div>
            </div>

<!-- 
                {{employee|json}} -->
            </div>
        </div>
    </div>
</div>

