var this_page_url = window.location.href;
var root_path = document.domain;

var limit = 50;
var start = 1;

if(this_page_url.indexOf("products_items_configuration.php") >= 0)
{
	$(document).ready(function(){
		$("#fixed_loading_div").show();
		$.ajax({
			type:"POST",
			url:"/app/admin/products_items_configuration_backend.php",
			data:
			{
				start:start,
				limit:limit
			},
			success:function(message)
			{
				$("#fixed_loading_div").hide();
				$("#product_items_configuration_result_div").html(message);
			}
		});


		$(document).on('click', '#pagination_prev_ul', function(){
			start--;
			$("#fixed_loading_div").show();
			$.ajax({
				type:"POST",
				url:"/app/admin/products_items_configuration_backend.php",
				data:
				{
					start:start,
					limit:limit
				},
				success:function(message)
				{
					$("#fixed_loading_div").hide();
					$("#product_items_configuration_result_div").html(message);
				}
			});
		});



		$(document).on('click', '#pagination_next_ul', function(){
			start++;
			$("#fixed_loading_div").show();
			$.ajax({
				type:"POST",
				url:"/app/admin/products_items_configuration_backend.php",
				data:
				{
					start:start,
					limit:limit
				},
				success:function(message)
				{
					$("#fixed_loading_div").hide();
					$("#product_items_configuration_result_div").html(message);
				}
			});
		});


		$(document).on('keydown', '#pagination_page_ul', function(e){
			var keycode = e.which;
			if(keycode == 13)
			{
				$("#fixed_loading_div").show();
				var page_number_entered = $("#pagination_page_ul").val();
				$.ajax({
					type:"POST",
					url:"/app/admin/products_items_configuration_backend.php",
					data:
					{
						start:page_number_entered,
						limit:limit
					},
					success:function(message)
					{
						$("#fixed_loading_div").hide();
						$("#product_items_configuration_result_div").html(message);
					}
				});
			}
		});


		$(document).on('click', '.edit_button_class', function(){
			var this_button_id_main = $(this).attr("id");
			var this_button_id_array = this_button_id_main.split("_");
			var this_button_id = this_button_id_array[2];
			var this_product_id = $("#product_id_"+this_button_id).val();

			var popup_object = window.open("products_items_configuration_popup_front.php?product_id="+this_product_id, 'newwindow', 'width=1100, height=600');
		});
	});
}


