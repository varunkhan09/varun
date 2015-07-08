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

if(this_page_url.indexOf("myvendors.php") >= 0)
{
	$(document).ready(function(){
		$(document).on('click', '#myvendors_invite_b', function(){
			$("#myvendors_below_operations_div_id").show();
		});


		$(document).on('click', '#addvendor_add_b', function(){
			var temp_name = $("#addvendor_name_t").val();
			var temp_email = $("#addvendor_email_t").val();
			var temp_phone = $("#addvendor_phone_t").val();

			if(temp_name == "")
			{
				$("#addvendor_name_t").focus();
				alert("Fill name");
				return;
			}

			if(temp_email == "")
			{
				$("#addvendor_email_t").focus();
				alert("Fill email");
				return;
			}

			if(temp_phone == "")
			{
				$("#addvendor_phone_t").focus();
				alert("Fill phone");
				return;
			}

			$.ajax({
				type:"POST",
				url:"myvendors_backend.php",
				data:
				{
					mode:"invite_vendor",
					name:temp_name,
					email:temp_email,
					phone:temp_phone
				},

				success:function(message)
				{
					if(message.indexOf("-1|") > -1)
					{
						alert(message);
					}
					else
					{
						if(message.indexOf("-2|") > -1)
						{
							alert("Invite has already been sent.");
						}
						else
						{
							$("#addvendor_name_t").val("");
							$("#addvendor_email_t").val("");
							$("#addvendor_phone_t").val("");
							$("#myvendors_below_operations_div_id").hide();
							alert("Invite sent successfully.");
						}
					}
				}
			});
		});
	});
}