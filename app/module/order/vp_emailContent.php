<?php
include 'vp_dbconfig.php';

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
*/
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











/****** VARUN : THIS IS CODE FOR HANDELING FLABERRY SELF ORDERS ******/
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
/****** VARUN : THIS IS CODE FOR HANDELING FLABERRY SELF ORDERS ******/














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
?>

