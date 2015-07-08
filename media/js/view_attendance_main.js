var this_page_url = window.location.href;

var current_row = 1;

if(this_page_url.indexOf("view_attendance_main.php") >= 0)
{
	$(function(){$(".datepicker").datepicker({autoclose:true});});

	//$(function(){$(".item_name_t_class").timepicker();});


	$(document).ready(function(){
		$(document).on('click', '#load_attendance_b', function(){
			var temp_employee = $("#employee_dd").val();
			//var start_date = $("#start_date_t").val();
			//var end_date = $("#end_date_t").val();
			var month = $("#month_dd").val();
			var shift_hours = $("#shift_hours_t").val();

/*
			if(start_date == "")
			{				
				$("#dialog-confirm-msg").html("Select a Start Date.");
					$("#dialog-confirm-msg").dialog({
						resizable: false,
						modal: true,
						title:"Error",
						height: 175,
						width: 400,
						buttons: {
						"OK": function()
						{
							$(this).dialog('close');
							$("#start_date_t").focus();
						}
					}
				});
				return false;
			}

			if(end_date == "")
			{				
				$("#dialog-confirm-msg").html("Select an End Date.");
					$("#dialog-confirm-msg").dialog({
						resizable: false,
						modal: true,
						title:"Error",
						height: 175,
						width: 400,
						buttons: {
						"OK": function()
						{
							$(this).dialog('close');
							$("#end_date_t").focus();
						}
					}
				});
				return false;
			}
*/

			if(month == "")
			{				
				$("#dialog-confirm-msg").html("Select a Month.");
					$("#dialog-confirm-msg").dialog({
						resizable: false,
						modal: true,
						title:"Error",
						height: 175,
						width: 400,
						buttons: {
						"OK": function()
						{
							$(this).dialog('close');
							$("#month_dd").focus();
						}
					}
				});
				return false;
			}

			if(temp_employee == "")
			{				
				$("#dialog-confirm-msg").html("Select an Employee.");
					$("#dialog-confirm-msg").dialog({
						resizable: false,
						modal: true,
						title:"Error",
						height: 175,
						width: 400,
						buttons: {
						"OK": function()
						{
							$(this).dialog('close');
							$("#employee_dd").focus();
						}
					}
				});
				return false;
			}

			if(shift_hours == "")
			{
				$("#dialog-confirm-msg").html("Select Shift Hours.");
					$("#dialog-confirm-msg").dialog({
						resizable: false,
						modal: true,
						title:"Error",
						height: 175,
						width: 400,
						buttons: {
						"OK": function()
						{
							$(this).dialog('close');
							$("#shift_hours_t").focus();
						}
					}
				});
				return false;
			}

			$("html").css("cursor", "wait");
			$("#load_results_div").html("");
			$.ajax({
				method:"POST",
				url:"view_attendance_main_backend.php",
				data:
				{
					mode:"load_attendance",
					//start:start_date,
					//end:end_date,
					month:month,
					employee:temp_employee,
					shift_hours:shift_hours
				},
				success:function(message)
				{
					$("html").css("cursor", "default");
					if(message.indexOf("-1|") > -1)
					{
						$("#dialog-confirm-msg").html("Failed to Load Results.");
							$("#dialog-confirm-msg").dialog({
								resizable: false,
								modal: true,
								title:"Error",
								height: 175,
								width: 400,
								buttons: {
								"OK": function()
								{
									$(this).dialog('close');
								}
							}
						});
					}
					else
					{
						if(message.indexOf("-2|") > -1)
						{
							$("#dialog-confirm-msg").html("No data for given dates.");
								$("#dialog-confirm-msg").dialog({
									resizable: false,
									modal: true,
									title:"Error",
									height: 175,
									width: 400,
									buttons: {
									"OK": function()
									{
										$(this).dialog('close');
									}
								}
							});
						}
						else
						{
							$("#load_results_div").html(message);
						}
					}
				}
			});
		});
	});
}