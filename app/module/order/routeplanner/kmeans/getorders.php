$order = Mage::getModel('sales/order')->loadByIncrementId($_REQUEST['orderid']);12:45 PM
$address = $order->getShippingAddress();
$recipient_information['pincode'] = $address->getPostcode();