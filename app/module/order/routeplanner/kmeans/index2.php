<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
?>


<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=yes">
	<title>K-Means Clustering Demo</title>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>

	<style>
		.clusters a 
		{
			text-decoration: none;
			display: inline-block;
			padding: 4px;
			outline: 0;
			color: #3a599d;
			transition-duration: 0.25s;
			transition-property: transform;
			transform: scale(1) rotate(0);
		}

		.clusters a:hover
		{
			text-decoration: none;
			border-radius: 4px;
		}

		.clusters a:nth-child(2n):hover
		{

		}
	</style>
<body>

<form action="setdata.php" method="POST" id="pincode-lat-lng-form" style="display:none;">
	<table>
		<tr>
			<td>Enter Pin codes with comma separate and no space:</td>
			<td>
				<textarea type="text" rows="2" cols="100" name="pincoeds" id="pincodes"><?php echo $_POST['delivery_pincodes']; ?></textarea>
			</td>
		</tr>

		<tr>
			<td>Enter No. Of Clusters:</td>
			<td>
				<input type="text" name="cluster_size" id="cluster_size" value="<?php echo $_POST['number_of_delivery_boys']; ?>">
				<input type="hidden" name="order_pincode_mapper" id="order_pincode_mapper" value="<?php echo $_POST['order_pincode_mapper']; ?>">
			</td>
		</tr>

		<tr>
			<td>
				<input type="submit" id="pllf-submit-btn" value="submit" />
			</td>
		</tr>
	</table>
</form>
<hr>

<canvas id="canvas" width="500" height="500" style="display:none;"></canvas>
<div class="getcluster-btn" style="position:absolute; top:60%; left:2%; width:25%;z-index:200;color:#bb1515;background-color:#eee;"></div>

<div class="clusters" style="width:30%;float:left; margin-top:62px;"></div>
<div id="map-canvas" style="min-height:600px;width:69%;float:left; margin-top:62px;"></div>


<script>
	var global_icon_base = "<?php echo $base_media_image_url; ?>/routeplanner_icons/black";
</script>

<script src="<?php echo $base_media_js_url; ?>/routeplanner_kmeans.js"></script>


<script type="text/javascript">
	$(document).ready(function(){
		$('#pincode-lat-lng-form').submit(function(e){
			$("body").css("cursor", "wait");
			e.preventDefault();
			var cluster_size = $('#cluster_size').val()
			console.log(cluster_size);
			var formData = {
			'pincodes':$('#pincodes').val(),
			'pincode_order_mapper':$("#order_pincode_mapper").val()
			}
			$.ajax({
				type     : 'POST',
				url    : 'setdata.php',
				data     : formData,
				datatype : 'json',
				encode   : true,
			})
			.success(function(data){
				$("body").css("cursor", "default");
				$('#pllf-submit-btn').attr('disabled','disabled');
				$('.getcluster-btn').html(data);
				setTimeout(function(){
				setup(cluster_size);
				},1000)
			})
		})
	})


	var order_pincode_mapper_final_array = {};
	$(document).ready(function(){
		var order_pincode_mapper_main = $("#order_pincode_mapper").val();
		var order_pincode_mapper_main_array = order_pincode_mapper_main.split(":");
		$.each(order_pincode_mapper_main_array, function(key_main, value_main){
			//alert(value_main);
			order_pincode_mapper_main_array_2 = value_main.split("-");
			pincode_as_index = order_pincode_mapper_main_array_2[0];
			pincode_as_index = parseInt(pincode_as_index);
			orders_under_this_pincode = order_pincode_mapper_main_array_2[1];
			orders_array_under_this_pincode = orders_under_this_pincode.split(",");
			//$.each(orders_array_under_this_pincode, function(key_main_2, value_main_2){
			//order_pincode_mapper_final_array.splice(pincode_as_index, 0, orders_array_under_this_pincode);
			order_pincode_mapper_final_array[pincode_as_index] = orders_array_under_this_pincode;
			//});
		});

		console.log(order_pincode_mapper_final_array);

		//alert(order_pincode_mapper_main);
		$("#pllf-submit-btn").trigger('click');
	});
</script>

</body>
</html>
