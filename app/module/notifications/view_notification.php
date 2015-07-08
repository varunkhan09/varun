<div class="blue_notification_bar" id="notification_board">
	<div id="notification_label" style="text-align:center; overflow:auto;"><label style="font-family: Open Sans; color:white; font-size:16px;" id="notification_label2">Notifications</label></div>
	<div id="notifications_div_main" style="background-color:white; height:270px; width:898px; border-right:2px solid #009ACD; overflow:auto;">
		<div id="notifications_div" style="width:100%; height:240px; overflow:auto;"></div>
		<div id="load_more_div" style="width:100%; height:auto; overflow:auto; text-align:center;"><input type="button" id="load_more_b" class="load_more_c" value="Load More"></div>
	</div>

</div>

<style>
	.blue_notification_bar
	{
		z-index:1;
		width:200px;
		position:fixed;
		height:30px;
		bottom:-8px;
		left:0px;
		background-color:#009ACD;
		border-top-right-radius: 20;
	}

	.red_notification_bar
	{
		width:200px;
		position:fixed;
		height:30px;
		bottom:-8px;
		left:0px;
		background-color:#BB1515;
		border-top-right-radius: 20;
	}

	.each_notification
	{
		display:block;
		font-size:16px;
		font-family: 'Raleway', sans-serif;
		overflow:auto;
		word-wrap: break-word;
		margin-bottom:5px;
	}

	.each_notification:hover
	{
		background-color:rgba(6, 6, 6, 0.1);
	}

	.each_new_notification
	{
		display:block;
		font-size:16px;
		font-family: 'Raleway', sans-serif;
		overflow:auto;
		word-wrap: break-word;
		margin-bottom:5px;
		background-color:#FFEFD5;
	}

	.each_new_notification:hover
	{
		background-color:#8B7E66;
	}

	.notification_date
	{
		display:block;
		text-align:center;
		width:100%;
		font-size:16px;
		font-family: Open Sans;
		border-bottom:2px solid #009ACD;
		padding-top:10px;
		border-top:2px solid #009ACD;
	}

	#notification_label:hover, #notification_label2:hover
	{
		cursor:pointer;
		background-color:#00688B;
		border-top-right-radius: 20;
	}

	.load_more_c
	{
		margin-left: 2%;
		border-radius: 6px;
		background-color: #00688B;
		color: white;
		font-weight: bold;
		height: 30px;
		border: 0;
		font-family: 'Raleway';
	}

	.load_more_c:hover
	{
		cursor:pointer;
		background-color:#00688B;
	}
</style>

<script>

var start = 0;
var limit = 20;
var loaded_count = limit;
var load_more_count = limit;
var notifications_id_array = [];

	$(document).ready(function(){
		$("#notification_label, #notification_label2").click(function(){
			$("#notification_board").addClass("blue_notification_bar");
			$("#notification_board").removeClass("red_notification_bar");
			$("#notification_label2").html("Notifications");

			var height = $("#notification_board").css("height");

			if(height == "30px")
			{
				$("#notification_board").animate({height:'300px'}, 200);
				$("#notification_board").animate({width:'900px'}, 200);
				//$("#notification_control").animate({bottom:'300px'}, 200);
			}
			else
			{
				if(height == "300px")
				{
					$("#notification_board").animate({height:'30px'}, 200);
					$("#notification_board").animate({width:'200px'}, 200);

					var content = $("#notifications_div").html();
					content = content.replace(/ \(new\)/g, "");
					content = content.replace(/each_new_notification/g, "each_notification");
					$("#notifications_div").html(content);


					//$("#notification_control").animate({bottom:'100px'}, 200);
				}
				/*
				if(height == "0px")
				{
					$("#notification_board").animate({height:'100px'}, 200);
					$("#notification_control").animate({bottom:'100px'}, 200);
				}
				*/
			}

			var parsed_into_json = JSON.stringify(notifications_id_array);
			$.ajax({
				type:"POST",
				url:"<?php echo $base_module_path; ?>/notifications/load_notification.php",
				data:
				{
					first:'mark_read',
					notification_ids:parsed_into_json
				},
				success:function(message)
				{
					if(message.indexOf("-1|") > -1)
					{
						alert("Some Error Occurred while Marking these Notifications.");
					}
					else
					{
						if(message.indexOf("+1|") > -1)
						{
							loaded_count = limit;
							console.log(notifications_id_array);
							notifications_id_array = [];
							console.log(notifications_id_array);
						}
						else
						{

						}
					}
				}
			});
		});
	});
/*
	$("document").ready(function(){
		$("#notification_board, #notification_label, #notification_label2").click(function(){
			var height = $("#notification_board").css("height");

			if(height == "300px")
			{
				$("#notification_board").animate({height:'32px'}, 200);
				//$("#notification_control").animate({bottom:'100px'}, 200);
			}
			else
			{
				if(height == "100px")
				{
					$("#notification_board").animate({height:'0px'}, 200);
					$("#notification_control").animate({bottom:'0px'}, 200);
				}
			}
		});
	});
*/

	$(document).ready(function(){
		$.ajax({
			type:"POST",
			url:"<?php echo $base_module_path; ?>/notifications/load_notification.php",
			data:
			{
				start:start,
				limit:limit,
				first:'yes'
			},
			success:function(message)
			{
				var notifications_array_main = $.parseJSON(message);
				var notifications_array = notifications_array_main["data"];
				if(notifications_array != undefined)
				{
					var x=0;
					//loaded_count = notifications_array.length;
					for(x=notifications_array.length-1; x>=0; x--)
					{
						$("#notifications_div").prepend("<div class='each_notification'>"+notifications_array[x]+"</div>");
					}
				}
			}
		});
	});


	$(document).ready(function(){
		$(document).on('click', '#load_more_b', function(){
			$.ajax({
				type:"POST",
				url:"<?php echo $base_module_path; ?>/notifications/load_notification.php",
				data:
				{
					start:load_more_count,
					limit:limit,
					first:'load_more'
				},
				success:function(message)
				{
					start = start+limit;
					var notifications_array_main = $.parseJSON(message);
					var notifications_array = notifications_array_main["data"];
					if(notifications_array != undefined)
					{
						load_more_count=load_more_count+limit;
						if(notifications_array.length != 0)
						{
							var x=0;
							for(x=0; x<=notifications_array.length-1; x++)
							{
								$("#notifications_div").append("<div class='each_notification'>"+notifications_array[x]+"</div>");
							}
						}
					}
					else
					{
						alert("No More Notifications to Load.");
					}
				}
			});
		});
	});


	$(document).ready(function(){
		setInterval(function()
		{
			$.ajax({
			type:"POST",
			url:"<?php echo $base_module_path; ?>/notifications/load_notification.php",
			data:
			{
				start:0,
				limit:limit,
				first:'no'
			},
			success:function(message)
			{
				var notifications_array_main = $.parseJSON(message);
				if(notifications_array_main.length != 0)
				{
					var notifications_array = notifications_array_main["data"];

					$.each(notifications_array_main["ids"], function(key, value){
						notifications_id_array.push(value);
					});					

					if(notifications_array != undefined)
					{
						if(limit == loaded_count){
							loaded_count = 0;
							new_notification_count = 0;
							new_notification = notifications_array.length;
						}
						else{
							new_notification = notifications_array.length - loaded_count ;
							new_notification_count = new_notification;
						}
						loaded_count+= new_notification;

						if(notifications_array.length != 0)
						{
							$("#notification_label2").html("Notifications");
							$("#notification_label2").append("&nbsp;&nbsp;("+loaded_count+" New)");
							$("#notification_board").addClass("red_notification_bar");
							$("#notification_board").removeClass("blue_notification_bar");

							var x=0;
							//for(x=0;x<notifications_array.length-new_notification_count;x++){
							//for(x=notifications_array.length-1; x>=notifications_array.length-new_notification_count-1; x--){
							for(x=0;x<notifications_array.length-new_notification_count;x++){
							
								$("#notifications_div").prepend("<div class='each_new_notification'>"+notifications_array[x]+" (new)</div>");
							}
						}

					}
				}
				else
				{

				}
			}
		});
		}, 10000);
	});
</script>
