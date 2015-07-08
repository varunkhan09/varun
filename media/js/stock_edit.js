var this_page_url = window.location.href;

var original_added_item_data = "";
var original_wasted_item_data = "";
var original_deduced_item_data = "";
var temp_date = "";

if(this_page_url.indexOf("stock_edit.php") >= 0)
{
	$(document).ready(function(){
		$(function(){$(".datepicker").datepicker({autoclose:true})});


		$(document).on('click', '#load_stock_details_b', function(){
			temp_date = $("#input_date_t").val();

			if(temp_date == "")
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
							$("#input_date_t").focus();
						}
					}
				});
				return;
			}

			$.ajax({
				type:"POST",
				url:"stock_edit_backend.php",
				data:
				{
					mode:'load_details',
					date:temp_date
				},
				success:function(message)
				{
					if(message.indexOf("-1|") > -1)
					{
						$("#dialog-confirm-msg").html("Could not load results.");
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
						$("#stock_details_div").html(message);
						console.log("Added Item Original : "+original_added_item_data);
						console.log("Wasted Item Original : "+original_wasted_item_data);
						console.log("Deduced Item Original : "+original_deduced_item_data);
					}
				}
			});
		});


		$(document).on('click', '#save_stock_details_b', function(){
			var temp_edited_added_item_form = $("#edited_added_item_form").serialize();
			var temp_edited_wasted_item_form = $("#edited_wasted_item_form").serialize();
			var temp_edited_deduced_item_form = $("#edited_deduced_item_form").serialize();

			temp_edited_added_item_form = temp_edited_added_item_form.replace(/&/g, ',');
			temp_edited_wasted_item_form = temp_edited_wasted_item_form.replace(/&/g, ',');
			temp_edited_deduced_item_form = temp_edited_deduced_item_form.replace(/&/g, ',');

			console.log("Added Item : "+temp_edited_added_item_form);
			console.log("Wasted Item : "+temp_edited_wasted_item_form);
			console.log("Deduced Item : "+temp_edited_deduced_item_form);

			$.ajax({
				type:"POST",
				url:"stock_edit_backend.php",
				data:
				{
					mode:'update_details',
					date:temp_date,

					original_added_item_data:original_added_item_data,
					edited_added_item_log:temp_edited_added_item_form,

					original_wasted_item_data:original_wasted_item_data,
					edited_wasted_item_data:temp_edited_wasted_item_form,

					original_deduced_item_data:original_deduced_item_data,
					edited_deduced_item_data:temp_edited_deduced_item_form
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