<?php

//include '../../../../../app/Mage.php';
include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
include $base_path_folder."/app/etc/dbconfig.php";
include $base_path_folder."/app/etc/mageinclusion.php";
mysql_select_db($custom_database);
include_once 'Barcode39.php';

/**
 * Description of flaberryorder
 *
 * @author flaberry
 */
class Flaberryorder {

    private $order_billing_address;
    private $order_shipping_address;
    private $order_items_count;
    private $order_total_price;
    private $order_products;
    private $order_dod;
    private $order_delivery_type;
    private $order;
    private $cust_comp_name;
    private $cust_billing_phone;
    private $cust_shipping_phone;
    private $cust_message;
    private $cust_special_instruction;
    private $mage_url;
    private $order_increment_id;
    private $barcode_img_height;
    private $barcode_img_width;
    private $barcode_img_name;
    private $barcode_img_extension;
    private $barcode_img_dpi;




    public function __construct() {
        $this->cust_billing_phone = NULL;
        $this->cust_shipping_phone = NULL;
        $this->cust_comp_name = NULL;
        $this->cust_message = NULL;
        $this->order_dod = NULL;
        $this->order_delivery_type = NULL;
        $this->cust_special_instruction = NULL;

        $this->order_billing_address = NULL;
        $this->order_shipping_address = NULL;
        $this->order_products = array();
        $this->order_items_count = 0;
        $this->order_total_price = 0;
        $this->order_increment_id = 0;
        $this->mage_url = "";
        $this->barcode_img_height = 0;
        $this->barcode_img_width = 0;
        $this->barcode_img_name = 'barcode-img.jpg';
        $this->barcode_img_dpi = 96;
        // $this->barcode_img_extension='jpg';


    }

    public function loadOrder($incrementId) {
        $this->order_increment_id = (!empty($incrementId) && is_numeric($incrementId)) ? $incrementId : 0;
        $this->order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
    }

    public function getOrderData() {
        return $this->order->getData();
    }

    public function getShippingAddressData() {
        $shipadd = $this->order->getShippingAddress()->getData();
        if (empty($shipadd['company'])) {
            $this->order_shipping_address .=$shipadd['firstname'] . " " . $shipadd['lastname'] . ",\n";
            $this->order_shipping_address .=$shipadd['street'] . ", \n";
            $this->order_shipping_address .=$shipadd['city'] . ", " . $shipadd['region'] . "-" . $shipadd['postcode'] . " ";
        } else {
            $this->order_shipping_address .=$shipadd['firstname'] . " " . $shipadd['lastname'] . ",\n";
            $this->order_shipping_address .=$shipadd['company'] . ", \n";
            $this->order_shipping_address .=$shipadd['street'] . ", \n";
            $this->order_shipping_address .=$shipadd['city'] . ", " . $shipadd['region'] . "-" . $shipadd['postcode'] . " ";
        }
        unset($shipadd);
        return $this->order_shipping_address;
    }

    public function getBillingAddressData() {
        $shipadd = $this->order->getBillingAddress()->getData();
        if (empty($shipadd['company'])) {
            $this->order_billing_address .=$shipadd['firstname'] . " " . $shipadd['lastname'] . ", \n";
            $this->order_billing_address .=$shipadd['street'] . ", \n";
            $this->order_billing_address .=$shipadd['city'] . ", " . $shipadd['region'] . "-" . $shipadd['postcode'] . " ";
        } else {
            $this->order_billing_address .=$shipadd['firstname'] . " " . $shipadd['lastname'] . ", \n";
            $this->order_billing_address .=$shipadd['company'] . ", \n";
            $this->order_billing_address .=$shipadd['street'] . ", \n";
            $this->order_billing_address .=$shipadd['city'] . ", " . $shipadd['region'] . "-" . $shipadd['postcode'] . " ";
        }
        unset($shipadd);
        return $this->order_billing_address;
    }

    public function getBillingPhoneNo() {
        $billadd = $this->order->getBillingAddress()->getData();
        $this->cust_billing_phone = $billadd['telephone'];
        unset($billadd);
        return $this->cust_billing_phone;
    }

    public function getShippingPhoneNo() {
        $shipadd = $this->order->getShippingAddress()->getData();
        $this->cust_shipping_phone = $shipadd['telephone'];
        unset($shipadd);
        return $this->cust_shipping_phone;
    }

    public function getOrderDeliveryType() {
        $shipping_types = array();
        foreach ($this->order->getAllItems() as $product) {
            $options = $product->getProductOptions();
            foreach ($options['options'] as $opt) {
                if (stristr($opt['label'], "Shipping Type") == TRUE) {
                    $shipping_types[] = strtolower($opt['value']);
                }
            }
        }

        $order_shipping_method = $this->order->getShippingMethod();
        if ($order_shipping_method == "freeshipping_freeshipping") {
            $shipping_types[] = "regular delivery";
        } elseif ($order_shipping_method == "flatrate_flatrate") {
            $shipping_types[] = "midnight delivery";
        }

        $this->order_delivery_type = ucfirst("regular delivery");
        foreach ($shipping_types as $key => $value) {
            if ($value !== "regular delivery") {
                $this->order_delivery_type = ucfirst("midnight delivery");
            }
        }
        unset($order_shipping_method);
        unset($shipping_types);
        return $this->order_delivery_type;
    }

    public function getOrderDeliveryDate() 
    {
        $delivery_date = array();
        foreach ($this->order->getAllItems() as $product) {
            $options = $product->getProductOptions();
            foreach ($options['options'] as $opt) {
                if (stristr($opt['label'], "Date of Delivery(MM/DD/YYYY)") == TRUE || stristr($opt['label'], "Date of Delivery(DD/MM/YYYY)") == TRUE) {
                    $delivery_date[] = strtotime($opt['option_value']);
                }
            }
        }
        
        if (!empty($delivery_date)) 
        {
            asort($delivery_date); // lower to high
            $this->order_dod = date('d/m/Y', $delivery_date[0]);
        }
        else
        {
            $this->order_dod = $this->getOrderCustomDeliveryDate();
        }
        unset($delivery_date);
        return $this->order_dod;
    }

    public function getOrderCustomerInstruction() {
        $orders = Mage::getModel('sales/order')->getCollection()->addAttributeToSelect("*")->addAttributeToFilter('increment_id', ($this->order_increment_id));
        foreach ($orders as $order) {
            $actualOrderId = $order->getId();
        }

        unset($orders);

        $_orders = Mage::getModel('onestepcheckout/onestepcheckout')->getCollection()->addFieldToFilter('sales_order_id', $actualOrderId);
        foreach ($_orders as $_order) {
            return $_order->getMwCustomercommentInfo();
        }
    }

    public function getOrderGrandTotal() {
        foreach ($this->order->getAllItems() as $product) {
            $this->order_total_price += number_format((float) ($product->getData('qty_ordered') * $product->getPrice()), 2, '.', '');
        }
        return number_format((float) $this->order_total_price, 2, '.', ',');
    }

    public function getProductInformation() 
    {
        $index = 1;
        foreach ($this->order->getAllItems() as $key => $product) 
        {
            $product_sku = $product->getSku();
            $productModel = Mage::getModel('catalog/product');
            $_prod = $productModel->loadByAttribute('sku', $product_sku);
            $product_id = $_prod->getId();
            $p = new stdClass();
            $p->serial_no = $index;
            $p->name = $product->getName();

            if(strpos($product_sku, "custom:") !== false)
            {
                $query = "select productvdescription from panelorderdetails where orderid=$this->order_increment_id and productid=$product_id";
                $result = mysql_query($query);
                $row = mysql_fetch_row($result);
                $p->vendor_description = $row[0];
            }
            else
            {
                $p->vendor_description = $_prod['vendor_description'];
            }
            $p->order_quantity = (int) $product->getData('qty_ordered');
            $p->price = number_format((float) ($product->getData('qty_ordered') * $product->getPrice()), 2, '.', ',');
            $index++;
            $this->order_products[$key] = $p;
        }
        unset($productModel);
        return $this->order_products;

        /*
        $index = 1;
        foreach ($this->order->getAllItems() as $key => $product) 
        {
            $productModel = Mage::getModel('catalog/product');
            $_prod = $productModel->loadByAttribute('sku', $product->getSku());
            $p = new stdClass();
            $p->serial_no = $index;
            $p->name = $product->getName();
            $p->vendor_description = $_prod['vendor_description'];
            $p->order_quantity = (int) $product->getData('qty_ordered');
            $p->price = number_format((float) ($product->getData('qty_ordered') * $product->getPrice()), 2, '.', ',');
            $index++;
            $this->order_products[$key] = $p;
        }
        unset($productModel);
        return $this->order_products;
        */
    }

//    public function setBarcodeImageName($image_name = NULL,$file_extension = NULL){
//        if(!empty($image_name) && !empty($file_extension)){
//            $this->barcode_img_extension = $file_extension;
//            $this->barcode_img_name = $image_name.'.'.$this->barcode_img_extension;
//        }
//    }

    public function getOrderBarCode() {
        $bctext = $this->order_increment_id;
        $bc = new Barcode39($bctext);
        $bc->barcode_text_size = 5;
        $bc->barcode_bar_thick = 3.5;
        $bc->barcode_bar_thin = 1;
        if (file_exists($this->barcode_img_name)) {
            unlink($this->barcode_img_name);
            $bc->draw($this->barcode_img_name);
        } else {
            $bc->draw($this->barcode_img_name);
        }

        $type = pathinfo($this->barcode_img_name, PATHINFO_EXTENSION);
        $data = file_get_contents($this->barcode_img_name);
        $dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $dataUri;
    }

    public function getBarcodeImageHeight() {
        list($width, $height, $type, $attr) = getimagesize($this->barcode_img_name);
        return round(($height * 25.4) / $this->barcode_img_dpi);
    }

    public function getBarcodeImageWidth() {
        list($width, $height, $type, $attr) = getimagesize($this->barcode_img_name);
        return round(($width * 25.4) / $this->barcode_img_dpi);
    }



    
    public function getOrderCustomDeliveryDate() 
    {

        $query = "select dod from panelorderdetails where orderid = ".$this->order_increment_id." limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_assoc($result);
        $dod = $row['dod'];
        

        $dod_array = explode("-", $dod);
        $dod = $dod_array[2]."/".$dod_array[1]."/".$dod_array[0];

        return $dod;
    }
}