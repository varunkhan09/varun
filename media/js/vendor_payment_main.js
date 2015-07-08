var this_page_url = window.location.href;
var root_path = document.domain;

if(this_page_url.indexOf("vendor_payment_main.php") >= 0)
{
	$(function(){$("#account_main_date1_id").datepicker({autoclose:true})});

	$(function(){$("#account_main_date2_id").datepicker({autoclose:true})});

	$(document).ready(function(){
		$(document).on('click', '#account_main_generate_report_button_id', function(){
			var start_date = $("#account_main_date1_id").val();
			var end_date = $("#account_main_date2_id").val();
			var report_mode = $("#report_mode_dd").val();
			var selected_vendor = $("#vendor_dd").val();

			if(start_date == "")
			{
				//alert("Select Start Date.");
				$("#dialog-confirm-msg").html("Select Start Date.");
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
							$("#account_main_date1_id").focus();
						}
					}
				});
				return;
			}

			if(end_date == "")
			{
				//alert("Select End Date.");
				$("#dialog-confirm-msg").html("Select End Date.");
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
							$("#account_main_date2_id").focus();
						}
					}
				});
				return;
			}

			if(selected_vendor == "")
			{
				//alert("Select a Vendor.");
				$("#dialog-confirm-msg").html("Select a Vendor.");
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
							$("#vendor_dd").focus();
						}
					}
				});
				return;
			}

			$("body").css("cursor", "pointer");
			$.ajax({
				type:"POST",
				url:"/app/module/account/vendor_payment_main_backend.php",
				data:
				{
					start_date:start_date,
					end_date:end_date,
					report_mode:report_mode,
					selected_vendor:selected_vendor
				},
				success:function(message)
				{
					$("body").css("cursor", "default");
					if(message.indexOf("-1|") >= 0)
					{
						$("#result_div").html("Could not load result.");
					}
					else
					{
						$("#result_div").html(message);
					}
				}
			});
		});
	});
}