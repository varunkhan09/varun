<html>
<head>
	<script type="text/javascript" src="jquery-latest.min.js"></script>
	<link rel="stylesheet" href="fancyBox/source/jquery.fancybox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/jquery.fancybox.pack.js"></script>
	<link rel="stylesheet" href="fancyBox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/helpers/jquery.fancybox-media.js"></script>
	<link rel="stylesheet" href="fancyBox/source/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancyBox/source/helpers/jquery.fancybox-thumbs.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	<style>
		.main_buttons
		{
			margin-left: 2%; border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}
		.main_buttons:hover
		{
			background-color: #00688B; cursor:pointer;
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

		.main_buttons_green1
		{
			margin-left: 2%; border-radius: 6px; background-color: #6E8B3D; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}

		.main_buttons_green1:hover
		{
			background-color: #556B2F; cursor:pointer;
		}

		.main_buttons_red_fire
		{
			margin-left: 2%; border-radius: 6px; background-color: #FF3030; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}

		.main_buttons_red_fire:hover
		{
			background-color: #CD2626; cursor:pointer;
		}

		.main_buttons_cancel
		{
			background-color:#FF0000;
		}

		.main_buttons_cancel:hover
		{
			background-color:#CD0000;
		}

		.main_buttons_close
		{
			background-color:#FF0000;
		}

		.main_buttons_close:hover
		{
			background-color:#CD0000;
		}

		.main_buttons_clicked
		{
			background-color:#CD8500;
		}

		.main_divs
		{
			float:left; display:inline; width:98%; margin:0% 0% 0% 1%; border-bottom:3px solid #00B2EE;
		}

		.main_divs_last
		{
			float:left; display:inline; width:98%; margin:0% 0% 0% 1%;
		}

		.first_div
		{
			float:left; display:inline; width:96%; margin:0% 0% 1% 2%; background-color:#F7F7F7; border:2px solid #00B2EE; border-radius: 4px;
		}

		.heading_labels
		{
			 font-size:24px; color:#00688B; font-family: Open Sans;
		}

		.data_labels_normal
		{
			font-family: 'Raleway', sans-serif;
		}

		.data_labels_bold
		{
			font-family: 'Raleway', sans-serif; font-weight:bold;
		}

		.main_buttons_without_form
		{
			border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}

		.main_buttons_without_form:hover
		{
			background-color: #00688B; cursor:pointer;
		}

		.heading
		{
			 font-size:32px; color:#009ACD; font-weight:bold;
		}

		.main_div1_0
		{
			float:left; display:inline; width:50%; margin: 10px 0px 4px 0px;
		}

		.main_div1_1
		{
			 float:right; display:inline; width:50%; text-align:right; margin: 10px 0px 4px 0px;
		}

		.main_div1_2
		{
			display:block; float:left; width:100%; text-align:center; margin-top:20px;
		}

		.main_div2_1
		{
			 float:left; display:inline; width:100%; margin:15px 0px 0px 2px;
		}

		.main_div2_2_0
		{
			 float:left; display:inline; width:32%; text-align:left; margin:50px 10px 0px 0px; border-right:2px solid #00B2EE;
		}

		.main_div2_2_1
		{
			 float:left; display:inline; width:32%; text-align:left; margin:50px 10px 0px 0px; border-right:2px solid #00B2EE;
		}

		.main_div2_2_2
		{
			 float:left; display:inline; width:32%; text-align:left; margin:50px 0px 0px 0px;
		}

		.main_div3_1_0
		{
			float:left; display:inline; width:32%; text-align:left; margin:0px 10px 0px 0px; border-right:2px solid #00B2EE;
		}

		.main_div3_1_1
		{
			float:left; display:inline; width:32%; text-align:left; margin:0px 10px 0px 0px; border-right:2px solid #00B2EE;
		}

		.main_div3_1_2
		{
			float:left; display:inline; width:32%; text-align:left; margin:0px 0px 0px 0px;
		}

		.main_div2_3
		{
			float:left; display:inline; width:20%; text-align:left;
		}

		.main_div3_2
		{
			float:left; display:inline; width:100%; text-align:left;
		}

		.main_div3_3
		{
			 float:left; display:inline; width:100%; text-align:left; margin:50px 0px 0px 0px;
		}

		.main_div5_1
		{
			 float:left; display:inline; width:100%; margin:5px 0px 5px 0px; border-bottom:1px solid #00B2EE; /*http://www.lncrnadb.org/static/images/largeWaiting.png*/
		}

		.main_div_3_container
		{
			
		}
	</style>
</head>
<body>

<?php
	//include "vp_footer.php";
?>

<div id='loading_div' style='display:none; position:fixed; width:100%; height:100%; text-align:center; vertical-align:middle; background-color:gray; opacity:0.5;'>
	<img src='http://www.theappmadeinheaven.com/resources/images/PleaseWait.gif' style='margin-top:20%; width:5%; height:10%;'>
</div>

<div id="dialog-confirm-msg"></div>

<?php
if($_REQUEST['display_flag'] != "0")
{
?>
	<center>
	<label class='heading'>View Order Panel</label>
	</center>
	<br>
	<br>
	<center>
		<form style='margin:0px; padding:0px;' method='POST'>
			<input type='textbox' name='orderid' placeholder='Enter Order ID' style='height:30px;'><input type='hidden' name='flag' value='1'><input type='submit' value='Load' class='main_buttons_without_form'>
		</form>
	<br>
<?php
}
if(!isset($_REQUEST['flag']))
{

}
else
{
	if($_REQUEST['flag'] == "1")
	{
		//include 'vp_dbconfig.php';
		ob_start();
		include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
		include $base_path_folder."/app/etc/dbconfig.php";
		include $base_path_folder."/app/etc/mageinclusion.php";
		$orderid = $_REQUEST['orderid'];

		$products_information = array();
		$sender_information = array();
		$recipient_information = array();
		$order_information = array();

		mysql_select_db($custom_database);
		$query = "select * from panelorderdetails where orderid=".$_REQUEST['orderid'];
		$result = mysql_query($query);
		
		if(mysql_num_rows($result)>0)
		{
			$order = Mage::getModel('sales/order')->loadByIncrementId($_REQUEST['orderid']);
			//var_dump($order);
			$address = $order->getShippingAddress();
			$recipient_information['phone'] = $address->getTelephone();
			$recipient_information['firstname'] = $address->getFirstname();
			$recipient_information['middlename'] = $address->getMiddlename();
			$recipient_information['lastname'] = $address->getLastname();
			$recipient_information['address'] = $address->getStreetFull();
			$recipient_information['city'] = $address->getCity();
			$recipient_information['state'] = $address->getRegion();
			$recipient_information['email'] = $address->getEmail();

			$address = $order->getBillingAddress();
			$sender_information['phone'] = $address->getTelephone();
			$sender_information['firstname'] = $address->getFirstname();
			$sender_information['middlename'] = $address->getMiddlename();
			$sender_information['lastname'] = $address->getLastname();
			$sender_information['address'] = $address->getStreetFull();
			$sender_information['city'] = $address->getCity();
			$sender_information['state'] = $address->getRegion();
			$sender_information['email'] = $address->getEmail();
			$sender_information['pincode'] = $address->getPostcode();

			$gift_message_id = $order->getGiftMessageId();
			$gift_message = Mage::getModel('giftmessage/message');
			$gift_message->load($gift_message_id);
			$order_information['message']['sender'] = $gift_message->getData('sender');
			$order_information['message']['recipient'] = $gift_message->getData('recipient');
			$order_information['message']['message'] = $gift_message->getData('message');
			$order_information['status'] = $order->getStatus();

			$order_information['magento_grand_total'] = $order->getGrandTotal();
			

			$merchandise_product_grand_total = 0;
			$vendor_product_grand_total = 0;
			$service_charges = 0;
			$shippingcharges = 0;
			$vendor_product_total = 0;
			$vendor_grand_total = 0;
			while($row = mysql_fetch_assoc($result))
			{
				$quantity = 0;
				$unitprice = 0;
				$vendorprice = 0;
				$products_information[$row['productid']]['sku'] = $row['productsku'];
				$products_information[$row['productid']]['quantity'] = $row['productquantity'];
				$products_information[$row['productid']]['unitprice'] = $row['productunitprice'];
				$products_information[$row['productid']]['sku'] = $row['productsku'];
				$products_information[$row['productid']]['vendor_description'] = $row['productvdescription'];
				$products_information[$row['productid']]['vendor_price_calculated'] = $row['vendor_price'];
				$vendor_product_total += $row['vendor_price'];

				$quantity = $row['productquantity'];
				$unitprice = $row['productunitprice'];
				$vendorprice = $row['vendor_price'];


				$vendor_id = $row['vendor_id'];
				if($vendor_id == "")
				{
					$vendor_id = 0;
					$flaberry_self_flag = 0;
				}
				else
				{
					$query_1 = "select vendor_name from vendors where vendor_id=$vendor_id";
					$result_1 = mysql_query($query_1);
					$row_1 = mysql_fetch_assoc($result_1);
					$vendor_name = $row_1['vendor_name'];

					if($vendor_name != "Flaberry - Self")
					{
						$flaberry_self_flag = 0;
					}
					else
					{
						$flaberry_self_flag = 1;
					}
				}


				//if($vendor_id != 0 && $vendorprice == "0")
				/*
				if($vendor_id != 0)
				{
					echo "I got here...$vendor_id && $vendorprice<br>";
					mysql_select_db($vendorshop_database);
					$query_inner = "select a.product_id, a.item_id, a.item_quantity, b.price from pos_products_item_details a inner join pos_vendor_item_price b on a.item_id = b.product_id where a.product_id = ".$row['productid']." and b.self_shop_id = $shop_id and b.vendor_shop_id = $vendor_id";
					echo $query_inner."<br>";
					$result_inner = mysql_query($query_inner);
					echo mysql_error();

					if(mysql_num_rows($result_inner) != 0)
					{
						while($row_inner = mysql_fetch_assoc($result_inner))
						{
							$temp_inner_item_id = $row_inner['item_id'];
							$temp_inner_product_id = $row_inner['product_id'];
							$temp_inner_item_quantity = $row_inner['item_quantity'];
							$temp_inner_item_price = $row_inner['price'];

							echo "Product ID : $temp_inner_product_id<br>";
							echo "Product Quantity : $quantity<br>";
							echo "Item ID : $temp_inner_item_id<br>";
							echo "Item Quantity : $temp_inner_item_quantity<br>";
							echo "Item Item Price : $temp_inner_item_price<br>";

							$temp_product_price = $temp_inner_item_quantity*$temp_inner_item_price*$quantity;
							echo "Total : $temp_product_price<br><br>";
							$products_information[$row['productid']]['vendor_price_calculated'] = $temp_product_price;
							//echo "<br>$temp_inner_product_id : $temp_product_price";
						}
					}
					else
					{
						$products_information[$row['productid']]['vendor_price_calculated'] = 0;
					}
				}
				else
				{
					echo "I got here...$vendor_id && $vendorprice";
					echo "I am not here.";
				}
				*/


				$product = Mage::getModel('catalog/product')->load($row['productid']);
				$products_information[$row['productid']]['image'] = $product->getImageUrl();
				$products_information[$row['productid']]['name'] = $product->getName();
				
				$merchandise_product_grand_total += $quantity*$unitprice;
				$vendor_product_grand_total += $quantity*$vendorprice;

				$recipient_information['pincode'] = $row['recipient_pincode'];
				$order_information['dod'] = $row['dod'];
				$order_information['shippingtype'] = $row['shippingtype'];
				$order_information['order_source'] = $row['order_source'];
				$order_information['account_type'] = $row['account_type'];
			}

			mysql_select_db($custom_database);
			$query5 = "select miscellaneous_charges, state, deliveryboy, deliveryboy_contact, shipping_charges from vendor_processing where orderid=$orderid limit 1";
			$result5 = mysql_query($query5);
			$row5 = mysql_fetch_assoc($result5);
			//var_dump($row5);
			$order_information['servicecharges'] = $row5['miscellaneous_charges'];
			$order_information['state'] = $row5['state'];
			$order_information['shipping_charges'] = $row5['shipping_charges'];
			//echo "Varun The Boss : ".$order_information['shipping_charges'];
			$order_information['deliveryboy_name'] = $row5['deliveryboy'];
			$order_information['deliveryboy_contact'] = $row5['deliveryboy_contact'];
			$service_charges = $row5['miscellaneous_charges'];

			//$order_information["grand_total"] += $order_information['servicecharges'];

			$temp_date = $order->getCreatedAtDate();
			$ts2 = strtotime($temp_date);
			$order_information["doc"] = date("d/m/Y", $ts2);


			$order_comments = $order->getAllStatusHistory();
			$string = '';
			foreach($order_comments as $each_comment)
			{
				$temp_id = $each_comment->getId();
				$temp_comment = $each_comment->getComment();
				$temp_date =  $each_comment->getCreatedAt();

				$order_information['comments'][$temp_id]['comment'] = $temp_comment;
				$order_information['comments'][$temp_id]['date'] = $temp_date;
			}
			echo "</center>";
			
			/*
			echo "<br><br>Sender Information<hr>";
			var_dump($sender_information);
			echo "<br><br>Recipient Information<hr>";
			var_dump($recipient_information);
			echo "<br><br>Product Information<hr>";
			var_dump($products_information);
			echo "<br><br>Order Information<hr>";
			var_dump($order_information);
			*/

			$filtered_shippingtype = explode("_", $order_information['shippingtype']);

			//This code fetches the shipping charges based on information if the order is Regular Delivery or Midnight Delivery...
			$temp_shipping_type = $filtered_shippingtype[0];
			mysql_select_db($custom_database);
			if(strpos($temp_shipping_type, "Midnight Delivery") !== false)
			{
				$shippingtype = "Midnight Delivery";
				$query = "select midnight_charges from vendor_shippingcharges where vendor_id=$vendor_id";
			}
			else
			{
				if(strpos($temp_shipping_type, "Regular Delivery") !== false)
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
				//echo "Ek pe bhi...$shippingcharges";
			}

			$order_information['merchandise_product_grand_total'] = $merchandise_product_grand_total;
			$order_information['vendor_product_grand_total'] = $vendor_product_grand_total;
			$order_information['servicecharges'] = $service_charges;
			$order_information['deliverycharges'] = $shippingcharges;
			//This code fetches the shipping charges based on information if the order is Regular Delivery or Midnight Delivery...
			
			$order_information["final_magento_price"] = $merchandise_product_grand_total;
			$order_information["final_vendor_price"] = $vendor_product_grand_total+$service_charges+$shippingcharges;


			$vendor_grand_total = $vendor_product_total+$order_information['shipping_charges']+$order_information['servicecharges'];
?>
			<div id="dialog-confirm-msg"></div>


			<div class='first_div'>
				<div id='main_div1' class='main_divs'>
					<div id='main_div0' class='main_div1_0'>
						<?php
						echo "<input type='button' id='cancel_order_b' value='Cancel this Order without Refund' class='main_buttons main_buttons_cancel' data-fancybox-type='iframe' href='vp_cancelorder.php?orderid=$orderid'>";
						echo "<input type='button' id='close_order_b' value='Cancel this Order with Refund' class='main_buttons main_buttons_close' data-fancybox-type='iframe' href='vp_closeorder.php?orderid=$orderid'>";

						if($order_information['state'] == 0 || $order_information['state'] == "")
						{
							//echo "<input type='button' id='update_vendor_price_b' value='Update Vendor Cost' class='main_buttons_green1'>";		//Commented because not needed now...the vendor price is now hard-coded and not to be changed..
						}
						
						//echo "<input type='button' id='update_vendor_price_b' value='Update Vendor Cost' class='main_buttons_green1'>";
						//echo "VARUN : ".$order_information['state'];
						//if($flaberry_self_flag == 1)
						//{
							if($order_information['state'] == "0")
							{
								echo "<input type='button' id='activity_b' value='Accept Order' class='main_buttons_red' data-fancybox-type='iframe' href='vp_acknowledge.php?orderid=$orderid'>";
								echo "<input type='button' id='reject_b' value='Reject Order' class='main_buttons_red_fire' data-fancybox-type='iframe' href='vp_reject.php?orderid=$orderid'>";
							}
							else
							{
								if($order_information['state'] == "1")
								{
									echo "<input type='button' id='activity_b' value='Ship Order' class='main_buttons_yellow' data-fancybox-type='iframe' href='vp_ship.php?orderid=$orderid'>";
								}
								else
								{
									if($order_information['state'] == "2")
									{
										echo "<input type='button' id='activity_b' value='Order Delivered' class='main_buttons_green' data-fancybox-type='iframe' href='vp_delivered.php?orderid=$orderid'>";
									}
									else
									{

									}
								}
							}
						//}
						?>
					</div>


					<div class='main_div1_1'>
					<!--	<input type='button' id='manage_b' value='Manage Order' class='main_buttons'> -->
					<!--		<input type='button' id='print_ticket_b' value='Print Super Ticket' class='main_buttons'> -->
						<form action='vp_printorder.php' method='POST' target='_blank' style='margin:0px; padding:0px; display:inline;'>
							<input type='hidden' name='orderid' value='<?php echo $orderid; ?>'>
							<input type='submit' id='print_receipt_b' value='Print Delivery Challan' class='main_buttons'>
						</form>

						<form action='vp_printmessageoncard.php' method='POST' target='_blank' style='margin:0px; padding:0px; display:inline;'>
							<input type='hidden' name='orderid' value='<?php echo $orderid; ?>_'>
							<input type='submit' id='print_msg_b' value='Print Message Card' class='main_buttons'>
						</form>

						<input type='button' id='return_b' value='Back to Main Menu' class='main_buttons'>
					</div>


					<div class='main_div1_2' <?php if($order_information['state'] < "2"){echo "style='display:none;'"; } ?>>
						<label class='data_labels_bold'>Delivery Boy Information : </label>
						<label class='data_labels_normal' id='delivery_boy_info_label'><?php echo $order_information['deliveryboy_name']."(".$order_information['deliveryboy_contact'].")"; ?></label>
					</div>
				</div>


				<div id='main_div2' class='main_divs'>
					<div class='main_div2_1'>
						<div  class='main_div2_3'><label class='data_labels_bold'>Order ID</label> : <label class='data_labels_normal'><?php echo $_REQUEST['orderid']; ?></label></div>
						<div  class='main_div2_3'><label class='data_labels_bold'>Order Type</label> : <label class='data_labels_normal'><?php echo $filtered_shippingtype[0]; ?></label></div>
						<div  class='main_div2_3'><label class='data_labels_bold'>Status</label> : <label class='data_labels_normal'><?php echo $order_information['status']; ?></label></div>
						
						<div  class='main_div2_3'><label class='data_labels_bold'>Creation Date</label> : <label class='data_labels_normal'>
							<?php
								$temp_dod = $order_information['cod'];
								$temp_dod_unix = strtotime($temp_dod);
								$dod_varun = date("j M'y", $temp_dod_unix);
								echo $dod_varun;
							?></label>
						</div>

						<div  class='main_div2_3'><label class='data_labels_bold'>Delivery Date</label> : <label class='data_labels_normal'>
							<?php
								$temp_dod = $order_information['dod'];
								$temp_dod_unix = strtotime($temp_dod);
								$dod_varun = date("j M'y", $temp_dod_unix);
								echo $dod_varun;
							?></label>
						</div>
					</div>

					<div class='main_div2_2_0'>
						<label class='heading_labels'>Billing Address</label>
					</div>

					<div class='main_div2_2_1'>
						<label class='heading_labels'>Shipping Address</label>
					</div>

					<div class='main_div2_2_2'>
						<label class='heading_labels'>Enclosure Card</label>
					</div>
				</div>







				<div id='main_div3' class='main_divs'>
					<div class='main_div_3_container'>
						<div class='main_div3_1_0'>
							<div class='main_div3_2'><label class='data_labels_bold'>Name</label> : <label class='data_labels_normal'><?php echo $sender_information['firstname']." ".$sender_information['middlename']." ".$sender_information['lastname']; ?></label></div>
							<div class='main_div3_2'><label class='data_labels_bold'>Contact</label> : <label class='data_labels_normal'><?php echo $sender_information['phone']; ?></label></div>
							<div class='main_div3_2'><label class='data_labels_bold'>Address</label> : <label class='data_labels_normal'><?php echo $sender_information['address'].", ".$sender_information['city'].", ".$sender_information['state'].", ".$sender_information['pincode']; ?></label></div>
							<div class='main_div3_2'><label class='data_labels_bold'>Email ID</label> : <label class='data_labels_normal'><?php echo $sender_information['email']; ?></label></div>
						</div>

						<div  class='main_div3_1_1'>
							<div class='main_div3_2'><label class='data_labels_bold'>Name</label> : <label class='data_labels_normal'><?php echo $recipient_information['firstname']." ".$recipient_information['middlename']." ".$recipient_information['lastname']; ?></label></div>
							<div class='main_div3_2'><label class='data_labels_bold'>Contact</label> : <label class='data_labels_normal'><?php echo $recipient_information['phone']; ?></label></div>
							<div class='main_div3_2'><label class='data_labels_bold'>Address</label> : <label class='data_labels_normal'><?php echo $recipient_information['address'].", ".$recipient_information['city'].", ".$recipient_information['state'].", ".$recipient_information['pincode']; ?></label></div>
							<!-- <div class='main_div3_2'><label class='data_labels_bold'>Email ID</label> : <label class='data_labels_normal'><?php //echo $recipient_information['email']; ?></label></div> -->
						</div>

						<div  class='main_div3_1_2'>
							<div class='main_div3_2'><label class='data_labels_bold'>From</label> : <label class='data_labels_normal'><?php echo $order_information['message']['sender']; ?></label></div>
							<div class='main_div3_2'><label class='data_labels_bold'>To</label> : <label class='data_labels_normal'><?php echo $order_information['message']['recipient']; ?></label></div>
							<div class='main_div3_2'><label class='data_labels_bold'>Message</label> : <label class='data_labels_normal'><?php echo $order_information['message']['message']; ?></label></div>
						</div>
						
						<div class='main_div3_3'>
							<label class='heading_labels'>Merchandise</label>
						</div>
					</div>
				</div>






				<div id='main_div5' class='main_divs'>
					<?php
					$counter=1;
					echo "<div class='main_div5_1'>";

					$temp_product_ids_array = array_keys($products_information);
					//var_dump($temp_product_ids_array);
					$product_id_counter=0;
					foreach($products_information as $each_product)
					{
						$temp_product_id = array_keys($each_product);
						
						if($counter == 3)
						{
							echo "</div>";
							echo "<div style='float:left; display:inline; width:100%; margin:0px 0px 5px 0px; border-bottom:1px solid #00B2EE; border-left'>";
							$counter=1;
						}
						if($counter == 1)
						{
							echo "<div style='float:left; display:inline; width:49%; border-right:0px solid #00B2EE;'>";
						}
						else
						{
							echo "<div style='float:left; display:inline; width:49%; padding-left:1%;'>";
						}
						echo "<div style='float:left; display:inline; width:20%; margin-bottom:5px;'><img src='".$each_product['image']."' style='width:100%; height:auto;'></div>";
						echo "<div style='float:left; display:inline; width:64%; margin-bottom:5px;'>";
						echo "<div style='float:left; display:inline; width:100%;'><label class='data_labels_bold'>Name</label> : <label class='data_labels_normal'>".$each_product['name']."</label></div>";
						echo "<div style='float:left; display:inline; width:100%;'><label class='data_labels_bold'>SKU</label> : <label class='data_labels_normal'>".$each_product['sku']."</label></div>";
						echo "<div style='float:left; display:inline; width:100%;'><label class='data_labels_bold'>Description</label> : <label class='data_labels_normal'>".$each_product['vendor_description']."</label></div>";
						echo "<div style='float:left; display:inline; width:100%;'><label class='data_labels_bold'>Unit Price</label> : <label class='data_labels_normal'>".$each_product['unitprice']."</label></div>";
						echo "</div>";

						echo "<div style='float:left; display:inline; width:16%; margin-bottom:5px;'>";
						echo "<div style='float:left; display:inline; width:100%; text-align:center; border:1px #00B2EE solid; border-bottom:none;'><label class='data_labels_bold' style='font-size:14px;'>Quantity</label> <br> <label class='data_labels_normal'>".$each_product['quantity']."</label></div>";
						$temp_total = $each_product['unitprice']*$each_product['quantity'];
						echo "<div style='float:left; display:inline; width:100%; text-align:center; border:1px #00B2EE solid; border-bottom:none;'><label class='data_labels_bold' style='font-size:14px;'>Total</label> <br> <label class='data_labels_normal'>".$temp_total."</label></div>";
						//echo "<div style='float:left; display:inline; width:100%; text-align:center; border:1px #00B2EE solid; overflow:hidden;'><label class='data_labels_bold' style='font-size:14px;'>Vendor Cost</label> <br> <label class='data_labels_normal'>".$each_product['vendor_price']."</label></div>";
						echo "<div style='float:left; display:inline; width:100%; text-align:center; border:1px #00B2EE solid; overflow:hidden;'><label class='data_labels_bold' style='font-size:14px;'>Vendor Cost</label> <br> <label class='data_labels_normal'>".$each_product['vendor_price_calculated']."</label></div>";
						echo "</div>";
						echo "</div>";
						$counter++;
						$product_id_counter++;
					}
					echo "</div>";
					?>
					<div style='float:left; display:inline; width:32%; text-align:left; margin:50px 10px 0px 0px; border-right:2px solid #00B2EE;'>
						<label class='heading_labels'>Payments</label>
					</div>

					<div style='float:left; display:inline; width:32%; text-align:left; margin:50px 10px 0px 0px; border-right:2px solid #00B2EE;'>
						<label class='heading_labels'>Totals</label>
					</div>

					<div style='float:left; display:inline; width:32%; text-align:left; margin:50px 0px 0px 0px;'>
						<label class='heading_labels'>Vendor Total</label>
					</div>
				</div>










				<div id='main_div5point1' class='main_divs'>
					<div style='display:inline; float:left; width:32%; margin:0px 10px 0px 0px; border-right:2px solid #00B2EE;'>
						<label class='data_labels_bold'>Order Payment Status</label> : <label class='data_labels_normal'><?php echo $order_information['orderstatus']; ?></label><br>
						<label class='data_labels_bold'>Cash Amount</label> : <label class='data_labels_normal'><?php echo $order_information['magento_grand_total']; ?></label>
						<br><br><br><br>
					</div>


					<div style='display:inline; float:left; width:32%; margin:0px 10px 0px 0px; border-right:2px solid #00B2EE;'>
						<label class='data_labels_bold'>Merchandise Total</label> : <label class='data_labels_normal'><?php echo $order_information["merchandise_product_grand_total"]; ?><br>
						<label class='data_labels_bold'>Grand Total</label> : <label class='data_labels_normal'><?php echo $order_information["final_magento_price"]; ?>
						<br><br><br><br>
					</div>

					<div style='display:inline; float:left; width:32%; margin:0px 0px 0px 0px;'>
						<label class='data_labels_bold'>Vendor Product Total</label> : <label class='data_labels_normal'><?php echo $vendor_product_total; ?><br>
						<label class='data_labels_bold'>Delivery Charges</label> : <label class='data_labels_normal'><?php echo $order_information['shipping_charges']; ?></label><br>
						<label class='data_labels_bold'>Miscellaneous Charges</label> : <label class='data_labels_normal'><?php echo $order_information['servicecharges']; ?></label><br>
						<label class='data_labels_bold'>Total Cost</label> : <label class='data_labels_normal'><?php echo $vendor_grand_total; ?></label>
					</div>

<!--
					<div style='float:left; display:inline; width:100%; text-align:left; margin:50px 0px 0px 0px;'>
						<label class='heading_labels'>Order Comments</label>
					</div>
-->
				
					<div style='float:left; display:inline; width:100%; text-align:left; margin:50px 0px 0px 0px;'>
						<label class='heading_labels'>Special Instructions</label>
					</div>
				</div>






<!-- 

				<div id='main_div5point2' class='main_divs'>
					<div style='display:inline; float:left; width:50%; margin:2px 0px 0px 0px;'>
					<?php
						$order_comments=Mage::getModel('onestepcheckout/onestepcheckout')->getCollection()->addFieldToFilter('sales_order_id',$order->getEntityId());
						foreach($order_comments as $each_comment)
						{
							$temp_special_instructions = $each_comment->getMwCustomercommentInfo();
							$temp_special_instructions = nl2br($temp_special_instructions);
							echo "<label class='data_labels_normal'>".$temp_special_instructions."</label>";
							echo "<br>";
						}
					?>
					</div>

				</div>
 -->









				<div id='main_div6' class='main_divs_last'>
					<div style='float:left; display:inline; width:100%; text-align:left; margin:10px 0px 20px 0px;'>
						<div style='float:left; display:inline; width:50%; text-align:center;'>	
							<div style='float:left; display:inline; text-align:left;'>
								<textarea rows='5' cols='70' id='comment_t'></textarea>
							</div>

							<div style='float:left; display:inline; text-align:left; margin:25px 0px 0px 0px;'>
								<input type='button' class='main_buttons_without_form' id='comment_b' value='Add Comment to Order'>
							</div>

							<div style="float:left; display:block; text-align:left; font-family:'Raleway', sans-serif; margin:5px 0px 0px 0px; width:100%;" id='comment_r'>

							</div>
						</div>

						<div style='float:left; display:inline; width:50%; text-align:left;' id='comments_data'>
						<?php
						$order_comments = array_keys($order_information['comments']);  //$order_information['comments'][$temp_id]['comment'] = $temp_comment;
						foreach($order_comments as $each_comment)
						{
							echo "<b>".$order_information['comments'][$each_comment]['comment']."</b>";
							echo "&nbsp;";
							$temp_date = $order_information['comments'][$each_comment]['date'];
							$temp_date_unix = strtotime($temp_date);
							$temp_date = date("g:ia, jS M, Y", $temp_date_unix);
							
							echo "(".$temp_date.")";
							echo "<br>";
						}
						?>
						</div>
					</div>
				</div>
			</div>
<?php
		}
		else
		{
			echo "Given Order ID not found.";
			exit();
		}
	}
}
?>
</center>
</body>
<script>



$(document).ready(function(){
	$.ajax({
	type:'POST',
	url:'vp_checkstate.php',
	data:
	{
		orderid:<?php echo "'".$orderid."'"; ?>
	},

	success:function(message)
	{
		if(message == "\n\n0")
		{
			$("#activity_b").val("Accept Order");
			$("#activity_b").attr("class", "main_buttons_red");
		}
		else
		{
			if(message == "\n\n1")
			{
				$("#activity_b").val("Ship Order");
				$("#activity_b").attr("class", "main_buttons_yellow");
				$("#activity_b").attr("href", 'vp_ship.php?orderid='+<?php echo "'".$orderid."'"; ?>);
				$("#reject_b").hide();
			}
			else
			{
				if(message == "\n\n2")
				{
					$("#activity_b").val("Order Delivered");
					$("#activity_b").attr("class", "main_buttons_green");
					$("#activity_b").attr("href", 'vp_delivered.php?orderid='+<?php echo "'".$orderid."'"; ?>);
					$("#reject_b").hide();
				}
				else
				{
					if(message == "\n\n3")
					{
						$("#activity_b").hide();
						$("#reject_b").hide();
					}
				}
			}
		}
	}
	});
});


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
				orderid:<?php echo "'".$orderid."'"; ?>
			},

			success:function(message)
			{
				if(message == "\n\n0")
				{
					$("#activity_b").val("Accept Order");
					$("#activity_b").attr("class", "main_buttons_red");
				}
				else
				{
					if(message == "\n\n1")
					{
						$("#activity_b").val("Ship Order");
						$("#activity_b").attr("class", "main_buttons_yellow");
						$("#activity_b").attr("href", 'vp_ship.php?orderid='+<?php echo "'".$orderid."'"; ?>);
						$("#reject_b").hide();
					}
					else
					{
						if(message == "\n\n2")
						{
							$("#activity_b").val("Order Delivered");
							$("#activity_b").attr("class", "main_buttons_green");
							$("#activity_b").attr("href", 'vp_delivered.php?orderid='+<?php echo "'".$orderid."'"; ?>);
							$("#reject_b").hide();
						}
						else
						{
							if(message == "\n\n3")
							{
								$("#activity_b").hide();
								$("#reject_b").hide();
							}
							else
							{
								if(message == "\n\n-1")
								{
									alert("Some error has occurred while performing operation.");
									$("#reject_b").hide();
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
				orderid:<?php echo "'".$orderid."'"; ?>
			},

			success:function(message)
			{
				if(message == "\n\n0")
				{
					$("#activity_b").val("Accept Order");
					$("#activity_b").attr("class", "main_buttons_red");
					$("#reject_b").hide();
				}
				else
				{
					if(message == "\n\n1")
					{
						$("#activity_b").val("Ship Order");
						$("#activity_b").attr("class", "main_buttons_yellow");
						$("#activity_b").attr("href", 'vp_ship.php?orderid='+<?php echo "'".$orderid."'"; ?>);
						$("#reject_b").hide();
					}
					else
					{
						if(message == "\n\n2")
						{
							$("#activity_b").val("Order Delivered");
							$("#activity_b").attr("class", "main_buttons_green");
							$("#activity_b").attr("href", 'vp_delivered.php?orderid='+<?php echo "'".$orderid."'"; ?>);
							$("#reject_b").hide();
						}
						else
						{
							if(message == "\n\n3")
							{
								$("#activity_b").hide();
								$("#reject_b").hide();
							}
							else
							{
								if(message == "\n\n-1")
								{
									alert("Some error has occurred while performing operation.");
									$("#reject_b").hide();
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
				orderid:<?php echo "'".$orderid."'"; ?>
			},

			success:function(message)
			{
				if(message == "\n\n0")
				{
					$("#activity_b").val("Accept Order");
					$("#activity_b").attr("class", "main_buttons_red");
					$("#reject_b").hide();
				}
				else
				{
					if(message == "\n\n1")
					{
						$("#activity_b").val("Ship Order");
						$("#activity_b").attr("class", "main_buttons_yellow");
						$("#activity_b").attr("href", 'vp_ship.php?orderid='+<?php echo "'".$orderid."'"; ?>);
						$("#reject_b").hide();
					}
					else
					{
						if(message == "\n\n2")
						{
							$("#activity_b").val("Order Delivered");
							$("#activity_b").attr("class", "main_buttons_green");
							$("#activity_b").attr("href", 'vp_delivered.php?orderid='+<?php echo "'".$orderid."'"; ?>);
							$("#reject_b").hide();
						}
						else
						{
							if(message == "\n\n3")
							{
								$("#activity_b").hide();
								$("#reject_b").hide();
							}
							else
							{
								if(message == "\n\n-1")
								{
									alert("Some error has occurred while performing operation.");
									$("#reject_b").hide();
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





$(".main_buttons_red_fire").fancybox({
	width		: '650px',
	height		: '200px',
	autoSize	: false,
	closeClick	: false,

	afterClose: function()
	{
		$("#loading_div").show();
		$('body').attr('disabled', 'disabled');
		$.ajax({
			type:'POST',
			url:'vp_checkstate2.php',
			data:
			{
				orderid:<?php echo "'".$orderid."'"; ?>
			},

			success:function(message)
			{
				if(message == "\n\n")
				{
					alert("You have rejected this Order.");
					window.location = "vp_vendormain.php";
				}
			}
		});
		$("#loading_div").hide();
		$('body').removeAttr("disabled");
	}
});












$("#cancel_order_b").fancybox({
	width		: '670px',
	height		: '180px',
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
				orderid:<?php echo "'".$orderid."'"; ?>
			},

			success:function(message)
			{
				if(message == "\n\n4")
				{
					alert("Order has been Cancelled.");
					/*
					$("#dialog-confirm-msg").html("Order has been Cancelled.");
						$("#dialog-confirm-msg").dialog({
							resizable: false,
							modal: true,
							title:"",
							height: 175,
							width: 400,
							buttons: {
							"OK": function()
							{
								$(this).dialog('close');
							}
						}
					});
					*/
				}
			}
		});
		$("#loading_div").hide();
		$('body').removeAttr("disabled");
	}
});



$("#close_order_b").fancybox({
	width		: '670px',
	height		: '180px',
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
				orderid:<?php echo "'".$orderid."'"; ?>
			},

			success:function(message)
			{
				if(message == "\n\n5")
				{
					alert("Order has been Closed.");
					/*
					$("#dialog-confirm-msg").html("Order has been Closed.");
						$("#dialog-confirm-msg").dialog({
							resizable: false,
							modal: true,
							title:"",
							height: 175,
							width: 400,
							buttons: {
							"OK": function()
							{
								$(this).dialog('close');
							}
						}
					});
					*/
				}
			}
		});
		$("#loading_div").hide();
		$('body').removeAttr("disabled");
	}
});



$(document).ready(function(){
	$(document).on('click', '#comment_b', function(){
		if($("#comment_t").val() == "")
		{
			$("#comment_r").html("Please Enter Comment.");
		}
		else
		{
			$.ajax({
				type:"POST",
				url:"vp_addcomment.php",
				data:{
					orderid:<?php echo "'".$orderid."'"; ?>,
					comment:$("#comment_t").val()
				},

				success:function(message)
				{
					if(message == "\n\n-1")
					{
						$('#comment_r').html("Comment could not be added to the Order. Please try again later.");
					}
					else
					{
						$('#comments_data').html("");
						var values = message.split("<>");
						var comment = '';
						var date = '';
						var i=0;
						for(i=0; i<values.length; i++)
						{
							var array = values[i].split("|");
							comment = array[0];
							date = array[1];
							$('#comments_data').prepend("<b>"+comment+"</b> ("+date+")<br>");
						}

						$("#comment_t").val('');
						$('#comment_r').html("Comment has been added to the Order.");

						/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
						$.ajax({
							type:"POST",
							url:"vp_recordnotifications.php",
							data:
							{
								orderid:'<?php echo $orderid; ?>',
								notification_type:"6",
								remark:comment,
								username:'<?php echo $user_name; ?>',
								vendor_id:'<?php echo $vendor_id; ?>'
							}
						});
						/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS */
					}
				}
			});
		}
	});
});



$(document).ready(function(){
	$(document).on('click', '#return_b', function(){
		window.location = "index.php";
	});
});






/*
$(document).ready(function(){
	$(document).on('click', '#update_vendor_price_b', function(){
		var temp_product_id_main = '';
		var temp_product_id_array = '';
		var temp_product_id = '';
		var temp_product_value = '';
		var product_array = {};
		$('.vendor_product_prices').each(function(){
			temp_product_id_main = $(this).attr('id');
			temp_product_id_array = temp_product_id_main.split("_");
			temp_product_id = temp_product_id_array[1];

			temp_product_value = $(this).val();

			product_array[temp_product_id] = temp_product_value;
		});

		var details = JSON.stringify(product_array);

		$.ajax({
			type:"POST",
			url:"vp_setvendorprice.php",
			data:
			{
				orderid:<?php echo $orderid; ?>,
				productdetails:details
			},

			success:function(message)
			{
				if(message == "\n\n+1")
				{
					alert("Details have been saved.");
					/* THIS CODE RECORDS THIS OPERATION *
					$.ajax({
						type:"POST",
						url:"vp_recordoperations.php",
						data:
						{
							orderid:<?php echo $orderid; ?>,
							comment_type:"1"
						}
					});
					/* THIS CODE RECORDS THIS OPERATION *

					/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS *
					$.ajax({
						type:"POST",
						url:"vp_recordnotifications.php",
						data:
						{
							orderid:<?php echo "'".$orderid."'"; ?>,
							notification_type:"1",
							username:'<?php echo $user_name; ?>',
							vendor_id:'<?php echo $vendor_id; ?>'
						}
					});
					/* THIS CODE RECORDS THIS OPERATION IN NOTIFICATIONS *
				}
				else
				{
					alert("Some error occurred.");
				}
			}
		});
	});
});
*/
</script>
</html>
