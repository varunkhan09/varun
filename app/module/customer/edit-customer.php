
<?php
include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";

if(isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])){
	include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";
	
	$shop_id = null; // shop id in which  customer will added....
    $user_id = (int) $_SESSION['loggedin']['user_id'];
    $cust_id = null;
		if(isset($_SESSION['loggedin']['user']['shop_id'])){
			$shop_id = (int) $_SESSION['loggedin']['user']['shop_id'];
		}
		if(isset($_GET['id']) && isset($_GET['sid'])){
			$cust_id = base64_decode($_GET['id']);
			$shop = base64_decode($_GET['sid']);
			$query ="";
			if($shop_id == $shop){
				$query = "SELECT * FROM pos_customers_entity WHERE customer_id = $cust_id AND shop_id = $shop AND is_active = 1 LIMIT 1";
			}
		
			$result = mysql_query($query);
			$row = array();
			if($result){
				$row = mysql_fetch_assoc($result);
			}
			$dob = '';
			if($row['dob'] =="0000-00-00"){
				$dob = "";
			}else{
				$date = $row['dob'];
				$date = DateTime::createFromFormat('Y-m-d', $date);
				$dob = $date->format('m/d/Y');
			}
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

            	<form class="form-horizontal"  id="edit_customer_form" role="form">

	            	<input type="hidden" name="shop_id" value="<?php echo $shop_id;  ?>" />
	            	<input type="hidden" name="user_id" value="<?php echo $user_id;  ?>" />
	            	<input type="hidden" name="customer_id" value="<?php echo $row['customer_id'];?>" />


					<div class="form-group">
				        <div class="col-md-6">
				            <label class="control-label" for="firstname" >First Name</label>
				            <input id="firstname"  type="text" class="form-control" value="<?php echo $row['firstname'];?>" name="firstname" onkeypress="return textonly(event);"/>
				        </div>
				        <div class="col-md-6">
				            <label class="control-label" for="lastname" >Last Name</label>
				            <input id="lastname" type="text" class="form-control" value="<?php echo $row['lastname'];?>" name="lastname" onkeypress="return textonly(event);"/>
				        </div>
				    </div> 

				    <div class="form-group">
				        <div class="col-md-6">
				            <label class="control-label" for="email" >Email</label>
				            <input id="email"  type="email" class="form-control" value="<?php echo $row['email'];?>" name="email" onblur="return emailText(this);"/>
				        </div>
				        <div class="col-md-6">
				            <label class="control-label" for="datepicker" >Date of Birth</label>
				          	<input id="datepicker" type="text" class="form-control" value="<?php echo $dob; ?>" name="dob"/>
				        </div>
				    </div> 

				    <div class="form-group">
				       
				        <div class="col-md-12">
				         <label for="address" class="control-label">Address</label>
				            <input type="text" class="form-control" id="address" value="<?php echo $row['address'];?>" name="address">
				        </div>
				    </div>

				     <div class="form-group">
				        <div class="col-md-6">
				            <label class="control-label" for="city" >City</label>
				            <input id="city"  type="text" class="form-control" value="<?php echo $row['city'];?>" name="city" onkeypress="return textonly(event);"/>
				        </div>
				        <div class="col-md-6">
				            <label class="control-label" for="state" >State</label>
				            <input id="state" type="text" class="form-control"  value="<?php echo $row['state'];?>" name="state" onkeypress="return textonly(event);"/>
				        </div>
				    </div> 

				    <div class="form-group">
				        <div class="col-md-6">
				            <label class="control-label" for="country" >Country</label>
				            <input id="country"  type="text" class="form-control" value="<?php echo $row['country'];?>" name="country" onkeypress="return textonly(event);"/>
				        </div>
				        <div class="col-md-6">
				            <label class="control-label" for="pincode" >Pincode</label>
				            <input id="pincode" type="text" class="form-control" value="<?php echo $row['pincode'];?>" name="pincode" maxlength="6" onkeypress="return numberonly(event);"/>
				        </div>
				    </div> 

				    <div class="form-group">                           
				        <div class="col-md-6">
				           	<label class="control-label" for="gender" >Gender</label>
				           	<select class="form-control" id="gender" name="gender">
				           		<option value="">--Select--</option>
				           		<option value="Male" <?php if($row['gender'] == "Male"){echo "selected";} ?>>Male</option>
				           		<option value="Female" <?php if($row['gender'] == "Female"){echo "selected";} ?>>Female</option>
				           		
				           	</select>           		
				    </div>

			        <div class="col-md-6">
			            <label class="control-label" for="telephone" >Telephone</label>
			            <input id="telephone" type="text" class="form-control" value="<?php echo $row['telephone'];?>"  name="telephone" onkeypress="return phoneText(event);"/>
			        </div>
			    </div> 
			   
			    <br>
			    <div class="form-group">
	                <div class="col-md-12" id="edit_cust_submit" >
	                    <input type="submit" value="Submit" class="btn btn-primary">
	                </div>
	            </div>    
	            
			</form>
            </div>
             <!-- confirmation dialog box -->
        <div id="dialog-confirm">

        </div>
        </div>
</div>

</body>
</html>

<?php 

	} else{
		$referrer = $_SERVER['HTTP_REFERER'];
		if ( !preg_match($base_path."/app/admin/user/login.php",$referrer)) {
			header('Location: '.$base_path);
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
		 	var AllowRegex  = /^\d+$/;
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

	  	$("#edit_cust_submit input[type=submit]").click(function(e){
            e.preventDefault();
            var str = $( "#edit_customer_form" ).serialize();
            $.ajax({
                url: "<?php echo $base_path ?>/app/module/customer/customer_action.php",
                type: "POST",
                data: { ssd: "update_customer", data: str },
                dataType: "JSON",
                success: function (response) {
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

 