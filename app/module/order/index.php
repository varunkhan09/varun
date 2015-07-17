<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";

	$associated_vendors_array = array();
	mysql_select_db($vendorshop_database);
	$query = "select b.shop_name, b.email, b.entity_id from pos_associated_vendors_entity a right join pos_shop_entity b on a.vendor_shop_id = b.entity_id where a.vendor_shop_id is not NULL and a.self_shop_id = $shop_id";
	$result = mysql_query($query);
	echo mysql_error();
	while($row = mysql_fetch_assoc($result))
	{
		$temp_vendor_shop_name = $row['shop_name'];
		$temp_vendor_shop_email = $row['email'];
		$temp_vendor_shop_id = $row['entity_id'];

		switch($temp_vendor_shop_id)
		{
			case $shop_id:
			{
				$temp_vendor_shop_name = "Self";
				break;
			}

			case $flaberry_self_shop_id:
			{
				$temp_vendor_shop_name = $flaberry_self_shop_name;
				break;
			}

			default:
			{
				break;
			}
		}

		$associated_vendors_array[$temp_vendor_shop_id]['name'] = $temp_vendor_shop_name;
		$associated_vendors_array[$temp_vendor_shop_id]['email'] = $temp_vendor_shop_email;
	}
	$associated_vendors_id_array = array_keys($associated_vendors_array);
?>

<style>
	table, tr, td
	{
		border:1px solid white;
	}

	.datepicker-dropdown
	{
		top: 175px !important;
	}

	.order_type_div
	{
		margin-top:10px;
	}

	.each_order_type_div
	{
		font-size:20px;
		text-align:center;
		background-color:#009ACD;
		color:white;
		padding:5px;
		height:80px;
		border:2px solid #009ACD;
		border-radius:5px;
		-webkit-border-radius:5px;
		-moz-border-radius:5px;

		-webkit-transition:background-color 0.3s, color 0.3s;
		-moz-transition:background-color 0.3s, color 0.3s;
		transition:background-color 0.3s, color 0.3s;
	}

	.each_order_type_div:hover
	{
		cursor:pointer;
		/*
		background-color: #009ACD;
		color:white;
		*/
	}
</style>



<script>
	var vendor_id = '<?php echo $shop_id; ?>';
	var checked_array_multiple_shipping = "";
	var query_array = [];
	var query_counter = 0;
	var query = "select distinct orderid from vendor_processing where (vendor_id="+vendor_id+") and (state = NULL OR state = 0)";
	var range = 20;


	$(function(){$( ".datepicker" ).datepicker({autoclose:true});});


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
					query = "select distinct orderid from panelorderdetails where vendor_id="+vendor_id;
					if(delivery_date != "")
					{
						var temp_delivery_date_array = delivery_date.split("/");

						var delivery_date_new = temp_delivery_date_array[2]+"-"+temp_delivery_date_array[0]+"-"+temp_delivery_date_array[1];
						query += " and dod='"+delivery_date_new+"'";
					}

					if(delivery_type != "-1")
					{
						query += " and delivery_type like '"+delivery_type+"%'";
					}
						
					if(vendor != "-1")
					{
						query += " and (shop_id_created="+vendor+" or shop_id_by="+vendor+")";
						//query += " and state="+order_status;
					}

					if(order_status != "6")
					{
						query = "select distinct orderid from vendor_processing where state="+order_status+" and orderid in ("+query+")";
					}

					/* THE AJAX CALL */
					window.history.pushState(query_counter, null, '');
					query_array.splice(query_counter, 0, query);
					console.log(query_array);
					query_counter++;
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
				query = "select distinct orderid from vendor_processing where vendor_id="+vendor_id+" and orderid="+orderid;

				/* THE AJAX CALL */
				window.history.pushState(query_counter, null, '');
				query_array.splice(query_counter, 0, query);
				console.log(query_array);
				query_counter++;
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
			window.history.pushState(query_counter, null, '');
			query_array.splice(query_counter, 0, query);
			console.log(query_array);
			query_counter++;
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
				window.history.pushState(query_counter, null, '');
				query_array.splice(query_counter, 0, query);
				console.log(query_array);
				query_counter++;
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
								if(message == "\n\n0")
								{
									$("#activity_b_"+starter[2]).val("Accept Order");
									$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
								}
								else
								{
									if(message == "\n\n1")
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
												username:'',
												vendor_id:<?php echo $vendor_id; ?>
											}
										});
										/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */


										$("#activity_b_"+starter[2]).val("Ship Order");
										$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
										$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+order_id_hidden);
									}
									else
									{
										if(message == "\n\n2")
										{
											$("#activity_b_"+starter[2]).val("Order Delivered");
											$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
											$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+order_id_hidden);
										}
										else
										{
											if(message == "\n\n3")
											{
												$("#activity_b_"+starter[2]).hide();
											}
											else
											{
												if(message == "\n\n-1")
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
						height		: '300px',
						autoSize	: false,
						closeClick	: false,
						live		: false,
	
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
									if(message == "\n\n0")
									{
										$("#activity_b_"+starter[2]).val("Accept Order");
										$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
									}
									else
									{
										if(message == "\n\n1")
										{
											$("#activity_b_"+starter[2]).val("Ship Order");
											$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
											$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+order_id_hidden);
										}
										else
										{
											if(message == "\n\n2")
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
														username:'',
														vendor_id:<?php echo $vendor_id; ?>
													}
												});
												/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */


												$("#activity_b_"+starter[2]).val("Order Delivered");
												$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
												$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+order_id_hidden);
											}
											else
											{
												if(message == "\n\n3")
												{
													$("#activity_b_"+starter[2]).hide();
												}
												else
												{
													if(message == "\n\n-1")
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
									if(message == "\n\n0")
									{
										$("#activity_b_"+starter[2]).val("Accept Order");
										$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
									}
									else
									{
										if(message == "\n\n1")
										{
											$("#activity_b_"+starter[2]).val("Ship Order");
											$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
											$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+order_id_hidden);
										}
										else
										{
											if(message == "\n\n2")
											{
												$("#activity_b_"+starter[2]).val("Order Delivered");
												$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
												$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+order_id_hidden);
											}
											else
											{
												if(message == "\n\n3")
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
															username:'',
															vendor_id:<?php echo $vendor_id; ?>
														}
													});
													/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
													$("#activity_b_"+starter[2]).hide();
												}
												else
												{
													if(message == "\n\n-1")
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






	$(document).ready(function(){
		$(document).on('click', '.each_order_type_div', function(){
			var state = $(this).attr("state");
			state_clicked(state);
		});
	});



	$(document).ready(function(){
		$(document).on('click', '#multiple_shipping_button', function(){
			console.log("Previous Query String : "+checked_array_multiple_shipping);
			exit_now = false;
			checked_array_multiple_shipping = "";
			$(".print_ch").each(function(){
				if($(this).prop('checked') == true)
				{
					var temp_order = $(this).attr("id");

					var temp_array = temp_order.split("_");
					var temp_row_counter = temp_array[2];
					//var this_order_state_class = $("#activity_b_"+temp_row_counter).attr("class");
					//console.log(this_order_state_class);
					if($("#activity_b_"+temp_row_counter).hasClass("main_buttons_yellow"))
					{
						checked_array_multiple_shipping += temp_array[1]+"_";
					}
					else
					{
						alert(temp_array[1]+" is not ready for shipping. Please de-select this order.");
						exit_now = true;
						return;
					}
				}
			});

			if(exit_now)
			{
				return;
			}
			else
			{
				if(checked_array_multiple_shipping.length != 0)
				{
					$("#multiple_shipping_button").fancybox({
						width		: '670px',
						height		: '380px',
						autoSize	: false,
						closeClick	: false,
						href		: "vp_ship_multiple.php?orderids="+checked_array_multiple_shipping,
						
						/*afterClose: function()
						{
							
						}*/
					});
				}
				else
				{
					alert("Select at least one order to multiple ship it.");
				}
			}
		});
	});


	window.onpopstate = function(event){
		$.ajax({
			type:"POST",
			url:"vp_vendormain_queryexecuter.php",
			data:
			{
				query:query_array[event.state],
				min:'0',
				max:range
			},

			success:function(message)
			{
				$("#loading_div").hide();
				$("#orders_loader").html(message);
			}
		});
	}


	function state_clicked(state)
	{
		$("#loading_div").show();
		switch(state)
		{
			case "unacknowledged":
			{
				query = "select distinct orderid from vendor_processing where state=0 and orderid in (select distinct orderid from panelorderdetails where vendor_id=<?php echo $shop_id; ?>)";
				break;
			}


			case "acknowledged":
			{
				query = "select distinct orderid from vendor_processing where state=1 and orderid in (select distinct orderid from panelorderdetails where vendor_id=<?php echo $shop_id; ?>)";
				break;
			}


			case "shipped":
			{
				query = "select distinct orderid from vendor_processing where state=2 and orderid in (select distinct orderid from panelorderdetails where vendor_id=<?php echo $shop_id; ?>)";
				break;
			}

			case "delivered":
			{
				query = "select distinct orderid from vendor_processing where state=3 and orderid in (select distinct orderid from panelorderdetails where vendor_id=<?php echo $shop_id; ?>)";
				break;
			}

			case "cancel_closed":
			{
				query = "select distinct orderid from vendor_processing where (state=4 or state=5) and orderid in (select distinct orderid from panelorderdetails where vendor_id=<?php echo $shop_id; ?>)";
				break;
			}

			default:
			{
				break;
			}
		}

		window.history.pushState(query_counter, null, '');
		query_array.splice(query_counter, 0, query);
		console.log(query_array);
		query_counter++;
		$.ajax({
			type:"POST",
			url:"<?php echo $base_module_path; ?>/order/vp_vendormain_queryexecuter.php",
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
	}

</script>




<div id='loading_div' style='display:none; position:fixed; width:100%; height:100%; text-align:center; vertical-align:middle; background-color:gray; opacity:0.5;'>
	<img src='http://www.theappmadeinheaven.com/resources/images/PleaseWait.gif' style='margin-top:20%; width:5%; height:10%;'>
</div>







<div class='base_div'>
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
					<option value='6'>All</option>
					<option value='0'>Orders to Accept</option>
					<option value='1'>Accepted Orders</option>
					<option value='2'>Shipped Orders</option>
					<option value='3'>Delivered Orders</option>
					<option value='4'>Cancelled Orders</option>
					<option value='5'>Closed (Refunded) Orders</option>
				</select>
			</td>

			<td style='padding:0px;'>
				<select name='filter_vendor_dd' id='filter_vendor_dd'>
					<option value='-1'>Select</option>
					<?php
						foreach($associated_vendors_id_array as $each_vendor_id)
						{
							echo "<option value='$each_vendor_id'>".$associated_vendors_array[$each_vendor_id]['name']."</option>";
						}
					?>
				</select>
			</td>

			<td style='padding:0px;'>
				<input type='textbox' name='filter_orderid_t' id='filter_orderid_t' placeholder='Enter Order ID'>
			</td>

			<td style='padding:0px;'><input type='button' id='filter_form_button' value='Load Orders' class='buttons'></td>
		</tr>
	</table>
</div>



<div class='orders_loader' id='orders_loader'>
	<div class="col-xs-12 order_type_div" style="<?php if(isset($_POST) && isset($_POST['header_clicked_state'])){echo 'display:none;';} ?>">
		<div class="col-xs-2 col-xs-offset-1">
			<div class="each_order_type_div" state="unacknowledged">
				Unacknowledged Orders (<?php echo $count_unacknowledged_orders; ?>)
			</div>
		</div>

		<div class="col-xs-2">
			<div class="each_order_type_div" state="acknowledged">
				Acknowledged Orders (<?php echo $count_acknowledged_orders; ?>)
			</div>
		</div>

		<div class="col-xs-2">
			<div class="each_order_type_div each_order_type_div" state="shipped">
				Shipped Orders (<?php echo $count_shipped_orders; ?>)
			</div>
		</div>

		<div class="col-xs-2">
			<div class="each_order_type_div each_order_type_div" state="delivered">
				Delivered Orders (<?php echo $count_delivered_orders; ?>)
			</div>
		</div>

		<div class="col-xs-2">
			<div class="each_order_type_div each_order_type_div" state="cancel_closed">
				Cancelled/Closed Orders (<?php echo $count_cancelled_orders; ?>)
			</div>
		</div>
	</div>
</div>



<?php
	if(isset($_POST) && isset($_POST['header_clicked_state']))
	{
		$temp_header_clicked_state = $_POST['header_clicked_state'];
		?>
			<script>
			$(document).ready(function(){
				$(".order_type_div").hide();

				state_clicked('<?php echo $temp_header_clicked_state; ?>');
			});
			</script>
		<?php
	}
?>


</body>
</html>