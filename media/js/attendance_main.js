var this_page_url = window.location.href;

var current_row = 1;

if(this_page_url.indexOf("attendance_main.php") >= 0)
{
	//$(function(){$(".datepicker").datepicker({autoclose:true});});

	$(function(){$(".item_name_t_class").timepicker();});

	$(document).ready(function(){
		$(document).on('click', '.remove_item_class', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			$("#items_table_tr_"+row_id).remove();
		});




		$(document).on('click', '#add_more_items_b', function(){
			current_row++;
			$("#items_table").append("<tr class='table_data_row' id='items_table_tr_"+current_row+"'><td><input type='date' id='input_date_t_"+current_row+"' style='height:31px;' class='datepicker' value='' placeholder='Select Date'></td><td><input type='text' id='in_time_"+current_row+"' class='item_name_t_class'></td><td><input type='text' id='out_time_"+current_row+"' class='item_name_t_class'></td><td><input type='button' id='remove_item_b_"+current_row+"' class='remove_item_class buttons' value='Remove'></td></tr>");
			$(function(){$(".item_name_t_class").timepicker();});
		});




		$(document).on('click', '#save_attendance_b', function(){
			var local_counter = 1;
			var string = "";
			
			var temp_date = '';
			var temp_in_time = '';
			var temp_out_time = '';
			var temp_employee_id = '';

			if($("#employee_dd").val() == "")
			{
				$("#employee_dd").focus();
				$("#dialog-confirm-msg").html("Select an employee.");
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
					return;
			}
			else
			{
				temp_employee_id = $("#employee_dd").val();
			}

			for(local_counter=1; local_counter<=current_row; local_counter++)
			{
				if($("#input_date_t_"+local_counter).val() == "")
				{
					$("#input_date_t_"+local_counter).focus();
					
					$("#dialog-confirm-msg").html("Select a Date.");
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

					return false;
				}
				if($("#in_time_"+local_counter).val() == "")
				{
					$("#in_time_"+local_counter).focus();

					$("#dialog-confirm-msg").html("Fill in the IN TIME.");
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

					return false;
				}
				if($("#out_time_"+local_counter).val() == "")
				{
					$("#out_time_"+local_counter).focus();

					$("#dialog-confirm-msg").html("Fill in the OUT TIME.");
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

					return false;
				}



				if($("#items_table_tr_"+local_counter).html() != undefined)
				{
					if($("#items_table_tr_"+local_counter).attr("disabled") == undefined)
					{
						temp_date = $("#input_date_t_"+local_counter).val();
						temp_in_time = $("#in_time_"+local_counter).val();
						temp_out_time = $("#out_time_"+local_counter).val();

						string = string + temp_date+"|"+temp_in_time+"-"+temp_out_time+"<>";
					}
				}
			}
			string = string.slice(0,-1);
			string = string.slice(0,-1);


			$.ajax({
				type:"POST",
				url:"/app/module/attendance/attendance_main_backend.php",
				data:
				{
					mode:"add_attendance",
					employee_id:temp_employee_id,
					attendance_data:string
				},
				success:function(message)
				{
					if(message.indexOf("+1|")>-1)
					{
						$("#dialog-confirm-msg").html("Attendance Details are saved.");
							$("#dialog-confirm-msg").dialog({
								resizable: false,
								modal: true,
								title:"Save Successful",
								height: 175,
								width: 400,
								buttons: {
								"OK": function()
								{
									$(this).dialog('close');
								}
							}
						});
						for(local_counter=1; local_counter<=current_row; local_counter++)
						{
							$("#items_table_tr_"+local_counter).attr("disabled", "disabled");
							$("#items_table_tr_"+local_counter+" *").attr("disabled", "disabled");
							$("#items_table_tr_"+local_counter).css("opacity", "0.6");
						}
					}
					else
					{
						$("#dialog-confirm-msg").html("Could not add Attendance Details.");
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
				}
			});
		});

		$(document).on('click', '#calender_icon', function(){
			$("#input_date_t").focus();
		});
	});
}