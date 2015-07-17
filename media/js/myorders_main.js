//$(document).ready(function(){
	vendor_id = $("#vendor_id_hidden").val();
	query = "select distinct orderid from vendor_processing where vendor_id="+vendor_id+" and (state is NULL or state = '')";
	range = 20;
//});

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
				query = "select distinct orderid from vendor_processing where vendor_id="+vendor_id;
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
				if(order_status != "4")
				{
					query += " and state="+order_status;
				}

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
			query = "select distinct orderid from vendor_processing where vendor_id="+vendor_id+" and orderid="+orderid;

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


/*
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
								


								/* THIS CODE RECORDS THIS OPERATION 
								$.ajax({
									type:"POST",
									url:"vp_recordoperations.php",
									data:
									{
										orderid:orderid,
										comment_type:"0"
									}
								});
								/* THIS CODE RECORDS THIS OPERATION 
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
		
		//alert(orderid);
	});
});
*/

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








/*
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
								/* THIS CODE RECORDS THIS OPERATION *
								$.ajax({
									type:"POST",
									url:"vp_recordoperations.php",
									data:
									{
										orderid:order_id_hidden,
										comment_type:"2"
									}
								});
								/* THIS CODE RECORDS THIS OPERATION *


								/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS *
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
								/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS *


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
										/* THIS CODE RECORDS THIS OPERATION *
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

										/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS *
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
										/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS *


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
												/* THIS CODE RECORDS THIS OPERATION *
												$.ajax({
													type:"POST",
													url:"vp_recordoperations.php",
													data:
													{
														orderid:order_id_hidden,
														comment_type:"4"
													}
												});
												/* THIS CODE RECORDS THIS OPERATION *

												/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS *
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
												/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS *
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
*/