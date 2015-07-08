
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title>Forget Password</title>

        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
        <!-- jQuery -->
        <script src="<?php echo $base_media_js_url; ?>/bootstrap/js/jquery-1.10.2.js"></script>
    
		<style type="text/css">
		.mvl {
		margin-top: 20px;
		margin-bottom: 20px;
		}
		.ui-pane {
		-webkit-border-radius: 4px;
		margin-left: auto;
		margin-right: auto;
		}
		.ptm {
		padding-top: 10px;
		}
		.ui-large-pane {
		width: 450px;top:0;bottom: 0;left: 0;right: 0;margin: auto;
		}
		.uiBoxWhite {
		background-color: #fff;
		border: 1px solid #ccc;
		}

		.uiButton{
		background-repeat: no-repeat;
		background-size: auto;
		background-position: 0 -98px;
		background-color: #e9eaed;
		border: 1px solid #999;
		border-bottom-color: #888;
		cursor: pointer;
		display: inline-block;
		font-size: 11px;
		font-weight: bold;
		line-height: 13px;
		padding: 2px 6px;
		text-align: center;
		text-decoration: none;
		vertical-align: top;
		white-space: nowrap;
		-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
		}
		a {
		color: #3b5998;
		cursor: pointer;
		text-decoration: none;
		}
		.ui-pane .inter-header {
		border-color: #ccc;
		padding-bottom: .5em;
		}
		.ui-header-btm-border {
		border-bottom: 1px solid #aaa;
		}

		.ui-header h2 {
		color: #1e2d4c;
		font-size: 16px;
		}

		.mhl {
		margin-left: 20px;
		margin-right: 20px;
		}
		.mts {
		margin-top: 5px;
		}

		.pts {
		padding-top: 5px;
		}

		.bottom {
		-webkit-border-bottom-right-radius: 3px;
		-webkit-border-bottom-left-radius: 3px;
		line-height: 16px;
		padding: 10px 20px 30px;
		}

		.gray-box {
		background-color: #f2f2f2;
		border-top: 1px solid #ccc;
		}

		body, button, input, label, select, td, textarea {
		font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;
		font-size: 11px;
		background: #fff;
		color: #141823;
		line-height: 1.28;
		margin: 0;
		padding: 0;
		text-align: left;
		direction: ltr;
		unicode-bidi: embed;
		}
		._ohf {
		float: right;
		}
		.rfloat {
		float: right;
		}
		.inputtext {
		border: 1px solid #bdc7d8;
		margin: 0;
		padding: 3px;
		-webkit-appearance: none;
		-webkit-border-radius: 0;
		}
		 .form-item {margin-top: 10%;}
		</style>
    </head>
    <body>



		<div class=" form-item mvl ptm ui-pane ui-large-pane uiBoxWhite">
			<div class="ui-header ui-header-btm-border mhl mts ui-headerPage">
				<div class="ui-headerTop">
					<h2 class="ui-headerTitle" aria-hidden="true">Password Reset </h2>
				</div>
			</div>
			<div class="phl ptm" style="margin-bottom: 15px;">

			<div style="margin-left:20px;width:400px;" id="search" >
				<form id="new-pwd-reset" role="form">
					<input type="hidden" id="user_id" name="entity_id" value="<?php echo $user_id;?>"> 
					<div>
						<label>Enter New Password </label>
						<input style="width:100%;" type="text" class="inputtext" id="new-pwd" name="new_password">
					</div>
					<div style="padding-top:10px;">
						<label>Enter Confirm Password </label>
						<input style="width:100%;" type="text" class="inputtext" id="new-conf-pwd" name="new_conf_password">
					</div>
					<div style="padding-top:10px;">
						<label>Enter PIN </label>
						<input style="width:100%;" type="text" class="inputtext" id="pin" name="pin">
					</div>
				</form>
				<h5 id="reset-err-msg"></h5>
			</div>
			</div>

			<div class="bottom gray-box">
				<div id="reset-pwd-action" class="rfloat _ohf">
					<input  class="uiButton ui-btn-confirm" autocomplete="off" value="Submit" type="submit" name="reset_pwd_submit">
					<input  class="uiButton ui-btn-confirm" autocomplete="off" value="Clear" type="button" name="clear_submit">
				</div>
			</div>
		</div>

	</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
    	$("#reset-pwd-action input[type=button]").click(function(e){
    		$('#reset-err-msg').html('');
    		$('input[type=text]').val('');
    	});

        $("#reset-pwd-action input[type=submit]").click(function(e){
        	e.preventDefault();
        	var pwd =  $('#new-pwd').val();
        	var cnfpwd =  $('#new-conf-pwd').val();
        	var pin =  $('#pin').val();
        	var decoded_pin = "<?php echo $decoded_pin ?>";

        	if(pwd !== cnfpwd){
        		$('#reset-err-msg').html("Password mismatch, Please provide correct information.");
        		$('#reset-err-msg').css("color", "#FF0000");
        		return false;
        	}
        	if((pwd === cnfpwd && pin == "") || (pwd === cnfpwd && pin == null) || (pwd === cnfpwd && pin == undefined)) {
        		$('#reset-err-msg').html("Pin is required to reset your password.");
        		$('#reset-err-msg').css("color", "#FF0000");
        		return false;
        	}

        	if(pin !== decoded_pin ) {
        		$('#reset-err-msg').html("Invalid Pin, Try to input correct pin.");
        		$('#reset-err-msg').css("color", "#FF0000");
        		return false;
        	}

        	if(pwd === cnfpwd && pin){
	            var reset_param = $( "#new-pwd-reset" ).serialize();
		      	$.ajax({
	                url: "<?php echo $base_path; ?>/app/admin/user/user_action.php",
	                type: "POST",
	                data: {
	                    ssd: "reset_user_pwd",
	                    data: reset_param
	                },
	                dataType: "JSON",
	                success: function (response) {
	                    if(response.statusCode =="OK"){
	                    	$('#reset-err-msg').html(response.statusText);
                      		$('#reset-err-msg').css("color", "#009ACD");
                      		$('input[type=text]').val('');
                      		$('input[type=hidden]').val('');
	                    }else if(response.statusCode =="ERROR"){
	                    	$('#reset-err-msg').html(response.statusText);
                      		$('#reset-err-msg').css("color", "#FF0000");
	                    }
	                }
	            });
        	}
        });
    });
</script>