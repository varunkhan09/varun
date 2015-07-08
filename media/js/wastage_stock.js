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

//var root_path = "http://192.168.1.137/pratmagento/panel/fast/vendorpanel/fromserver";
//var root_path = "http://varun.floshowers.com:8882";

if(this_page_url.indexOf("wastage_stock.php") >= 0)
{
	$(function(){$(".datepicker").datepicker({autoclose:true});});

	/* THIS CODE LOADS ALL THE ITEMS FROM DATABASE */
	$(function(){
		$.ajax({
			type:"POST",
			url:"/app/module/stock/wastage_stock_backend.php",
			data:
			{
				mode:"load_all_products"
			},
			success:function(message)
			{
				if(message.indexOf("-1|") < 0)
				{
					$("#item_name_dd_1").html("<option value=''>Select</option>");
					var message_json_decode = $.parseJSON(message);
					var counter=0;
					$.each(message_json_decode, function(key, value){
						item_id_array[value] = key;
						item_name_array[counter] = value;
						item_name_id_array[value] = key;
						counter++;
						$("#item_name_dd_1").append("<option value='"+key+"'>"+value+"</option>");
					});
					item_name_dd_html = $("#item_name_dd_1").html();

					//$("#item_name_dd_1").addClass("selectpicker");
					//$("#item_name_dd_1").attr("data-live-search", "true");

					$(function(){
						$("#item_name_t").autocomplete({
							source:item_name_array
						});
					});

					$("#item_name_t2_1").typeahead({
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

	$(document).ready(function(){
		$(document).on('typeahead:selected', '.item_name_t_class', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			var selected_item_id = item_name_id_array[$('#item_name_t2_'+row_id).val()];
			var item_selected = selected_item_id;
			if(item_selected != "")
			{
				$("#item_id_t_"+row_id).val(item_selected);
				$.ajax({
					type:"POST",
					url:"/app/module/stock/wastage_stock_backend.php",
					data:
					{
						mode:"current_stock",
						item_selected:item_selected
					},
					success:function(message)
					{
						if(message != "-1|")
						{
							$("#current_stock_label_"+row_id).html(message);
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
				$("#item_id_t_"+row_id).val("");
				$("#current_stock_label_"+row_id).html("");
			}
		});

		$(document).on('change', '.item_name_dd_class', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			var item_selected = $("#item_name_dd_"+row_id).val();
			if(item_selected != "")
			{
				$("#item_id_t_"+row_id).val(item_selected);
				$.ajax({
					type:"POST",
					url:"/app/module/stock/wastage_stock_backend.php",
					data:
					{
						mode:"current_stock",
						item_selected:item_selected
					},
					success:function(message)
					{
						if(message.indexOf("-1|") < 0)
						{
							$("#current_stock_label_"+row_id).html(message);
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
				$("#item_id_t_"+row_id).val("");
				$("#current_stock_label_"+row_id).html("");
			}
		});

		$(document).on('click', '.remove_item_class', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			$("#items_table_tr_"+row_id).remove();
		});

		$(document).on('click', '#add_more_items_b', function(){
			current_row++;
			$("#items_table").append("<tr class='table_data_row' id='items_table_tr_"+current_row+"'><td><input type='textbox' disabled='disabled' id='item_id_t_"+current_row+"'></td><td><input type='text' id='item_name_t2_"+current_row+"' data-provide='typeahead' class='item_name_t_class'></td><td><input type='textbox' id='item_quantity_"+current_row+"'></td><td><label class='normal_black_1' id='current_stock_label_"+current_row+"'></label></td><td><input type='button' id='remove_item_b_"+current_row+"' class='remove_item_class buttons' value='Remove Item'></td></tr>");
			$("#item_name_t2_"+current_row).typeahead({
				name: 'items',
				local:item_name_array,
				limit: 10
			});
		});

		$(document).on('click', '#add_stock_b', function(){
			var local_counter = 1;
			var string = "";
			var add_stock_array = [];
			var temp_item_id = "";
			var temp_item_quantity = "";
			var temp_item_rate_text = "";
			var temp_item_rate = "";
			for(local_counter=1; local_counter<=current_row; local_counter++)
			{
				if($("#item_name_t2_"+local_counter).val() == "")
				{
					$("#item_name_t2_"+local_counter).focus();
					
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
				if($("#item_quantity_"+local_counter).val() == "")
				{
					$("#item_quantity_"+local_counter).focus();

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



				if($("#items_table_tr_"+local_counter).html() != undefined)
				{
					if($("#items_table_tr_"+local_counter).attr("disabled") == undefined)
					{
						temp_item_id = $("#item_id_t_"+local_counter).val();
						temp_item_quantity = $("#item_quantity_"+local_counter).val();
						string = string+temp_item_id+"|"+temp_item_quantity+"<>";
					}
				}
			}
			string = string.slice(0,-1);
			string = string.slice(0,-1);

			add_stock_date = $("#input_date_t").val();
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
				url:"/app/module/stock/wastage_stock_backend.php",
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
						$("#dialog-confirm-msg").html("Could not add wastage stock.");
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
			$("#input_date_t").focus();
		});
	});
}