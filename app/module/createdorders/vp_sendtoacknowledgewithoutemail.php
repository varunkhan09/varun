<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

	$order_id = $_REQUEST['order_id'];
	$vendor_shop_id = $_REQUEST['vendor_shop_id'];

	mysql_select_db($custom_database);
	$query = "select * from panelorderdetails where orderid = $order_id";
	$result = mysql_query($query);



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
	echo $query_final;
	mysql_select_db($custom_database);
	mysql_query($query_final);
?>