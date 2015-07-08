<?php
include "vp_dbconfig.php";

$orderId=$_REQUEST['orderid'];
$vendor_id=$_REQUEST['vendor_id'];

$shippingType = 'Regular Delivery';                                                 //for default shipping type




$theOrder = Mage::getModel('sales/order')->load($orderId,'increment_id');           //To fetch the details of the ORDER by using the ORDER Increment ID////





$shippingAddress = $theOrder->getShippingAddress();                                 // To Fetch the Address object for an ORDER////
$shippingId = $shippingAddress->getId();                                            //To fetch the Shipping ID////
$address = Mage::getModel('sales/order_address')->load($shippingId);                //To fetch the SHIPPING ADDRESS using the SHIPPING ID////
$name=$address['firstname']." ".$address['lastname'];
$pin=$address['postcode'];
$city = $address['city'];
$recipientStreet=$address['street'];
$recipientTelephone=$address['telephone'];
$productImg="";





$giftMessageId=$theOrder->getGiftMessageId();                                       //To fetch Gift Message ID//////
$giftMessage = Mage::getModel('giftmessage/message');                               //To fetch Object Of All Gift Messages///////
if(!is_null($giftMessageId))
{
	$giftMessage->load($giftMessageId);
	$senderName = $giftMessage->getData('sender');
	$recipientName = $giftMessage->getData('recipient');
	$cardMessage = $giftMessage->getData('message');
}






if(fetchVoiceMessageFlag($orderId))
{
	$cardMessage.="<br/><br/>Please call at +91-8010-386-062 from ".$recipientTelephone." to listen to your voice message.";
}








$items = $theOrder->getAllItems();                                                  //To get all Ordered Items////
$count = 1;
foreach ($items as $itemId => $item)
{
	$sku=$item->getSku();
	$productTypeArr = explode(':', $sku);

	$catproduct = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
	$vender_desc = $catproduct->getVendorDescription();

	$prodId=$item->getProductId();
	$product = Mage::getModel('catalog/product')->load($prodId);
	if (intval($item->getQtyOrdered()) > 1)
	{
		$productImg.=$count++.". ".$vender_desc."<br><hr><div><font color='red'><u>Quantity = ".intval($item->getQtyOrdered())."</u></font><br/><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
	}
	else
	{
		$productImg.=$count++.". ".$vender_desc."<br><hr><div><img src='".Mage::helper('catalog/image')->init($product, 'image')."' width='200' height='200' ><br/><br/></div><hr>";
	}
	$prodOps=$item->getProductOptions();                                    //To Fetch Ordered Products' Options' Object////

	foreach ($prodOps['options'] as $option)                                    //To fetch options for each product one by one////
	{
		$optionTitle = $option['label'];
		if(stristr($optionTitle,"Date of Delivery(DD/MM/YYYY)")==TRUE)
		{		                              
		                                                                        //To Fetch just the date in DD-MM-YYYY from YYYY-MM-DD HH:MM:SS///////////////////////////
		$optionRevValue =$option['value'];
		$date = date("d-M-Y", strtotime($optionRevValue));
		$optionValueDateTimeArr = explode(" ",$optionRevValue);                 //to break string into Array////
		$optionValueDateNoTime = array_shift($optionValueDateTimeArr);          //to fetch First Element from array////
		$optionValueDateArr = explode("-", $optionValueDateNoTime);             //break string into array////
		$revArr = array_reverse($optionValueDateArr);                           // to reverse the array ////
		                                                                        //To check If date is Modified and SET it to Modified Date If True//////
		}
		if(stristr($optionTitle,"Shipping Type")==TRUE)
		{
			$shippingType = $option['value'];
		}
		if(stristr($optionTitle,"Delivery City")==TRUE)
		{
			$DeliveryCity = $option['value'];
		}
	}
}

$specialInst = explode(':', fetchCommentOnOrderId($orderId));
//echo $specialInst[1];

$subject="Floshowers order for delivery in ".$city." on ".$date."-".$orderId;

//BODY START HERE....
$body="
<html>           
<body>
	Hello<br/><br/>
	Please acknowledge the below order along with the cost :- <br/><br/>
	<b>Order No.: - </b><font color='red'>".$orderId."</font><br/><br/>";

if(!is_null($recipientName))
{
	$body.= "<br/><br/><b>Recipient Name : </b>".$recipientName;
}
else
{
	$body.= "<br/><br/><b>Recipient Name : </b>".$name;
}

if(!is_null($cardMessage))
{
	$body.= "<br/><br/><b>Message On Card</b> : ".$cardMessage;
}
else
{
	$body.= "<br/><br/><b>Message On Card</b> : Best Wishes!";
}


if(!is_null($senderName))
{
	$body.= "<br/><br/><b>Sender Name : </b>".$senderName;
}

$deliveryStatus=fetchDeliverySatisfaction($orderId);
	
if($deliveryStatus == TRUE)
{
	$deliveryStatus = "Handle with care. Repeat customer of your service";
}
else
{
	$repeat = fetchIfRepeatByEmail($orderId);
	if($repeat == TRUE)
	{
		$deliveryStatus = "Handle with care. Repeat customer of your service";
	}
}


$body.=	"<br/><br/><b>Delivery Address:</b><br/>".$name."<br/>".$recipientStreet."<br/>".$city."-".$pin."<br/><br/>
		<b>Recipient No.</b>: ".$recipientTelephone."<br/><br/><br/><b>Date Of Delivery : </b><font color='red'><b>".$date."</b></font><br/>";

$body.= "<br/><b>Special Instructions</b><br><font color='red'><b><br><br/><u>".strtoupper($shippingType)."</u><br><br>".strtoupper($specialInst[1])."</b></font><br/><br/>";
//$body.= "<br><br/><b><u><font color='red'>".strtoupper($shippingType)."</font></u></b><br><br>";
$body.=	"<span style='color:green; font-size:16px;'>".$deliveryStatus."</span><br><br><b>Product Description:</b><br>";
$body.=	$productImg."</body></html>";

//BODY END HERE....
//echo $body;

//SEND MAIL BLOCK START HERE....
require('googleemail/class.phpmailer.php');                                      //Include PHPMailer Class/////
try
{
	$mail = new PHPMailer();                                                     //New instance, with exceptions enabled
	$mail->IsSMTP();                                                             // tell the class to use SMTP
	$mail->SMTPAuth   = true;                                                    // enable SMTP authentication
	//$mail->SMTPDebug  = true; 
	$mail->Port       = 465;                                                     // set the SMTP server port
	$mail->Host       = "smtp.gmail.com";                                        // SMTP server
	$mail->Username   = "varunk@flaberry.com";                                  // SMTP server username
	$mail->Password   = "01nanotech51";                                           // SMTP server password
	$mail->SMTPSecure = "ssl";
	//$mail->AddReplyTo("pratyooshm@floshowers.com","First Last");
	$mail->From       = "Floshowers V";
	$mail->FromName   = "Floshowers V";
	//$to = "pratyoosh.mahajan@gmail.com";
	$tos=explode(',',fetchVendorEmailFromId($vendor_id));
	foreach($tos as $to)
	$mail->AddAddress($to);
	$mail->Subject  = $subject;
	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 50;                                                       // set word wrap
	$mail->Body = $body;
	$mail->IsHTML(true);                                                          // send as HTML		
	if($mail->Send() == true)
	{
		echo "+1";
		//addOrdertoVenderProcessing($orderId, $vendor_id);
		addOrdertoTables($orderId, $vendor_id);
		//addOrderTovendors_ordersOnEmailSent($orderId,$vendorName,$productType);
	}
} 
catch (phpmailerException $e)
{
	echo "-1";
	echo $e->errorMessage();
}
//SEND MAIL BLOCK END HERE....

function fetchVendorEmailFromId($vendor_id)
		{
		$q = "SELECT vendor_email from vendors WHERE vendor_id = '".$vendor_id."' LIMIT 1";   //SQL Query////
		$result_vendors_fetchVendorEmailFromId = mysql_query($q);                              //Result Set for the SQL QUERRY's Result////
		$value = mysql_fetch_array($result_vendors_fetchVendorEmailFromId);                    //Fetch result array one by one////
		return $value['vendor_email'];
		}

function fetchVendorIdFromName($vendorName)
		{
		$q = "SELECT vendor_id from vendors WHERE vendor_name = '".$vendorName."' LIMIT 1";      //SQL Query////
		$result_vendors_fetchVendorIdFromName = mysql_query($q);                                 //Result Set for the SQL QUERRY's Result////
		$value = mysql_fetch_array($result_vendors_fetchVendorIdFromName);                       //Fetch result array one by one////
		return $value['vendor_id'];
		}

	
function fetchVoiceMessageFlag($orderId)
	{
	$theOrder = Mage::getModel('sales/order')->load($orderId,'increment_id');
	$actualOrderId = $theOrder->getId();
	$q = "SELECT floshowers.sales_order_custom.id from floshowers.sales_order_custom WHERE floshowers.sales_order_custom.order_id = '".$actualOrderId."' LIMIT 1"; ////SQL Query////
		$result_voice_msg = mysql_query($q);                                                    //Result Set for the SQL QUERRY's Result////
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

function fetchCommentOnOrderId($orderId)                                                      //Fetch Comment from comments_orders(also contains Modified date) if exists/////
	{
	$q = "SELECT  comments_orders_comment from comments_orders WHERE comments_orders_orderid = '".$orderId."'"; 
	$result_comments_orders_comment = mysql_query($q);                                        //Result Set for the SQL QUERRY's Result////
	$num_rows = mysql_num_rows($result_comments_orders_comment);
	$value = mysql_fetch_array($result_comments_orders_comment);
	if(($num_rows == 0) || (($num_rows>0) && ($value['comments_orders_comment'] == NULL)))
	$comment = "No";                                                                          //fetchCustCommentOnOrderId($orderId);
	elseif($num_rows>0 && $value['comments_orders_comment'] != NULL)
	$comment = $value['comments_orders_comment'];
	return $comment;
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
			//echo "Insertion Successful.";
		}
		else
		{
			//echo "Insertion Unsuccessful.";
		}
	}
}

// function findvendorid($vendorName){
// 	$q = "SELECT vendor_id FROM vendors WHERE vendor_name LIKE'%".$vendorName."%'";
// 	$result = mysql_query($q);
// 	$row = mysql_fetch_array($result);
// 	$result_vendor_id = $row['vendor_id'];
// 	return $result_vendor_id;
// }

// function addOrderTovendors_ordersOnEmailSent($orderId,$vendorName,$productType)
// 	{
// 	$vendorId=fetchVendorIdFromName($vendorName);
// 	$q = "SELECT COUNT(*) as count from vendors_orders WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
// 	$result_check = mysql_query($q); ////Result Set for the SQL QUERRY's Result////
// 	$value = mysql_fetch_array($result_check); ////Fetch result array one by one////
// 	if(!$value['count'])	
// 		{
// 		$q = "INSERT INTO vendors_orders(vendors_orders_orderid, vendors_orders_acknowledgement) VALUES (".$orderId.", '0')"; ////SQL Query////
// 		mysql_query($q); ////Result Set for the SQL QUERRY's Result////
// 		}
		
// 	switch ($productType)
// 		{
// 		case "":
// 			$q = "UPDATE vendors_orders SET vendors_orders_fcbc_vendorid = '".$vendorId."' WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
// 			mysql_query($q); ////Execute SQL QUERRY////
// 			$q = "UPDATE vendors_orders SET vendors_orders_acknowledgement = vendors_orders_acknowledgement + 1000 WHERE vendors_orders_orderid = '".$orderId."' AND vendors_orders_acknowledgement/1000 < '1'"; ////SQL Query////
// 			mysql_query($q); ////Result Set for the SQL QUERRY's Result////
// 		break;
// 		case "dark-ivory":
// 			$q = "UPDATE vendors_orders SET vendors_orders_chocolate_vendorid = '".$vendorId."' WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
// 			mysql_query($q); ////Execute SQL QUERRY////
// 			$q = "UPDATE vendors_orders SET vendors_orders_acknowledgement = vendors_orders_acknowledgement + 100 WHERE vendors_orders_orderid = '".$orderId."' AND (vendors_orders_acknowledgement%1000)/100 < '1'"; ////SQL Query////
// 			mysql_query($q); ////Result Set for the SQL QUERRY's Result////
// 		break;
// 		case "plant":


// 			$q = "UPDATE vendors_orders SET vendors_orders_plant_vendorid = '".$vendorId."' WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
// 			mysql_query($q); ////Execute SQL QUERRY////
// 			$q = "UPDATE vendors_orders SET vendors_orders_acknowledgement = vendors_orders_acknowledgement + 10 WHERE vendors_orders_orderid = '".$orderId."' AND ((vendors_orders_acknowledgement%1000)%100)/10 < '1'"; ////SQL Query////
// 			mysql_query($q); ////Result Set for the SQL QUERRY's Result////
// 		break;
// 		case "sweets":
// 			$q = "UPDATE vendors_orders SET vendors_orders_sweet_vendorid = '".$vendorId."' WHERE vendors_orders_orderid = '".$orderId."'"; ////SQL Query////
// 			mysql_query($q); ////Execute SQL QUERRY////
// 			$q = "UPDATE vendors_orders SET vendors_orders_acknowledgement = vendors_orders_acknowledgement + 1 WHERE vendors_orders_orderid = '".$orderId."' AND ((vendors_orders_acknowledgement%1000)%100)%10 < '1'"; ////SQL Query////
// 			mysql_query($q); ////Result Set for the SQL QUERRY's Result////
// 		break;
// 		default :
// 			echo "Unknown Product Type";
// 		}
// 	}