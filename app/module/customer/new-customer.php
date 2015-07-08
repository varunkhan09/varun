
<?php
include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";

if(isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])){
	include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

	$shop_id = null; 
    $user_id = (int) $_SESSION['loggedin']['user_id'];
		if(isset($_SESSION['loggedin']['user']['shop_id'])){
			$shop_id = (int) $_SESSION['loggedin']['user']['shop_id'];
		}
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

     input.btn.btn-primary {width: 125px;background: #009ACD;}

</style>

<div class="col-xs-6 col-sm-4 div-center form-item">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
            	<strong class="headingname">New Customer Information</strong>
            </div>
            <div class="panel-body">
            	<form class="form-horizontal" id="new_customer_form" role="form">
            	<input type="hidden" name="shop_id" value="<?php echo $shop_id;  ?>" />
            	<input type="hidden" name="user_id" value="<?php echo $user_id;  ?>" />


				<div class="form-group">
			        <div class="col-md-6">
			            <label class="control-label" for="firstname" >First Name</label>
			            <input id="firstname" value=""  type="text" class="form-control" name="firstname" onkeypress="return textonly(event);"/>
			        </div>
			        <div class="col-md-6">
			            <label class="control-label" for="lastname" >Last Name</label>
			            <input id="lastname" value=""  type="text" class="form-control" name="lastname" onkeypress="return textonly(event);"/>
			        </div>
			    </div> 

			    <div class="form-group">
			        <div class="col-md-6">
			            <label class="control-label" for="email" >Email</label>
			            <input id="email"  value="" type="email" class="form-control" name="email" onblur="return emailText(this);"/>
			        </div>
			        <div class="col-md-6">
			            <label class="control-label" for="datepicker" >Date of Birth</label>
			          	<input id="datepicker" value=""  type="text" class="form-control" name="dob"/>
			        </div>
			    </div> 

			    <div class="form-group">
			       
			        <div class="col-md-12">
			         <label for="address" class="control-label">Address</label>
			            <input type="text" value=""  class="form-control" id="address" name="address">
			        </div>
			    </div>

			     <div class="form-group">
			        <div class="col-md-6">
			            <label class="control-label" for="city" >City</label>
			            <input id="city" value=""  type="text" class="form-control" name="city" onkeypress="return textonly(event);"/>
			        </div>
			        <div class="col-md-6">
			            <label class="control-label" for="state" >State</label>
			            <input id="state" value=""  type="text" class="form-control" name="state" onkeypress="return textonly(event);"/>
			        </div>
			    </div> 

			     <div class="form-group">
			        <div class="col-md-6">
			            <label class="control-label" for="country" >Country</label>
			            <input id="country"  value=""  type="text" class="form-control" name="country" onkeypress="return textonly(event);"/>
			        </div>
			        <div class="col-md-6">
			            <label class="control-label" for="pincode" >Pincode</label>
			            <input id="pincode" value=""  type="text" class="form-control" name="pincode" maxlength="6" onkeypress="return numberonly(event);"/>
			        </div>
			    </div> 

			     <div class="form-group">                           
			        <div class="col-md-6">
			           	<label class="control-label" for="gender" >Gender</label>
			           	<select class="form-control" id="gender" name="gender">
			           		<option selected value="">--Select--</option>
			           		<option value="Male">Male</option>
			           		<option value="Female">Female</option>
			           		
			           	</select>           		
			        </div>

			        <div class="col-md-6">
			            <label class="control-label" for="telephone" >Telephone</label>
			            <input id="telephone" value="" type="text" class="form-control" name="telephone" onkeypress="return phoneText(event);"/>
			        </div>
			    </div> 
			   
			    <br>
			    <div class="form-group">
	                <div class="col-md-12" id="cust_submit" >
	                    <input type="submit" value="Submit" class="btn btn-primary">
	                </div>
	            </div>    
	            
			</form>
            </div>
        </div>

         <!-- confirmation dialog box -->
        <div id="dialog-confirm">

        </div>
</div>

</body>
</html>

<?php 

	} else{
		$referrer = $_SERVER['HTTP_REFERER'];
		if ( !preg_match($base_path."/app/admin/user/login.php",$referrer)) {
			header('Location:'.$base_path);
		} 
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


	function phoneText(e){
		var code;
		if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		var character = String.fromCharCode(code);
		 	var AllowRegex  = /^[\b+0-9\s-]$/;
		    if (AllowRegex.test(character)) return true;     
		    return false; 
	}

	function numberonly(e){
		var code;
		if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		var character = String.fromCharCode(code);
		 	var AllowRegex  = /^\d+$/ ; // /^[\b0-9\s-]$/;
		    if (AllowRegex.test(character)) return true;     
		    return false; 
	}

	function emailText(_this){
		//var id = _this.id;
		var value = _this.value;
		var status =  /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test( value );
		if(status === false){
			document.getElementById('email').value='';
			return status;
		}
		return status;
	}


	  $(document).ready(function(){
	  	$( "#datepicker" ).datepicker({
	  		autoclose: true
	  	});

	  	$("#cust_submit input[type=submit]").click(function(e){
            e.preventDefault();
            var str = $( "#new_customer_form" ).serialize();
            $.ajax({
                url: "<?php echo $base_path?>/app/module/customer/customer_action.php",
                type: "POST",
                data: { ssd: "save_customer", data: str },
                dataType: "JSON",
                success: function (response) {
                  	console.log(response);
                    if(response.statusCode == 200){
                    	$("#dialog-confirm").html(response.statusText);
					    $("#dialog-confirm").dialog({
					        resizable: false,
					        modal: true,
					        title: "<?php echo $base_path?>"+ ' Says...',
					        height: 175,
					        width: 400,
					        buttons: {
					            "OK": function () {
					                $(this).dialog('close');
					                window.history.go(-1);

					            },
					            "Cancel": function () {
					                $(this).dialog('close');
					    
					            }
					        }
					    });
                        
                    }else if(response.statusCode == 404){
                        $("#dialog-confirm").html(response.statusText);
					    $("#dialog-confirm").dialog({
					        resizable: false,
					        modal: true,
					        title: "<?php echo $base_path?>"+ ' Says...',
					        height: 175,
					        width: 400,
					        buttons: {
					            "OK": function () {
					                $(this).dialog('close');
					            
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
	  });
</script>

 