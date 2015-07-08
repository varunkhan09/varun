<?php
session_start();
include_once 'flaberryorder.class.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'order_to_price_challan') {
      getOrderInfo();
    }
}

function getOrderInfo(){
      $orderObj = new Flaberryorder;
      
        $orderObj->loadOrder($_POST['order_id']);
        $barcode_image = $orderObj->getOrderBarCode();
        $barcode_height = $orderObj->getBarcodeImageHeight();
        $barcode_width =  $orderObj->getBarcodeImageWidth();
        
        $final_data =  array();
        $final_data['shipping_add'] = trim($orderObj->getShippingAddressData());
        $final_data['billing_add'] = trim($orderObj->getBillingAddressData());
        $final_data['shipping_phone'] = $orderObj->getShippingPhoneNo();
        $final_data['billing_phone'] = $orderObj->getBillingPhoneNo();   
        $final_data['subtotal_amount'] = $orderObj->getOrderGrandTotal();
        $final_data['delivery_type'] = $orderObj->getOrderDeliveryType();
        $final_data['date_of_delivery'] = $orderObj->getOrderDeliveryDate();
        $final_data['special_instruction'] = $orderObj->getOrderCustomerInstruction();
        $final_data['loggedinuser'] = 'Varun';//$_SESSION['logged_in_auth_user_name'];
        $final_data['barcode_image'] = $barcode_image;
        $final_data['barcode_height'] = $barcode_height;
        $final_data['barcode_width'] = $barcode_width;



        $shop_logo_image = $orderObj->getShopLogo();
        $final_data['shop_logo_image'] = $shop_logo_image;
        $final_data['shop_logo_height'] = 15;
        $final_data['shop_logo_width'] = 50;
        $final_data['shop_name'] = $orderObj->getShopName();
        
        
        $products =$orderObj->getProductInformation(); 
        foreach ($products as $key => $product) {
            $final_data['product'][$key]['serial_no'] = $product->serial_no;
            $final_data['product'][$key]['name'] = $product->name;
            $final_data['product'][$key]['vendor_description'] = $product->vendor_description;
            $final_data['product'][$key]['order_quantity'] = $product->order_quantity;
            $final_data['product'][$key]['price'] = $product->price; 
        }
       echo json_encode($final_data);       
}