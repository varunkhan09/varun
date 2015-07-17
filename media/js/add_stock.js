var this_page_url = window.location.href;
var root_path = document.domain;
root_path = "http://"+root_path+":8882";
var item_id_array = [];
var item_name_array = [];
var item_rate_name_array = [];
var item_name_id_array = [];
var item_rate_to_unitscale_conversion_array = [];

var current_row = 1;
var item_name_dd_html = "";
var item_rate_dd_html = "";
var original_add_stock_serialized = "";
var add_stock_date = "";

if(this_page_url.indexOf("add_stock.php") >= 0)
{
	/* THIS CODE LOADS ALL THE ITEMS FROM DATABASE */
	$(function(){
		$.ajax({
			type:"POST",
			url:"/app/module/stock/add_stock_backend.php",
			data:
			{
				mode:"load_all_products"
			},
			success:function(message)
			{
				if(message.indexOf("-1|") < 0)
				{
					$("#item_rate_dd_1_add_stock").html("<option value=''>Select</option>");
					var message_json_decode = $.parseJSON(message);
					var counter=0;
					$.each(message_json_decode, function(key, value){
						item_id_array[value] = key;
						item_name_array[counter] = value;
						item_name_id_array[value] = key;
						counter++;
						$("#item_rate_dd_1_add_stock").append("<option value='"+key+"'>"+value+"</option>");
					});
					item_name_dd_html = $("#item_rate_dd_1_add_stock").html();

					//$("#item_name_dd_1").addClass("selectpicker");
					//$("#item_name_dd_1").attr("data-live-search", "true");

					$("#item_name_t2_1_add_stock").typeahead({
						name: 'items',
						local:item_name_array,
						//['Audi', 'BMW', 'Bugatti', 'Ferrari', 'Ford', 'Lamborghini', 'Mercedes Benz', 'Porsche', 'Rolls-Royce', 'Volkswagen'],
						limit: 10
					});
					
				}
				else
				{
					$("#dialog-confirm-msg").html("Could not load items.");
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
	/* THIS CODE LOADS ALL THE ITEMS FROM DATABASE */


	/* THIS CODE LOADS ALL THE RATES FROM DATABASE */
	$(function(){
		$.ajax({
			type:"POST",
			url:"/app/module/stock/add_stock_backend.php",
			data:
			{
				mode:"load_all_rates"
			},
			success:function(message)
			{
				if(message.indexOf("-1|") < 0)
				{
					$("#item_rate_dd_1_add_stock").html("<option value=''>Select Rate Type</option>");
					var message_json_decode = $.parseJSON(message);
					var counter=0;
					$.each(message_json_decode, function(key, value)
					{
						item_rate_to_unitscale_conversion_array[value] = key;
						item_rate_name_array[counter] = value;
						counter++;
						$("#item_rate_dd_1_add_stock").append("<option value='"+key+"'>"+value+"</option>");
					});
					item_rate_dd_html = $("#item_rate_dd_1_add_stock").html();
				}
				else
				{
					$("#dialog-confirm-msg").html("Could not load rates.");
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
	/* THIS CODE LOADS ALL THE RATES FROM DATABASE */


	$(function(){$(".datepicker").datepicker({autoclose:true})});

	$(document).ready(function(){
		$(document).on('typeahead:selected', '.item_name_t_class_add_stock', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			var selected_item_id = item_name_id_array[$('#item_name_t2_'+row_id+"_add_stock").val()];
			var item_selected = selected_item_id;
			if(item_selected != "")
			{
				$("#item_id_t_"+row_id+"_add_stock").val(item_selected);
				$.ajax({
					type:"POST",
					url:"/app/module/stock/add_stock_backend.php",
					data:
					{
						mode:"current_stock",
						item_selected:item_selected
					},
					success:function(message)
					{
						if(message.indexOf("-1|") < 0)
						{
							$("#current_stock_label_"+row_id+"_add_stock").html(message);
						}
						else
						{
							$("#dialog-confirm-msg").html("Could not load item's current stock.");
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
			}
			else
			{
				$("#item_id_t_"+row_id+"_add_stock").val("");
				$("#current_stock_label_"+row_id+"_add_stock").html("");
			}
		});


		$(document).on('click', '.remove_item_class_add_stock', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			$("#items_table_tr_"+row_id+"_add_stock").remove();
		});


		$(document).on('change', '.item_rate_dd_class_add_stock', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			var temp_value = $("#item_rate_dd_"+row_id+"_add_stock option:selected").text();
			if(temp_value != "")
			{
				$("#item_rate_value_"+row_id+"_add_stock").attr("placeholder", "Price/"+temp_value);
			}
		});


		$(document).on('click', '#add_more_items_b_add_stock', function(){
			current_row++;
			$("#items_table").append("<tr class='table_data_row' id='items_table_tr_"+current_row+"_add_stock'><td><input type='textbox' disabled='disabled' id='item_id_t_"+current_row+"_add_stock'></td><td><input type='text' id='item_name_t2_"+current_row+"_add_stock' data-provide='typeahead' class='item_name_t_class_add_stock'></td><td><input type='textbox' id='item_quantity_"+current_row+"_add_stock'></td><td><select class='form-control' id='item_rate_dd_"+current_row+"_add_stock'>"+item_rate_dd_html+"</select><br><input type='textbox' class='form-control item_rate_value_class_add_stock' id='item_rate_value_"+current_row+"_add_stock' placeholder='Price/Unit Rate'></td><td><label class='normal_black_1' id='current_stock_label_"+current_row+"_add_stock'></label></td><td><input type='button' id='remove_item_b_"+current_row+"_add_stock' class='remove_item_class_add_stock buttons' value='Remove Item'></td></tr>");
			$("#item_name_t2_"+current_row+"_add_stock").typeahead({
				name: 'items',
				local:item_name_array,
				//['Audi', 'BMW', 'Bugatti', 'Ferrari', 'Ford', 'Lamborghini', 'Mercedes Benz', 'Porsche', 'Rolls-Royce', 'Volkswagen'],
				limit: 10
			});
		});


		$(document).on('click', '#add_stock_b_add_stock', function(){
			var local_counter = 1;
			var string = "";
			var add_stock_array = [];
			var temp_item_id = "";
			var temp_item_quantity = "";
			var temp_item_rate_text = "";
			var temp_item_rate = "";
			var temp_rate_value = "";
			for(local_counter=1; local_counter<=current_row; local_counter++)
			{
				if($("#item_name_t2_"+local_counter+"_add_stock").val() == "")
				{
					$("#item_name_t2_"+local_counter+"_add_stock").focus();
					
					$("#dialog-confirm-msg").html("Please select a Product first.");
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
				if($("#item_quantity_"+local_counter+"_add_stock").val() == "")
				{
					$("#item_quantity_"+local_counter+"_add_stock").focus();

					$("#dialog-confirm-msg").html("Please fill in the Quantity.");
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
				if($("#item_rate_dd_"+local_counter+"_add_stock").val() == "")
				{
					$("#item_rate_dd_"+local_counter+"_add_stock").focus();

					$("#dialog-confirm-msg").html("Please fill in the Rate.");
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
				if($("#item_rate_value_"+local_counter+"_add_stock").val() == "")
				{
					$("#item_rate_value_"+local_counter+"_add_stock").focus();
					$("#dialog-confirm-msg").html("Please fill in the Rate Value.");
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
				if($("#items_table_tr_"+local_counter+"_add_stock").html() != undefined)
				{
					if($("#items_table_tr_"+local_counter+"_add_stock").attr("disabled") == undefined)
					{
						temp_item_id = $("#item_id_t_"+local_counter+"_add_stock").val();
						temp_item_quantity = $("#item_quantity_"+local_counter+"_add_stock").val();
						temp_item_rate = $("#item_rate_dd_"+local_counter+"_add_stock").val();
						temp_item_rate_text = $("#item_rate_dd_"+local_counter+"_add_stock"+" option:selected").text();
						temp_rate_value = $("#item_rate_value_"+local_counter+"_add_stock").val();
						console.log(temp_item_id);
						console.log(temp_item_quantity);
						console.log(temp_item_rate);
						console.log(temp_item_rate_text);
						string = string+temp_item_id+"|"+temp_item_quantity+"|"+temp_item_rate+"|"+temp_item_rate_text+"|"+temp_rate_value+"<>";
					}
					else
					{
						
					}
				}
			}
			string = string.slice(0,-1);
			string = string.slice(0,-1);

			add_stock_date = $("#input_date_t_add_stock").val();
			if(add_stock_date == "")
			{
				input_date = "none";
			}
			else
			{
				input_date = add_stock_date;
			}
			
			$.ajax({
				type:"POST",
				url:"/app/module/stock/add_stock_backend.php",
				data:
				{
					mode:"add_stock",
					item_data:string,
					input_date:input_date
				},
				success:function(message)
				{
					if(message.indexOf("+1|")>-1)
					{
						$("#dialog-confirm-msg").html("Item(s) stock is saved.");
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
									$("#dialog_form_varun").dialog();
								}
							}
						});

						for(local_counter=1; local_counter<=current_row; local_counter++)
						{
							$("#items_table_tr_"+local_counter+"_add_stock").attr("disabled", "disabled");
							$("#items_table_tr_"+local_counter+"_add_stock *").attr("disabled", "disabled");
							$("#items_table_tr_"+local_counter+"_add_stock").css("opacity", "0.6");
						}
					}
					else
					{
						$("#dialog-confirm-msg").html("Could not add stock.");
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
			//console.log(string);
			//alert(string);
		});


		$(document).on('click', '#calender_icon', function(){
			$("#input_date_t_add_stock").focus();
		});


		$(document).on('click', '#travelling_form_b', function(){
			var travelling_charges = $("#travelling_form_t").val();
			if(travelling_charges != "")
			{
				add_stock_date = $("#input_date_t_add_stock").val();
				if(add_stock_date == "")
				{
					input_date = "none";
				}
				else
				{
					input_date = add_stock_date;
				}

				$.ajax({
					type:"POST",
					url:"/app/module/stock/add_stock_backend.php",
					data:
					{
						mode:"add_travelling_charges",
						charges:travelling_charges,
						input_date:input_date
					},
					success:function(message)
					{
						if(message.indexOf("-1|") < 0)
						{
							$("#dialog_form_varun").dialog("close");
							window.location = "/app/module/stock/stock_report.php";
						}
						else
						{

						}
					}
				});
			}
		});


		$(document).on('click', '#load_stock_details_b', function(){
			add_stock_date = $("#input_date_t_add_stock").val();

			if(add_stock_date == "")
			{
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
				return;
			}

			$.ajax({
				type:"POST",
				url:"/app/module/stock/add_stock_backend.php",
				data:
				{
					mode:"load_date_items_details",
					date:add_stock_date
				},

				success:function(message)
				{
					if(message.indexOf("-1|") > -1)
					{
						$("#dialog-confirm-msg").html("Could not load item details.");
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
							$("#dialog-confirm-msg").html("There is no addition data for this date.");
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
							$("#load_item_details_div").html(message);
							$("#load_item_details_div").show();
							console.log(original_add_stock_serialized);
						}
					}
				}
			});
		});


		$(document).on('click', '#update_add_stock_b', function(){
			var updated_add_stock_serialized = $("#edited_added_item_form").serialize();
			updated_add_stock_serialized = updated_add_stock_serialized.replace(/&/g, ",");
			console.log(updated_add_stock_serialized);

			$.ajax({
				type:"POST",
				url:"stock_edit_backend.php",
				data:
				{
					mode:'update_details',
					type_of:"add_stock",
					date:add_stock_date,
					original_added_item_data:original_add_stock_serialized,
					edited_added_item_log:updated_add_stock_serialized
				},

				success:function(message)
				{
					if(message.indexOf("-1|") > -1)
					{
						$("#dialog-confirm-msg").html("Could not update data.");
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
						if(message.indexOf("+1|") > -1)
						{
							$("#dialog-confirm-msg").html("Successfully updated the data.");
								$("#dialog-confirm-msg").dialog({
									resizable: false,
									modal: true,
									title:"Success",
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
				}
			});
		});
	});
}
