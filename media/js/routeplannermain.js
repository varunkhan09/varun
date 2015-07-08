var this_page_url = window.location.href;

if(this_page_url.indexOf("routeplanner/kmeans/") >= 0)
{
	$(function(){$("#dod_t").datepicker({autoclose:true});});

	$(document).ready(function(){
		$(document).on('click', '#routeplanner_submit_b', function(){
			temp_dod = $("#dod_t").val();
			temp_no_of_delivery_boys = $("#no_of_delivery_boys_t").val();

			if(temp_dod == "")
			{
				$("#dod_t").focus();
				return;
			}

			if(temp_no_of_delivery_boys == "")
			{
				$("#no_of_delivery_boys_t").focus();
				return;
			}

			$.ajax({
				type:"POST",
				url:"index_back.php",
				data:
				{
					temp_dod:temp_dod
				},
				success:function(message)
				{
					setInterval(1000);
					if(message.indexOf("-1|") > -1)
					{
						alert("Some unexpected error occurred.");
					}
					else
					{
						if(message.indexOf("-2|") > -1)
						{
							alert("There are no Orders to be delivered today.");
						}
						else
						{
							if(message.indexOf('success|') > -1)
							{
								message = message.replace('\n\n', '');
								temp_message_array = message.split("|");
								temp_delivery_pincodes_set = temp_message_array[1];
								html_inside_form = "<input type='hidden' name='delivery_pincodes' value='"+temp_delivery_pincodes_set+"'><input type='hidden' name='number_of_delivery_boys' value='"+temp_no_of_delivery_boys+"'><input type='hidden' name='order_pincode_mapper' value='"+temp_message_array[2]+"'>";
								$("#submit_this_form").html(html_inside_form);
								$("#submit_this_form").submit();
							}
							else
							{
								alert("Some unexpected error occurred.");
							}
						}
					}
				}
			});

			
		});
	});
}