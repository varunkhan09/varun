<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($custom_database);
	$orderid = $_REQUEST['orderid'];

	$query = "select shipping_charges, dod, delivery_type from vendor_processing where orderid=$orderid";
	$result = mysql_query($query);

	$row = mysql_fetch_assoc($result);

	$shippingtype = $row['delivery_type'];
	$shippingcharges = $row['shipping_charges'];
	$delivery_date = $row['dod'];
?>

<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

	<script src="<?php echo $base_media_js_url; ?>/jquery-latest.min.js"></script>
	
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
<label class='heading'>Accept Order</label>
</center>
<div id='main_div' style='float:left; display:inline; width:96%; margin:0% 0% 0% 2%; background-color:#F7F7F7; border:2px solid #00B2EE; border-radius: 4px; text-align:center;'>
<?php

if(!isset($_REQUEST['flag']))
{
?>	
	<div id='main_div1' style='float:left; display:inline; width:100%; margin:10px 0px 10px 0px;'>
		<label class='heading_labels'>Please fill the following details (if required) to acknowledge the given order :</label>
	</div>
	<div id='main_div2'>
		<form method='POST' style='margin:0px; padding:0px;'>
			<label class='data_labels_normal'>Order ID</label> : <label class='data_labels_normal'><?php echo $orderid; ?></label>
			<br>
			<label class='data_labels_normal'>Delivery Date</label> : <label class='data_labels_normal'>
			<?php
				$temp_dod = $delivery_date;
				$temp_dod_unix = strtotime($temp_dod);
				$delivery_date = date("j M'y", $temp_dod_unix);
				echo $delivery_date;
			?>
			</label>
			<br>
			<label class='data_labels_normal'>Delivery Type</label> : <label class='data_labels_normal'><?php echo $shippingtype; ?></label>
			<br>
			<label class='data_labels_normal'>Shipping Charges</label> : <label class='data_labels_normal'><?php echo $shippingcharges; ?></label>
			<br>
			<label class='data_labels_normal'>Miscellanious Charges</label> : <input type='number' name='miscellaneous_charges' id='miscellaneous_charges' min='0' value='0' style='width:50px;'>
			<br>
			<label class='data_labels_normal'>Remarks</label> :<br><textarea name='remarks_t' id='remarks_t' rows=5 columns=5></textarea>
			<br>
			<input type='hidden' name='flag' value='1'>
			<input type='hidden' name='orderid' value='<?php echo $orderid; ?>'>
			<input type='hidden' name='vendor_id' value='<?php echo $shop_id; ?>'>
			<input type='hidden' name='shipping_charges' value='<?php echo $shippingcharges; ?>'>
			<input type='submit' value='Accept Order' class='main_buttons'>
		</form>
	</div>
<?php
}
else
{
	if($_REQUEST['flag'] == "1")
	{
		$orderid = $_REQUEST['orderid'];
		$vendor_id = $_REQUEST['vendor_id'];
		$shipping_charges = $_REQUEST['shipping_charges'];
		$miscellaneous_charges = $_REQUEST['miscellaneous_charges'];
		$remarks = $_REQUEST['remarks_t'];

		$query = "select vendor_id from vendor_processing where orderid=$orderid limit 1";
		$result = mysql_query($query);

		$row = mysql_fetch_row($result);
		if($row[0] == "") 				//This case handles cases when this order has been rejected by at least one vendor previously...
		{
			$query = "update vendor_processing set vendor_id=$shop_id, miscellaneous_charges=$miscellaneous_charges, remarks='$remarks', state=1 where orderid=$orderid";
		}
		else 							//This case handles cases when this order is processd normally, without being rejected...
		{
			$query = "update vendor_processing set miscellaneous_charges=$miscellaneous_charges, remarks='$remarks', state=1 where orderid=$orderid";
		}
		$result = mysql_query($query);
?>
		<script>
			$(document).ready(function(){
				/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
				$.ajax({
					type:"POST",
					url:"<?php echo $base_module_path; ?>/notifications/record_notification.php",
					data:
					{
						notf_type:'order_accepted',
						shop_id_accepted:'<?php echo $vendor_id; ?>',
						order_id:'<?php echo $orderid; ?>',
					}
				});
				/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
			});
		</script>
<?php

		


		if($remarks != "")
		{
			?>
				<script>
					$(document).ready(function(){
						/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
						$.ajax({
							type:"POST",
							url:"<?php echo $base_module_path; ?>/notifications/record_notification.php",
							data:
							{
								notf_type:'order_accepted_with_remarks',
								shop_id_accepted:'<?php echo $vendor_id; ?>',
								order_id:'<?php echo $orderid; ?>',
								remarks:'<?php echo $remarks; ?>'
							}
						});
						/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
					});
				</script>
			<?php
		}

		if($result)
		{
			echo "<label class='heading_labels'>Order has been accepted.</label><br><br>";
		}
		else
		{
			echo "<label class='heading_labels'>Could not accept the order. Please try again.</label><br><br>";
			echo mysql_error();
		}
	}
}
?>
</div>
</body>
</html>