<?php
	include 'vp_dbconfig.php';
	include 'vp_authorize.php';
?>
<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="jquery-latest.min.js"></script>
	<link rel='stylesheet' href='jquery-ui.css'>
	<script src='jquery-ui.js'></script>
	<link rel="stylesheet" href="fancyBox/source/jquery.fancybox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/jquery.fancybox.pack.js"></script>
	<link rel="stylesheet" href="fancyBox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/helpers/jquery.fancybox-media.js"></script>
	<link rel="stylesheet" href="fancyBox/source/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/helpers/jquery.fancybox-thumbs.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

	<script>
		//$(document).ready(function(){
			query = "select distinct orderid from panelorderdetails where vendor_id is NULL or vendor_id=''";
			range = 20;
		//});

		$(function(){$( ".datepicker" ).datepicker();});


		$(document).ready(function(){
			$(document).on('mouseover', '.order_id_div', function() {
				var button_id = $(this).attr('id');
				var starter = button_id.split('_');

				$('#orderdetails_'+starter[1]).show();
			});
		});


		$(document).ready(function(){
			$(document).on('mouseout', '.order_id_div', function() {
				var button_id = $(this).attr('id');
				var starter = button_id.split('_');

				$('#orderdetails_'+starter[1]).hide();
			});
		});


		$(document).ready(function(){
			/* THE AJAX CALL */
			$("#loading_div").show();
			$.ajax({
				type:"POST",
				url:"vp_vendormain_queryexecuter.php",
				data:
				{
					query:query,
					min:'0',
					max:range
				},

				success:function(message)
				{
					$("#loading_div").hide();
					$("#orders_loader").html(message);
				}
			});
			/* THE AJAX CALL */
		});


		$(document).ready(function(){
			$(document).on('click', '#filter_form_button', function(){
				var delivery_date = $("#filter_dod_t").val();
				var delivery_type = $("#filter_type_dd").val();
				var order_status = $("#filter_status_dd").val();
				var vendor = $("#filter_vendor_dd").val();
				var orderid = $("#filter_orderid_t").val();

				$("#loading_div").show();

				if(orderid == "")
				{
					if(delivery_date == "")
					{
						alert("Please select a Delivery Date.");
						$("#loading_div").hide();
					}
					else
					{
						query = "select distinct orderid from panelorderdetails where 1=1";
						if(delivery_date != "")
						{
							var temp_delivery_date_array = delivery_date.split("/");

							var delivery_date_new = temp_delivery_date_array[2]+"-"+temp_delivery_date_array[0]+"-"+temp_delivery_date_array[1];
							query += " and dod='"+delivery_date_new+"'";
						}
						if(delivery_type != "-1")
						{
							query += " and shippingtype like '"+delivery_type+"%'";
						}
						
						if(vendor != "-1")
						{
							query += " and vendor_id="+vendor;
						}

						if(order_status != "4")
						{
							query = "select distinct orderid from vendor_processing where state="+order_status+" and orderid in ("+query+")";
						}


						//alert(query);
						/* THE AJAX CALL */
						$.ajax({
							type:"POST",
							url:"vp_vendormain_queryexecuter.php",
							data:
							{
								query:query,
								min:'0',
								max:range
							},

							success:function(message)
							{
								$("#loading_div").hide();
								$("#orders_loader").html(message);
							}
						});
						/* THE AJAX CALL */
					}
				}
				else
				{
					query = "select distinct orderid from panelorderdetails where orderid="+orderid;

					/* THE AJAX CALL */
					$.ajax({
						type:"POST",
						url:"vp_vendormain_queryexecuter.php",
						data:
						{
							query:query,
							min:'0',
							max:range
						},

						success:function(message)
						{
							$("#loading_div").hide();
							$("#orders_loader").html(message);
						}
					});
					/* THE AJAX CALL */
				}
			});
		});
	</script>


	<style>
		.heading
		{
			font-size:32px; color:#009ACD; font-weight:bold;
		}

		.table
		{
			width:96%;
			text-align:center;
			margin:0px 0px 250px 0px;
		}

		.buttons
		{
			margin-left: 2%; border-radius: 6px; background-color: #00688B; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}
		.buttons:hover
		{
			background-color: #4A708B; cursor:pointer;
		}

		.table_heading_row
		{
			font-size:20px;
			color:white;
			background-color:#009ACD;
			padding:90px;
			font-family: Open Sans;
			height:56px;
		}

		.table_heading_row_small
		{
			font-size:18px;
			color:white;
			background-color:#009ACD;
			padding:90px;
			font-family: Open Sans;
			height:30px;
		}

		.table_data_row
		{
			background-color:#DCDCDC;
			font-family: 'Raleway', sans-serif;
			height:100px;
		}

		.table_data_row:hover
		{
			background-color:#D3D3D3;
		}

		.notice_labels
		{
			font-size:20px;
			color:#00688B;
			font-family: Open Sans;
		}

		.order_details_div
		{
			position:absolute;
			display:none;
			left:10px;
			float:left;
			//border:3px solid black;
			//background-color:red;
			width:1200px;
		}

		.order_products_table
		{
			border:0px black solid;
			text-align:center;
			background-color:#EEEEE0;
		}

		.order_products_table_cell
		{
			border: 3px solid black;
			width:16%;
		}

		.main_buttons
		{
			margin-left: 2%; border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}
		.main_buttons:hover
		{
			background-color: #00688B; cursor:pointer;
		}

		.checkbox
		{
			height:15px;
			width:15px;
		}

		.filter_table
		{
			width:100%;
			text-align:center;
			margin:0px 0px 0px 0px;
			border-left:3px solid #009ACD;
			border-right:3px solid #009ACD;
			border-top:3px solid #009ACD;
		}

		.base_div
		{
			display:block;
			position:fixed;
			background-color:white;
			top:0px;
			left:0px;
			width:100%;
			text-align:center;
			border-bottom:3px solid #009ACD;
			margin:0% 0% 3% 0%;
		}

		.datepicker
		{
			width:90px;
		}

		.main_buttons_red
		{
			margin-left: 2%; border-radius: 6px; background-color: #B22222; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}

		.main_buttons_red:hover
		{
			background-color: #8B1A1A; cursor:pointer;
		}

		.main_buttons_yellow
		{
			margin-left: 2%; border-radius: 6px; background-color: #EEB422; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}

		.main_buttons_yellow:hover
		{
			background-color: #CD9B1D; cursor:pointer;
		}

		.main_buttons_green
		{
			margin-left: 2%; border-radius: 6px; background-color: #6E8B3D; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}

		.main_buttons_green:hover
		{
			background-color: #556B2F; cursor:pointer;
		}

		.main_buttons_orange
		{
			margin-left: 2%; border-radius: 6px; background-color: #FF8C00; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}

		.main_buttons_orange:hover
		{
			background-color: #CD6600; cursor:pointer;
		}

		select
		{
			background-color: white;
		}

		.orders_loader
		{
			width:94%;
			text-align:center;
			margin:0px 0px 250px 50px;
		}

		.pagination_buttons
		{
			border-radius: 20px;
			font-size: 15px;
			background-color: #009ACD;
			padding: 0px;
			height: 25px;
			width: 25px;
			color: white;
			border: 2px solid #607B8B;
		}

		.pagination_buttons:hover
		{
			cursor:pointer;
		}

		.pagination_buttons_selected
		{
			background-color:white;
			color:#009ACD;
		}

		.pagination_div
		{
			float:left;
			position:fixed;
			bottom:0px;
			right: 0px;
			margin:0px 0px 5px 0px;
			text-align:center;
			width:auto;
		}

		.pagination_textbox
		{
			height:25px;
			width:35px;
		}
	</style>
</head>
<body>

<?php
	include "vp_footer.php";
?>


<div id='loading_div' style='display:none; position:fixed; width:100%; height:100%; text-align:center; vertical-align:middle; background-color:gray; opacity:0.5;'>
	<img src='http://www.theappmadeinheaven.com/resources/images/PleaseWait.gif' style='margin-top:20%; width:5%; height:10%;'>
</div>







<div class='base_div'>
	<div style='float:left; display:inline; text-align:left; margin:5px 0px 0px 5px;'>
		<form method='POST' action='vp_logout.php' style='margin:0px; padding:0px;'><input type='submit' value='Logout' class='buttons'></form>
	</div>


	<img src="images/flaberry.png" style="height: 50px; width: 250px; margin-top: 10px;">
	<div style='float:right; display:inline; text-align:right; position:fixed; right:0px; margin:0px 10px 0px 0px;'>
		<label style='color:#009ACD; font-size:18px;'>Not Forwarded Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='not_forwarded_orders'></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Unacknowledged Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='unacknowledged_orders'></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Acknowledged Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='acknowledged_orders'></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Shipped Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='shipped_orders'></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Delivered Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='delivered_orders'></label>
		<br><br>
	</div>
	<br>
	<br>
	<br>
	<!--
	<form method='POST' style='margin:0px; padding:0px;' id='filter_form'>
	<input type='hidden' name='filters_flag' value='1'>
	-->
	<table class='filter_table' cellspacing="0">
		<tr class='table_heading_row_small'>
			<td style='padding:0px;'>Select Delivery Date<label style='color:red; font-size:15px;'><sup>*</sup></label></td>
			<td style='padding:0px;'>Select Delivery Type</td>
			<td style='padding:0px;'>Select Order Status</td>
			<td style='padding:0px;'>Select by Vendor</td>
			<!-- <td style='padding:0px;'>Select by Delivery City</td> -->
			<td style='padding:0px;'>Load by Order ID</td>
			<td style='padding:0px;'>Load Orders</td>
		</tr>

		<tr style='height:40px;'>
			<td style='padding:0px;'><input type='textbox' id='filter_dod_t' name='filter_dod_t' class='datepicker'></td>

			<td style='padding:0px;'>
				<select name='filter_type_dd' id='filter_type_dd'>
				<option value='-1'>Select</option>
				<option value='Regular Delivery'>Regular Delivery</option>
				<option value='Midnight Delivery'>Midnight Delivery</option>
				</select>
			</td>

			<td style='padding:0px;'>
				<select name='filter_status_dd' id='filter_status_dd'>
					<option value='4'>All</option>
					<option value='0'>Orders to Accept</option>
					<option value='1'>Accepted Orders</option>
					<option value='2'>Shipped Orders</option>
					<option value='3'>Delivered Orders</option>
				</select>
			</td>



			<td style='padding:0px;'>
				<select name='filter_vendor_dd' id='filter_vendor_dd'>
					<option value='-1'>Select</option>
					<?php
					$query = "select vendor_id, vendor_name from vendors";
					$result = mysql_query($query);

					while($row = mysql_fetch_assoc($result))
					{
						echo "<option value='".$row['vendor_id']."'>".$row['vendor_name']."</option>";
					}
					?>
				</select>
			</td>


			<!--
			<td style='padding:0px;'>
				<select name='filter_city_dd'>
					<option value='-1'>Select</option>
					<?php
					//$query = "select distinct city from pincode_product_map";
					//$result = mysql_query($query);

					//while($row = mysql_fetch_assoc($result))
					{
						//$temp_city = $row['city'];
						//$temp_city = strtolower($temp_city);
						//$temp_city = ucwords($temp_city);
						//echo "<option value='$temp_city'>$temp_city</option>";
					}
					?>
				</select>
			</td>
			-->


			<td style='padding:0px;'>
				<input type='textbox' name='filter_orderid_t' id='filter_orderid_t' placeholder='Enter Order ID'>
			</td>

			<td style='padding:0px;'><input type='button' id='filter_form_button' value='Load Orders' class='buttons'></td>
		</tr>
	</table>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class='orders_loader' id='orders_loader'></div>


</body>


<script>
$(document).ready(function(){
	$(document).on('click', '#print_b', function(){
		var checked_array = '';
		$(".print_ch").each(function(){
			if($(this).prop('checked') == true)
			{
				var temp_order = $(this).attr("id");
				var temp_array = temp_order.split("_");
				checked_array += temp_array[1]+"_";
			}
		});
		$("#print_form").remove();
		$("body").append("<form id='print_form' action='vp_printorder.php' method='POST' style='display:none;' target='_blank'><input type='hidden' value='"+checked_array+"' name='orderid'></form>");
		$("#print_form").submit();
	});
});


$(document).ready(function(){
	$(document).on('click', '#print_b_card', function(){
		var checked_array = '';
		$(".print_ch").each(function(){
			if($(this).prop('checked') == true)
			{
				var temp_order = $(this).attr("id");
				var temp_array = temp_order.split("_");
				checked_array += temp_array[1]+"_";
			}
		});
		$("#print_form_card").remove();
		$("body").append("<form id='print_form_card' action='vp_printmessageoncard.php' method='POST' style='display:none;' target='_blank'><input type='hidden' value='"+checked_array+"' name='orderid'></form>");
		$("#print_form_card").submit();
	});
});


$(document).ready(function(){
	$(document).on('click', '.sendemailbutton', function(){
		
		var button_id = $(this).attr('id');
		var starter = button_id.split('_');
		var orderid = $("#orderid_hidden_"+starter[1]).val();
		var vendor_id = $("#vendor_id_dd_"+starter[1]+" option:selected").val();
		var vendor_name = $("#vendor_id_dd_"+starter[1]+" option:selected").text();

		if(vendor_id != "Select")
		{
			$.fancybox({
				'href'              : 'vp_emailContent.php?vendorCan_orderId='+vendor_name+'_'+orderid,
				'width'             : '60%',
				'height'            : '60%',
				'autoScale'         : false,
				'transitionIn'      : 'none',
				'transitionOut'     : 'none',
				'type'              : 'iframe',

				afterClose: function()
				{
					//alert();
					$.ajax({
						type:'POST',
						url:'vp_checkstate.php',
						data:
						{
							orderid:orderid
						},

						success:function(message)
						{
							if(message == "0")
							{
								/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
								$.ajax({
									type:"POST",
									url:"vp_recordnotifications.php",
									data:
									{
										orderid:orderid,
										notification_type:'0',
										username:'<?php echo $user_name; ?>',
										vendor_id:vendor_id
									}
								});
								/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */


								$("#orderid_vendor_"+starter[1]).html(vendor_name+"<br>Unacknowledged");
								$("#vendor_id_dd_"+starter[1]).remove();

								if(vendor_name == "Flaberry - Self")
								{
									$("#sendemailb_"+starter[1]).val("Accept Order");
									$("#sendemailb_"+starter[1]).removeClass("buttons");
									$("#sendemailb_"+starter[1]).removeClass("sendemailbutton");
									$("#sendemailb_"+starter[1]).addClass("main_buttons_red");
									$("#sendemailb_"+starter[1]).addClass("varun");
									$("#sendemailb_"+starter[1]).attr("data-fancybox-type", "iframe");
									$("#sendemailb_"+starter[1]).attr("href", "vp_acknowledge.php?orderid="+orderid);
									$("#sendemailb_"+starter[1]).attr("id", "activity_b_"+starter[1]);
								}
								else
								{
									$("#sendemailb_"+starter[1]).remove();
								}
								//<input type='button' id='activity_b_$orders_details_array_inside_foreach' href='vp_acknowledge.php?orderid=$orders_details_array_inside_foreach'>
								


								/* THIS CODE RECORDS THIS OPERATION */
								$.ajax({
									type:"POST",
									url:"vp_recordoperations.php",
									data:
									{
										orderid:orderid,
										comment_type:"0"
									}
								});
								/* THIS CODE RECORDS THIS OPERATION */
							}
							else
							{
								alert("Some unexpected error occurred.");
							}
						}
					});
				}
			});
		}
		else
		{
			alert("Please select a Vendor first.");
		}
		/*
		$("html").css("cursor", "wait");
		var button_id = $(this).attr('id');
		var starter = button_id.split('_');
		var orderid = $("#orderid_hidden_"+starter[1]).val();

		var vendor_id = $("#vendor_id_dd_"+starter[1]+" option:selected").val();
		var vendor_name = $("#vendor_id_dd_"+starter[1]+" option:selected").text();

		if(vendor_id != "Select")
		{
			$.ajax({
				type:"POST",
				url:"vp_acknowledgeemail.php",
				data:
				{
					orderid:orderid,
					vendor_id:vendor_id
				},

				success:function(message)
				{
					$("html").css("cursor", "default");
					if(message == "+1")
					{
						//alert("It's a success!!");
						$("#orderid_vendor_"+starter[1]).html(vendor_name+"<br>Unacknowledged");
						$("#vendor_id_dd_"+starter[1]).remove();

						$("#sendemailb_"+starter[1]).val("Accept Order");
						$("#sendemailb_"+starter[1]).removeClass("buttons");
						$("#sendemailb_"+starter[1]).removeClass("sendemailbutton");
						$("#sendemailb_"+starter[1]).addClass("main_buttons_red");
						$("#sendemailb_"+starter[1]).addClass("varun");
						$("#sendemailb_"+starter[1]).attr("data-fancybox-type", "iframe");
						$("#sendemailb_"+starter[1]).attr("href", "vp_acknowledge.php?orderid="+orderid);
						$("#sendemailb_"+starter[1]).attr("id", "activity_b_"+starter[1]);

						//<input type='button' id='activity_b_$orders_details_array_inside_foreach' href='vp_acknowledge.php?orderid=$orders_details_array_inside_foreach'>
						


						/* THIS CODE RECORDS THIS OPERATION /
						$.ajax({
							type:"POST",
							url:"vp_recordoperations.php",
							data:
							{
								orderid:orderid,
								comment_type:"0"
							}
						});
						/* THIS CODE RECORDS THIS OPERATION /
					}
					else
					{
						alert("Some unexpected error occurred.");
					}
				}
			});
		}
		else
		{
			alert("Please select a Vendor first.");
		}
		*/
		//alert(orderid);
	});
});


$(document).ready(function(){
	$(document).on('click', '.pagination_event1', function(){
		var event_value = $(this).attr('value');
		var max_page_number = $("#max_page_number").val();

		$("#loading_div").show();

		if(event_value == "<")
		{
			var button_number = $(".pagination_textbox").val();
			if(button_number == 1)
			{
				button_number = 1;
			}
			else
			{
				button_number = parseInt(button_number)-1;
			}
		}
		else
		{
			if(event_value == ">")
			{
				var button_number = $(".pagination_textbox").val();
				if(button_number == max_page_number)
				{
					button_number = max_page_number;
				}
				else
				{
					button_number = parseInt(button_number)+1;
				}
			}
			else
			{
				alert(s);
				if(event_value > max_page_number)
				{
					//event_value = max_page_number;
				}
				var button_number = event_value;
			}
		}

		var min = (button_number-1)*range;
		var max = range;
		//alert(min+" to "+max);

		/* THE AJAX CALL */
		$.ajax({
			type:"POST",
			url:"vp_vendormain_queryexecuter.php",
			data:
			{
				query:query,
				min:min,
				max:max
			},

			success:function(message)
			{
				$("#loading_div").hide();
				$("#orders_loader").html(message);
			}
		});
		/* THE AJAX CALL */
	});
});



$(document).ready(function(){
	$(document).on('keydown', '.pagination_event2', function(e){
		var keycode = e.which;
		var max_page_number = $("#max_page_number").val();
		var button_number = $("#current_page_number").val();

		if(keycode == 13)
		{
			$("#loading_div").show();
			if(button_number > max_page_number)
			{
				button_number = max_page_number;
			}
			else
			{
				if(button_number < "1")
				{
					button_number = "1";
				}
			}

			var min = (button_number-1)*range;
			var max = range;
			//alert(min+" to "+max);

			/* THE AJAX CALL */
			$.ajax({
				type:"POST",
				url:"vp_vendormain_queryexecuter.php",
				data:
				{
					query:query,
					min:min,
					max:max
				},

				success:function(message)
				{
					$("#loading_div").hide();
					$("#orders_loader").html(message);
				}
			});
			/* THE AJAX CALL */
		}
	});
});









$(document).ready(function(){
	$(document).on('click', '.varun', function(){
		var button_id = $(this).attr('id');
		var starter = button_id.split("_");

		var class_main = $(this).attr('class');
		var class_array = class_main.split(' ');
		var order_id_hidden = $("#orderid_hidden_"+starter[2]).val();

		if(class_array[0] == "main_buttons_red")
		{
			$(".main_buttons_red").fancybox({
			width		: '670px',
			height		: '380px',
			autoSize	: false,
			closeClick	: false,

			afterClose: function()
			{
				$("#loading_div").show();
				$('body').attr('disabled', 'disabled');
				$.ajax({
					type:'POST',
					url:'vp_checkstate.php',
					data:
					{
						orderid:order_id_hidden
					},

					success:function(message)
					{
						if(message == "0")
						{
							$("#activity_b_"+starter[2]).val("Accept Order");
							$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
						}
						else
						{
							if(message == "1")
							{
								/* THIS CODE RECORDS THIS OPERATION */
								$.ajax({
									type:"POST",
									url:"vp_recordoperations.php",
									data:
									{
										orderid:order_id_hidden,
										comment_type:"2"
									}
								});
								/* THIS CODE RECORDS THIS OPERATION */

								/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
								$.ajax({
									type:"POST",
									url:"vp_recordnotifications.php",
									data:
									{
										orderid:order_id_hidden,
										notification_type:"2",
										username:"<?php echo $user_name; ?>",
										vendor_id:"0"
									}
								});
								/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
								
								$("#activity_b_"+starter[2]).val("Ship Order");
								$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
								$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+order_id_hidden);
							}
							else
							{
								if(message == "2")
								{
									$("#activity_b_"+starter[2]).val("Order Delivered");
									$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
									$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+order_id_hidden);
								}
								else
								{
									if(message == "3")
									{
										$("#activity_b_"+starter[2]).hide();
									}
									else
									{
										if(message == "-1")
										{
											alert("Some error has occurred while performing operation.");
										}
									}
								}
							}
						}
					}
				});
				$("#loading_div").hide();
				$('body').removeAttr("disabled");
			}
		});
		}

		else
		{
			if(class_array[0] ==  "main_buttons_yellow")
			{
				$(".main_buttons_yellow").fancybox({
				width		: '670px',
				height		: '280px',
				autoSize	: false,
				closeClick	: false,

				afterClose: function()
				{
					$("#loading_div").show();
					$('body').attr('disabled', 'disabled');
					$.ajax({
						type:'POST',
						url:'vp_checkstate.php',
						data:
						{
							orderid:order_id_hidden
						},

						success:function(message)
						{
							if(message == "0")
							{
								$("#activity_b_"+starter[2]).val("Accept Order");
								$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
							}
							else
							{
								if(message == "1")
								{
									$("#activity_b_"+starter[2]).val("Ship Order");
									$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
									$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+order_id_hidden);
								}
								else
								{
									if(message == "2")
									{
										/* THIS CODE RECORDS THIS OPERATION */
										$.ajax({
											type:"POST",
											url:"vp_recordoperations.php",
											data:
											{
												orderid:order_id_hidden,
												comment_type:"3"
											}
										});
										/* THIS CODE RECORDS THIS OPERATION */

										/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
										$.ajax({
											type:"POST",
											url:"vp_recordnotifications.php",
											data:
											{
												orderid:order_id_hidden,
												notification_type:"3",
												username:"<?php echo $user_name; ?>",
												vendor_id:"0"
											}
										});
										/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */


										$("#activity_b_"+starter[2]).val("Order Delivered");
										$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
										$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+order_id_hidden);
									}
									else
									{
										if(message == "3")
										{
											$("#activity_b_"+starter[2]).hide();
										}
										else
										{
											if(message == "-1")
											{
												alert("Some error has occurred while performing operation.");
											}
										}
									}
								}
							}
						}
					});
					$("#loading_div").hide();
					$('body').removeAttr("disabled");
				}
			});
			}

			else
			{
				if(class_array[0] == "main_buttons_green")
				{
					$(".main_buttons_green").fancybox({
					width		: '670px',
					height		: '230px',
					autoSize	: false,
					closeClick	: false,

					afterClose: function()
					{
						$("#loading_div").show();
						$('body').attr('disabled', 'disabled');
						$.ajax({
							type:'POST',
							url:'vp_checkstate.php',
							data:
							{
								orderid:order_id_hidden
							},

							success:function(message)
							{
								if(message == "0")
								{
									$("#activity_b_"+starter[2]).val("Accept Order");
									$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
								}
								else
								{
									if(message == "1")
									{
										$("#activity_b_"+starter[2]).val("Ship Order");
										$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
										$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+order_id_hidden);
									}
									else
									{
										if(message == "2")
										{
											$("#activity_b_"+starter[2]).val("Order Delivered");
											$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
											$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+order_id_hidden);
										}
										else
										{
											if(message == "3")
											{
												/* THIS CODE RECORDS THIS OPERATION */
												$.ajax({
													type:"POST",
													url:"vp_recordoperations.php",
													data:
													{
														orderid:order_id_hidden,
														comment_type:"4"
													}
												});
												/* THIS CODE RECORDS THIS OPERATION */

												/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
												$.ajax({
													type:"POST",
													url:"vp_recordnotifications.php",
													data:
													{
														orderid:order_id_hidden,
														notification_type:"4",
														username:"<?php echo $user_name; ?>",
														vendor_id:"0"
													}
												});
												/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */

												$("#activity_b_"+starter[2]).hide();
											}
											else
											{
												if(message == "-1")
												{
													alert("Some error has occurred while performing operation.");
												}
											}
										}
									}
								}
							}
						});
						$("#loading_div").hide();
						$('body').removeAttr("disabled");
					}
				});
				}
			}
		}
	});
});
</script>
