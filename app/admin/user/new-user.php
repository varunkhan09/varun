<?php
//session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
//include_once $_SERVER['DOCUMENT_ROOT'] . "/app/etc/dbconfig.php";

// if( (!isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])) 
//     || (isset($_SESSION['loggedin']['user_id']) && !isset($_SESSION['loggedin']['email'])) 
//     || (!isset($_SESSION['loggedin']['user_id']) && !isset($_SESSION['loggedin']['email']))){

//     header("Location: $_SERVER['HTTP_HOST']/app/admin/user/login.php");
// }else{
//     include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
// }

if(isset($_SESSION['loggedin']['user']['is_admin'])){

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
            .form-item {margin-top: 10%;}
            input.btn.btn-default {width: 125px;}
            .ok{border: 2px solid #006600;}
            .ohh{border: 2px solid #FF0000;}
            .ui-dialog-content.ui-widget-content{
                font-size: 14px;
            }
        </style>

        <div class="container">
            <div class="col-xs-6 col-sm-4 div-center form-item">
                <div class="panel panel-default">
              		<div class="panel-heading text-center"><strong class="headingname">Create Store Admin Account</strong></div>
                    <div class="panel-body">
                        <form name="admin_user_form" class="form-horizontal" id="admin_user" role="form">

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ac_1">Username</label>
                                <div class="col-md-5">
                                    <input required id="ac_1" name="username" class="form-control" type="text">
                                </div>   
                            </div>

                            <!-- first name -->
                             <div class="form-group">
                                <label class="col-md-4 control-label" for="ac_5">First Name</label>
                                <div class="col-md-5">
                                    <input required id="ac_5" name="firstname" class="form-control" type="text" onkeypress="return textonly(event);">
                                </div>   
                            </div>


                            <!-- last name-->

                             <div class="form-group">
                                <label class="col-md-4 control-label" for="ac_6">Last Name</label>
                                <div class="col-md-5">
                                    <input required id="ac_6" name="lastname" class="form-control" type="text" onkeypress="return textonly(event);">
                                </div>   
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ac_2">Email</label>
                                <div class="col-md-5">
                                    <input required id="ac_2" name="email" class="form-control" type="email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ac_3">Password</label>
                                <div class="col-md-5">
                                    <input required id="ac_3" name="password" class="form-control" type="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ac_4">Confirm Password</label>
                                <div class="col-md-5">
                                    <input required id="ac_4" name="cpassword" class="form-control" type="password">
                                </div>
                            </div>

                             <!-- <div class="form-group">

                                <label class="col-md-4 control-label" for="admin-role-type">Role Type</label>
                                <div class="col-md-5">
                                    <select class="form-control" id="admin-role-type" name="role_id">
                                        <option id='0' value="">--Select--</option>
                                         <?php 
                                            // $query =  "SELECT * FROM pos_user_roles";
                                            // $result =  mysql_query($query);
                                            // if(mysql_num_rows($result)>0 ){
                                            //     while ($row = mysql_fetch_assoc($result)) {
                                            //         echo "<option id='".$row['role_id']."' value='".$row['role_id']."'> {$row['role_name']}</option>";
                                            //     }
                                            // }
                                         ?>

                                    </select> 
                                </div>
                            </div> -->



                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div id="user_submit" class="col-md-5">
                                    <input type="submit" value="Submit" class="btn btn-default buttons"style="float: left;">
                                </div>
                            </div>  
                        </form>     
                    </div>
                </div>
            </div>
            <!-- confirmation dialog box -->
        <div id="dialog-confirm-msg">

        </div>

        </div>

<?php 
}
?>

<script type="text/javascript">

    function textonly(e){
        var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        if (code === 45) return false;
        var character = String.fromCharCode(code);
            var AllowRegex  = /^[\ba-zA-Z\s-]$/;
            if (AllowRegex.test(character)) return true;     
            return false; 
    }

    $(document).ready(function(){
        $("#user_submit input[type=submit]").click(function(e){

            // var role_id = $("#admin-role-type option:selected").val();
            // if(!role_id){
            //     alert('Select role type');
            //     return false;
            // }

           //var fdata =  $( "#admin_user" ).serialize();

       //    console.log(fdata);

            e.preventDefault();
            $.ajax({
                url: "user_action.php",
                type: "POST",
                data: {
                    ssd: "insert_user",
                    data: $( "#admin_user" ).serialize()
                },
                dataType: "JSON",
                success: function (response) {
                    
                    if(response.statusCode == 200){
                    
                        $("#dialog-confirm-msg").html(response.statusText);
                        $("#dialog-confirm-msg").dialog({
                            resizable: false,
                            modal: true,
                            title: "<?php echo $base_path?>"+ ' Says...',
                            height: 175,
                            width: 400,
                            buttons: {
                                "OK": function () {
                                    // close and redirect
                                    $(this).dialog('close');
                                    var str = "<?php echo $base_path; ?>/app/module/myshop/#/user/"+response.user_id+"/shop";
                                    window.location= str;
                                   
                                },
                                "Cancel": function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }else{

                        $("#dialog-confirm-msg").html(response.statusText);
                        $("#dialog-confirm-msg").dialog({
                            resizable: false,
                            modal: true,
                            title: "<?php echo $base_path?>"+ ' Says...',
                            height: 175,
                            width: 400,
                            buttons: {
                                "OK": function () {
                                    // close and reload.
                                    $(this).dialog('close');
                                    window.location.reload();

                                },
                                "Cancel": function () {
                                    $(this).dialog('close');
                                }
                            }
                        });


                    }
                }
            });
        });

         
        // #ac_3 = password
        // #ac_4 = confirmed password

		$('#ac_3, #ac_4').on('keyup', function () {
            // if both fields is not empty

            if($('#ac_3').val() && $('#ac_4').val()){
                // if both fields contain same value.
                if ($('#ac_3').val() == $('#ac_4').val()) {
                        // ohh class represent something wrong and red border color, if it have , remove it
                        if($("#ac_3, #ac_4").hasClass('ohh')) {
                            $("#ac_3, #ac_4").removeClass("ohh");
                        }
                    // $("#ac_3, #ac_4").addClass("ok");
               
                } else{ 

                        if($("#ac_3, #ac_4").hasClass('ok')) {
                            $("#ac_3, #ac_4").removeClass("ok");
                        }    
                     $("#ac_3, #ac_4").addClass("ohh");
                }
            }
		 
            if((    !$('#ac_3').val() && !$('#ac_4').val()  ) || (  $('#ac_3').val() && !$('#ac_4').val()   )){
                $("#ac_3, #ac_4").removeClass("ohh ok");
            }

            if(!$('#ac_3').val() && $('#ac_4').val()){
                $("#ac_3, #ac_4").addClass("ohh");
            }
		});
    });
</script>
