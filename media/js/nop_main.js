var this_page_url = window.location.href;
var root_path = document.domain;
root_path = "http://"+root_path+":8882";
var add_new_item_count = 2;
var current_item_count = 1;
var current_delivery_charges = 0;
var current_price_adjustment = 0;
var item_name_array = [];
var product_entry_handler_array = [];
var custom_product_items_details_array = [];

//var root_path = "http://192.168.1.137/pratmagento/panel/fast/vendorpanel/fromserver";
//var root_path = "http://varun.floshowers.com:8882";

if(this_page_url.indexOf("nop_main.php") >= 0)
{

	$(function(){
		$("#delivery_details_date_id").datepicker({
			autoclose:true
		});
		if($('#all_users').val() != undefined)
		{
			var availableTags = $('#all_users').val().split('<>');
			//console.log(availableTags);
			$( '#search_sender_t').typeahead({
				name: 'serch_sender',
				local: availableTags,
				limit:10
			});
		}
	});

	$(document).ready(function(){
		$(document).on('click', '#delivery_details_time_1_dd', function(){
			var value= $('#delivery_details_time_1_dd').val();

			if(value == "Specified")
			{
				$('#delivery_details_time_2_dd').prop('disabled', false);
				$('#delivery_details_time_3_dd').prop('disabled', false);
			}
			else
			{
				$('#delivery_details_time_2_dd').prop('disabled', true);
				$('#delivery_details_time_3_dd').prop('disabled', true);
			}
		});

		$(document).on('click', '.classification_dd', function(){
			var button_id = $(this).attr('id');
			var starter = button_id.split('_');
			var value= $('#classification_dd_'+starter[2]).val();

			$('#catalog_button_'+starter[2]).val("View "+value+" Catalog");
		});

		$(document).on('click', '.catalog_button', function(){
			var button_id = $(this).attr('id');
			var starter = button_id.split('_');
			var value= $('#catalog_button_'+starter[2]).val();
			value = value.replace("View ", "");
			value = value.replace(" Catalog", "");
			var varun = window.open('nop_popup_front.php?cat='+value, 'newwindow', 'width=750, height=800');
			current_item_count = starter[2];
		});

		$(document).on('focusout', '.quantity_t', function(){
			var button_id = $(this).attr('id');
			var starter = button_id.split('_');
			var value1 = $('#price_t_'+starter[2]).val();
			var value2 = $('#quantity_t_'+starter[2]).val();
			var value3 = value1*value2;

			$('#total_price_t_'+starter[2]).val(value3);

			var x=1;
			var sum = 0;
			var temp = '';
			for(x=1; x<=add_new_item_count-1; x++)
			{
				if($('#total_price_t_'+x).val() != undefined)
				{
					temp = $('#total_price_t_'+x).val();
					sum += parseInt(temp);
				}
			}
			sum += current_delivery_charges;
			$('#grand_total_t').val(sum);
		});

		$(document).on('focusout', '.price_t', function(){
			var button_id = $(this).attr('id');
			var starter = button_id.split('_');
			var value1 = $('#price_t_'+starter[2]).val();
			var value2 = $('#quantity_t_'+starter[2]).val();
			var value3 = value1*value2;

			$('#total_price_t_'+starter[2]).val(value3);

			var x=1;
			var sum = 0;
			var temp = '';
			for(x=1; x<=add_new_item_count-1; x++)
			{
				if($('#total_price_t_'+x).val() != undefined)
				{
					temp = $('#total_price_t_'+x).val();
					sum += parseInt(temp);
				}
			}
			sum += current_delivery_charges;
			$('#grand_total_t').val(sum);
		});

		$(document).on('focusout', '#price_adjustment_t', function(){
			var temp_price_adjustment = $("#price_adjustment_t").val();
			var temp_grand_total = $("#grand_total_t").val();

			temp_price_adjustment = parseInt(temp_price_adjustment);
			temp_grand_total = parseInt(temp_grand_total);

			temp_grand_total = temp_grand_total - current_price_adjustment;
			temp_grand_total = temp_grand_total + temp_price_adjustment;
			current_price_adjustment = temp_price_adjustment;
			$("#grand_total_t").val(temp_grand_total);
		});

		$(document).on('click', '#add_new_item_b', function(){
			var varun = $('#classification_dd_1').html();

			$('#merchandise_table').append("<tr id='product_selector_row_"+add_new_item_count+"' class='hoverable product_selector_row_class'><td colspan='2'><input type='button' id='product_selector_custom_"+add_new_item_count+"' class='buttons product_selector_custom_class' value='Create Custom Product'></td><td colspan='1'><input type='button' id='product_selector_catalog_"+add_new_item_count+"' class='buttons product_selector_catalog_class' value='Choose from Catalog'></td><td colspan='3'></td></tr><tr id='product_row_"+add_new_item_count+"' class='hoverable product_row_class'><td class='thistd'><input type='textbox' id='product_name_"+add_new_item_count+"' name='product_name_"+add_new_item_count+"' style='width:100%;'></td><td class='thistd'><textarea id='product_description_textarea_"+add_new_item_count+"' name='product_description_textarea_"+add_new_item_count+"' class='product_description_textarea' style='width:100%; height:90px;' required></textarea></td><td class='thistd'><input type='number' id='quantity_t_"+add_new_item_count+"' name='quantity_t_"+add_new_item_count+"' class='quantity_t' style='width:60px;' min='0' value='1' required></td><td class='thistd'><input type='number' id='price_t_"+add_new_item_count+"' name='price_t_"+add_new_item_count+"' class='price_t' style='width:60px;' min='0' value='0' required></td><td class='thistd'><input type='number' id='total_price_t_"+add_new_item_count+"' name='total_price_t_"+add_new_item_count+"' class='total_price_t' style='width:60px;' min='0' value='0' disabled='disabled'><input type='hidden' id='product_id_"+add_new_item_count+"' name='product_id_"+add_new_item_count+"'  value='0'></td><td class='thistd'><input type='button' value='Remove' id='remove_product_"+add_new_item_count+"' class='remove_c thisbuttonfont buttons'></td></tr>");

			$('#number_of_products_in_order').val(add_new_item_count);
			add_new_item_count++;


			$("#product_item_entry_"+add_new_item_count).typeahead({
				name: 'items',
				local:item_name_array,
				limit: 10
			});

			product_entry_handler_array[add_new_item_count] = $("#product_item_backtext_"+add_new_item_count).tagsManager();
		});

		$(document).on('click', '#submit_b', function(){
			var employee_dd = $('#employee_dd').val();
			var order_type_dd = $('#ordertype_dd').val();
		});

		$(document).on('click', '#delivery_type_dd', function(){
			var current_grand_total = $('#grand_total_t').val();
			var varun = parseInt(current_grand_total);
			var varun2 = varun - current_delivery_charges;
			var varun3 = $('#delivery_type_dd').val();
			var varun3_array = varun3.split('_');
			var varun4 = parseInt(varun3_array[1]);
			current_delivery_charges = varun4;
			var varun5 = varun2 + current_delivery_charges;
			$('#grand_total_t').val(varun5);
		});

		$(document).on('click', '.remove_c', function(){
			var button_id = $(this).attr('id');
			var starter = button_id.split('_');
			var grand_total_value = $('#total_price_t_'+starter[2]).val();
			var grand_total_value2 = $('#grand_total_t').val();
			var temp = 0;
			
			$('#product_row_'+starter[2]).remove();
			$('#product_selector_row_'+starter[2]).remove();


			if(grand_total_value != undefined)
			{
				temp = grand_total_value2-grand_total_value;
				$('#grand_total_t').val(temp);
			}
		});

		$(document).on('click', '#search_sender_b', function(){
			var value = $('#search_sender_t').val();
			var value1 = value.split('(');
			var value2 = value1[1].split(')');
			$.ajax({
			type:"POST",
			url:"/app/module/createorder/nop_load_customer_details.php",
			data:
			{
				'email':value2[0],
				'flag':'1'
			},
			success:function(message)
			{
				if(message != "-1" || message.length != "0" || message != "")
				{
					var data = message.split('|');
					$('#sender_first_name_t').val(data[0]);
					$('#sender_last_name_t').val(data[1]);
					$('#sender_phone_t').val(data[2]);
					$('#sender_address_line_1_t').val(data[3]);
					$('#sender_city_t').val(data[4]);
					$('#sender_state_dd').val(data[5]);
					$('#sender_zip_t').val(data[6]);
					$('#sender_email').val(data[7]);
				}
				else
				{
					alert("This user has no saved address.");
				}
			}
			});
		});

		$(document).on('click', '#ordertype_dd', function(){
			var varun = $('#ordertype_dd').val();
			$.ajax({
				type:"POST",
				url:"/app/module/createorder/nop_load_customer_details.php",
				data:
				{
					'company_shop_id':varun,
					'flag':'2'
				},
				success:function(message)
				{
					if(message != "-1" || message.length != "0")
					{
						var data = message.split('|');
						$('#sender_first_name_t').val(data[0]);
						$('#sender_last_name_t').val(data[0]);
						$('#sender_phone_t').val(data[2]);
						$('#sender_company_t').val(data[0]);
						$('#sender_address_line_1_t').val(data[3]);
						//$('#sender_city_t').val(data[4]);
						//$('#sender_state_dd').val(data[6]);
						$('#sender_zip_t').val(data[5]);
						$('#sender_email').val(data[1]);
					}
					else
					{
						alert("This user has no saved address.");
					}
				}
			});
		});


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
						item_name_array[counter] = value+"-";
						counter++;
					});
					//console.log(item_name_array);
					$(".product_tags_textbox_class").tagsinput({
						typeahead:
						{
							local:item_name_array
						}
					});
				}
			}
		});










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
						item_name_array[counter] = value+"-";
						counter++;
					});

					$(".product_item_entry_class").typeahead({
						name: 'items',
						local:item_name_array,
						limit: 10
					});
				}
			}
		});




		$(document).on('keypress', '.product_item_entry_class', function(e) {
			if(e.which == 13) 
			{
				var this_product_item_entry_id = $(this).attr("id");
				var this_row_number_array = this_product_item_entry_id.split("_");
				var this_row_number = this_row_number_array[3];

				var value=$("#product_item_entry_"+this_row_number).val();
				var array_value = value.split("-");
				var prefix = array_value[0]+"-";
				var suffix = array_value[1];

				var flag_prefix = (item_name_array.indexOf(prefix) > -1) ? 1:0;
				var flag_suffix_numeric = ($.isNumeric(suffix)) ? 1:0;
				var flag_suffix_wholenumber = (Math.floor(suffix) == suffix) ? 1:0;


				if(flag_prefix == 1)
				{
					if(flag_suffix_numeric == 1)
					{
						if(flag_suffix_wholenumber == 1)
						{
							product_entry_handler_array[this_row_number].tagsManager("pushTag", value);
							$("#product_item_entry_"+this_row_number).val("");
						}
						else
						{
							alert("Invalid Tag. Please enter a number.");
						}
					}
					else
					{
						alert("Invalid Tag. Please enter a number.");
					}
				}
				else
				{
					alert("Invalid Tag. Please use items suggested by the list.");
				}
			}
		});


		product_entry_handler_array[1] = $("#product_item_backtext_1").tagsManager();




		






		$(document).on('click', '.product_selector_custom_class', function(){
			var this_button_id_main = $(this).attr("id");
			var this_button_id_array = this_button_id_main.split("_");
			var this_button_id = this_button_id_array[3];

			var varun = window.open('nop_add_custom_product_front.php', 'newwindow', 'width=1100, height=600');
			current_item_count = this_button_id;
		});




		$(document).on('click', '.product_selector_catalog_class', function(){
			var this_button_id_main = $(this).attr("id");
			var this_button_id_array = this_button_id_main.split("_");
			var this_button_id = this_button_id_array[3];

			var varun = window.open('nop_popup_front.php?cat=Full', 'newwindow', 'width=750, height=800');
			current_item_count = this_button_id;
		});





		$(document).on('click', '#forward_order_b', function(){
			var selected_vendor = $("#forward_order_dd").val();
			var vendorName = "";
			var created_order_id = "";
			var product_description = "";
			var special_instructions = "";
			var delivery_type = "";
			var number_of_products = $("#product_total_number").val();
			number_of_products = parseInt(number_of_products);

			if(selected_vendor != "")
			{
				created_order_id = $("#created_order_id_hidden").val();
				vendorName = $("#forward_order_dd option:selected").text();


				special_instructions = $("#order_special_instructions").val();

				for(x=1; x<=number_of_products; x++)
				{
					product_description += $("#product_description_"+x+"_hidden").val()+"<hr>";
				}

				if($("#order_delivery_type").val().indexOf("Regular") >= 0)
				{
					delivery_type = "Daytime";
				}
				else
				{
					if($("#order_delivery_type").val().indexOf("Midnight") >= 0)
					{
						delivery_type = "Midnight";
					}
				}

				$.fancybox({
					'href'              : 'vp_emailContent.php?order_id='+created_order_id+'&vendor_shop_id='+selected_vendor,//+'&vendorName='+vendorName+'&productDesc='+product_description+'&specialInst='+special_instructions+'&dilType'+delivery_type+'&productType=',
					'width'             : '70%',
					'height'            : '70%',
					'autoScale'         : false,
					'transitionIn'      : 'none',
					'transitionOut'     : 'none',
					'type'              : 'iframe',

					afterClose: function()
					{
						$("#forward_order_b").attr("disabled", "disabled");
					}
				});
			}
			else
			{
				alert("Select a vendor to forward this order.");
			}
		});
	});








































/*
	$(document).on('click', '#forward_order_b', function(){
			var selected_vendor = $("#forward_order_dd").val();
			var vendorName = "";
			var created_order_id = "";
			var product_description = "";
			var special_instructions = "";
			var delivery_type = "";
			var number_of_products = $("#product_total_number").val();
			number_of_products = parseInt(number_of_products);

			if(selected_vendor != "")
			{
				created_order_id = $("#created_order_id_hidden").val();
				
				/*
				$.ajax({
					type:"POST",
					url:"nop_fetchemaildetails.php",
					data:
					{
						mode:"vendornamefromid",
						selected_vendor:selected_vendor
					},
					success:function(message)
					{
						vendorName = message;
					}
				});
				*
				vendorName = $("#forward_order_dd option:selected").text();


				special_instructions = $("#order_special_instructions").val();

				for(x=1; x<=number_of_products; x++)
				{
					product_description += $("#product_description_"+x+"_hidden").val()+"<hr>";
				}

				if($("#order_delivery_type").val().indexOf("Regular") >= 0)
				{
					delivery_type = "Daytime";
				}
				else
				{
					if($("#order_delivery_type").val().indexOf("Midnight") >= 0)
					{
						delivery_type = "Midnight";
					}
				}

				$.fancybox({
					'href'              : 'nop_emailContent.php?vendorCan_orderId='+vendorName+'_'+created_order_id,//+'&vendorName='+vendorName+'&productDesc='+product_description+'&specialInst='+special_instructions+'&dilType'+delivery_type+'&productType=',
					'width'             : '70%',
					'height'            : '70%',
					'autoScale'         : false,
					'transitionIn'      : 'none',
					'transitionOut'     : 'none',
					'type'              : 'iframe',

					afterClose: function()
					{
						alert("pop");
					}
				});


/*
				$.fancybox({
					'href'              : 'nop_SendEmail.php?orderId='+created_order_id+'&vendorName='+vendorName+'&productDesc='+product_description+'&specialInst='+special_instructions+'&dilType'+delivery_type+'&productType=',
					'width'             : '70%',
					'height'            : '70%',
					'autoScale'         : false,
					'transitionIn'      : 'none',
					'transitionOut'     : 'none',
					'type'              : 'iframe',

					afterClose: function()
					{
						alert("pop");
					}
				});
*
				/*
				$("#forward_form").html("");
				$("#forward_form").append("<input type='hidden' name='orderId' value='"+created_order_id+"'><input type='hidden' name='vendorName' value='"+vendorName+"'><input type='hidden' name='productDesc' value='"+product_description+"'><input type='hidden' name='specialInst' value='"+special_instructions+"'><input type='hidden' name='dilType' value='"+delivery_type+"'><input type='hidden' name='productType' value=''>");
				$("#forward_form").submit();
				*
			}
			else
			{
				alert("Select a vendor to forward this order.");
			}
		});
	});
*/


}

function HandlePopupResult(result)
{
	$.ajax({
		type:"POST",
		url:"/app/module/createorder/nop_fill_vendor_desc.php",
		data:
		{
			'product_id':result
		},
		success:function(message)
		{
			var starter = message.split('|');
			$('#product_description_textarea_'+current_item_count).prop('readonly', false);
			$('#product_description_textarea_'+current_item_count).val(starter[0]);
			$('#product_description_textarea_'+current_item_count).prop('readonly', true);
			$('#product_name_'+current_item_count).prop('readonly', true);
			$('#product_name_'+current_item_count).val(starter[2]);
			$('#price_t_'+current_item_count).val(starter[1]);
			$('#price_t_'+current_item_count).prop('readonly', true);
			$('#product_id_'+current_item_count).val(result);

			$('#total_price_t_'+current_item_count).val($('#quantity_t_'+current_item_count).val()*$('#price_t_'+current_item_count).val());
			
			var x=1;
			var sum = 0;
			var temp = '';
			for(x=1; x<=add_new_item_count-1; x++)
			{
				if($('#total_price_t_'+x).val() != undefined)
				{
					temp = $('#total_price_t_'+x).val();
					sum += parseInt(temp);
				}
			}
			sum += current_delivery_charges + current_price_adjustment;
			$('#grand_total_t').val(sum);

			$("#product_selector_row_"+current_item_count).hide();
			$("#product_row_"+current_item_count).show();
		}
	});
}




function HandlePopupResult2(result)
{
	var value_returned = result;

	var value_returned_array = value_returned.split("^^");

	//var items_details_array_level1 = items_details_main.split("<>");
	//console.log(items_details_array_level1);
	//var items_details_array_level2 = "";
	//var x=0, y=0;
	//console.log(items_details_array_level1.length);
	
	//for(x=0; x<items_details_array_level1.length; x++)
	//{
		//console.log(items_details_array_level1[x]);
		//items_details_array_level2 = items_details_array_level1[x].split("|");
		//console.log(items_details_array_level2);
		//custom_product_items_details_array[current_item_count][items_details_array_level2[0]] = items_details_array_level2[1];
	//}
	//console.log(custom_product_items_details_array);

	$("#main_form").append("<input type='hidden' name='custom_product_item_details_"+current_item_count+"' id='custom_product_item_details_"+current_item_count+"' value='"+value_returned_array[0]+"'>");

	$("#product_description_textarea_"+current_item_count).val(value_returned_array[1]);
	$("#product_name_"+current_item_count).val("Custom Product");
	$("#product_name_"+current_item_count).prop("readonly", true);

	$("#product_selector_row_"+current_item_count).hide();
	$("#product_row_"+current_item_count).show();
}