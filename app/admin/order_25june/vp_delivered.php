<?php
	date_default_timezone_set("Asia/Kolkata");
	include 'vp_dbconfig.php';
	$orderid = $_REQUEST['orderid'];
?>
<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

	<style>
		.heading
		{
			 font-size:32px; color:#009ACD; font-weight:bold;
		}

		.data_labels_normal
		{
			font-family: Open Sans, sans-serif; font-size:15px;
		}

		.data_labels_bold
		{
			font-family: Open Sans, sans-serif; font-weight: bold; font-size:14px;
		}

		.heading_labels
		{
			font-size:16px; color:#00688B; font-family: Open Sans;
		}
		.main_buttons
		{
			margin:20px 0px 10px 0px; border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}
		.main_buttons:hover
		{
			background-color: #00688B; cursor:pointer;
		}
	</style>

	<script>
	function FillCurrentTime()
	{
		var currentTime = new Date();
		var currentHours = currentTime.getHours();
		var currentMinutes = currentTime.getMinutes();

		return currentHours+":"+currentMinutes;
	}
	</script>
</head>

<body>
<center>
<label class='heading'>Order Delivery Details</label>
</center>
<div id='main_div' style='float:left; display:inline; width:96%; margin:0% 0% 0% 2%; background-color:#F7F7F7; border:2px solid #00B2EE; border-radius: 4px; text-align:center;'>
<?php

if(!isset($_REQUEST['flag']))
{
?>	
	<div id='main_div1' style='float:left; display:inline; width:100%; margin:10px 0px 10px 0px;'>
		<label class='heading_labels'>Please fill the following details to mark the Order Delivered :</label>
	</div>
	<div id='main_div2'>
		<form method='POST' style='margin:0px; padding:0px;'>
			<label class='data_labels_normal'>Delivery Time</label><sup style='color:red; font-size:12px;'>*</sup> : <input type='time' name='delivery_time' id='delivery_time' required='required' value='<?php echo date("H:i", strtotime(date("H:i"))+19800); ?>'>
			<br>
			<label class='data_labels_normal'>Recipient Name</label><sup style='color:red; font-size:12px;'>*</sup> : <input type='textbox' name='delivery_name' id='delivery_name' required='required'>
			<br>
			<input type='hidden' name='flag' value='1'>
			<input type='hidden' name='orderid' value='<?php echo $orderid; ?>'>
			<input type='submit' value='Make Order Shipped' class='main_buttons'>
		</form>
	</div>
<?php
}
else
{
	if($_REQUEST['flag'] == "1")
	{
		$orderid = $_REQUEST['orderid'];
		$delivery_time = $_REQUEST['delivery_time'];
		$delivery_name = $_REQUEST['delivery_name'];

		$query = "update vendor_processing set delivery_time='$delivery_time', delivery_name='$delivery_name', state=3 where orderid=$orderid";
		$result = mysql_query($query);
		echo mysql_error();
		
		if($result)
		{
			echo "<label class='heading_labels'>Order has been marked Delivered.</label><br><br>";
			?>
			<script>
			$(document).ready(function(){
				$.ajax({
					url:'vp_sendsmsemail.php',
					type:'POST',
					data:
					{
						order_id:"<?php echo $_REQUEST['orderid']; ?>",
						status:'3'
					},

					success:function(message)
					{
						if(message == "0")
						{
							alert("Customer Email and SMS notification failed.");
						}
						else
						{
							if(message == "1")
							{
								alert("Customer SMS sent Email failed.");
							}
							else
							{
								if(message == "2")
								{
									alert("Customer Email sent sms failed.");
								}
								else
								{
									if(message == "3")
									{
										alert("Customer Email and SMS notification success.");
									}
								}
							}
						}
					}
				});
			});
			</script>
			<?php
		}
		else
		{
			echo "<label class='heading_labels'>Could not mark the order Delivered. Please try again.</label><br><br>";
			echo mysql_error();
		}
	}
}
?>
</div>
</body>
</html>