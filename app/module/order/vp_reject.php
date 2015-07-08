<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($custom_database);
	$orderid = $_REQUEST['orderid'];
?>
<html>
<head>
	<script src="<?php echo $base_media_js_url; ?>/jquery-latest.min.js"></script>
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
</head>

<body>
<center>
<label class='heading'>Reject Order</label>
</center>
<div id='main_div' style='float:left; display:inline; width:96%; margin:0% 0% 0% 2%; background-color:#F7F7F7; border:2px solid #00B2EE; border-radius: 4px; text-align:center;'>
<?php

if(!isset($_REQUEST['flag']))
{
?>	
	<div id='main_div1' style='float:left; display:inline; width:100%; margin:10px 0px 10px 0px;'>
		<label class='heading_labels'>Are you sure you want to Reject this order?</label>
	</div>

	<div id='main_div2'>
		<form method='POST' style='margin:0px; padding:0px; display:inline;'>
			<input type='hidden' name='orderid' value="<?php echo $orderid; ?>">
			<input type='hidden' name='flag' value='1'>
			<textarea name="reject_reason" required="required" cols="40" rows="2"></textarea><br>
			<input type='submit' value='Yes' class='main_buttons'>
		</form>
	</div>
<?php
}
else
{
	if($_REQUEST['flag'] == "1")
	{
		$orderid = $_REQUEST['orderid'];
		$rejection_reason = $_REQUEST['reject_reason'];
		$query = "update vendor_processing set vendor_id=NULL, shipping_charges=NULL, state=NULL, vendor_price=NULL where orderid = $orderid";
		$result = mysql_query($query);

		$query = "update panelorderdetails set vendor_id = NULL, vendor_price=NULL, rejection_reason='$vendor_id|$rejection_reason' where orderid = $orderid";
		$result1 = mysql_query($query);

		if($result && $result1)
		{
			?>
			<script>
				/* THIS CODE RECORDS THIS OPERATION */
				$.ajax({
					type:"POST",
					url:"<?php echo $base_module_path; ?>/notifications/record_notification.php",
					data:
					{
						notf_type:'order_rejected',
						shop_id_accepted:'<?php echo $vendor_id; ?>',
						order_id:'<?php echo $orderid; ?>',
						rejection_reason:'<?php echo $rejection_reason; ?>'
					}
				});
				/* THIS CODE RECORDS THIS OPERATION */
			</script>
			<?php
			echo "<label class='heading_labels'>Order has been Rejected.</label><br><br>";
		}
		else
		{
			echo "<label class='heading_labels'>Could not Reject the order. Please try again.</label><br><br>";
			echo mysql_error();
		}
	}
}
?>
</div>
</body>
</html>