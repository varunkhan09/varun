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

if(this_page_url.indexOf("products_items_configuration_popup_front.php") >= 0)
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
		$(document).on('typeahead:selected', '.item_name_t_class', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			var selected_item_id = item_name_id_array[$('#item_name_typeahead_'+row_id).val()];
			var item_selected = selected_item_id;
			if(item_selected != "")
			{
				$("#item_id_t_"+row_id).val(item_selected);
			}
			else
			{
				$("#item_id_t_"+row_id).val("");
			}
		});



		$(document).on('click', '.product_image_class', function(){
			var image_url = $(this).attr("src");
			$.fancybox({
				'href'			: image_url,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'			: 'iframe'
			});
		});



		$(document).on('click', '.remove_item_class', function(){
			var item_selected = $(this).attr("id");
			var item_selected_array = item_selected.split("_");
			var row_id = item_selected_array[3];
			$("#items_table_tr_"+row_id).remove();
		});

		$(document).on('click', '#add_more_items_b', function(){
			current_row++;
			$("#items_table").append("<tr class='table_data_row' id='items_table_tr_"+current_row+"'><td><input type='textbox' disabled='disabled' id='item_id_t_"+current_row+"'></td><td><input type='text' id='item_name_typeahead_"+current_row+"' data-provide='typeahead' class='item_name_t_class'></td><td><input type='textbox' id='item_quantity_"+current_row+"'></td><td><input type='button' id='remove_item_b_"+current_row+"' class='remove_item_class buttons' value='Remove Item'></td></tr>");
			$("#item_name_typeahead_"+current_row).typeahead({
				name: 'items',
				local:item_name_array,
				limit: 10
			});
		});

		$(document).on('click', '#add_stock_b', function(){
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
					//if($("#items_table_tr_"+local_counter).attr("disabled") != undefined)
					{
						temp_item_id = $("#item_id_t_"+local_counter).val();
						temp_item_quantity = $("#item_quantity_"+local_counter).val();
						
						console.log(temp_item_id);
						console.log(temp_item_quantity);
						string = string+temp_item_id+"|"+temp_item_quantity+"<>";
					}
				}
			}
			string = string.slice(0,-1);
			string = string.slice(0,-1);

			$.ajax({
				type:"POST",
				url:"/app/admin/products_items_configuration_popup_back.php",
				data:
				{
					product_id:$("#product_id").val(),
					string:string
				},
				success:function(message)
				{
					if(message.indexOf("+1|") > -1)
					{
						$("#dialog-confirm-msg").html("Product Item Details saved.");
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
									window.close();
									return false;
								}
							}
						});
					}
					else
					{
						$("#dialog-confirm-msg").html("Product Item Details could not be saved.");
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
	});
}
