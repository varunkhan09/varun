<?php
// DONT DELETE BCZ HERE SESSION IS NOT COMING FROM HEADER FILES.
session_start();
?>

<style>
    .form-item {
        margin-top: 10%;
    }
    .newemp{
        margin-top: 35px;
    }
    .newemp > a{
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
    }           
    .table tr td a{
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }
</style>


<!-- Page Content -->
<div class="container">

<?php 
    $emp_add_href = "javascript:void(0);";
    $emp_edit_href = "javascript:void(0);";
    $emp_view_href = "javascript:void(0);";

    if(isset($_SESSION['loggedin'])){
        $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
        $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
        if(!is_null($user_id) && (!is_null($shop_id))){
            $emp_add_href =  "#/user/$user_id/shop/$shop_id/add-emp";
            $emp_edit_href =  "#/user/$user_id/shop/$shop_id/edit-emp";
            $emp_view_href =  "#/user/$user_id/shop/$shop_id/view-emp";
        }
    }
?>

    <div class="panel panel-default form-item">
        <div class="panel-heading text-center">
            <strong class="headingname">Employee Maintenance</strong>
        </div>
        <div class="panel-body">
            <div class="emp-list">
                <table style="margin-left:0%;width:96%;text-align:left;" class="table table-hover">
                    <thead>
                        <tr>
                           <!--  <th>S.No</th> -->
                            <th>Emp ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>User Role</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                         <tr ng-repeat="employee in employeeList">
                            <!-- <td>{{$index+1 }}</td> -->
                            <td>{{employee.entity_id}}</td>
                            <td>{{ employee.firstname }}</td>
                            <td>{{ employee.lastname }}</td>
                            <td>{{ employee.role_name }}</td>

                            <!-- 7 is the role of Store Owner, that is hardcoded in app/admin/user/user_operation.php -->

                            <td ng-if="employee.role_id == 7" >
                                <a href="javascript:void(0);">Edit</a>
                            </td>

                            <td ng-if="employee.role_id != 7" >
                                <a href="<?php echo $emp_edit_href; ?>/{{employee.entity_id}}">Edit</a>
                            </td>

                            <td >
                                <a href="<?php echo $emp_view_href; ?>/{{employee.entity_id}}">View</a>
                            </td>
                                

                            <td ng-if="employee.role_id == 7" >
                                <a href="javascript:void(0);">Delete</a>
                            </td>

                            <td ng-if="employee.role_id != 7">
                                <a href="javascript:void(0);" ng-click = "empDelete(employee.entity_id)">Delete</a>
                            </td>
                        </tr>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

            <div class="form-group newemp"> 
                <br>
                <a href="<?php echo $emp_add_href; ?>">Add Employee</a>
            </div>  
        </div>
    </div>
</div>
