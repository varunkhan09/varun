<?php
	include 'vp_dbconfig.php';

	$orderid = $_REQUEST['orderid'];

	$query = "select shipping_charges, dod, delivery_type, vendor_id from vendor_processing where orderid=$orderid";
	$result = mysql_query($query);

	$row = mysql_fetch_assoc($result);

	$shippingtype = $row['delivery_type'];
	$shippingcharges = $row['shipping_charges'];
	$delivery_date = $row['dod'];
	$vendor_id = $row['vendor_id'];
	/*
	$query = "select * from panelorderdetails where orderid=$orderid";
	//echo $query."<br>";
	$result = mysql_query($query);
	echo mysql_error();
	while($row = mysql_fetch_assoc($result))
	{

		//This code fetches the shipping charges based on information if the order is Regular Delivery or Midnight Delivery...
		$temp_shipping_type = $row['shippingtype'];
		if(strpos($temp_shipping_type, "Midnight Delivery") !== false)
		{
			$shippingtype = "Midnight Delivery";
			$query = "select midnight_charges from vendor_shippingcharges where vendor_id=$vendor_id";
		}
		else
		{
			if(strpos($temp_shipping_type, "Regular Delivery"))
			{
				$shippingtype = "Regular Delivery";
				$query = "select regular_charges from vendor_shippingcharges where vendor_id=$vendor_id";
			}
		}
		//echo $query."<br>";
		$result1 = mysql_query($query);
		echo mysql_error();
		while($row1 = mysql_fetch_row($result1))
		{
			$shippingcharges = $row1[0];
		}
		//This code fetches the shipping charges based on information if the order is Regular Delivery or Midnight Delivery...
		$delivery_date = $row['dod'];
	}
	*/
?>

<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

	<script type="text/javascript" src="jquery-latest.min.js"></script>

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
			<input type='hidden' name='vendor_id' value='<?php echo $vendor_id; ?>'>
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
		if($row[0] == "")
		{
			$query = "update vendor_processing set vendor_id=$vendor_id, miscellaneous_charges=$miscellaneous_charges, remarks='$remarks', state=1 where orderid=$orderid";
		}
		else
		{
			$query = "update vendor_processing set miscellaneous_charges=$miscellaneous_charges, remarks='$remarks', state=1 where orderid=$orderid";
		}
		$result = mysql_query($query);

		if($remarks != "")
		{
			?>
				<script>
					$(document).ready(function(){
						/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
						$.ajax({
							type:"POST",
							url:"vp_recordnotifications.php",
							data:
							{
								orderid:'<?php echo $orderid; ?>',
								notification_type:"7",
								remark:'<?php echo $remarks; ?>',
								username:'<?php echo $user_name; ?>',
								vendor_id:'<?php echo $vendor_id; ?>'
							}
						});
						/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
					});
				</script>
			<?php
		}
		//$query = "update panelorderdetails set vendor_id = ".$_REQUEST['vendor_id']." where orderid=".$_REQUEST['orderid'];
		//echo $query."<br>";
		//$result = mysql_query($query);
		//echo mysql_error();

		//if($result)
		//{
		//	$query = "insert into vendor_processing (orderid, vendor_id, productid, quantity, unitprice, amount, shipping_charges, miscellaneous_charges, remarks, state) values ";

		//	$query1 = "select productid, productquantity, productunitprice from panelorderdetails where orderid=$orderid";
		//	$result1 = mysql_query($query1);
		//	echo mysql_error();
		//	while($row1 = mysql_fetch_assoc($result1))
		//	{
		//		$quantity = $row1['productquantity'];
		//		$unitprice = $row1['productunitprice'];
		//		$temp = $quantity*$unitprice;
		//		$query .= "($orderid, $vendor_id, ".$row1['productid'].", ".$row1['productquantity'].", $unitprice, $temp, $shippingcharges, $miscellaneous_charges, '$remarks', 1), ";
		//	}
		//	$query = rtrim($query, ", ");

			//echo "<br><br>$query";
			//echo $query."<br>";
		//	$result2 = mysql_query($query);
		//	echo "<br>".mysql_error();

		if($result)
		{
			echo "<label class='heading_labels'>Order has been accepted.</label><br><br>";
		}
		else
		{
			echo "<label class='heading_labels'>Could not accept the order. Please try again.</label><br><br>";
			echo mysql_error();
		}
		//}
		//else
		//{
		//	echo "<label class='heading_labels'>Could not accept the order. Please try again.</label><br><br>";
		//	echo mysql_error();
		//}
	}
}
?>
</div>
</body>
</html>