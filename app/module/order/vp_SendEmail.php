<?php
include 'vp_dbconfig.php';

$orderId=$_REQUEST['orderId'];
$vendorName=$_REQUEST['vendorName'];
$productDesc=nl2br($_REQUEST['productDesc']);/////nl2br To Replace /n with <br/>////////
$specialInst=nl2br($_REQUEST['specialInst']);
$deliveryType=$_REQUEST['dilType'];
$productType=$_REQUEST['productType'];



$theOrder = Mage::getModel('sales/order')->load($orderId,'increment_id'); ////To fetch the details of the ORDER by using the ORDER Increment ID////

$shippingAddress = $theOrder->getShippingAddress();//// To Fetch the Address object for an ORDER////
$shippingId = $shippingAddress->getId();////To fetch the Shipping ID////
$address = Mage::getModel('sales/order_address')->load($shippingId);////To fetch the SHIPPING ADDRESS using the SHIPPING ID////
$name=$address['firstname']." ".$address['lastname'];
$pin=$address['postcode'];
$city = $address['city'];
$recipientStreet=$address['street'];
$recipientTelephone=$address['telephone'];

$productImg="";
$giftMessageId=$theOrder->getGiftMessageId(); //////To fetch Gift Message ID//////
$giftMessage = Mage::getModel('giftmessage/message'); ////////To fetch Object Of All Gift Messages///////
if(!is_null($giftMessageId))
	{
	$giftMessage->load($giftMessageId);
	$senderName = $giftMessage->getData('sender');
	$recipientName = $giftMessage->getData('recipient');
	$cardMessage = $giftMessage->getData('message');
	}
if(fetchVoiceMessageFlag($orderId))
$cardMessage.="<br/><br/>Please call at +91-8010-386-062 from ".$recipientTelephone." to listen to your voice message.";

$items = $theOrder->getAllItems();  ////To get all Ordered Items////
//$itemcount=count($items);
//$name=array();
//$unitPrice=array();
//$sku=array();
//$qty=array();
foreach ($items as $itemId => $item)
	{
	//$name[] = $item->getName();
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
				if (intval($item->getQtyOrdered()) > 1)
				$productImg.="<div><font color='red'><u>Quantity = ".intval($item->getQtyOrdered())."</u></font><br/><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
				else
				$productImg.="<div><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
				//echo $product->getImage();
				//echo $ids[$itemId]." ";
				$prodOps=$item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
				}	
		break;
		case "dark-ivory":
			if(stristr($sku, 'dark-ivory') == TRUE)
				{
				$prodId=$item->getProductId();
				$product = Mage::getModel('catalog/product')->load($prodId);
				if (intval($item->getQtyOrdered()) > 1)
				$productImg.="<div><font color='red'><u>Quantity = ".intval($item->getQtyOrdered())."</u></font><br/><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
				else
				$productImg.="<div><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
				//echo $product->getImage();
				//echo $ids[$itemId]." ";
				$prodOps=$item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
				}	
		break;
		case "plant":
			if(stristr($sku,"plant") == TRUE)
				{
				$prodId=$item->getProductId();
				$product = Mage::getModel('catalog/product')->load($prodId);
				if (intval($item->getQtyOrdered()) > 1)
				$productImg.="<div><font color='red'><u>Quantity = ".intval($item->getQtyOrdered())."</u></font><br/><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
				else
				$productImg.="<div><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
				//echo $product->getImage();
				//echo $ids[$itemId]." ";
				$prodOps=$item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
				}
		break;
		case "sweets":
			if((stristr($sku,"sweet:") == TRUE) || (stristr($sku,"sweets:") == TRUE))
				{
				$prodId=$item->getProductId();
				$product = Mage::getModel('catalog/product')->load($prodId);
				if (intval($item->getQtyOrdered()) > 1)
				$productImg.="<div><font color='red'><u>Quantity = ".intval($item->getQtyOrdered())."</u></font><br/><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
				else
				$productImg.="<div><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
				//echo $product->getImage();
				//echo $ids[$itemId]." ";
				$prodOps=$item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
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
		if(stristr($optionTitle,"Date of Delivery(DD/MM/YYYY)")==TRUE)
			{
			/////////////////////////////To Fetch just the date in DD-MM-YYYY from YYYY-MM-DD HH:MM:SS///////////////////////////
			$optionRevValue =$option['option_value'];
			$optionValueDateTimeArr = explode(" ",$optionRevValue); ////to break string into Array////
			$optionValueDateNoTime = array_shift($optionValueDateTimeArr); ////to fetch First Element from array////
			$optionValueDateArr = explode("-", $optionValueDateNoTime); ////break string into array////
			$revArr = array_reverse($optionValueDateArr); //// to reverse the array ////
			//////To check If date is Modified and SET it to Modified Date If True//////
			}
		/////////////////////////////End To Fetch just the date in DD-MM-YYYY from YYYY-MM-DD HH:MM:SS///////////////////////////
		
		}
	}
if(dateIfModified($orderId))
$revArr = explode("-", dateIfModified($orderId));
switch ($revArr[1])  /////To Display Month's Name Instead Of Number//////
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
$date = $optionValue;	

//////////////Subject Condition As Per Delivery Type///////////
if($deliveryType=='Daytime')
	{
	$subject="Floshowers order for delivery in ".$city." on ".$date."-".$orderId;
	$body=		"<html>
				<body>
				Hello<br/><br/>
				Please acknowledge the below order along with the cost :- <br/><br/>
				<b>Order No.: - </b><font color='red'>".$orderId."</font><br/><br/>";
	if(!is_null($recipientName))
	$body.= "<br/><br/><b>Recipient Name : </b>".$recipientName;
	else
	$body.= "<br/><br/><b>Recipient Name : </b>".$name;
	if(!is_null($cardMessage))
	$body.= "<br/><br/><b>Message On Card</b> : ".$cardMessage;
	else
	$body.= "<br/><br/><b>Message On Card</b> : Best Wishes!";
	if(!is_null($senderName))
	$body.= "<br/><br/><b>Sender Name : </b>".$senderName;
	//else
	//$body.= "<br/><br/><b>Sender Name : </b>";
	$deliveryStatus=fetchDeliverySatisfaction($orderId);
	if($deliveryStatus == TRUE)
	$deliveryStatus = "Handle with care. Repeat customer of your service";
	else
		{
		$repeat = fetchIfRepeatByEmail($orderId);
		if($repeat == TRUE)
		$deliveryStatus = "Handle with care. Repeat customer of your service";
		}
	$body.=		"<br/><br/><b>Delivery Address:</b><br/>".
				$name."<br/>".
				$recipientStreet."<br/>".
				$city."-".$pin."<br/><br/>
				<b>Recipient No.</b>: ".$recipientTelephone."<br/><br/><br/>
				<b>Date Of Delivery : </b><font color='red'><b>".$date."</b></font><br/>";
	if($specialInst != '')
	$body.=	"<br/><b>Special Instructions:</b><br/><b><b><font color='red'>".$specialInst."</font></b></b><br><br>";
	$body.=	"<span style='color:green; font-size:16px;'>".$deliveryStatus."</span><br><br>";
				
	$body.=		"<br/><b>Product Description: </b><br/>".
				$productDesc."</font><br/><br/>".
				$productImg."</body></html>";
	}
elseif($deliveryType=='Midnight')
	{
	$subject="Floshowers order for MIDNIGHT delivery in ".$city." on ".$date."-".$orderId;
	$body=		"<html>
				<body>
				Hello<br/><br/>
				Please acknowledge the below order along with the cost :- <br/><br/>
				<b>Order No.: - </b><font color='red'>".$orderId."</font><br/><br/>";
	if(!is_null($recipientName))
	$body.= "<br/><br/><b>Recipient Name : </b>".$recipientName;
	else
	$body.= "<br/><br/><b>Recipient Name : </b>".$name;
	if(!is_null($senderName))
	$body.= "<br/><br/><b>Message On Card</b> : ".$cardMessage;
	else
	$body.= "<br/><br/><b>Message On Card</b> : Best Wishes!";
	if(!is_null($senderName))
	$body.= "<br/><br/><b>Sender Name : </b>".$senderName;
	else
	$body.= "<br/><br/><b>Sender Name : </b> ";
	
	$deliveryStatus=fetchDeliverySatisfaction($orderId);
	if($deliveryStatus == TRUE)
	$deliveryStatus = "Handle with care. Repeat customer of your service";
	else
		{
		$repeat = fetchIfRepeatByEmail($orderId);
		if($repeat == TRUE)
		$deliveryStatus = "Handle with care. Repeat customer of your service";
		}
	$body.=		"<br/><br/><b>Delivery Address:</b><br/>".
				$name."<br/>".
				$recipientStreet."<br/>".
				$city."-".$pin."<br/><br/>
				<b>Recipient Contact No.</b>: ".$recipientTelephone."<br/><br/><br/>
				<b>Date Of Delivery : </b><font color='red'><b>".$date."</b></font><br/><br/>
				<b>Special Instructions:</b><br/><br/><b><font color='red'><u>MIDNIGHT DELIVERY</u></font></b><br/><br/><b><font color='red'>Please do not disclose the product description to the recipient.<br/>".
				$specialInst."</font><br><br><span style='color:green; font-size:16px;'>".$deliveryStatus."</span></b><br/><br/>
				<b>Product Description: </b><br/>".
				$productDesc."<br/><br/>".
				$productImg;
	}
	$temp_main2 = $_REQUEST['varun'];
	$temp_main = str_replace(PHP_EOL, "", $temp_main2);
	$temp = str_replace("	", "", $temp_main);
	$body .= "<br/><br/><b>Product Pricing and Other important details</b> : <br/>$temp";




	/****** VARUN : THIS IS CODE FOR HANDELING FLABERRY SELF ORDERS ******/
	$flaberry_self_flag = $_REQUEST['flaberry_self_flag'];
	if($flaberry_self_flag == '1')
	{
		$billing_address = $theOrder->getBillingAddress();
		$billing_name_first = $billing_address->getFirstname();
		$billing_name_last = $billing_address->getLastname();

		$ordered_products_in_an_order = $theOrder->getAllItems();
		foreach($ordered_products_in_an_order as $each_ordered_product_in_an_order)
		{
			$custom_options_of_a_product = $each_ordered_product_in_an_order->getProductOptions();
			if(isset($custom_options_of_a_product['options']))
			{
				foreach($custom_options_of_a_product['options'] as $foreach_loop_of_custom_options)
				{
					if($foreach_loop_of_custom_options["label"] == "Date of Delivery")
					{
						$date_of_delivery_main = $foreach_loop_of_custom_options["option_value"];
						$temp1 = explode(" ", $date_of_delivery_main);
						$date_of_delivery = $temp1[0];
					}
				}
			}
		}
		$subject = "Order generated for $billing_name_first reference number $billing_name_last, dated: $date_of_delivery";
		$body = "Flaberry Order Number is <b>$orderId</b>.";
	}
	/****** VARUN : THIS IS CODE FOR HANDELING FLABERRY SELF ORDERS ******/



//////////////End To Subject Condition As Per Delivery Type///////////	
require('googleemail/class.phpmailer.php');  ////Include PHPMailer Class/////

	try
		{
			$mail = new PHPMailer(); //New instance, with exceptions enabled
			$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			//$mail->SMTPDebug  = true; 
			$mail->Port       = 465;                    // set the SMTP server port
			$mail->Host       = "smtp.gmail.com"; // SMTP server
			$mail->Username   = "varunk@flaberry.com";     // SMTP server username
			$mail->Password   = "01nanotech51";            // SMTP server password
			$mail->SMTPSecure = "ssl";
			//$mail->AddReplyTo("pratyooshm@floshowers.com","First Last");
			$mail->From       = "Technical Department - Flaberry";
			$mail->FromName   = "Varun Kumar";
			//$to = "pratyoosh.mahajan@gmail.com";
			$tos=explode(',',fetchVendorEmailFromName($vendorName));
			foreach($tos as $to)
			$mail->AddAddress($to);
			$mail->Subject  = $subject;
			//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			$mail->WordWrap   = 50; // set word wrap
			$mail->Body = $body;
			$mail->IsHTML(true); // send as HTML		
			if($mail->Send() == true)
			{
				$vendor_id = fetchVendorIdFromName($vendorName);
				addOrdertoTables($orderId, $vendor_id);
				echo 'Message has been sent.';
				addOrderTovendors_ordersOnEmailSent($orderId,$vendorName,$productType);
			}
		} 
		catch (phpmailerException $e)
		{
			echo $e->errorMessage();
		}

function fetchVendorEmailFromName($vendorName)
		{
		$q = "SELECT vendor_email from vendors WHERE vendor_name = '".$vendorName."' LIMIT 1"; ////SQL Query////
		$result_vendors_fetchVendorEmailFromName = mysql_query($q); ////Result Set for the SQL QUERRY's Result////
		$value = mysql_fetch_array($result_vendors_fetchVendorEmailFromName); ////Fetch result array one by one////
		return $value['vendor_email'];
		}

function fetchVendorIdFromName($vendorName)
		{
		$q = "SELECT vendor_id from vendors WHERE vendor_name = '".$vendorName."' LIMIT 1"; ////SQL Query////
		$result_vendors_fetchVendorIdFromName = mysql_query($q); ////Result Set for the SQL QUERRY's Result////
		$value = mysql_fetch_array($result_vendors_fetchVendorIdFromName); ////Fetch result array one by one////
		return $value['vendor_id'];
		}
		
function addOrderTovendors_ordersOnEmailSent($orderId,$vendorName,$productType)
	{
	$vendorId=fetchVendorIdFromName($vendorName);
	$q = "SELECT COUNT(*) as count from vendors_orders WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
	$result_check = mysql_query($q); ////Result Set for the SQL QUERRY's Result////
	$value = mysql_fetch_array($result_check); ////Fetch result array one by one////
	if(!$value['count'])	
		{
		$q = "INSERT INTO vendors_orders(vendors_orders_orderid, vendors_orders_acknowledgement) VALUES (".$orderId.", '0')"; ////SQL Query////
		mysql_query($q); ////Result Set for the SQL QUERRY's Result////
		}
		
	switch ($productType)
		{
		case "":
			$q = "UPDATE vendors_orders SET vendors_orders_fcbc_vendorid = '".$vendorId."' WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
			mysql_query($q); ////Execute SQL QUERRY////
			$q = "UPDATE vendors_orders SET vendors_orders_acknowledgement = vendors_orders_acknowledgement + 1000 WHERE vendors_orders_orderid = '".$orderId."' AND vendors_orders_acknowledgement/1000 < '1'"; ////SQL Query////
			mysql_query($q); ////Result Set for the SQL QUERRY's Result////
		break;
		case "dark-ivory":
			$q = "UPDATE vendors_orders SET vendors_orders_chocolate_vendorid = '".$vendorId."' WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
			mysql_query($q); ////Execute SQL QUERRY////
			$q = "UPDATE vendors_orders SET vendors_orders_acknowledgement = vendors_orders_acknowledgement + 100 WHERE vendors_orders_orderid = '".$orderId."' AND (vendors_orders_acknowledgement%1000)/100 < '1'"; ////SQL Query////
			mysql_query($q); ////Result Set for the SQL QUERRY's Result////
		break;
		case "plant":
			$q = "UPDATE vendors_orders SET vendors_orders_plant_vendorid = '".$vendorId."' WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
			mysql_query($q); ////Execute SQL QUERRY////
			$q = "UPDATE vendors_orders SET vendors_orders_acknowledgement = vendors_orders_acknowledgement + 10 WHERE vendors_orders_orderid = '".$orderId."' AND ((vendors_orders_acknowledgement%1000)%100)/10 < '1'"; ////SQL Query////
			mysql_query($q); ////Result Set for the SQL QUERRY's Result////
		break;
		case "sweets":
			$q = "UPDATE vendors_orders SET vendors_orders_sweet_vendorid = '".$vendorId."' WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
			mysql_query($q); ////Execute SQL QUERRY////
			$q = "UPDATE vendors_orders SET vendors_orders_acknowledgement = vendors_orders_acknowledgement + 1 WHERE vendors_orders_orderid = '".$orderId."' AND ((vendors_orders_acknowledgement%1000)%100)%10 < '1'"; ////SQL Query////
			mysql_query($q); ////Result Set for the SQL QUERRY's Result////
		break;
		default :
			echo "Unknown Product Type";
		}
	}

function dateIfModified($orderId)
	{
	$q= "SELECT comments_orders_modified_date from comments_orders WHERE comments_orders_orderid = '$orderId' LIMIT 1";
	$result_comments_orders = mysql_query($q);
	$value = mysql_fetch_array($result_comments_orders);
	return $value['comments_orders_modified_date'];
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
	
function fetchDeliverySatisfaction($orderId)
	{
	$q="SELECT floshowers.sales_flat_order.customer_email FROM floshowers.sales_flat_order WHERE floshowers.sales_flat_order.increment_id = ".$orderId." LIMIT 1";
	$result = mysql_query($q);
	while($value = mysql_fetch_array($result))
	$email = $value['customer_email'];
	$q="SELECT operations.complaints.order_id FROM operations.complaints WHERE operations.complaints.order_id = ".$orderId." OR operations.complaints.email_id = '".$email."'";
	$result = mysql_query($q);
	$num_rows = mysql_num_rows($result);
	if($num_rows)
		return TRUE;
	else
		return FALSE;
	}
function fetchIfRepeatByEmail($orderId)
	{
	$q="SELECT floshowers.sales_flat_order.customer_email FROM floshowers.sales_flat_order WHERE floshowers.sales_flat_order.increment_id = ".$orderId." LIMIT 1";
	$result = mysql_query($q);
	$num_rows = mysql_num_rows($result);
	if($num_rows)
		{
		while($value = mysql_fetch_array($result))
		$email = $value['customer_email'];
		$q="SELECT floshowers.sales_flat_order.increment_id FROM floshowers.sales_flat_order WHERE floshowers.sales_flat_order.increment_id != ".$orderId." AND floshowers.sales_flat_order.customer_email = '".$email."' LIMIT 1";
		$result2 = mysql_query($q);
		$num_rows2 = mysql_num_rows($result2);
		if($num_rows2)
		return TRUE;
		else
		return FALSE;
		}
	else
		return FALSE;
	}







function addOrdertoTables($orderid, $vendorid)
{
	$vendor_id = $vendorid;
	$query = "select vendor_id from vendor_processing where orderid = $orderid limit 1";
	//echo $query."<br>";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	if(mysql_num_rows($result)!=0)
	{
		//echo "Got vendorid from vendor_processing based on orderid $orderid i.e. we have a row for this orderid in vendor_processing.<br>";
		$query = "update vendor_processing set vendor_id = $vendorid, state=0 where orderid=$orderid";
		//echo $query."<br>";
		$result1 = mysql_query($query);

		$query = "update panelorderdetails set vendor_id=$vendorid where orderid=$orderid";
		//echo $query."<br>";
		$result2 = mysql_query($query);

		if($result1 && $result2)
		{
			//echo "Updated vendor_processing for vendor_id against orderid.<br>";
			//echo "Updated panelorderdetails for vendor_id against orderid.<br>";
			//echo "Total Updation Successful.<br>";
		}
		else
		{
			//echo "Update in vendor_processing for vendor_id against orderid' or 'Update in panelorderdetails for vendor_id against orderid' Unsuccessful.<br>";
			//echo "Total Updation Unsuccessful.<br>";
		}
		
		if(strpos($row[0], $vendorid) !== false)
		{
			//echo "This order has already been assigned to same vendor.";
		}
		else
		{
			//echo "This order has been assigned to a different vendor ".$row[0];
		}
		
	}
	else
	{
		//echo "Could not get vendorid from vendor_processing based on orderid $orderid i.e. we do not have a row for this orderid in vendor_processing.<br>";
		$query1 = "update panelorderdetails set vendor_id=$vendorid where orderid=$orderid";
		//echo $query1."<br>";
		$result1 = mysql_query($query1);

		$query2 = "insert into vendor_processing (orderid, vendor_id, productid, quantity, unitprice, amount, shipping_charges, state, dod, delivery_type, vendor_price) values ";

		$query0 = "select * from panelorderdetails where orderid=$orderid";
		//echo $query0."<br>";
		$result0 = mysql_query($query0);
		while($row0 = mysql_fetch_assoc($result0))
		{
			//echo "We have row in panelorderdetails against this orderid.<br>";
			//This code fetches the shipping charges based on information if the order is Regular Delivery or Midnight Delivery...
			$temp_shipping_type = $row0['shippingtype'];
			//echo $temp_shipping_type."<br>";
			if(strpos($temp_shipping_type, "Midnight Delivery") !== false)
			{
				//echo "Shipping Type got from panelorderdetails matched Midnight Delivery. I should see this.<br>";
				$temp_delivery_type = "Midnight Delivery";
				$query3 = "select midnight_charges from vendor_shippingcharges where vendor_id=$vendor_id";
			}
			else
			{
				if(strpos($temp_shipping_type, "Regular Delivery") !== false)
				{
					//echo "Shipping Type got from panelorderdetails matched Regular Delivery. I should see this.<br>";
					$temp_delivery_type = "Regular Delivery";
					$query3 = "select regular_charges from vendor_shippingcharges where vendor_id=$vendor_id";
				}
				else
				{
					//echo "Shipping Type got from panelorderdetails did not matched any possible delivery type. I should not see this.<br>";
				}
			}
			//echo $query."<br>";
			$result3 = mysql_query($query3);
			//echo $query3."<br>";
			//echo mysql_error();
			while($row3 = mysql_fetch_row($result3))
			{
				$temp_shippingcharges = $row3[0];
			}
			//This code fetches the shipping charges based on information if the order is Regular Delivery or Midnight Delivery...




			$temp_quantity = $row0['productquantity'];
			$temp_unitprice = $row0['productunitprice'];
			$temp_amount = $temp_unitprice*$temp_quantity;
			$temp_vendor_price = $row0['vendor_price'];
			if($temp_vendor_price == "")
			{
				$temp_vendor_price = 0;
			}

			$query2 .= "($orderid, $vendorid, ".$row0['productid'].", ".$temp_quantity.", ".$temp_unitprice.", ".intval($temp_amount).", ".$temp_shippingcharges.", 0, '".$row0['dod']."', '$temp_delivery_type', $temp_vendor_price), ";
		}
		$query2 = rtrim($query2, ", ");
		//echo "<br>Final Query executed is :<br>$query2<br>";
		$result2 = mysql_query($query2);
		echo mysql_error();

		if($result1 && $result2)
		{
			//echo "+1";
			//echo "Insertion Successful.";
		}
		else
		{
			//echo "-1";
			//echo "Insertion Unsuccessful.";
		}
	}
}
?>
