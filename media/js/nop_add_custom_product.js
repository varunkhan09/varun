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

//alert(root_path+"/app/module/stock/add_stock_backend.php");

//var root_path = "http://192.168.1.137/pratmagento/panel/fast/vendorpanel/fromserver";
//var root_path = "http://varun.floshowers.com:8882";

if(this_page_url.indexOf("nop_add_custom_product_front.php") >= 0)
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
				if(message.indexOf("-1") < 0)
				{
					var message_json_decode = $.parseJSON(message);
					var counter=0;
					$.each(message_json_decode, function(key, value){
						item_id_array[value] = key;
						item_name_array[counter] = value;
						item_name_id_array[value] = key;
						counter++;
					});
					
					$("#item_name_typeahead_1").typeahead({
						name: 'items',
						local:item_name_array,
						limit: 10
					});
					
				}
				else
				{
					
				}
			}
		});
	});
	/* THIS CODE LOADS ALL THE ITEMS FROM DATABASE */

	$(document).ready(function(){
		$(document).on('typeahead:selected', '.item_name_t_class_add_stock', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			var selected_item_id = item_name_id_array[$('#item_name_typeahead_'+row_id).val()];
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
						if(message.indexOf("-1") < 0)
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

		$(document).on('click', '#add_more_items_b_add_stock', function(){
			current_row++;
			$("#items_table").append("<tr class='table_data_row' id='items_table_tr_"+current_row+"_add_stock'><td><input type='textbox' disabled='disabled' id='item_id_t_"+current_row+"_add_stock'></td><td><input type='text' id='item_name_typeahead_"+current_row+"' data-provide='typeahead' class='item_name_t_class_add_stock'></td><td><input type='textbox' id='item_quantity_"+current_row+"_add_stock'></td><td><label class='normal_black_1' id='current_stock_label_"+current_row+"_add_stock'></label></td><td><input type='button' id='remove_item_b_"+current_row+"_add_stock' class='remove_item_class_add_stock buttons' value='Remove Item'></td></tr>");
			$("#item_name_typeahead_"+current_row).typeahead({
				name: 'items',
				local:item_name_array,
				limit: 10
			});
		});

		$(document).on('click', '#add_stock_b_add_stock', function(){
			var local_counter = 1;
			var string = "";
			var temp_item_id = "";
			var temp_item_quantity = "";
			var temp_item_rate = "";
			for(local_counter=1; local_counter<=current_row; local_counter++)
			{
				if($("#item_name_typeahead_"+local_counter).val() == "")
				{
					$("#item_name_typeahead_"+local_counter).focus();
					
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
				if($("#vendor_description_textarea").val() == "")
				{
					$("#vendor_description_textarea").focus();

					$("#dialog-confirm-msg").html("Please fill in the Vendor Description.");
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
					//if($("#items_table_tr_"+local_counter).attr("disabled") != undefined)
					{
						temp_item_id = $("#item_id_t_"+local_counter+"_add_stock").val();
						temp_item_quantity = $("#item_quantity_"+local_counter+"_add_stock").val();
						
						console.log(temp_item_id);
						console.log(temp_item_quantity);
						string = string+temp_item_id+"|"+temp_item_quantity+"<>";
					}
				}
			}
			string = string.slice(0,-1);
			string = string.slice(0,-1);
			string = string + "^^";
			string = string + $("#vendor_description_textarea").val();

			CloseMySelf(string);
		});
	});
}


function CloseMySelf(data)
{
	try
	{
		window.opener.HandlePopupResult2(data);
	}
	catch (err)
	{

	}
	window.close();
	return false;
}