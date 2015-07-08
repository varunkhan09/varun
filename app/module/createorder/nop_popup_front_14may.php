<?php
	//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/global_variables.php";
	include "/var/www/varun/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($magento_database);
?>


<html>
<head>
<link rel="stylesheet" href="<?php echo $base_media_css_url; ?>/jquery-ui.css">
<script src="<?php echo $base_media_js_url; ?>/jquery-1.10.2.js"></script>
<script src="<?php echo $base_media_js_url; ?>/jquery-ui.js"></script>
<script>

/*
$(function() {
	var availableTags = [];

	availableTags = $('#all_names').val().split('_');

    $( '#search_name_t').autocomplete({
      source: availableTags
	});
});

$(function() {
	var availableTags = [];

	availableTags = $('#all_skus').val().split('_');

    $( '#search_sku_t').autocomplete({
      source: availableTags
	});
});
*/

function CloseMySelf(sender)
{
    try
    {
    	window.opener.HandlePopupResult(sender.getAttribute("id"));
	}
	catch (err)
	{}
	window.close();
	return false;
}
</script>


<style>
	.pagination_div
	{
		float:left;
		position:fixed;
		bottom:0px;
		right: 0px;
		margin:0px 0px 5px 0px;
		text-align:center;
		width:auto;
	}

	.pagination_textbox
	{
		height:25px;
		width:35px;
	}

	.pagination_buttons
	{
		border-radius: 20px;
		font-size: 15px;
		background-color: #009ACD;
		padding: 0px;
		height: 25px;
		width: 25px;
		color: white;
		border: 2px solid #607B8B;
	}

	.pagination_buttons:hover
	{
		cursor:pointer;
	}
</style>
</head>



<body>
<?php
$cat = $_REQUEST['cat'];

echo "<center><table style='background-color:gray; color:white;'>";
echo "<tr>";
echo "<td><input type='textbox' id='search_name_t' name='search_name_t' placeholder='Search by Name'></td>";
echo "<td>OR</td>";
echo "<td><input type='textbox' id='search_sku_t' name='search_sku_t' placeholder='Search by SKU'></td>";
echo "<td><input type='button' id='search_b' value='Search'></td>";
echo "</tr>";
echo "</table></center>";

echo "<br><br>";

echo "<center><div id='product_loader_div' style='width:90%;'></div></center>";

?>
</body>

<script>
var limit = 10;
var start = 1;

$(document).ready(function(){
	$.ajax({
		type:"POST",
		url:"nop_popup_back.php",
		data:
		{
			limit:limit,
			start:start,
			category:'<?php echo $cat; ?>'
		},
		success:function(message)
		{
			if(message != "-1")
			{
				start++;
				$("#product_loader_div").html(message);
			}
			else
			{
				if(message == "-1")
				{
					$("#product_loader_div").html("Error loading Products.");
				}
			}
		}
	});
});


$(document).ready(function(){
	$(document).on('click', '.pagination_event1', function(){
		var event_value = $(this).attr('value');
		var max_page_number = $("#max_page_number").val();
		
		if(event_value == "<")
		{
			var button_number = $(".pagination_textbox").val();
			if(button_number == 1)
			{
				button_number = 1;
			}
			else
			{
				button_number = parseInt(button_number)-1;
			}
		}
		else
		{
			if(event_value == ">")
			{
				var button_number = $(".pagination_textbox").val();
				if(button_number == max_page_number)
				{
					button_number = max_page_number;
				}
				else
				{
					button_number = parseInt(button_number)+1;
				}
			}
			else
			{
				if(event_value > max_page_number)
				{
					//event_value = max_page_number;
				}
				var button_number = event_value;
			}
		}
		/* THE AJAX CALL */
		$.ajax({
			type:"POST",
			url:"nop_popup_back.php",
			data:
			{
				limit:limit,
				start:button_number,
				category:'<?php echo $cat; ?>'
			},
			success:function(message)
			{
				if(message != "-1")
				{
					start++;
					$("#product_loader_div").html(message);
				}
				else
				{
					if(message == "-1")
					{
						$("#product_loader_div").html("Error loading Products.");
					}
				}
			}
		});
		/* THE AJAX CALL */
	});
});





$(document).ready(function(){
	$(document).on('keydown', '.pagination_event2', function(e){
		var keycode = e.which;
		var max_page_number = $("#max_page_number").val();
		var button_number = $("#current_page_number").val();

		if(keycode == 13)
		{
			if(button_number > max_page_number)
			{
				button_number = max_page_number;
			}
			else
			{
				if(button_number < "1")
				{
					button_number = "1";
				}
			}

			/* THE AJAX CALL */
			$.ajax({
				type:"POST",
				url:"nop_popup_back.php",
				data:
				{
					limit:button_number,
					start:start,
					category:'<?php echo $cat; ?>'
				},
				success:function(message)
				{
					if(message != "-1")
					{
						start++;
						$("#product_loader_div").html(message);
					}
					else
					{
						if(message == "-1")
						{
							$("#product_loader_div").html("Error loading Products.");
						}
					}
				}
			});
			/* THE AJAX CALL */
		}
	});
});



$(document).ready(function(){
	$(document).on('click', '#search_b', function(){
		/* THE AJAX CALL */
			$.ajax({
				type:"POST",
				url:"nop_popup_back.php",
				data:
				{
					flag:'1',
					search_name_t:$("#search_name_t").val(),
					search_sku_t:$("#search_sku_t").val(),
					category:'<?php echo $cat; ?>'
				},
				success:function(message)
				{
					if(message != "-1")
					{
						start++;
						$("#product_loader_div").html(message);
					}
					else
					{
						if(message == "-1")
						{
							$("#product_loader_div").html("Error loading Products.");
						}
					}
				}
			});
			/* THE AJAX CALL */
	});
});

</script>
</html>
