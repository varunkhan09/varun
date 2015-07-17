<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";

	$order_id = $_REQUEST['order_id'];
	$vendor_shop_id = $_REQUEST['vendor_shop_id'];

	$query_main = "select * from panelorderdetails where orderid=$order_id and shop_id_created=$shop_id";
	mysql_select_db($custom_database);
	$result_main = mysql_query($query_main);
	mysql_select_db($vendorshop_database);
	$date_of_delivery = "";
	$type_of_delivery = "";
	$merchandise_total = 0;
	$product_details_array = array();
	$product_price_array = array();
	while($row_main = mysql_fetch_assoc($result_main))
	{
		$date_of_delivery = $row_main['dod'];
		$type_of_delivery = $row_main['shippingtype'];

		$temp_product_id = $row_main['productid'];
		$product = Mage::getModel('catalog/product')->load($temp_product_id);
		$temp_product_name = $product->getName();
		$temp_product_image = $product->getImageUrl();
		$temp_product_quantity = $row_main['productquantity'];
		$temp_product_vendor_description = $row_main['productvdescription'];
		$temp_product_sku = $row_main['productsku'];

		$product_details_array[$temp_product_id]['name'] = $temp_product_name;
		$product_details_array[$temp_product_id]['image'] = $temp_product_image;
		$product_details_array[$temp_product_id]['quantity'] = $temp_product_quantity;
		$product_details_array[$temp_product_id]['vendor_description'] = $temp_product_vendor_description;
		$product_details_array[$temp_product_id]['sku'] = $temp_product_sku;



		if(strpos($temp_product_sku, 'custom:') !== false)
		{
			//echo "$temp_inner_product_sku is a custom product.<br>";
			$query_inner = "select a.product_id, a.product_quantity, a.item_id, a.item_quantity, b.price from pos_order_items_entity a inner join pos_vendor_item_price b on a.item_id = b.product_id where a.order_id = ".$order_id." and a.product_id = ".$temp_product_id." and b.self_shop_id = ".$shop_id." and b.vendor_shop_id = ".$vendor_shop_id;
			//echo $query_inner."<br>";
			$result_inner = mysql_query($query_inner);
			echo mysql_error();
			while($row_inner = mysql_fetch_assoc($result_inner))
			{
				$temp_inner_product_id = $row_inner['product_id'];
				$temp_inner_item_id = $row_inner['item_id'];
				$temp_inner_item_quantity = $row_inner['item_quantity'];
				$temp_inner_item_price = $row_inner['price'];
				$product_price_array[$temp_inner_product_id]['single_price'] += $temp_inner_item_quantity*$temp_inner_item_price;
				$product_price_array[$temp_inner_product_id]['total_price'] += $temp_inner_item_quantity*$temp_inner_item_price*$temp_product_quantity;
			}
		}
		else
		{
			//echo "$temp_inner_product_sku is a normal product.<br>";
			$query_inner = "select a.product_id, a.item_id, a.item_quantity, b.price from pos_products_item_details a inner join pos_vendor_item_price b on a.item_id = b.product_id where a.product_id = ".$temp_product_id." and b.self_shop_id = $shop_id and b.vendor_shop_id = ".$vendor_shop_id;
			//echo $query_inner."<br>";
			$result_inner = mysql_query($query_inner);
			echo mysql_error();
			while($row_inner = mysql_fetch_assoc($result_inner))
			{
				$temp_inner_product_id = $row_inner['product_id'];
				$temp_inner_item_id = $row_inner['item_id'];
				$temp_inner_item_quantity = $row_inner['item_quantity'];
				$temp_inner_item_price = $row_inner['price'];

				$product_price_array[$temp_inner_product_id]['single_price'] += $temp_inner_item_quantity*$temp_inner_item_price;
				$product_price_array[$temp_inner_product_id]['total_price'] += $temp_inner_item_quantity*$temp_inner_item_price*$temp_product_quantity;
			}
		}
	}

	$date_of_delivery_unix = strtotime($date_of_delivery);
	$date_of_delivery_final = date("jS F, Y", $date_of_delivery_unix);
	$type_of_delivery_array = explode("_", $type_of_delivery);
	$type_of_delivery_final = $type_of_delivery_array[0];
	if(strpos($type_of_delivery_final, "Regular Delivery") !== false)
	{
		$inner_query = "select attribute_id from pos_attributes where attribute_code = 'regular_delivery_charge'";
	}
	else
	{
		$inner_query = "select attribute_id from pos_attributes where attribute_code = 'midnight_delivery_charge'";
	}
	$inner_result = mysql_query($inner_query);
	$inner_row = mysql_fetch_assoc($inner_result);
	$temp_delivery_charge_attribute_id = $inner_row['attribute_id'];
	$inner_query = "select value from pos_shop_entity_decimal where attribute_id=$temp_delivery_charge_attribute_id and entity_id=$vendor_shop_id";
	$inner_result = mysql_query($inner_query);
	$inner_row = mysql_fetch_assoc($inner_result);
	$temp_delivery_charges = $inner_row['value'];
	$delivery_charges = (int)$temp_delivery_charges;




	$order = Mage::getModel('sales/order')->loadByIncrementId($order_id);

	$recipient = $order->getShippingAddress();
	$recipient_phone = $recipient->getTelephone();
	$recipient_firstname = $recipient->getFirstname();
	$recipient_lastname = $recipient->getLastname();
	$recipient_address = $recipient->getStreetFull();
	$recipient_city = $recipient->getCity();
	$recipient_state = $recipient->getRegion();
	$recipeint_pincode = $recipient->getPostcode();

	$recipient_full_name = $recipient_firstname." ".$recipient_lastname;
	$recipeint_full_address = $recipient_address.", ".$recipient_city.", ".$recipient_state.", ".$recipeint_pincode;



	$sender = $order->getBillingAddress();
	$sender_firstname = $sender->getFirstname();
	$sender_lastname = $sender->getLastname();

	$sender_full_name = $sender_firstname." ".$sender_lastname;




	$gift_message_id = $order->getGiftMessageId();
	$gift_message = Mage::getModel('giftmessage/message');
	$gift_message->load($gift_message_id);
	$message_sender = $gift_message->getData('sender');
	$message_recipient = $gift_message->getData('recipient');
	$message_body = $gift_message->getData('message');




	$temp_order_comments=Mage::getModel('onestepcheckout/onestepcheckout')->getCollection()->addFieldToFilter('sales_order_id',$order->getEntityId());
	$temp_special_instructions = "";
	foreach($temp_order_comments as $each_temp_comment)
	{
		$temp_special_instructions_main = $each_temp_comment->getMwCustomercommentInfo();
		$temp_special_instructions_main = nl2br($temp_special_instructions_main);
		$temp_special_instructions .= "$temp_special_instructions_main<br>";
	}
	$special_instructions = $temp_special_instructions;


	$body = "<html>";
	$body .= "<head>";
	$body .= "<meta http-equiv='Content-Type' content='text/html;charset=UTF-8'>";
	$body .= "</head>";
	$body .= "<body>";

	$body .= "<div style='display:inline; float:left; width:800px; border:4px solid #F0F0F0; background-color:#F0F0F0;'>";
		$body .= "<div style='float:left; display:inline; width:90%; margin:0px; padding:5px 0px 0px 5px;'>";
			$body .= "Hi,<br>";
			$body .= "Please acknowledge this order along with the cost.";
		$body .= "</div>";







		$body .= "<div style='float:left; display:inline; width:800px; margin:0px; margin-top:20px;'>";
			$body .= "<center>";
			$body .= "<label style='font-size:22px;'>Order Details</label><br>";
			$body .= "<table style='width:98%; border:2px solid white; background-color:white;'>";
				$body .= "<tr>";
					$body .= "<td style='width:50%; text-align:right;padding-right:10px; font-weight:bold;'>Order ID :</td>";
					$body .= "<td style='width:50%; '>$order_id</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Date of Delivery :</td>";
					$body .= "<td>$date_of_delivery_final</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Type of Delivery :</td>";
					$body .= "<td>$type_of_delivery_final</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Message From :</td>";
					$body .= "<td>$message_sender</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Message To :</td>";
					$body .= "<td>$message_recipient</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Message on Card :</td>";
					$body .= "<td>$message_body</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Special Instructions :</td>";
					$body .= "<td>$special_instructions</td>";
				$body .= "</tr>";
			$body .= "</table>";
			$body .= "</center>";
		$body .= "</div>";








		$body .= "<div style='float:left; display:inline; width:800px; margin:0px; margin-top:40px;'>";
			$body .= "<center>";
			$body .= "<label style='font-size:22px;'>Shipping Details</label><br>";
			$body .= "<table style='width:98%; border:2px solid white; background-color:white;'>";
				$body .= "<tr>";
					$body .= "<td style='width:50%; text-align:right; padding-right:10px; font-weight:bold;'>Sender Name :</td>";
					$body .= "<td style='width:50%;'>$sender_firstname</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Recipient Name :</td>";
					$body .= "<td>$recipient_full_name</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Recipient Contact Number :</td>";
					$body .= "<td>$recipient_phone</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='text-align:right;padding-right:10px; font-weight:bold;'>Recipient Address :</td>";
					$body .= "<td>$recipeint_full_address</td>";
				$body .= "</tr>";
			$body .= "</table>";
			$body .= "</center>";
		$body .= "</div>";







	echo "<form method='POST' action='vp_emailContent2.php'>";

		$body .= "<div style='float:left; display:inline; width:800px; margin:0px; margin-top:40px;'>";
			$body .= "<center>";
			$body .= "<label style='font-size:22px;'>Product Details</label><br>";
			$body .= "<table cellspacing='0' style='width:98%; border:2px solid #949494; text-align:center; background-color:white;'>";
				$body .= "<tr style='font-weight:bold; font-size:22px; background-color:#C2C2C2;'>";
					$body .= "<td>S. No.</td>";
					$body .= "<td>Product</td>";
					$body .= "<td>Product Description</td>";
					$body .= "<td>Quantity</td>";
					$body .= "<td>Unit Price</td>";
					$body .= "<td>Product Total</td>";
				$body .= "</tr>";

				$all_product_id_array = array_keys($product_details_array);
				$product_counter = 0;
				foreach($all_product_id_array as $each_product_id)
				{
					$product_counter++;
					$body .= "<tr>";
						$body .= "<td style='border:1px solid #949494;'>$product_counter</td>";
						$body .= "<td style='border:1px solid #949494;'>".$product_details_array[$each_product_id]['name']."<br><img src='".$product_details_array[$each_product_id]['image']."' style='height:150px; width:150px;'></td>";
						$body .= "<td style='border:1px solid #949494;'><textarea name='vendor_description_$each_product_id'>".$product_details_array[$each_product_id]['vendor_description']."</textarea></td>";
						$body .= "<td style='border:1px solid #949494;'>".$product_details_array[$each_product_id]['quantity']."</td>";
						$body .= "<td style='border:1px solid #949494;'>₹ ".number_format($product_price_array[$each_product_id]['single_price'])."</td>";
						$body .= "<td style='border:1px solid #949494;'>₹ ".number_format($product_price_array[$each_product_id]['total_price'])."</td>";
						$merchandise_total += $product_price_array[$each_product_id]['total_price'];
					$body .= "</tr>";
				}

				$body .= "<tr>";
					$body .= "<td colspan='5' style='font-weight:bold; text-align:right; border:1px solid #949494; padding:5px;'>Merchandise Total</td>";
					$body .= "<td style='border:1px solid #949494; padding:5px;'>₹ ".number_format($merchandise_total)."</td>";
				$body .= "</tr>";
			$body .= "</table>";
			$body .= "</center>";
		$body .= "</div>";






		$body .= "<div style='float:left; display:inline; width:800px; margin:0px; margin-top:40px; margin-bottom:20px;'>";
			$body .= "<center>";
			$body .= "<label style='font-size:22px;'>Price Details</label><br>";
			$body .= "<table style='width:98%; border:2px solid white; text-align:center; background-color:white;'>";
				$body .= "<tr>";
					$body .= "<td style='width:50%; text-align:right; font-weight:bold;'>Merchandise Total :</td>";
					$body .= "<td style='width:50%; text-align:left;'>₹ ".number_format($merchandise_total)."</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='width:50%; text-align:right; font-weight:bold;'>Delivery Charges :</td>";
					$body .= "<td style='width:50%; text-align:left;'>₹ ".number_format($delivery_charges)."</td>";
				$body .= "</tr>";

				$body .= "<tr>";
					$body .= "<td style='width:50%; text-align:right; font-weight:bold;'>Grand Total :</td>";
					$vendor_grand_total = $merchandise_total+$delivery_charges;
					$body .= "<td style='width:50%; text-align:left;'>₹ ".number_format($vendor_grand_total)."</td>";
				$body .= "</tr>";
			$body .= "</table>";
		$body .= "</div>";
	$body .= "</div>";

	$body .= "</body>";
	$body .= "</html>";



	echo $body;
	echo "<br><br><br>";
		echo "<input type='hidden' name='order_id' value='$order_id'>";
		echo "<input type='hidden' name='vendor_shop_id' value='$vendor_shop_id'>";
		echo "<input type='submit' class='buttons' value='Send Email'>";
	echo "</form>";
	echo "<br><br>";
	die();





















































	mysql_select_db($custom_database);
	$query = "select * from panelorderdetails where orderid = $order_id";
	$result = mysql_query($query);


	$query_verifying = "select orderid from vendor_processing where orderid = $order_id";
	echo $query_verifying."<br>";
	$result_verifying = mysql_query($query_verifying);
	echo mysql_error()."<br>";

	if(mysql_num_rows($result_verifying) == 0)
	{
		echo "It is a new Order to record.<br>";
		$query_final = "insert into vendor_processing (orderid, vendor_id, productid, quantity, unitprice, amount, shipping_charges, dod, delivery_type) values ";
		mysql_select_db($vendorshop_database);
		while($row = mysql_fetch_assoc($result))
		{
			$temp_productid = $row['productid'];
			$temp_productquantity = $row['productquantity'];
			$temp_productunitprice = $row['productunitprice'];
			$temp_amount = $temp_productquantity*$temp_productunitprice;
			$temp_dod = $row['dod'];


			$temp_shippingtype = $row['shippingtype'];
			$temp_shippingtype_array = explode("_", $temp_shippingtype);
			$temp_shippingtype = $temp_shippingtype_array[0];
			if(strpos($temp_shippingtype, "Regular Delivery") !== false)
			{
				$inner_query = "select attribute_id from pos_attributes where attribute_code = 'regular_delivery_charge'";
			}
			else
			{
				$inner_query = "select attribute_id from pos_attributes where attribute_code = 'midnight_delivery_charge'";
			}

			$inner_result = mysql_query($inner_query);
			$inner_row = mysql_fetch_assoc($inner_result);
			$temp_delivery_charge_attribute_id = $inner_row['attribute_id'];

			$inner_query = "select value from pos_shop_entity_decimal where attribute_id=$temp_delivery_charge_attribute_id and entity_id=$vendor_shop_id";
			$inner_result = mysql_query($inner_query);
			$inner_row = mysql_fetch_assoc($inner_result);
			$temp_delivery_charges = $inner_row['value'];
			$temp_delivery_charges = (int)$temp_delivery_charges;

			$query_final .= "($order_id, $vendor_shop_id, $temp_productid, $temp_productquantity, $temp_productunitprice, $temp_amount, $temp_delivery_charges, '$temp_dod', '$temp_shippingtype'), ";
		}

		$query_final = rtrim($query_final, ", ");
		$query_final_2 = "update panelorderdetails set vendor_id=$vendor_shop_id where orderid=$order_id";
		echo "<hr><hr>";

		echo $query_final."<br><br>".$query_final_2;
		mysql_select_db($custom_database);
		mysql_query($query_final);
		echo "<br><br>";
		echo mysql_error();
		mysql_query($query_final_2);
		echo "<br><br>";
		echo mysql_error();
	}
	else
	{
		echo "It is a rejected Order to be reassigned.<br>";
		
		$query_final = "update vendor_processing set vendor_id=$vendor_shop_id, state=0 where orderid=$order_id";
		echo $query_final."<br>";
		mysql_query($query_final);
		
		$query_final_2 = "update panelorderdetails set vendor_id=$vendor_shop_id where orderid=$order_id";
		echo $query_final_2;
		mysql_query($query_final_2);

		mysql_select_db($vendorshop_database);
		while($row = mysql_fetch_assoc($result))
		{
			$temp_shippingtype = $row['shippingtype'];
			$temp_shippingtype_array = explode("_", $temp_shippingtype);
			$temp_shippingtype = $temp_shippingtype_array[0];
			if(strpos($temp_shippingtype, "Regular Delivery") !== false)
			{
				$inner_query = "select attribute_id from pos_attributes where attribute_code = 'regular_delivery_charge'";
			}
			else
			{
				$inner_query = "select attribute_id from pos_attributes where attribute_code = 'midnight_delivery_charge'";
			}

			$inner_result = mysql_query($inner_query);
			$inner_row = mysql_fetch_assoc($inner_result);
			$temp_delivery_charge_attribute_id = $inner_row['attribute_id'];

			$inner_query = "select value from pos_shop_entity_decimal where attribute_id=$temp_delivery_charge_attribute_id and entity_id=$vendor_shop_id";
			$inner_result = mysql_query($inner_query);
			$inner_row = mysql_fetch_assoc($inner_result);
			$temp_delivery_charges = $inner_row['value'];
			$temp_delivery_charges = (int)$temp_delivery_charges;
		}

		mysql_select_db($custom_database);
		$query_final = "update vendor_processing set shipping_charges=$temp_delivery_charges where orderid=$order_id";
		echo $query_final;
		mysql_query($query_final);
	}











































	mysql_select_db($custom_database);
	$query = "select productid, productsku, productquantity, shop_id_created from panelorderdetails where orderid=$order_id";
	echo $query."<br><br>";
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result))
	{
		//var_dump($row);
		echo "<hr>";
		$temp_shop_id_created = $row['shop_id_created'];
		$temp_inner_product_id = $row['productid'];
		$temp_inner_product_quantity = $row['productquantity'];
		$temp_inner_product_sku = $row['productsku'];

		mysql_select_db($vendorshop_database);
		if(strpos($temp_inner_product_sku, 'custom:') !== false)
		{
			echo "$temp_inner_product_sku is a custom product.<br>";
			$query_inner = "select a.product_id, a.product_quantity, a.item_id, a.item_quantity, b.price from pos_order_items_entity a inner join pos_vendor_item_price b on a.item_id = b.product_id where a.order_id = ".$order_id." and a.product_id = ".$temp_inner_product_id." and b.self_shop_id = ".$shop_id." and b.vendor_shop_id = ".$vendor_shop_id;
			echo $query_inner."<br>";
			$result_inner = mysql_query($query_inner);
			echo mysql_error();


			$product_price_array = array();
			while($row_inner = mysql_fetch_assoc($result_inner))
			{
				echo "<br><br>";
				//var_dump($row_inner);
				//echo "<br><br>";

				$temp_inner_product_id = $row_inner['product_id'];
				//$temp_inner_product_quantity = $row_inner['product_quantity'];
				$temp_inner_item_id = $row_inner['item_id'];
				$temp_inner_item_quantity = $row_inner['item_quantity'];
				$temp_inner_item_price = $row_inner['price'];

				echo "<br>Product ID : $temp_inner_product_id<br>";
				echo "Product Quantity : $temp_inner_product_quantity<br>";
				echo "Item ID : $temp_inner_item_id<br>";
				echo "Item Quantity : $temp_inner_item_quantity<br>";
				echo "Item Item Price : $temp_inner_item_price<br>";

				$product_price_array[$temp_inner_product_id]['price'] += $temp_inner_item_quantity*$temp_inner_item_price*$temp_inner_product_quantity;

				//$temp_product_price = $temp_inner_item_quantity*$temp_inner_item_price*$quantity;
				//echo "Total : $temp_product_price<br><br>";
				//$products_information[$row['productid']]['vendor_price_calculated'] = $temp_product_price;
				//echo "<br>$temp_inner_product_id : $temp_product_price";
			}

			$all_products_array = array_keys($product_price_array);
			echo "<br><br>Final Queries for Product $temp_inner_product_id are : <br>";
			mysql_select_db($custom_database);
			foreach($all_products_array as $each_product)
			{
				$query_inner = "update vendor_processing set vendor_price = ".$product_price_array[$each_product]['price']." where orderid=$order_id and productid = $each_product";
				echo $query_inner."<br>";
				mysql_query($query_inner);
				$query_inner = "update panelorderdetails set vendor_price = ".$product_price_array[$each_product]['price']." where orderid=$order_id and productid = $each_product";
				echo $query_inner."<br><br>";
				mysql_query($query_inner);
			}
		}
		else
		{
			echo "$temp_inner_product_sku is a normal product.<br>";

			$query_inner = "select a.product_id, a.item_id, a.item_quantity, b.price from pos_products_item_details a inner join pos_vendor_item_price b on a.item_id = b.product_id where a.product_id = ".$temp_inner_product_id." and b.self_shop_id = $shop_id and b.vendor_shop_id = ".$vendor_shop_id;
			echo $query_inner."<br>";
			$result_inner = mysql_query($query_inner);
			echo mysql_error();


			$product_price_array = array();
			while($row_inner = mysql_fetch_assoc($result_inner))
			{
				echo "<br><br>";
				var_dump($row_inner);
				echo "<br><br>";

				$temp_inner_product_id = $row_inner['product_id'];
				$temp_inner_item_id = $row_inner['item_id'];
				$temp_inner_item_quantity = $row_inner['item_quantity'];
				$temp_inner_item_price = $row_inner['price'];

				echo "<br>Product ID : $temp_inner_product_id<br>";
				echo "Product Quantity : $temp_inner_product_quantity<br>";
				echo "Item ID : $temp_inner_item_id<br>";
				echo "Item Quantity : $temp_inner_item_quantity<br>";
				echo "Item Item Price : $temp_inner_item_price<br>";

				$product_price_array[$temp_inner_product_id]['price'] += $temp_inner_item_quantity*$temp_inner_item_price*$temp_inner_product_quantity;

				//$temp_product_price = $temp_inner_item_quantity*$temp_inner_item_price*$quantity;
				//echo "Total : $temp_product_price<br><br>";
				//$products_information[$row['productid']]['vendor_price_calculated'] = $temp_product_price;
				//echo "<br>$temp_inner_product_id : $temp_product_price";
			}

			$all_products_array = array_keys($product_price_array);
			echo "<br><br>Final Queries for Product $temp_inner_product_id are : <br>";
			mysql_select_db($custom_database);
			foreach($all_products_array as $each_product)
			{
				$query_inner = "update vendor_processing set vendor_price = ".$product_price_array[$each_product]['price']." where orderid=$order_id and productid = $each_product";
				echo $query_inner."<br>";
				mysql_query($query_inner);
				$query_inner = "update panelorderdetails set vendor_price = ".$product_price_array[$each_product]['price']." where orderid=$order_id and productid = $each_product";
				echo $query_inner."<br><br>";
				mysql_query($query_inner);
			}
		}
	}



























/*
//include 'vp_dbconfig.php';
include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
include $base_path_folder."/app/etc/dbconfig.php";
include $base_path_folder."/app/etc/mageinclusion.php";


$vendorCan_orderId=$_REQUEST['vendorCan_orderId'];
$vendorCan_orderIdArr=explode('_',$vendorCan_orderId);
$orderId=array_pop($vendorCan_orderIdArr);
$vendorName=array_shift($vendorCan_orderIdArr);
$vendorDescriptions="";
//$productType=fetchProductType($_SERVER['HTTP_REFERER']);

/*
if(!isset($vendorName))
	{
	echo "Please Choose a Vendor to Forward the Order";
	exit;
	}
*
////////To fetch the details of the ORDER by using the ORDER Increment ID////////
$theOrder = Mage::getModel('sales/order')->load($orderId,'increment_id');
$productImg="";
$stypeFromOrder = $theOrder->getShippingMethod(); //////Fetch Order's Shipping Type////////
	
	///////SHIPPING METHOD RENAME///////
	if($stypeFromOrder == "freeshipping_freeshipping")
		{
		$stypeFromOrder = "Daytime";
		}
	elseif($stypeFromOrder == "flatrate_flatrate")
		{
		$stypeFromOrder = "Midnight";
		}
	///////End To SHIPPING METHOD RENAME///////
	$shippingAddress = $theOrder->getShippingAddress();//// To Fetch the Address object for an ORDER////
	$shippingId = $shippingAddress->getId();////To fetch the Shipping ID////
	$address = Mage::getModel('sales/order_address')->load($shippingId);////To fetch the SHIPPING ADDRESS using the SHIPPING ID////
	$name=$address['firstname']." ".$address['lastname'];
	$pin=$address['postcode'];
	$city = $address['city'];
	$recipientStreet=$address['street'];
	$recipientTelephone=$address['telephone'];
	
	$giftMessageId=$theOrder->getGiftMessageId(); //////To fetch Gift Message ID//////
	$giftMessage = Mage::getModel('giftmessage/message');////////To fetch Object Of All Gift Messages///////
	if(!is_null($giftMessageId))
		{
		$giftMessage->load($giftMessageId);
		$senderName = $giftMessage->getData('sender');
		$recipientName = $giftMessage->getData('recipient');
		$i = $giftMessage->getData('message');
		}
	if(fetchVoiceMessageFlag($orderId))
	$cardMessage.="<br/><br/>Please call at +91-8010-386-062 from ".$recipientTelephone." to listen to your voice message.";
	$items = $theOrder->getAllItems();  ////To get all Ordered Items////
	//$itemcount=count($items);
	//$name=array();
	//$unitPrice=array();
	//$sku=array();
	//$qty=array();
	$countProducts=1;
	foreach ($items as $itemId => $item)
		{
		//$itemName = $item->getName();
		//echo $name[$itemId]." ";
		//$unitPrice[]=$item->getPrice();
		//echo $unitPrice[$itemId]." ";
		$sku=$item->getSku();
		//echo $sku[$itemId]." ";
		switch ($productType)
		{
		case "":
			if((stristr($sku, 'dark-ivory') == FALSE) && (stristr($sku,"sweet:") == FALSE) && (stristr($sku,"sweets:") == FALSE) && (stristr($sku,"plant") == FALSE))
				{
				$prodId=$item->getProductId();
				$product = Mage::getModel('catalog/product')->load($prodId);
				$vendorDescription= $product->getVendorDescription();
				if (intval($item->getQtyOrdered()) > 1)
					{
					echo "greater";
					$vendorDescriptions.=$countProducts.". <font color='red'> Quantity = ".intval($item->getQtyOrdered())."</font>\n".$vendorDescription."<hr>\n\n";
					$productImg.="<div align='right'><font color='red'>Quantity = ".intval($item->getQtyOrdered())."</font>   <img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200'></br></br></div><hr>";
					}
				else
					{
					$vendorDescriptions.=$countProducts.". ".$vendorDescription."<hr>\n\n";
					$productImg.="<div align='right'>   <img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200'></br></br></div><hr>";
					}
				//echo $product->getImage();
				//echo $ids[$itemId]." ";
				$prodOps=$item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
				$countProducts++;
				}	
		break;
		case "dark-ivory":
			if(stristr($sku, 'dark-ivory') == TRUE)
				{
				$prodId=$item->getProductId();
				$product = Mage::getModel('catalog/product')->load($prodId);
				$vendorDescription= $product->getVendorDescription();
				if (intval($item->getQtyOrdered()) > 1)
					{
					$vendorDescriptions.=$countProducts.". <font color='red'> Quantity = ".intval($item->getQtyOrdered())."</font>\n".$vendorDescription."<hr>\n\n";
					$productImg.="<div align='right'><font color='red'>Quantity = ".intval($item->getQtyOrdered())."</font>   <img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200'></br></br></div><hr>";
					}
				else
					{
					$vendorDescriptions.=$countProducts.". ".$vendorDescription."<hr>\n\n";
					$productImg.="<div align='right'>   <img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200'></br></br></div><hr>";
					}
				//echo $product->getImage();
				//echo $ids[$itemId]." ";
				$prodOps=$item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
				$countProducts++;
				}	
		break;
		case "plant":
			if(stristr($sku,"plant") == TRUE)
				{
				$prodId=$item->getProductId();
				$product = Mage::getModel('catalog/product')->load($prodId);
				$vendorDescription= "<u>".$product->getName()."</u><br><br>".$product->getVendorDescription();
				if (intval($item->getQtyOrdered()) > 1)
					{
					$vendorDescriptions.=$countProducts.". <font color='red'> Quantity = ".intval($item->getQtyOrdered())."</font>\n".$vendorDescription."<hr>\n\n";
					$productImg.="<div align='right'><font color='red'>Quantity = ".intval($item->getQtyOrdered())."</font>   <img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200'></br></br></div><hr>";
					}
				else
					{
					$vendorDescriptions.=$countProducts.". ".$vendorDescription."<hr>\n\n";
					$productImg.="<div align='right'>   <img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200'></br></br></div><hr>";
					}
				//echo $product->getImage();
				//echo $ids[$itemId]." ";
				$prodOps=$item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
				$countProducts++;
				}
		break;
		case "sweets":
			if((stristr($sku,"sweet:") == TRUE) || (stristr($sku,"sweets:") == TRUE))
				{
				$prodId=$item->getProductId();
				$product = Mage::getModel('catalog/product')->load($prodId);
				$vendorDescription= $product->getVendorDescription();
				if (intval($item->getQtyOrdered()) > 1)
					{
					$vendorDescriptions.=$countProducts.". <font color='red'> Quantity = ".intval($item->getQtyOrdered())."</font>\n".$vendorDescription."<hr>\n\n";
					$productImg.="<div align='right'><font color='red'>Quantity = ".intval($item->getQtyOrdered())."</font>   <img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200'></br></br></div><hr>";
					}
				else
					{
					$vendorDescriptions.=$countProducts.". ".$vendorDescription."<hr>\n\n";
					$productImg.="<div align='right'>   <img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200'></br></br></div><hr>";
					}
				//echo $product->getImage();
				//echo $ids[$itemId]." ";
				$prodOps=$item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
				$countProducts++;
				}
		break;
		default :
			echo "Unknown Product Type";
		}
		//$qty[]=$item->getQtyToInvoice();
		//echo $qty[$itemId]." "."<br>";		
		//var_dump($prodOps);
		foreach ($prodOps['options'] as $option)  ////To fetch options for each product one by one////
			{
			//var_dump( $option);
			$optionTitle = $option['label'];
			//var_dump($optionId);
			if(stristr($optionTitle,"Date of Delivery(DD/MM/YYYY)")==TRUE)
				{
				/////////////////////////////To Fetch just the date in DD-MM-YYYY from YYYY-MM-DD HH:MM:SS///////////////////////////
				$optionRevValue =$option['option_value'];
				$optionValueDateTimeArr = explode(" ",$optionRevValue); ////to break string into Array////
				$optionValueDateNoTime = array_shift($optionValueDateTimeArr); ////to fetch First Element from array////
				$optionValueDateArr = explode("-", $optionValueDateNoTime); ////break string into array////
				$revArr = array_reverse($optionValueDateArr); //// to reverse the array ////
				}
			if(stristr($optionTitle,"Shipping Type")==TRUE)
				{
				if(stristr($customShippingMethod, 'Midnight') == FALSE)
				$customShippingMethod = $option['value'];
				}
			}
		}
$modifiedDOD = dateIfModified($orderId);
//////To check If date is Modified and SET it to Modified Date If True//////
if($modifiedDOD)
$revArr = explode("-", $modifiedDOD);
switch ($revArr[1]) /////To Display Month's Name Instead Of Number//////
	{
	case 1:
	$revArr[1]="Jan";
	break;
	case 2:
	$revArr[1]="Feb";
	break;
	case 3:
	$revArr[1]="March";
	break;
	case 4:
	$revArr[1]="April";
	break;
	case 5:
	$revArr[1]="May";
	break;
	case 6:
	$revArr[1]="June";
	break;
	case 7:
	$revArr[1]="July";
	break;
	case 8:
	$revArr[1]="August";
	break;
	case 9:
	$revArr[1]="Sep";
	break;
	case 10:
	$revArr[1]="Oct";
	break;
	case 11:
	$revArr[1]="Nov";
	break;
	case 12:
	$revArr[1]="Dec";
	break;
	}
$optionValue=implode("-", $revArr); //// to make a string from array////
////////////////////////End To Fetch just the date in DD-MM-YYYY from YYYY-MM-DD HH:MM:SS/////////////////////
$date = $optionValue;
////////////HTML////////////


echo "<form method='POST' action='vp_SendEmail.php'>
<input type='hidden' name='vendorName' value='".$vendorName."'>
<input type='hidden' name='orderId' value='".$orderId."'>
Hello<br/><br/>
Please acknowledge the below order along with the cost :- <br/><br/>
<b>Order No.: - </b><font color='red'>".$orderId."</font>";
if(!is_null($recipientName))
echo "<br/><br/><b>Recipient Name : </b>".$recipientName;
else
echo "<br/><br/><b>Recipient Name : </b>".$name;
if(!is_null($cardMessage))
echo "<br/><br/><b>Message On Card</b> : ".$cardMessage;
else
echo "<br/><br/><b>Message On Card</b> : Best Wishes!";
if(!is_null($senderName))
echo "<br/><br/><b>Sender Name : </b>".$senderName;
//else
//echo "<br/><br/><b>Sender Name : </b>";
echo "<br/><br/><b>Delivery Address:</b><br/>".
$name."<br/>".
$recipientStreet."<br/>".
$city."-".$pin."<br/><br/>
<b>Recipient Contact No.</b>: ".$recipientTelephone."</br></br></br>
<b>Date Of Delivery : </b><font color='red'><b>".$date."</b></font></br></br>";
echo "<table><td><b>Special Instructions:</b></br>
<textarea name='specialInst' cols='50' rows='5'>".fetchCommentOnOrderId($orderId)."</textarea></br></br></br>
<b>Product Description: </b></br>
<textarea name='productDesc' cols='50' rows='5'>".$vendorDescriptions."</textarea></br></br></br></td><td>";
echo $productImg;
echo "</td>";	

if($stypeFromOrder==$customShippingMethod)
	{
	$deliveryType=$customShippingMethod;
	echo "Select Delivery Type : <select id='dilType' name='dilType'>
	<option value='".$deliveryType."' selected>".$deliveryType."</option>
	<option value='Daytime'>Daytime</option>
	<option value='Midnight'>Midnight</option>";
	}
else
	{
	if(stristr($stypeFromOrder, 'Midnight') == TRUE)
	echo "<b>Order Shipping Method : </b><font color='red'>".$stypeFromOrder."</font></br>";
	else
	echo "<b>Order Shipping Method : </b>".$stypeFromOrder."</br>";
	if(stristr($customShippingMethod, 'Midnight') == TRUE)
	echo "<b>Custom Shipping Method : </b><font color='red'>".$customShippingMethod."</font></br></br>";
	else
	echo "<b>Custom Shipping Method : </b>".$customShippingMethod."</br></br>";
	

	echo "<br>
	<textarea id='varun' name='varun' rows='15' cols='100'></textarea><br><br>";
?>
	<script src="jquery-latest.min.js"></script>
	<script>
	$(document).ready(function() {
		$.ajax({
			type:'GET',
			url:'../../../knowproductprice/knowpriceoforder2.php',
			data:
			{
				order_id:'<?php echo $orderId; ?>',
				vendor_name:'<?php echo $vendorName; ?>',
			},
			success:function(message)
			{
				$('#varun').val(message);
			}
		});
	});
	</script>

<?php
	echo "Select Delivery Type : <select id='dilType' name='dilType'>
	<option value='Daytime'>Daytime</option>
	<option value='Midnight'>Midnight</option>";
	}
echo "<b>CONFIRM  </b><input type='hidden' name='productType' value='".$productType."'>";











/****** VARUN : THIS IS CODE FOR HANDELING FLABERRY SELF ORDERS ******
$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
$billing_address = $order->getBillingAddress();
$billing_name_first = $billing_address->getFirstname();

if(strpos($vendorName, "Flaberry - Self") !== false && strpos($billing_name_first, "Vendor") !== false)
{
	$flaberry_self_flag = 1;
}
else
{
	$flaberry_self_flag = 0;
}


echo "<input type='hidden' name='flaberry_self_flag' value='$flaberry_self_flag'>";
if($flaberry_self_flag == '1')
{
	echo "<input type='submit' name='send' value='Send to Self'></form></body></html>";
}
else
{
	echo "<input type='submit' name='send' value='Send Email'></form></body></html>";
}
/****** VARUN : THIS IS CODE FOR HANDELING FLABERRY SELF ORDERS ******














///////////////End To HTML///////////////		

function dateIfModified($orderId)
	{
	$q= "SELECT comments_orders_modified_date FROM comments_orders WHERE comments_orders_orderid = '$orderId' LIMIT 1";
	$result_comments_orders = mysql_query($q);
	$value = mysql_fetch_array($result_comments_orders);
	return $value['comments_orders_modified_date'];
	}
	
function fetchCommentOnOrderId($orderId)/////Fetch Comment from comments_orders(also contains Modified date) if exists/////
	{
	$q = "SELECT  comments_orders_comment from comments_orders WHERE comments_orders_orderid = '".$orderId."'"; ////SQL Query////
	$result_comments_orders_comment = mysql_query($q); ////Result Set for the SQL QUERRY's Result////
	$num_rows = mysql_num_rows($result_comments_orders_comment);
	$value = mysql_fetch_array($result_comments_orders_comment);
	if(($num_rows == 0) || (($num_rows>0) && ($value['comments_orders_comment'] == NULL)))
	$comment = "No";//fetchCustCommentOnOrderId($orderId);
	elseif($num_rows>0 && $value['comments_orders_comment'] != NULL)
	$comment = $value['comments_orders_comment'];
	return $comment;
	}

function fetchCustCommentOnOrderId($orderId)
	{
	$orders = Mage::getModel('sales/order')->getCollection()->addAttributeToSelect("*")->addAttributeToFilter('increment_id', ($orderId));
	foreach($orders as $order)
	$actualOrderId=$order->getId();
	$_orders=Mage::getModel('onestepcheckout/onestepcheckout')->getCollection()->addFieldToFilter('sales_order_id',$actualOrderId);
	foreach($_orders as $_order)
	return $_order->getMwCustomercommentInfo();
	}
function fetchProductType($baseUrl)
	{
	if(strstr($baseUrl,"fcbcorders.php") == TRUE)
	return "";
	if(strstr($baseUrl,"chocolateorders.php") == TRUE)
	return "dark-ivory";
	else if(strstr($baseUrl,"sweetorders.php") == TRUE)
	return "sweets";
	else if(strstr($baseUrl,"plantorders.php") == TRUE)
	return "plant";
	}
function fetchVoiceMessageFlag($orderId)
	{
	$theOrder = Mage::getModel('sales/order')->load($orderId,'increment_id');
	$actualOrderId = $theOrder->getId();
	$q = "SELECT floshowers.sales_order_custom.id from floshowers.sales_order_custom WHERE floshowers.sales_order_custom.order_id = '".$actualOrderId."' LIMIT 1"; ////SQL Query////
		$result_voice_msg = mysql_query($q); ////Result Set for the SQL QUERRY's Result////
		$num_rows = mysql_num_rows($result_voice_msg);
		if($num_rows>0)
			{
			return TRUE;
			}
		elseif($num_rows == 0)
			{
			return FALSE;
			}
	}
*/
?>