
<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title>Login</title>

        
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
         <link href="<?php echo $base_media_css_url; ?>/bootstrap/css/a/shop.css" rel="stylesheet">

        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="<?php echo $base_media_js_url; ?>/bootstrap/js/jquery-1.10.2.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/bootstrap/js/bootstrap.min.js"></script>

        <style>
            .div-center{width: 50%;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;}
            .form-item {margin-top: 10%;}
            .panel-default>.panel-heading {background: #009ACD;color: #fff;border-radius: 0px;}
            input.btn.btn-primary {width: 100px;background: #009ACD;}
        </style>

    </head>
    <body>

    <?php 

   // if(function_exists('check_login')){
        //check_login();
   // }
   
    //function check_login(){
        // only valid case if both email,user_id 

       if(isset($_SESSION['loggedin']['email']) && isset($_SESSION['loggedin']['user_id'])){
          //header("Location: http://varun.floshowers.com:8882/home.php"); 
            header("Location: http://".$_SERVER['HTTP_HOST']."/home.php"); 
        }else{
            // If not valid then LOGIN PAGE .
            
            ?>
            <div class="container">
                <div class="col-xs-6 col-sm-4 div-center form-item">
                    <div class="panel panel-default">

                        <div class="panel-heading text-center">
                            <strong class="headingname">User Login</strong>
                        </div>

                        <div class="panel-body">
                            <form name="login_form" class="form-horizontal" id="login_user" role="form">

                                <div class="form-group" id="login-fail">
                                    <label class="col-md-4 control-label"></label>
                                    <div class="col-md-5" id="login-error-msg" style="color:#FF0000"></div>   
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="username">Username</label>
                                    <div class="col-md-6">
                                        <input required id="username" name="username" class="form-control" type="text">
                                    </div>   
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="password">Password</label>
                                    <div class="col-md-6">
                                        <input required id="password" name="password" class="form-control" type="password">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-4 control-label"></label>
                                    <div id="user_login_submit" class="col-md-6">
                                        <input type="submit" value="Log In" class="btn btn-primary"style="float: left;">
                                       &nbsp; <a id="login-forget-pwd" href="<?php echo $base_path ?>/pwd-helper.php">Forgot your password?</a>
                                    </div>

                                </div>  
                            </form>  

                        </div>

                    </div>
                </div>
            </div> 

            <?php
        }
   // }
?>


</body>
</html>



<script type="text/javascript">
    $(document).ready(function(){
        $("#user_login_submit input[type=submit]").click(function(e){
            e.preventDefault();
            var str = $( "#login_user" ).serialize();
            $.ajax({
                url: "<?php echo $base_path; ?>/app/admin/user/user_action.php",
                type: "POST",
                data: {
                    ssd: "login_user",
                    data: str
                },
                dataType: "JSON",
                success: function (response) {
                    console.log(response);
                    if(response.statusCode == 200){
                        //  alert(response.statusText);
                        window.location= '<?php echo $base_path; ?>/app/admin/user/loggedin.php';

                    }else if(response.statusCode == 404){
                        // alert(response.statusText);
                         $('#login-error-msg').html(response.statusText)
                    }
                }
            });
        });
    });
</script>
