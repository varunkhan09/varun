<?php
session_start();
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
        
        <title>Forget Password</title>

        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
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
            		<h2 class="ui-headerTitle" aria-hidden="true">Find Your Account / Password</h2>
            	</div>
            </div>
            <div class="phl ptm" style="margin-bottom: 15px;">

            <div style="margin-left:20px;width:400px;" id="search" >
            	<form id="pwd-search-param" role="form">
            		<div>
            			<label>Enter Your Email or Username <span style="color:red;">*</span></label>
            			<input style="width:100%;" type="text" class="inputtext" id="search-text" name="search_txt">
            		</div>

            		<div style="padding-top:10px;">
            			<label>Enter Your Alternate Email (Optional)</label>
            			<input style="width:100%;" type="email" class="inputtext" id="alternate-email" name="alternate_email">
            		</div>
            	</form>
            	<h5 id="nodata"></h5>
            </div>
            </div>

            <div class="bottom gray-box">
            	<div id="forget-pwd-action" class="rfloat _ohf">
            		<input  class="uiButton ui-btn-confirm" autocomplete="off" value="Submit" type="submit" name="forget_pwd_submit">
            		<a class="uiButton" href="<?php echo $base_path; ?>" role="button">
            			<span class="ui-btn-text">Cancel</span>
            		</a>
            	</div>
            </div>
            </div>

    </body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        $("#forget-pwd-action input[type=submit]").click(function(e){
        	e.preventDefault();
        	var txt =  $('#search-text').val();
        	if(txt== ""|| txt == null || txt == undefined){
        		$('#nodata').html("Please Enter Your Email or Username, It is Required.");
        		$('#nodata').css("color", "#FF0000");
        		return false;
        	}
        
            var search_param = $( "#pwd-search-param" ).serialize();
            $.ajax({
                url: "<?php echo $base_path; ?>/app/admin/user/user_action.php",
                type: "POST",
                data: {
                    ssd: "user_pwd_forget_recovery",
                    data: search_param
                },
                dataType: "JSON",
                success: function (response) {
                    console.log(response);
                    if(response.statusCode === 'NOTFOUND'){
                        $('#nodata').html(response.statusText);
                        $('#nodata').css("color", "#FF0000");
                    }else if(response.statusCode === 'FOUND'){
                    	$('#nodata').html(response.statusText);
                      	$('#nodata').css("color", "#009ACD");
                    }

                }
            });
        });
    });
</script>