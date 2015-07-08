var this_page_url = window.location.href;
var root_path = document.domain;

if(this_page_url.indexOf("stock_report.php") >= 0)
{
	$(function(){$("#stock_report_date1_id").datepicker({autoclose:true})});

	$(function(){$("#stock_report_date2_id").datepicker({autoclose:true})});

	$(document).ready(function(){
		$(document).on('click', '#stock_report_generate_report_button_id', function(){
			var start_date = $("#stock_report_date1_id").val();
			var end_date = $("#stock_report_date2_id").val();

			$.ajax({
				type:"POST",
				url:"/app/module/stock/stock_report_backend.php",
				data:
				{
					start_date:start_date,
					end_date:end_date
				},
				success:function(message)
				{
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