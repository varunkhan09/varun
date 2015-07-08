<?php
	include 'vp_dbconfig.php';
	$orderid = $_REQUEST['orderid'];

	$query = "select * from panelorderdetails where orderid=$orderid";
	$result_count = mysql_query($query);

	if(!mysql_num_rows($result_count))
	{
		$order_source = "Website";
		$account_type = "Website";

		$order = Mage::getModel('sales/order')->loadByIncrementId($orderid);
		$ordered_products_in_an_order = $order->getAllItems();

		$query = "insert into panelorderdetails (orderid, productid, productsku, productquantity, productunitprice, productvdescription, order_source, account_type, dod, shippingtype, recipient_pincode, vendor_id) values ";
		foreach($ordered_products_in_an_order as $each_product)
		{
			$custom_options_of_a_product = $each_product->getProductOptions();
			if(isset($custom_options_of_a_product['options']))
			{
				foreach($custom_options_of_a_product['options'] as $foreach_loop_of_custom_options)
				{
					//if(strpos($foreach_loop_of_custom_options["label"], "Date of Delivery(DD/MM/YYYY)"))
					if($foreach_loop_of_custom_options["label"] == "Date of Delivery(DD/MM/YYYY)")
					{
						$temp_dod = $foreach_loop_of_custom_options["option_value"];
						$temp_dod_array = explode(" ", $temp_dod);
						$temp_dod = $temp_dod_array[0];
						$temp_dod_global = $temp_dod;
						break;
					}
				}
				foreach($custom_options_of_a_product['options'] as $foreach_loop_of_custom_options)
				{
					//if(strpos($foreach_loop_of_custom_options["label"], "Shipping Type"))
					if($foreach_loop_of_custom_options["label"] == "Shipping Type")
					{
						$temp_shippingtype = $foreach_loop_of_custom_options["value"];
						$temp_shippingtype_global = $temp_shippingtype;
						break;
					}
				}
			break;
			}
		}


		echo "Date of Delivery : $temp_dod_global<br>";
		echo "Shipping Type : $temp_shippingtype_global<br>";


		foreach($ordered_products_in_an_order as $each_product)
		{
			$temp_sku = $each_product->getSku();
			$temp_quantity = (int)$each_product->getQtyOrdered();
			$temp_unitprice = (int)$each_product->getPrice();

			$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $temp_sku);
			$temp_vendordescription = $product->getVendorDescription();
			$temp_product_id = $product->getId();


			$shipping_address = $order->getShippingAddress();
			$temp_shippingpincode = $shipping_address->getPostcode();

			$query .= "($orderid, $temp_product_id, '$temp_sku', $temp_quantity, $temp_unitprice, '$temp_vendordescription', '$order_source', '$account_type', '$temp_dod_global', '$temp_shippingtype_global', '$temp_shippingpincode', NULL), ";
		}

		$query = rtrim($query, ", ");

		//$result = mysql_query($query);
		$result = 1;
		if($result)
		{
			echo "Insertion in panelorderdetails Successful.";
		}
		else
		{
			echo mysql_error();
		}
	}
	else
	{
		echo "This orderid is already present in database. Insertion failed.";
	}
?>