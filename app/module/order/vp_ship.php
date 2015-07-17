<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($custom_database);

	$orderid = $_REQUEST['orderid'];

	$query = "select dod, delivery_type from vendor_processing where orderid=$orderid";
	$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result))
	{
		$delivery_date = $row['dod'];
		$shippingtype = $row['delivery_type'];
	}
?>

<html>
<head>
	<script src="<?php echo $base_media_js_url; ?>/jquery-latest.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

	<style>
		.heading
		{
			 font-size:32px; color:#009ACD; font-weight:bold;
		}

		.data_labels_normal
		{
			font-family: Open Sans, sans-serif; font-size:15px;
		}

		.data_labels_bold
		{
			font-family: Open Sans, sans-serif; font-weight: bold; font-size:14px;
		}

		.heading_labels
		{
			font-size:16px; color:#00688B; font-family: Open Sans;
		}
		.main_buttons
		{
			margin:20px 0px 10px 0px; border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}
		.main_buttons:hover
		{
			background-color: #00688B; cursor:pointer;
		}
	</style>
</head>

<body>
<center>
<label class='heading'>Order Shipment Details</label>
</center>
<div id='main_div' style='float:left; display:inline; width:96%; margin:0% 0% 0% 2%; background-color:#F7F7F7; border:2px solid #00B2EE; border-radius: 4px; text-align:center;'>
<?php

if(!isset($_REQUEST['flag']))
{
?>	
	<div id='main_div1' style='float:left; display:inline; width:100%; margin:10px 0px 10px 0px;'>
		<label class='heading_labels'>Please fill the following details to mark the Order Shipped :</label>
	</div>
	<div id='main_div2'>
		<form method='POST' style='margin:0px; padding:0px;' id='to_submit_form'>
			<label class='data_labels_normal'>Order ID</label> : <label class='data_labels_normal'><?php echo $orderid; ?></label>
			<br>
			<label class='data_labels_normal'>Delivery Date</label> : <label class='data_labels_normal'><?php echo $delivery_date; ?></label>
			<br>
			<label class='data_labels_normal'>Delivery Type</label> : <label class='data_labels_normal'><?php echo $shippingtype; ?></label>
			<br>
			<label class='data_labels_normal'>Delivery Boy Name</label><sup style='color:red; font-size:12px;'>*</sup> : 
			<div id='varunkumarisgreat' style='float:inherit; display:inline;'>
			<?php
			mysql_select_db($vendorshop_database);
			$query = "select role_id from pos_user_roles where role_name='Delivery Boy'";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$temp_role_id = $row['role_id'];
			$query = "select role_id from pos_user_roles where role_name='Adhoc Delivery Boy'";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$temp_adhoc_role_id = $row['role_id'];
			$query = "select firstname, lastname, phone_number, entity_id from pos_user_entity where shop_id = $shop_id and role_id = $temp_role_id OR role_id = $temp_adhoc_role_id";
			$result = mysql_query($query);

			echo "<select name='delivery_boy_info' id='delivery_boy_info'>";
			echo "<option value='Select'>Select</option>";
			while($row = mysql_fetch_assoc($result))
			{
				$temp_firstname = $row['firstname'];
				$temp_lastname = $row['lastname'];
				$temp_name = $temp_firstname." ".$temp_lastname;
				$temp_phonenumber = $row['phone_number'];
				$temp_deliveryboy_id = $row['entity_id'];

				$vendor_details_array[$temp_name] = $temp_phonenumber;
				echo "<option value='".$temp_name."_".$vendor_details_array[$temp_name]."_".$temp_deliveryboy_id."'>$temp_name</option>";
			}
			echo "<option value='Other'>Other</option>";
			echo "</select>";
			?>
			</div>
			<br>
			<label class='data_labels_normal'>Delivery Boy Contact</label><sup style='color:red; font-size:12px;'>*</sup> : <input type='textbox' name='delivery_boy_contact' id='delivery_boy_contact' required>
			<br>
			<label class='data_labels_normal'>Select Device</label> : 
			<select name='delivery_device' id='delivery_device'>
			<option value=''>Select</option>
			<?php
				$query = "select name, device_id from pos_shop_order_track where shop_id = $shop_id and is_active=1";
				$result = mysql_query($query);
				while($row = mysql_fetch_assoc($result))
				{
					$temp_device_name = $row['name'];
					$temp_device_id = $row['device_id'];

					echo "<option value='$temp_device_id'>$temp_device_name</option>";
				}
			?>
			</select>

			<br>
			<input type='hidden' name='flag' value='1'>
			<input type='hidden' name='orderid' value='<?php echo $orderid; ?>'>
			<input type='button' id='make_order_shop_b' value='Make Order Shipped' class='main_buttons'>
		</form>
	</div>
<?php
}
else
{
	if($_REQUEST['flag'] == "1")
	{
		$orderid = $_REQUEST['orderid'];
		mysql_select_db($custom_database);

		if(isset($_REQUEST['new_delivery_boy']) && $_REQUEST['new_delivery_boy'] == "1")
		{
			//echo "I am in IF<br>";
			$delivery_boy_name = $_REQUEST['delivery_boy_name'];
			$temp_delivery_boy_name = $delivery_boy_name." ";
			$deliveryboy_contact = $_REQUEST['delivery_boy_contact'];
			$deliveryboy_name_array = explode(" ", $temp_delivery_boy_name);
		}
		else
		{
			//echo "I am in ELSE<br>";
			$delivery_boy_info = $_REQUEST['delivery_boy_info'];
			//echo "Delivery Boy Info : ".$delivery_boy_info."<br>";
			$delivery_boy_info_array = explode("_", $delivery_boy_info);
			//var_dump($delivery_boy_info_array);
			$deliveryboy_contact = $delivery_boy_info_array[1];
			$delivery_boy_name = $delivery_boy_info_array[0];
			$delivery_boy_id = $delivery_boy_info_array[2];
		}

		$delivery_device_id = $_REQUEST['delivery_device'];

		//echo $delivery_boy_name."<br>".$delivery_boy_name;
		//exit();

		if(isset($_REQUEST['new_delivery_boy']) && $_REQUEST['new_delivery_boy'] == "1")
		{
			mysql_select_db($vendorshop_database);
			$query_user_created = "select role_id from pos_user_roles where role_name = 'Adhoc Delivery Boy'";
			$result_user_created = mysql_query($query_user_created);
			$row_user_created = mysql_fetch_assoc($result_user_created);
			$adhoc_delivery_boy_role_id = $row_user_created['role_id'];
			$query_user_created = "insert into pos_user_entity (role_id, shop_id, firstname, lastname, phone_number) values ($adhoc_delivery_boy_role_id, $shop_id, '".$deliveryboy_name_array[0]."', '".$deliveryboy_name_array[1]."', '$deliveryboy_contact')";
			//echo $query_user_created."<br><br>";
			mysql_query($query_user_created);
			$query_user_created = "select entity_id from pos_user_entity where shop_id=$shop_id and firstname='".$deliveryboy_name_array[0]."' and lastname='".$deliveryboy_name_array[1]."'";
			$result_user_created = mysql_query($query_user_created);
			$row_user_created = mysql_fetch_assoc($result_user_created);
			$delivery_boy_id = $row_user_created['entity_id'];
			//echo mysql_error()."<br><br>";
			

			mysql_select_db($custom_database);
			$query = "insert into delivery_boys_info (name, phone_no1, phone_no2, rider_type) values ('$delivery_boy_name', '$deliveryboy_contact', '', 1)";
			//echo $query."<br><br>";
			mysql_query($query);
			//echo mysql_error()."<br><br>";
		}

		$query = "update vendor_processing set deliveryboy='$delivery_boy_name', deliveryboy_contact='$deliveryboy_contact', deliveryboy_id=$delivery_boy_id, device_id='$delivery_device_id', state=2 where orderid=$orderid";
		$result = mysql_query($query);
		//echo $query;
		//echo "<br><br>";
		//echo mysql_error();
		
		if($result)
		{
			echo "<label class='heading_labels'>Order has been marked Shipped.</label><br><br>";
			?>
			<script>
			/* THIS CODE RECORDS THIS OPERATION */
				$.ajax({
					type:"POST",
					url:"<?php echo $base_module_path; ?>/notifications/record_notification.php",
					data:
					{
						notf_type:'order_shipped',
						shop_id_accepted:'<?php echo $shop_id; ?>',
						order_id:'<?php echo $orderid; ?>',
					}
				});
			/* THIS CODE RECORDS THIS OPERATION */






			$(document).ready(function(){
				$.ajax({
					url:'vp_sendsmsemail.php',
					type:'POST',
					data:
					{
						order_id:"<?php echo $_REQUEST['orderid']; ?>",
						status:'2'
					},

					success:function(message)
					{
						if(message == "0")
						{
							alert("Customer Email and SMS notification failed.");
						}
						else
						{
							if(message == "1")
							{
								alert("Customer SMS sent Email failed.");
							}
							else
							{
								if(message == "2")
								{
									alert("Customer Email sent sms failed.");
								}
								else
								{
									if(message == "3")
									{
										alert("Customer Email and SMS notification success.");
									}
								}
							}
						}
					}
				});
			});
			</script>
			<?php
		}
		else
		{
			echo "<label class='heading_labels'>Could not mark the order Shipped. Please try again.</label><br><br>";
			//echo mysql_error();
		}
	}
}
?>
</div>
</body>


<script>
$(document).ready(function(){
	//$("#delivery_boy_contact").attr("readonly", "readonly");
});

$(document).ready(function(){
	$(document).on('change', '#delivery_boy_info', function(){
		var value = $("#delivery_boy_info option:selected").val();
		var value_array = value.split("_");
		var text = $("#delivery_boy_info option:selected").text();
		//alert(text);
		//alert(value);

		if(String(value) == String("Select"))
		{
			//alert("You selected Select.");
			$("#delivery_boy_contact").val("");
		}
		else
		{
			if(String(value) == String("Other"))
			{
				$("#varunkumarisgreat").html("");
				$("#delivery_boy_contact").val("");
				$("#to_submit_form #varunkumarisgreat").append("<input type='textbox' name='delivery_boy_name' id='delivery_boy_name_t'><input type='hidden' name='new_delivery_boy' value='1'>");
				$("#delivery_boy_contact").removeAttr("readonly");
			}
			else
			{
				$("#delivery_boy_contact").val(value_array[1]);
				if(value_array[1] == '')
				{
					$("#delivery_boy_contact").removeAttr("readonly");
				}
				else
				{
					$("#delivery_boy_contact").attr("readonly", "readonly");
				}
			}
		}
	});
});

$(document).ready(function(){
	$(document).on('click', '#make_order_shop_b', function(){

		var value = $("#delivery_boy_info option:selected").val();
		if(value != undefined)
		{
			if(String(value) == String("Select"))
			{
				alert("Please select a Delivery Boy");
				$("#delivery_boy_info").focus();
				return;
			}
		}


		if($("#delivery_boy_name_t").val() != undefined)
		{
			if($("#delivery_boy_name_t").val() == '')
			{
				alert("Please enter a Delivery Boy Name");
				$("#delivery_boy_name_t").focus();
				return;
			}
		}

		var value2 = $("#delivery_boy_contact").val();
		if(value2 == '')
		{
			alert("Please enter contact number of Delivery Boy");
			$("#delivery_boy_contact").focus();
			return;
		}

		$("#to_submit_form").submit();
	});
});

</script>
</html>