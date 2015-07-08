var this_page_url = window.location.href;

if(this_page_url.indexOf("email_configuration.php") >= 0)
{
	$(document).ready(function(){
		$(document).on('click', '#order_forward_save_b', function(){
			var temp_email_id = $("#order_forward_email_t").val();
			var temp_email_pass = $("#order_forward_pass_t").val();
			var temp_new_user_flag = $("#new_entry_flag").val();

			if(temp_email_id == "")
			{
				$("#dialog-confirm-msg").html("Enter an Email ID.");
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
							$("#order_forward_email_t").focus();
						}
					}
				});
				return;
			}

			if(temp_email_pass == "")
			{
				$("#dialog-confirm-msg").html("Enter Password.");
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
							$("#order_forward_pass_t").focus();
						}
					}
				});
				return;
			}


			$.ajax({
				url:"email_configuration_backend.php",
				type:"POST",
				data:
				{
					mode:'save',
					email:temp_email_id,
					pass:temp_email_pass,
					new_user:temp_new_user_flag
				},
				success:function(message)
				{
					if(message.indexOf("+1|") > -1)
					{
						$("#dialog-confirm-msg").html("Details saved successfully.");
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
					else
					{
						if(message.indexOf("-1|") > -1)
						{
							$("#dialog-confirm-msg").html("Failed to save the details.");
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
							$("#dialog-confirm-msg").html("Failed to save the details.");
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
				}
			});
		});
	});
}