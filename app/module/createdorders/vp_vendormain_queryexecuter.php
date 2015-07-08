<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";




	$associated_vendors_array = array();
	mysql_select_db($vendorshop_database);
	$query = "select b.shop_name, b.email, b.entity_id from pos_associated_vendors_entity a right join pos_shop_entity b on a.vendor_shop_id = b.entity_id where a.vendor_shop_id is not NULL and a.self_shop_id = $shop_id";
	$result = mysql_query($query);
	echo mysql_error();
	while($row = mysql_fetch_assoc($result))
	{
		$temp_vendor_shop_name = $row['shop_name'];
		$temp_vendor_shop_email = $row['email'];
		$temp_vendor_shop_id = $row['entity_id'];

		switch($temp_vendor_shop_id)
		{
			case $shop_id:
			{
				$temp_vendor_shop_name = "Self";
				break;
			}

			case $flaberry_self_shop_id:
			{
				$temp_vendor_shop_name = $flaberry_self_shop_name;
				break;
			}

			default:
			{
				break;
			}
		}

		$associated_vendors_array[$temp_vendor_shop_id]['name'] = $temp_vendor_shop_name;
		$associated_vendors_array[$temp_vendor_shop_id]['email'] = $temp_vendor_shop_email;
	}
	$associated_vendors_id_array = array_keys($associated_vendors_array);
	mysql_select_db($custom_database);


	$query = $_REQUEST['query'];

	if(isset($_REQUEST['min']) && isset($_REQUEST['max']))
	{
		$min = $_REQUEST['min'];
		$max = $_REQUEST['max'];

		$range = $max;
		$button_number = ($min/$max)+1;


		$result = mysql_query($query);
		$number_of_orders = mysql_num_rows($result);
		$max_page_number = $number_of_orders/$range;
		$max_page_number = ceil($max_page_number);
		//echo "<br>Maximum Page Number : ".$max_page_number;
		echo "<input type='hidden' id='max_page_number' value='$max_page_number'>";
		//echo "<br>Total Number of Orders : ".$number_of_orders;
		//echo "<br>Range : $range";
		echo "<div class='pagination_div'>";

		echo "<input type='button' class='pagination_buttons pagination_event1' value='<' tooltip='Page 1'>";
		echo "<input type='number' id='current_page_number' class='pagination_textbox pagination_event2' value='$button_number' min='1' max='$max_page_number' required='required'>";
		echo "<input type='button' class='pagination_buttons pagination_event1' value='>' tooltip='Page $max_page_number'>";
		/*
		$button_counter = "1";
		for($x=1; $x<=$number_of_orders || $x>=$number_of_orders+$range;)
		{
			if($button_counter == $button_number)
			{
				echo "<input type='button' class='pagination_buttons pagination_buttons_selected' value='$button_counter'>";
			}
			else
			{
				echo "<input type='button' class='pagination_buttons' value='$button_counter'>";
			}
		
			$button_counter++;
			$x = $x+$range;
		}
		*/
		echo "</div>";



		$count_unacknowledged_orders = 0;
		$count_acknowledged_orders = 0;
		$count_shipped_orders = 0;
		$count_delivered_orders = 0;
		$count_not_forwarded_orders = 0;
		while($row = mysql_fetch_assoc($result))
		{
			$orderid_inside_while_loop = $row['orderid'];
			$query_varun = "select MAX(state) from vendor_processing where orderid=$orderid_inside_while_loop";
			//echo $query_varun;
			$result_varun = mysql_query($query_varun);
			//echo "<br>".mysql_error();
			$row_varun = mysql_fetch_row($result_varun);
			$state_varun = $row_varun[0];
			//echo "VARUN '$state_varun'";
			if($state_varun != "")
			{
				if($state_varun == "0")
				{
					$count_unacknowledged_orders++;
				}
				else
				{
					if($state_varun == "1")
					{
						$count_acknowledged_orders++;
					}
					else
					{
						if($state_varun == "2")
						{
							$count_shipped_orders++;
						}
						else
						{
							if($state_varun == "3")
							{
								$count_delivered_orders++;
							}
						}
					}
				}
			}
			else
			{
				$count_not_forwarded_orders++;
			}
		}
		?>
		<script>
			$("#not_forwarded_orders").html(<?php echo $count_not_forwarded_orders; ?>);
			$("#unacknowledged_orders").html(<?php echo $count_unacknowledged_orders; ?>);
			$("#acknowledged_orders").html(<?php echo $count_acknowledged_orders; ?>);
			$("#shipped_orders").html(<?php echo $count_shipped_orders; ?>);
			$("#delivered_orders").html(<?php echo $count_delivered_orders; ?>);
		</script>
		<?php
		$query = $query." ORDER BY orderid DESC, dod DESC limit $min, $max";
	}


	//echo $query;
	//exit();
	$result = mysql_query($query);

	if(mysql_num_rows($result) != 0)
	{
		$orders_flag = 1;
		$orders_details_array = array();
		while($row = mysql_fetch_assoc($result))
		{
			$orderid_inside_while_loop = $row['orderid'];


			/* GETTING ORDER SHIPPING ADDRESS INFORMATION */
			$order_inside_while_loop = Mage::getModel('sales/order')->loadByIncrementId($orderid_inside_while_loop);
			$shipping_address_inside_while_loop = $order_inside_while_loop->getShippingAddress();
			//var_dump($shipping_address);
			//echo "<hr>";
			$shipping_address_real = $shipping_address_inside_while_loop->getStreetFull();
			$shipping_state = $shipping_address_inside_while_loop->getRegion();
			$shipping_city = $shipping_address_inside_while_loop->getCity();
			$shipping_pincode = $shipping_address_inside_while_loop->getPostcode();
			$temp_shipping_address = $shipping_address_real.", ".$shipping_city.", ".$shipping_state.", ".$shipping_pincode;
			/* GETTING ORDER SHIPPING ADDRESS INFORMATION */


			/* GETTING ORDER BILLING PHONE NUMBER */
			$billing_address_inside_while_loop = $order_inside_while_loop->getBillingAddress();
			$temp_billing_number = $billing_address_inside_while_loop->getTelephone();
			/* GETTING ORDER BILLING PHONE NUMBER */



			$query2 = "select * from panelorderdetails where orderid=$orderid_inside_while_loop";
			$result2 = mysql_query($query2);

			$temp_product_array = array();
			while($row2 = mysql_fetch_assoc($result2))
			{
				$temp_date_of_delivery = $row2['dod'];


				$temp_shipping_type_main = $row2['shippingtype'];
				$temp_shipping_type_array = explode("_", $temp_shipping_type_main);
				$temp_shipping_type = $temp_shipping_type_array[0];


				$temp_vendor_id = $row2['vendor_id'];
				//echo "My Vendor ID : $temp_vendor_id";
				if($temp_vendor_id == "" || $temp_vendor_id == "0")
				{
					$temp_vendor_id = "NA";
					$temp_vendor_name = "Vendor not assigned";
				}
				else
				{
					$temp_vendor_name = $associated_vendors_array[$temp_vendor_id]['name'];
				}


				$self_order_flag = 0;
				/* THIS CODE CHECKS IF THIS ORDER IS TO BE SERVICED TO THIS SHOP OR NOT */
					if($temp_vendor_id == $shop_id)
					{
						$orders_details_array[$orderid_inside_while_loop]['self_order_flag'] = 1;
					}
					else
					{
						$orders_details_array[$orderid_inside_while_loop]['self_order_flag'] = 0;
					}
				/* THIS CODE CHECKS IF THIS ORDER IS TO BE SERVICED TO THIS SHOP OR NOT */



				/* GETTING EACH PRODUCT DETAILS */
					$temp_product_id = $row2['productid'];
					$product = Mage::getModel('catalog/product')->load($temp_product_id);
					$temp_product_image_url = $product->getImageUrl();
					$temp_product_name = $product->getName();
					$temp_product_vendor_desc = $row2['productvdescription'];
					$temp_product_array[$temp_product_id]['image'] = $temp_product_image_url;
					$temp_product_array[$temp_product_id]['vdescription'] = $temp_product_vendor_desc;
					$temp_product_array[$temp_product_id]['name'] = $temp_product_name;
				/* GETTING EACH PRODUCT DETAILS */



				/* GETTING SEPCIAL COMMENTS OF THE ORDER */
					$temp_order_comments=Mage::getModel('onestepcheckout/onestepcheckout')->getCollection()->addFieldToFilter('sales_order_id',$order_inside_while_loop->getEntityId());
					$temp_special_instructions = "";
					foreach($temp_order_comments as $each_temp_comment)
					{
						$temp_special_instructions_main = $each_temp_comment->getMwCustomercommentInfo();
						$temp_special_instructions_main = nl2br($temp_special_instructions_main);
						$temp_special_instructions .= "$temp_special_instructions_main<br>";
					}
				/* GETTING SEPCIAL COMMENTS OF THE ORDER */


				$query3 = "select max(state) as state from vendor_processing where orderid=$orderid_inside_while_loop";
				$result3 = mysql_query($query3);
				$row3 = mysql_fetch_assoc($result3);
				$temp_state = $row3['state'];
				
				if($temp_state == "")
				{
					$temp_state = "NA";
				}


				$orders_details_array[$orderid_inside_while_loop]['products'] = $temp_product_array;
				$orders_details_array[$orderid_inside_while_loop]['dod'] = $temp_date_of_delivery;
				$orders_details_array[$orderid_inside_while_loop]['address'] = $temp_shipping_address;
				$orders_details_array[$orderid_inside_while_loop]['recipientphone'] = $temp_billing_number;
				$orders_details_array[$orderid_inside_while_loop]['shippingtype'] = $temp_shipping_type;
				$orders_details_array[$orderid_inside_while_loop]['state'] = $temp_state;
				$orders_details_array[$orderid_inside_while_loop]['vendor_id'] = $temp_vendor_id;
				$orders_details_array[$orderid_inside_while_loop]['vendor_name'] = $temp_vendor_name;
				$orders_details_array[$orderid_inside_while_loop]['specialinstructions'] = $temp_special_instructions;
			}
		}
	}
	else
	{
		$orderd_flag = 0;
	}


	//print_r($orders_details_array);
?>


<table class='table_myorders'>
<tr class='table_heading_row'>
	<td style="padding:5px; width:4%;">
		<input type='button' id='print_b' class='buttons' value='Print Challan'>
		<br>
		<input type='button' id='print_b_card' class='buttons' style="margin-top:5px;" value='Print Card'>
	</td>
	<td style="width:10%;">Order ID</td>
	<td style="width:8%;">Delivery Date</td>
	<td style="width:18%;">Delivery Address</td>
	<td style="width:10%;">Recipient Number</td>
	<td style="width:8%;">Shipping Type</td>
	<td>Special Instructions</td>
	<!-- <td>Price</td> -->
	<td style="width:14%;">Controls</td>
	<td style="width:11%;">Status</td>
</tr>

<?php
if($orders_flag == 1)
{
	$orders_details_array_keys =  array_keys($orders_details_array);
	$row_counter=0;
	foreach($orders_details_array_keys as $orders_details_array_inside_foreach)
	{
		echo "<tr class='table_data_row' id='tr_$row_counter'>";





		echo "
			<td style='width:4%;' class='checkbox_td' id='checkboxtd_$row_counter'>
				<input type='hidden' id='orderid_hidden_$row_counter' value='$orders_details_array_inside_foreach'>
				<input type='checkbox' class='print_ch checkbox' id='printch_$orders_details_array_inside_foreach'>
			</td>";










		echo "
			<td class='order_id_div' id='orderid_$row_counter' style='width:10%;'>
				$orders_details_array_inside_foreach
				<div class='order_details_div' id='orderdetails_$row_counter'>";

				$temp_products_array = array_keys($orders_details_array[$orders_details_array_inside_foreach]['products']);
			echo "
				<table class='order_products_table'>
				<tr>";
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
			echo "</tr>
				</table>
				</div>
			</td>";







			$temp_dod = $orders_details_array[$orders_details_array_inside_foreach]['dod'];
			$temp_dod_unix = strtotime($temp_dod);
			$dod_varun = date("j M'y", $temp_dod_unix);
			echo "<td class='form_click' id='orderid_$row_counter' style='width:8%;'>".$dod_varun."</td>";









			echo "<td class='form_click' id='orderid_$row_counter' style='width:18%;'>".$orders_details_array[$orders_details_array_inside_foreach]['address']."</td>";
			







			echo "<td class='form_click' id='orderid_$row_counter' style='width:10%;'>".$orders_details_array[$orders_details_array_inside_foreach]['recipientphone']."</td>";








			if(strpos($orders_details_array[$orders_details_array_inside_foreach]['shippingtype'], "Midnight Delivery") !== false)
			{
				echo "<td class='form_click' id='orderid_$row_counter' style='width:9%; background-color:red;color:white;'>Midnight Delivery</td>";
			}
			else
			{
				echo "<td class='form_click' id='orderid_$row_counter' style='width:9%;'>Regular Delivery</td>";
			}








			echo "<td>".$orders_details_array[$orders_details_array_inside_foreach]['specialinstructions']."</td>";









			echo "
			<td class='form_click' id='orderid_$row_counter' style='width:14%;'>
				<form method='POST' action='vp_ordermain.php' id='form_$row_counter' target='_blank' style='margin:0px; padding:0px;'>
					<input type='hidden' name='orderid' value='$orders_details_array_inside_foreach'>
					<input type='hidden' name='flag' value='1'>
					<input type='hidden' name='display_flag' value='0'>
					<input type='submit' value='View Order' class='main_buttons_orange'>
					</form>
					<br style='line-height:1px;'>
				";

			if($orders_details_array[$orders_details_array_inside_foreach]['state'] == "NA" || ($orders_details_array[$orders_details_array_inside_foreach]['state'] == "0" && $orders_details_array[$orders_details_array_inside_foreach]['vendor_id'] == "NA"))
			{
				echo "<select id='vendor_id_dd_$row_counter'>";
				echo "<option value='Select'>Select</option>";
				foreach($associated_vendors_id_array as $each_vendor_id)
				{
					echo "<option value='$each_vendor_id'>".$associated_vendors_array[$each_vendor_id]['name']."</option>";
				}
				echo "</select>";
				echo "<br>";
				echo "<input type='button' value='Send Email' class='buttons sendemailbutton' id='sendemailb_$row_counter'>";
			}
			else
			{

			}
			//echo "</td>";









			if($orders_details_array[$orders_details_array_inside_foreach]['state'] == "0")
			{
				$style = "background-color:none;";
				$count_unacknowledged_orders += 1;
				$display_label = "Unacknowledged";
				if($orders_details_array[$orders_details_array_inside_foreach]['self_order_flag'] == 1)
				{
					//echo "<input type='button' id='activity_b_$row_counter' value='Accept Order' class='main_buttons_red varun' data-fancybox-type='iframe' href='vp_acknowledge.php?orderid=$orders_details_array_inside_foreach'>";
				}
			}
			else
			{
				if($orders_details_array[$orders_details_array_inside_foreach]['state'] == "1")
				{
					$style = "background-color:yellow;";
					$count_acknowledged_orders += 1;
					$display_label = "Acknowledged";
					if($orders_details_array[$orders_details_array_inside_foreach]['self_order_flag'] == 1)
					{
						//echo "<input type='button' id='activity_b_$row_counter' value='Ship Order' class='main_buttons_yellow varun' data-fancybox-type='iframe' href='vp_ship.php?orderid=$orders_details_array_inside_foreach'>";
					}
				}
				else
				{
					if($orders_details_array[$orders_details_array_inside_foreach]['state'] == "2")
					{
						$style = "background-color:blue; color:white;";
						$count_shipped_orders += 1;
						$display_label = "Shipped";
						if($orders_details_array[$orders_details_array_inside_foreach]['self_order_flag'] == 1)
						{
							//echo "<input type='button' id='activity_b_$row_counter' value='Order Delivered' class='main_buttons_green varun' data-fancybox-type='iframe' href='vp_delivered.php?orderid=$orders_details_array_inside_foreach'>";
						}

					}
					else
					{
						if($orders_details_array[$orders_details_array_inside_foreach]['state'] == "3")
						{
							$style="background-color:#008B00; color:white;";
							$count_delivered_orders += 1;
							$display_label = "Delivered";
						}
						else
						{
							if($orders_details_array[$orders_details_array_inside_foreach]['state'] == "4")
							{
								$style="background-color:#CD0000; color:white;";
								$display_label = "Cancelled without Refund";
							}
							else
							{
								if($orders_details_array[$orders_details_array_inside_foreach]['state'] == "5")
								{
									$style="background-color:#CD0000; color:white;";
									$display_label = "Cancelled with Refund";
								}
								else
								{
									$style="background-color:none;";
									$display_label = "";
								}
							}
						}
					}
				}
			}
			echo "</td>";


			
			echo "<td class='form_click' id='orderid_vendor_$row_counter' style='width:11%; $style'><u>$display_label</u><br><br>To : ".$orders_details_array[$orders_details_array_inside_foreach]['vendor_name']."</td>";
			





		echo "</tr>";
		$row_counter++;
	}
	echo "</table>";
}
else
{
	echo "</table>";
	echo "<br><br>";
	echo "<label class='notice_labels'>You have no order(s) by this data.</label>";
}
?>
