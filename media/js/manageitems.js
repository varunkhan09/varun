var this_page_url = window.location.href;
var root_path = document.domain;
var value_parent = "";
var value_child_add = "";
var value_child_sub_category_add = "";
var value_child_disable = "";

//var root_path = "http://192.168.1.137/pratmagento/panel/fast/vendorpanel/fromserver";
//var root_path = "http://varun.floshowers.com:8882";

if(this_page_url.indexOf("manageitems.php") >= 0)
{
	$(document).ready(function(){
		$(document).on('click', '#add_new_item_b', function(){
			CloseAllPanels();
			$("#add_new_item_div").show();
		});

		$(document).on('click', '#disable_item_b', function(){
			CloseAllPanels();
			$("#disable_item_div").show();
		});

		$("#add_new_item_category_dd").change(function(){
			value_parent = $("#add_new_item_category_dd").val();
			if(value_parent != "")
			{
				$.ajax({
					url:"/app/admin/manageitems_backend.php",
					type:"POST",
					data:
					{
						mode:"load_sub_category",
						parent:value_parent
					},
					success:function(message)
					{
						if(message.indexOf("-1") < 0)
						{
							$("#add_new_item_sub_category_dd").html(message);
						}
						else
						{
							if(message == "0")
							{
								$("#result_div").show();
								$("#result_label").html("This category doesn't have any sub-categories.");
							}
							else
							{
								$("#result_div").show();
								$("#result_label").html("Failed to load sub-categories.");
							}
						}
					}
				});
				$("#add_new_item_operation_div").show();
			}
		});

		$("#disable_item_category_dd").change(function(){
			value_parent = $("#disable_item_category_dd").val();
			if(value_parent != "")
			{
				$("html").css("cursor", "wait");
				$("#disable_item_select_item_dd").html("");
				$.ajax({
					type:"POST",
					url:"/app/admin/manageitems_backend.php",
					data:
					{
						mode:'load_items',
						value_parent:value_parent
					},
					success:function(message)
					{
						$("html").css("cursor", "default");
						if(message.indexOf("-1") < 0)
						{
							$("#disable_item_select_item_dd").html("<option value=''>Select</option>");
							$("#disable_item_select_item_dd").append(message);
						}
						else
						{
							$("#result_label").html("Some error occurred. Item could not be loaded.");
						}
					}
				});
				$("#disable_item_operation_div").show();
			}
		});

		$(document).on('click', '#add_new_item_add_item_b', function(){
			$("#added_items_div").show();
			value_child_add = $("#add_new_item_new_item_t").val();
			value_child_sub_category_add = $("#add_new_item_sub_category_dd").val();
			if(value_child_add != "")
			{
				$.ajax({
					type:"POST",
					url:"/app/admin/manageitems_backend.php",
					data:
					{
						mode:'add',
						value_parent:value_parent,
						value_child_sub_category_add:value_child_sub_category_add,
						value_child_add:value_child_add
					},
					success:function(message)
					{
						CloseAllPanels();
						$("#result_div").show();
						if(message.indexOf("+1") >= 0)
						{
							$("#result_label").html("Item Added.");
							$("#added_items_div").append(", "+value_child_add);
						}
						else
						{
							$("#result_label").html("Some error occurred. Item could not be added.");
						}
					}
				});
			}
			else
			{
				alert("Enter Item Name before adding.");
			}
		});

		$(document).on('click', '#disable_item_disable_item_b', function(){
			$("#disabled_items_div").show();
			value_child_disable = $("#disable_item_select_item_dd").val();
			value_child_disable_text = $("#disable_item_select_item_dd option:selected").text();
			if(value_child_disable != "")
			{
				$.ajax({
					type:"POST",
					url:"/app/admin/manageitems_backend.php",
					data:
					{
						mode:'disable',
						value_child_disable:value_child_disable
					},
					success:function(message)
					{
						CloseAllPanels();
						$("#result_div").show();
						if(message.indexOf("+1") >= 0)
						{
							$("#result_label").html("Item Disabled.");
							$("#disabled_items_div").append(", "+value_child_disable_text);
						}
						else
						{
							$("#result_label").html("Some error occurred. Item could not be disabled.");
						}
					}
				});
			}
			else
			{
				alert("Select an Item before disabling.");
			}
		});
	});
}

function CloseAllPanels()
{
	$("#add_new_item_div").hide();
	$("#add_new_item_operation_div").hide();
	$("#add_new_item_category_dd").val('');
	$("#add_new_item_new_item_t").val('');


	$("#disable_item_div").hide();
	$("#disable_item_operation_div").hide();
	$("#disable_item_select_item_dd").val('');
	$("#disable_item_category_dd").val('');


	$("#result_div").hide();
	$("#result_label").html("");
}
