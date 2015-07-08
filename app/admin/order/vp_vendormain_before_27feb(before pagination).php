<?php

include 'vp_dbconfig.php';

/* THIS CODE LOADS ALL THE VENDORS FOR WHOLE PAGE */
$query = "select vendor_name, vendor_id from vendors";
$result = mysql_query($query);
$vendors_array = array();
while($row = mysql_fetch_assoc($result))
{
	$vendors_array[$row['vendor_id']] = $row['vendor_name'];
}
/* THIS CODE LOADS ALL THE VENDORS FOR WHOLE PAGE */





$count_unacknowledged_orders = 0;
$count_acknowledged_orders = 0;
$count_shipped_orders = 0;
$count_delivered_orders = 0;
$count_not_forwarded_orders = 0;

/* Control goes here when user submits the form */
if(isset($_REQUEST['filters_flag']) && $_REQUEST['filters_flag'] == "1")
{
	//echo "I am in big IF<br>";
	/* Control goes here when user does not enters the order id in filter */
	if($_REQUEST['filter_orderid_t'] == "")
	{
		$query = "select distinct orderid from panelorderdetails where 1=1";
		

		/* IF USER FILTERED OUT ON DATE ON DELIVERY */
		if($_REQUEST['filter_dod_t'] != "")
		{
			//echo "I am in dod filter.<br>";
			$filter_date_flag = 1;
			$date = $_REQUEST['filter_dod_t'];
			$date_array = explode("/", $date);
			$date = $date_array[2]."-".$date_array[0]."-".$date_array[1];
			$query .=" and dod='$date'";
		}
		/* IF USER FILTERED OUT ON DATE ON DELIVERY */








		/* IF USER FILTERED OUT ON DELIVERY TYPE */
		if($_REQUEST['filter_type_dd'] != "-1")
		{
			//echo "I am in delivery type filter.<br>";
			$filter_type_flag = 1;
			$temp = $_REQUEST['filter_type_dd'];
			if($temp == "0")
			{
				$temp_delivery_type = "Regular Delivery";
			}
			else
			{
				if($temp == "1")
				{
					$temp_delivery_type = "Midnight Delivery";
				}
			}
			$query .= " and shippingtype like '$temp_delivery_type%'";
		}
		/* IF USER FILTERED OUT ON DELIVERY TYPE */









		/* IF USER FILTERED OUT ON ORDER STATUS OF VENDOR */
		if($_REQUEST['filter_status_dd'] != "4")
		{
			//echo "I am in status filter.<br>";
			$filter_status_flag = 1;
			$query .= " and state=".$_REQUEST['filter_status_dd'];
		}
		/* IF USER FILTERED OUT ON ORDER STATUS OF VENDOR */









		/* IF USER FILTERED OUT ON VENDOR NAME */
		if($_REQUEST['filter_vendor_dd'] != "-1")
		{
			//echo "I am in status filter.<br>";
			$filter_status_flag = 1;
			$query .= " and vendor_id=".$_REQUEST['filter_vendor_dd'];
		}
		/* IF USER FILTERED OUT ON VENDOR NAME */

	}
	/* Control goes here when user does not enters the order id in filter */


	/* Control goes here when user enters the order id in filter */
	else
	{
		$query = "select distinct orderid from panelorderdetails where orderid=".$_REQUEST['filter_orderid_t'];
	}
	/* Control goes here when user enters the order id in filter */

	$query .= " ORDER BY orderid DESC, dod DESC";
	//echo $query;

	/* Control goes here when user fills up the filter form */
	if($_REQUEST['filters_flag'] == "1")
	{
		//echo "<br><br><br><br><br><br><br><br><br>$query";
		$result = mysql_query($query);
		if($result && mysql_num_rows($result))
		{
			$orders_flag = 1;
			$temp_shipping_charges = 0;
			$orders_details_array = array();
			$temp_product_details_array = array();
			while($row = mysql_fetch_assoc($result))
			{
				$grand_total = 0;
				$orderid = $row['orderid'];


























				$query = "select MAX(state) from vendor_processing where orderid=$orderid";
				$result_state_for_ticker = mysql_query($query);
				$row_state_for_ticker = mysql_fetch_row($result_state_for_ticker);
				$temp_order_state = $row_state_for_ticker[0];
				if($temp_order_state == "0")
				{
					$count_unacknowledged_orders += 1;
				}
				else
				{
					if($temp_order_state == "1")
					{
						$count_acknowledged_orders += 1;
					}
					else
					{
						if($temp_order_state == "2")
						{
							$count_shipped_orders += 1;
						}
						else
						{
							if($temp_order_state == "3")
							{
								$count_delivered_orders += 1;
							}
							else
							{
								$count_not_forwarded_orders += 1;
							}
						}
					}
				}




















































				$query = "select * from panelorderdetails where orderid = $orderid";
				//echo "<br>$query";
				$result1 = mysql_query($query);
				while($row1 = mysql_fetch_assoc($result1))
				{
					//echo "POPAT ".$row1['vendor_id']."<br>";

					/* GETTING VENDOR NAME INFORMATION */
					$temp_vendor_id = $row1['vendor_id'];
					//echo "<br>VARUN '$temp_vendor_id'";
					if($temp_vendor_id == "")
					{
						$temp_vendor_name = "Vendor Not Assigned";
					}
					else
					{
						$query5 = "select vendor_name from vendors where vendor_id=$temp_vendor_id";
						//echo $query5."<br>";
						$result5 = mysql_query($query5);
						//echo mysql_error()."<br>";
						$row5 = mysql_fetch_row($result5);
						$temp_vendor_name = $row5[0];
					}
					/* GETTING VENDOR NAME INFORMATION */


					/* GETTING DELIVERY DATE INFORMATION */
					$temp_delivery_date = $row1['dod'];
					/* GETTING DELIVERY DATE INFORMATION */


					/* GETTING SHIPPING TYPE INFORMATION */
					$temp_shipping_type = $row1['shippingtype'];
					$filtered_shippingtype = explode("_", $temp_shipping_type);
					$temp_shipping_type = $filtered_shippingtype[0];
					/* GETTING SHIPPING TYPE INFORMATION */


					/* GETTING PER PRODUCT PRICE INFORMATION */
					$temp_product_quantity = $row1['productquantity'];
					$temp_product_unitprice = $row1['productunitprice'];
					$temp_product_amount = $temp_product_quantity*$temp_product_unitprice;
					$grand_total += $temp_product_amount;
					/* GETTING PER PRODUCT PRICE INFORMATION */


					/* GETTING PER PRODUCT VENDOR DESCRIPTION AND IMAGE INFORMATION */
					$temp_product_id = $row1['productid'];
					$product = Mage::getModel('catalog/product')->load($temp_product_id);
					$temp_product_image_url = $product->getImageUrl();
					$temp_product_name = $product->getName();
					$temp_product_vendor_desc = $row1['productvdescription'];
					$temp_product_details_array[$temp_product_id]['image'] = $temp_product_image_url;
					$temp_product_details_array[$temp_product_id]['vdescription'] = $temp_product_vendor_desc;
					$temp_product_details_array[$temp_product_id]['name'] = $temp_product_name;
					/* GETTING PER PRODUCT VENDOR DESCRIPTION AND IMAGE INFORMATION */
				}

				/* GETTING ORDER SHIPPING ADDRESS INFORMATION */
				$order = Mage::getModel('sales/order')->loadByIncrementId($orderid);
				$shipping_address = $order->getShippingAddress();
				//var_dump($shipping_address);
				//echo "<hr>";
				$shipping_address_real = $shipping_address->getStreetFull();
				$shipping_state = $shipping_address->getRegion();
				$shipping_city = $shipping_address->getCity();
				$shipping_pincode = $shipping_address->getPostcode();
				$temp_shipping_address = $shipping_address_real.", ".$shipping_city.", ".$shipping_state.", ".$shipping_pincode;

				$billing_address = $order->getBillingAddress();
				$billing_number = $billing_address->getTelephone();
				/* GETTING ORDER SHIPPING ADDRESS INFORMATION */


				/* GETTING SHIPPING CHARGES INFORMATION */
				$query = "select shipping_charges from vendor_processing where orderid = $orderid limit 1";
				$result7 = mysql_query($query);
				while($row7 = mysql_fetch_assoc($result7))
				{
					$temp_shipping_charges = $row7['shipping_charges'];
				}
				$grand_total += $temp_shipping_charges;
				/* GETTING SHIPPING CHARGES INFORMATION */


				


				$orders_details_array[$orderid]['deliverydate'] = $temp_delivery_date;
				$orders_details_array[$orderid]['deliveryaddress'] = $temp_shipping_address;
				$orders_details_array[$orderid]['shippingtype'] = $temp_shipping_type;
				$orders_details_array[$orderid]['price'] = $grand_total;
				$orders_details_array[$orderid]['contact'] = $billing_number;
				$orders_details_array[$orderid]['products'] = $temp_product_details_array;
				$orders_details_array[$orderid]['vendorname'] = $temp_vendor_name;
			}
		}
		else
		{
			$orders_flag = 0;
		}
	}
	/* Control goes here when user fills up the filter form */


	else
	{
		$orders_flag = 3;
	}
}
/* Control goes here when user submits the form */


else
{
	//echo "I am in big ELSE<br>";
	$query = "select distinct orderid from panelorderdetails where vendor_id is NULL ORDER BY orderid DESC, dod ASC";
	echo $query;

	$result = mysql_query($query);
	if($result && mysql_num_rows($result))
	{
		$orders_flag = 1;
		$temp_shipping_charges = 0;
		$orders_details_array = array();
		while($row = mysql_fetch_assoc($result))
		{
			$grand_total = 0;
			$orderid = $row['orderid'];











			$query = "select MAX(state) from vendor_processing where orderid = $orderid";
			$result_state_for_ticker = mysql_query($query);
			$row_state_for_ticker = mysql_fetch_row($result_state_for_ticker);
			$temp_order_state = $row_state_for_ticker[0];
			if($temp_order_state == "0")
			{
				$count_unacknowledged_orders += 1;
			}
			else
			{
				if($temp_order_state == "1")
				{
					$count_acknowledged_orders += 1;
				}
				else
				{
					if($temp_order_state == "2")
					{
						$count_shipped_orders += 1;
					}
					else
					{
						if($temp_order_state == "3")
						{
							$count_delivered_orders += 1;
						}
						else
						{
							$count_not_forwarded_orders += 1;
						}
					}
				}
			}

















			$query = "select * from panelorderdetails where orderid = $orderid";
			$result1 = mysql_query($query);
			while($row1 = mysql_fetch_assoc($result1))
			{
				/* GETTING DELIVERY DATE INFORMATION */
				$temp_delivery_date = $row1['dod'];
				/* GETTING DELIVERY DATE INFORMATION */


				/* GETTING SHIPPING TYPE INFORMATION */
				$temp_shipping_type = $row1['shippingtype'];
				$filtered_shippingtype = explode("_", $temp_shipping_type);
				$temp_shipping_type = $filtered_shippingtype[0];
				/* GETTING SHIPPING TYPE INFORMATION */


				/* GETTING PER PRODUCT PRICE INFORMATION */
				$temp_product_quantity = $row1['productquantity'];
				$temp_product_unitprice = $row1['productunitprice'];
				$temp_product_amount = $temp_product_quantity*$temp_product_unitprice;
				$grand_total += $temp_product_amount;
				/* GETTING PER PRODUCT PRICE INFORMATION */


				/* GETTING PER PRODUCT VENDOR DESCRIPTION AND IMAGE INFORMATION */
				$temp_product_id = $row1['productid'];
				$product = Mage::getModel('catalog/product')->load($temp_product_id);
				$temp_product_image_url = $product->getImageUrl();
				$temp_product_name = $product->getName();
				$temp_product_vendor_desc = $row1['productvdescription'];
				$temp_product_details_array[$temp_product_id]['image'] = $temp_product_image_url;
				$temp_product_details_array[$temp_product_id]['vdescription'] = $temp_product_vendor_desc;
				$temp_product_details_array[$temp_product_id]['name'] = $temp_product_name;
				/* GETTING PER PRODUCT VENDOR DESCRIPTION AND IMAGE INFORMATION */
			}

				/* GETTING ORDER SHIPPING ADDRESS INFORMATION */
			$order = Mage::getModel('sales/order')->loadByIncrementId($orderid);
			$shipping_address = $order->getShippingAddress();
			//var_dump($shipping_address);
			//echo "<hr>";
			$shipping_address_real = $shipping_address->getStreetFull();
			$shipping_state = $shipping_address->getRegion();
			$shipping_city = $shipping_address->getCity();
			$shipping_pincode = $shipping_address->getPostcode();
			$temp_shipping_address = $shipping_address_real.", ".$shipping_city.", ".$shipping_state.", ".$shipping_pincode;

			$billing_address = $order->getBillingAddress();
			$billing_number = $billing_address->getTelephone();
			/* GETTING ORDER SHIPPING ADDRESS INFORMATION */


			/* GETTING SHIPPING CHARGES INFORMATION */
			$query = "select shipping_charges from vendor_processing where orderid = $orderid limit 1";
			$result1 = mysql_query($query);
			while($row1 = mysql_fetch_assoc($result1))
			{
				$temp_shipping_charges = $row1['shipping_charges'];	
			}
			$grand_total += $temp_shipping_charges;
			/* GETTING SHIPPING CHARGES INFORMATION */


			/* GETTING DELIVERY DATE INFORMATION */
			$temp_vendor_name = "Vendor Not Assigned";
			/* GETTING DELIVERY DATE INFORMATION */



			$orders_details_array[$orderid]['deliverydate'] = $temp_delivery_date;
			$orders_details_array[$orderid]['deliveryaddress'] = $temp_shipping_address;
			$orders_details_array[$orderid]['shippingtype'] = $temp_shipping_type;
			$orders_details_array[$orderid]['price'] = $grand_total;
			$orders_details_array[$orderid]['contact'] = $billing_number;
			$orders_details_array[$orderid]['products'] = $temp_product_details_array;
			$orders_details_array[$orderid]['vendorname'] = $temp_vendor_name;
		}
	}
	else
	{
		$orders_flag = 0;
	}
}
?>

<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="jquery-latest.min.js"></script>
	<link rel='stylesheet' href='jquery-ui.css'>
	<script src='jquery-ui.js'></script>

	<!--
	<link rel="stylesheet" href="fancyBox/source/jquery.fancybox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/jquery.fancybox.pack.js"></script>
	<link rel="stylesheet" href="fancyBox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/helpers/jquery.fancybox-media.js"></script>
	<link rel="stylesheet" href="fancyBox/source/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/helpers/jquery.fancybox-thumbs.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	-->

	<script>
	$(function(){$( ".datepicker" ).datepicker();});


	$(document).ready(function(){
		$(document).on('mouseover', '.order_id_div', function() {
			var button_id = $(this).attr('id');
			var starter = button_id.split('_');

			$('#orderdetails_'+starter[1]).show();
		});
	});


	$(document).ready(function(){
		$(document).on('mouseout', '.order_id_div', function() {
			var button_id = $(this).attr('id');
			var starter = button_id.split('_');

			$('#orderdetails_'+starter[1]).hide();
		});
	});
	</script>
	<style>
	.heading
	{
		font-size:32px; color:#009ACD; font-weight:bold;
	}

	.table
	{
		width:96%;
		text-align:center;
		margin:0px 0px 250px 0px;
	}

	.buttons
	{
		margin-left: 2%; border-radius: 6px; background-color: #00688B; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}
	.buttons:hover
	{
		background-color: #4A708B; cursor:pointer;
	}

	.table_heading_row
	{
		font-size:20px;
		color:white;
		background-color:#009ACD;
		padding:90px;
		font-family: Open Sans;
		height:56px;
	}

	.table_heading_row_small
	{
		font-size:18px;
		color:white;
		background-color:#009ACD;
		padding:90px;
		font-family: Open Sans;
		height:30px;
	}

	.table_data_row
	{
		background-color:#DCDCDC;
		font-family: 'Raleway', sans-serif;
		height:100px;
	}

	.table_data_row:hover
	{
		background-color:#D3D3D3;
	}

	.notice_labels
	{
		font-size:20px;
		color:#00688B;
		font-family: Open Sans;
	}

	.order_details_div
	{
		position:absolute;
		display:none;
		left:10px;
		float:left;
		//border:3px solid black;
		//background-color:red;
		width:1200px;
	}

	.order_products_table
	{
		border:0px black solid;
		text-align:center;
		background-color:#EEEEE0;
	}

	.order_products_table_cell
	{
		border: 3px solid black;
		width:16%;
	}

	.main_buttons
	{
		margin-left: 2%; border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}
	.main_buttons:hover
	{
		background-color: #00688B; cursor:pointer;
	}

	.checkbox
	{
		height:15px;
		width:15px;
	}

	.filter_table
	{
		width:100%;
		text-align:center;
		margin:0px 0px 0px 0px;
		border-left:3px solid #009ACD;
		border-right:3px solid #009ACD;
		border-top:3px solid #009ACD;
	}

	.base_div
	{
		display:block;
		position:fixed;
		background-color:white;
		top:0px;
		left:0px;
		width:100%;
		text-align:center;
		border-bottom:3px solid #009ACD;
		margin:0% 0% 3% 0%;
	}

	.datepicker
	{
		width:90px;
	}

	.main_buttons_red
	{
		margin-left: 2%; border-radius: 6px; background-color: #B22222; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_red:hover
	{
		background-color: #8B1A1A; cursor:pointer;
	}

	.main_buttons_yellow
	{
		margin-left: 2%; border-radius: 6px; background-color: #EEB422; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_yellow:hover
	{
		background-color: #CD9B1D; cursor:pointer;
	}

	.main_buttons_green
	{
		margin-left: 2%; border-radius: 6px; background-color: #6E8B3D; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_green:hover
	{
		background-color: #556B2F; cursor:pointer;
	}

	.main_buttons_orange
	{
		margin-left: 2%; border-radius: 6px; background-color: #FF8C00; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_orange:hover
	{
		background-color: #CD6600; cursor:pointer;
	}

	select
	{
		background-color: white;
	}
	</style>
</head>
<body>

<div id='loading_div' style='display:none; position:fixed; width:100%; height:100%; text-align:center; vertical-align:middle; background-color:gray; opacity:0.5;'>
	<img src='http://www.theappmadeinheaven.com/resources/images/PleaseWait.gif' style='margin-top:20%; width:5%; height:10%;'>
</div>

<div class='base_div'>
	<label class='heading'>Orders</label>
	<div style='float:right; display:inline; text-align:right; position:fixed; right:0px; margin:0px 10px 0px 0px;'>
		<label style='color:#009ACD; font-size:18px;'>Not Forwarded Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;'><?php echo $count_not_forwarded_orders; ?></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Unacknowledged Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;'><?php echo $count_unacknowledged_orders; ?></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Acknowledged Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;'><?php echo $count_acknowledged_orders; ?></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Shipped Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;'><?php echo $count_shipped_orders; ?></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Delivered Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;'><?php echo $count_delivered_orders; ?></label>
		<br><br>
	</div>
	<br>
	<br>
	<br>
	<br>
	<br>
	<form method='POST' style='margin:0px; padding:0px;' id='filter_form'>
	<input type='hidden' name='filters_flag' value='1'>
		<table class='filter_table' cellspacing="0">
			<tr class='table_heading_row_small'>
				<td style='padding:0px;'>Select Delivery Date<label style='color:red; font-size:15px;'><sup>*</sup></label></td>
				<td style='padding:0px;'>Select Delivery Type</td>
				<td style='padding:0px;'>Select Order Status</td>
				<td style='padding:0px;'>Select by Vendor</td>
				<!-- <td style='padding:0px;'>Select by Delivery City</td> -->
				<td style='padding:0px;'>Load by Order ID</td>
				<td style='padding:0px;'>Load Orders</td>
			</tr>

			<tr style='height:40px;'>
				<td style='padding:0px;'><input type='textbox' id='filter_dod_t' name='filter_dod_t' class='datepicker'></td>

				<td style='padding:0px;'>
					<select name='filter_type_dd' id='filter_type_dd'>
					<option value='-1'>Select</option>
					<option value='0'>Regular Delivery</option>
					<option value='1'>Midnight Delivery</option>
					</select>
				</td>

				<td style='padding:0px;'>
					<select name='filter_status_dd' id='filter_status_dd'>
						<option value='4'>All</option>
						<option value='0'>Orders to Accept</option>
						<option value='1'>Accepted Orders</option>
						<option value='2'>Shipped Orders</option>
						<option value='3'>Delivered Orders</option>
					</select>
				</td>



				<td style='padding:0px;'>
					<select name='filter_vendor_dd' id='filter_vendor_dd'>
						<option value='-1'>Select</option>
						<?php
						$query = "select vendor_id, vendor_name from vendors";
						$result = mysql_query($query);

						while($row = mysql_fetch_assoc($result))
						{
							echo "<option value='".$row['vendor_id']."'>".$row['vendor_name']."</option>";
						}
						?>
					</select>
				</td>


				<!--
				<td style='padding:0px;'>
					<select name='filter_city_dd'>
						<option value='-1'>Select</option>
						<?php
						//$query = "select distinct city from pincode_product_map";
						//$result = mysql_query($query);

						//while($row = mysql_fetch_assoc($result))
						{
							//$temp_city = $row['city'];
							//$temp_city = strtolower($temp_city);
							//$temp_city = ucwords($temp_city);
							//echo "<option value='$temp_city'>$temp_city</option>";
						}
						?>
					</select>
				</td>
				-->


				<td style='padding:0px;'>
					<input type='textbox' name='filter_orderid_t' id='filter_orderid_t' placeholder='Enter Order ID'>
				</td>

				<td style='padding:0px;'><input type='button' id='filter_form_button' value='Load Orders' id='filter_b' class='buttons'></td>
			</tr>
		</table>
	</form>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<center>
<table class='table'>
<tr class='table_heading_row'>
	<td><input type='button' id='print_b' class='buttons' value='Print'></td>
	<td>Order ID</td>
	<td>Delivery Date</td>
	<td>Delivery Address</td>
	<td>Recipient Number</td>
	<td>Shipping Type</td>
	<!-- <td>Price</td> -->
	<td>Acknowledge</td>
	<td>Assigned Vendor</td>
</tr>

<?php
if($orders_flag == 1)
{
	$orders_details_array_keys =  array_keys($orders_details_array);
	$row_counter=0;
	foreach($orders_details_array_keys as $orders_details_array_inside_foreach)
	{
		echo "<tr class='table_data_row' id='tr_$row_counter'>";
		echo "<td style='width:4%;' class='checkbox_td' id='checkboxtd_$row_counter'><input type='hidden' id='orderid_hidden_$row_counter' value='$orders_details_array_inside_foreach'><input type='checkbox' class='print_ch checkbox' id='printch_$orders_details_array_inside_foreach'></td>";
		echo "<td class='order_id_div' id='orderid_$row_counter' style='width:10%;'>";
		echo "$orders_details_array_inside_foreach";
		echo "<div class='order_details_div' id='orderdetails_$row_counter'>";
		$temp_products_array = array_keys($orders_details_array[$orders_details_array_inside_foreach]['products']);
		echo "<table class='order_products_table'>";
		echo "<tr>";
		$row_flag_inner=0;
		foreach($temp_products_array as $each_product)
		{
			if($row_flag_inner == 6)
			{
				echo "</tr><tr>";
				$row_flag_inner = 0;
			}
			echo "<td class='order_products_table_cell'>";
			echo $orders_details_array[$orders_details_array_inside_foreach]['products'][$each_product]['name'];
			echo "<br>";
			echo "<img src='".$orders_details_array[$orders_details_array_inside_foreach]['products'][$each_product]['image']."' style='height:100px; width:100px;'>";
			echo "<br>";
			echo $orders_details_array[$orders_details_array_inside_foreach]['products'][$each_product]['vdescription'];
			echo "</td>";
			$row_flag_inner++;
		}
		echo "</tr>";
		echo "</table>";
		echo "</div></td>";
		$temp_dod = $orders_details_array[$orders_details_array_inside_foreach]['deliverydate'];
		$temp_dod_unix = strtotime($temp_dod);
		$dod_varun = date("j M'y", $temp_dod_unix);
		echo "<td class='form_click' id='orderid_$row_counter' style='width:8%;'>".$dod_varun."</td>";
		echo "<td class='form_click' id='orderid_$row_counter' style='width:18%;'>".$orders_details_array[$orders_details_array_inside_foreach]['deliveryaddress']."</td>";
		echo "<td class='form_click' id='orderid_$row_counter' style='width:10%;'>".$orders_details_array[$orders_details_array_inside_foreach]['contact']."</td>";
		
		if(strpos($orders_details_array[$orders_details_array_inside_foreach]['shippingtype'], "Midnight Delivery") !== false)
		{
			echo "<td class='form_click' id='orderid_$row_counter' style='width:9%; background-color:red;color:white;'>";
		}
		else
		{
			echo "<td class='form_click' id='orderid_$row_counter' style='width:9%;'>";
		}
		echo $orders_details_array[$orders_details_array_inside_foreach]['shippingtype']."</td>";
		//echo "<td class='form_click' id='orderid_$row_counter' style='width:8%;'>".$orders_details_array[$orders_details_array_inside_foreach]['price']."</td>";
		
		$query = "select MAX(state) from vendor_processing where orderid=$orders_details_array_inside_foreach";
		$result2 = mysql_query($query);
		//echo $query."<br>";
		while($row2 = mysql_fetch_row($result2))
		{
			$temp_order_state = $row2[0];
			//echo "Varun $temp_order_state<br>";
		}
		echo "<td class='form_click' id='orderid_$row_counter' style='width:14%;'>";
		echo "<form method='POST' action='vp_ordermain.php' id='form_$row_counter' target='_blank' style='margin:0px; padding:0px;'>";
		echo "<input type='hidden' name='orderid' value='$orders_details_array_inside_foreach'>";
		echo "<input type='hidden' name='flag' value='1'>";
		echo "<input type='hidden' name='display_flag' value='0'>";
		echo "<input type='submit' value='View Order' class='main_buttons_orange'>";
		echo "</form><br style='line-height:1px;'>";


		if($temp_order_state == "0" || $temp_order_state == "")
		{
			echo "<select id='vendor_id_dd_$row_counter'>";
			echo "<option value='Select'>Select</option>";
			foreach($vendors_array as $each_vendor_id => $each_vendor_name)
			{
				echo "<option value='$each_vendor_id'>$each_vendor_name</option>";
			}
			echo "</select>";
			echo "<br>";
			echo "<input type='button' value='Send Email' class='buttons sendemailbutton' id='sendemailb_$row_counter'>";
		}
		/*
		if($temp_order_state == "0")
		{
			echo "<input type='button' id='activity_b_$orders_details_array_inside_foreach' value='Accept Order' class='main_buttons_red varun' data-fancybox-type='iframe' href='vp_acknowledge.php?orderid=$orders_details_array_inside_foreach'></td>";
		}
		else
		{
			if($temp_order_state == "1")
			{
				echo "<input type='button' id='activity_b_$orders_details_array_inside_foreach' value='Ship Order' class='main_buttons_yellow varun' data-fancybox-type='iframe' href='vp_ship.php?orderid=$orders_details_array_inside_foreach'></td>";
			}
			else
			{
				if($temp_order_state == "2")
				{
					echo "<input type='button' id='activity_b_$orders_details_array_inside_foreach' value='Order Delivered' class='main_buttons_green varun' data-fancybox-type='iframe' href='vp_delivered.php?orderid=$orders_details_array_inside_foreach'></td>";
				}
				else
				{
					if($temp_order_state == "3")
					{

					}
				}
			}
		}
		*/
		echo "</td>";

		if($temp_order_state == "0")
		{
			$style = "background-color:none;";
			$count_unacknowledged_orders += 1;
			$display_label = "Unacknowledged";
		}
		else
		{
			if($temp_order_state == "1")
			{
				$style = "background-color:yellow;";
				$count_acknowledged_orders += 1;
				$display_label = "Acknowledged";
			}
			else
			{
				if($temp_order_state == "2")
				{
					$style = "background-color:blue; color:white;";
					$count_shipped_orders += 1;
					$display_label = "Shipped";
				}
				else
				{
					if($temp_order_state == "3")
					{
						$style="background-color:none;";
						$count_delivered_orders += 1;
						$display_label = "Delivered";
					}
					else
					{
						$style="background-color:none;";
						$display_label = "";
					}
				}
			}
		}
		echo "<td class='form_click' id='orderid_vendor_$row_counter' style='width:11%; $style'>".$orders_details_array[$orders_details_array_inside_foreach]['vendorname']."<br>$display_label</td>";
		echo "</tr>";
		$row_counter++;
	}
}
else
{
	if($orders_flag == "0")
	{
		echo "</table>";
		echo "<br><br>";
		echo "<label class='notice_labels'>You have no orders by this data.</label>";
	}
	else
	{
		if($orders_flag == "2")
		{	
			echo "</table>";
			echo "<br><br>";
			echo "<label class='notice_labels'>Please select at least one filter to load Orders.</label>";
		}
		else
		{
			if($orders_flag == "3")
			{
				echo "</table>";
				echo "<br><br>";
				echo "<label class='notice_labels'>Please select at least one filter to load Orders.</label>";
			}
		}
	}
}
?>














			


</center>
</body>

<script>
$(document).ready(function(){
	$(document).on('click', '.varun', function(){
		var button_id = $(this).attr('id');
		var starter = button_id.split("_");

		var class_main = $(this).attr('class');
		var class_array = class_main.split(' ');

		if(class_array[0] == "main_buttons_red")
		{
			$(".main_buttons_red").fancybox({
			width		: '670px',
			height		: '380px',
			autoSize	: false,
			closeClick	: false,

			afterClose: function()
			{
				$("#loading_div").show();
				$('body').attr('disabled', 'disabled');
				$.ajax({
					type:'POST',
					url:'vp_checkstate.php',
					data:
					{
						orderid:starter[2]
					},

					success:function(message)
					{
						if(message == "0")
						{
							$("#activity_b_"+starter[2]).val("Accept Order");
							$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
						}
						else
						{
							if(message == "1")
							{
								$("#activity_b_"+starter[2]).val("Ship Order");
								$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
								$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+starter[2]);
							}
							else
							{
								if(message == "2")
								{
									$("#activity_b_"+starter[2]).val("Order Delivered");
									$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
									$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+starter[2]);
								}
								else
								{
									if(message == "3")
									{
										$("#activity_b_"+starter[2]).hide();
									}
									else
									{
										if(message == "-1")
										{
											alert("Some error has occurred while performing operation.");
										}
									}
								}
							}
						}
					}
				});
				$("#loading_div").hide();
				$('body').removeAttr("disabled");
			}
		});
		}

		else
		{
			if(class_array[0] ==  "main_buttons_yellow")
			{
				$(".main_buttons_yellow").fancybox({
				width		: '670px',
				height		: '280px',
				autoSize	: false,
				closeClick	: false,

				afterClose: function()
				{
					$("#loading_div").show();
					$('body').attr('disabled', 'disabled');
					$.ajax({
						type:'POST',
						url:'vp_checkstate.php',
						data:
						{
							orderid:starter[2]
						},

						success:function(message)
						{
							if(message == "0")
							{
								$("#activity_b_"+starter[2]).val("Accept Order");
								$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
							}
							else
							{
								if(message == "1")
								{
									$("#activity_b_"+starter[2]).val("Ship Order");
									$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
									$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+starter[2]);
								}
								else
								{
									if(message == "2")
									{
										$("#activity_b_"+starter[2]).val("Order Delivered");
										$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
										$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+starter[2]);
									}
									else
									{
										if(message == "3")
										{
											$("#activity_b_"+starter[2]).hide();
										}
										else
										{
											if(message == "-1")
											{
												alert("Some error has occurred while performing operation.");
											}
										}
									}
								}
							}
						}
					});
					$("#loading_div").hide();
					$('body').removeAttr("disabled");
				}
			});
			}

			else
			{
				if(class_array[0] == "main_buttons_green")
				{
					$(".main_buttons_green").fancybox({
					width		: '670px',
					height		: '230px',
					autoSize	: false,
					closeClick	: false,

					afterClose: function()
					{
						$("#loading_div").show();
						$('body').attr('disabled', 'disabled');
						$.ajax({
							type:'POST',
							url:'vp_checkstate.php',
							data:
							{
								orderid:starter[2]
							},

							success:function(message)
							{
								if(message == "0")
								{
									$("#activity_b_"+starter[2]).val("Accept Order");
									$("#activity_b_"+starter[2]).attr("class", "main_buttons_red varun");
								}
								else
								{
									if(message == "1")
									{
										$("#activity_b_"+starter[2]).val("Ship Order");
										$("#activity_b_"+starter[2]).attr("class", "main_buttons_yellow varun");
										$("#activity_b_"+starter[2]).attr("href", 'vp_ship.php?orderid='+starter[2]);
									}
									else
									{
										if(message == "2")
										{
											$("#activity_b_"+starter[2]).val("Order Delivered");
											$("#activity_b_"+starter[2]).attr("class", "main_buttons_green varun");
											$("#activity_b_"+starter[2]).attr("href", 'vp_delivered.php?orderid='+starter[2]);
										}
										else
										{
											if(message == "3")
											{
												$("#activity_b_"+starter[2]).hide();
											}
											else
											{
												if(message == "-1")
												{
													alert("Some error has occurred while performing operation.");
												}
											}
										}
									}
								}
							}
						});
						$("#loading_div").hide();
						$('body').removeAttr("disabled");
					}
				});
				}
			}
		}
	});
});


$(document).ready(function(){
	$(document).on('click', '#print_b', function(){
		var checked_array = '';
		$(".print_ch").each(function(){
			if($(this).prop('checked') == true)
			{
				var temp_order = $(this).attr("id");
				var temp_array = temp_order.split("_");
				checked_array += temp_array[1]+"_";
			}
		});

		$("body").append("<form id='print_form' action='vp_printorder.php' method='POST' style='display:none;' target='_blank'><input type='hidden' value='"+checked_array+"' name='orderid'></form>");
		$("#print_form").submit();
	});
});



$(document).ready(function(){
	$(document).on('click', '#filter_form_button', function(){
		//alert("kakakkaka");
		var orderid_flag = $("#filter_orderid_t").val();
		var dod_flag = $("filter_dod_t").val();

		if(orderid_flag == "")
		{
			dod_flag = $("#filter_dod_t").val();
			if(dod_flag == "")
			{
				alert("Please select Date of Delivery.");
			}
			else
			{
				$("#filter_form").submit();
			}
		}
		else
		{
			$("#filter_form").submit();
		}
	});
});



$(document).ready(function(){
	$(document).on('click', '.sendemailbutton', function(){
		$("html").css("cursor", "wait");
		var button_id = $(this).attr('id');
		var starter = button_id.split('_');
		var orderid = $("#orderid_hidden_"+starter[1]).val();

		var vendor_id = $("#vendor_id_dd_"+starter[1]+" option:selected").val();
		var vendor_name = $("#vendor_id_dd_"+starter[1]+" option:selected").text();

		if(vendor_id != "Select")
		{
			$.ajax({
				type:"POST",
				url:"vp_acknowledgeemail.php",
				data:
				{
					orderid:orderid,
					vendor_id:vendor_id
				},

				success:function(message)
				{
					$("html").css("cursor", "default");
					if(message == "+1")
					{
						//alert("It's a success!!");
						$("#orderid_vendor_"+starter[1]).html(vendor_name+"<br>Unacknowledged");
						$("#vendor_id_dd_"+starter[1]).remove();
						$("#sendemailb_"+starter[1]).remove();


						/* THIS CODE RECORDS THIS OPERATION */
						$.ajax({
							type:"POST",
							url:"vp_recordoperations.php",
							data:
							{
								orderid:orderid,
								comment_type:"0"
							}
						});
						/* THIS CODE RECORDS THIS OPERATION */
					}
					else
					{
						alert("Some unexpected error occurred.");
					}
				}
			});
		}
		else
		{
			alert("Please select a Vendor first.");
		}
		//alert(orderid);
	});
});
</script>
</html>