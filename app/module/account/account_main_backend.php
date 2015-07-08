<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

	$item_id_to_name_mapper = array();
	$query = "select entity_id, item_name from pos_item_entity";
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result))
	{
		$item_id_to_name_mapper[$row['entity_id']] = $row['item_name'];
	}

	/* THIS CODE EXTRACTS THE ATTRIBUTE ID FOR CONVEYANCE CHARGES */
	$query_inner = "select attribute_id from pos_attributes where attribute_code = 'conveyance'";
	$result_inner = mysql_query($query_inner);
	$row_inner = mysql_fetch_assoc($result_inner);
	$conveyance_attribute_id = $row_inner['attribute_id'];
	/* THIS CODE EXTRACTS THE ATTRIBUTE ID FOR CONVEYANCE CHARGES */


	$start_date = $_REQUEST['start_date'];
	$end_date = $_REQUEST['end_date'];

	$start_date_unix = strtotime($start_date);
	$end_date_unix = strtotime($end_date);

	$start_date_final = date("Y-m-d", $start_date_unix);
	$start_date_final_without_time = $start_date_final;
	$start_date_final = $start_date_final." 00:00:00";

	$end_date_final = date("Y-m-d", $end_date_unix);
	$each_date_final_without_time = $end_date_final;
	$end_date_final = $end_date_final." 23:59:59";

	$query = "select * from pos_stock_addition_entity where created_at between '$start_date_final' and '$end_date_final' and shop_id=$shop_id order by created_at ASC";
	$result = mysql_query($query);
	$number_of_rows_in_this_query = mysql_num_rows($result);
	if($result)
	{
		$all_add_stock_information_array = array();
		$previous_date = "";
		$loop_counter = 0;
		while($row = mysql_fetch_assoc($result))
		{
			$loop_counter++;
			$temp_entity_id = $row['entity_id'];
			$temp_item_id = $row['item_id'];
			$temp_item_quantity = $row['item_quantity'];
			$temp_rate_name = $row['rate_name'];
			$temp_item_unitscale_quantity = $row['item_unitscale_quantity'];
			$temp_rate_value = $row['rate_value'];
			$temp_created_at = $row['created_at'];

			$temp_created_at_unix = strtotime($temp_created_at);
			$temp_created_at_final = date("Y-m-d", $temp_created_at_unix);


			$query_inner = "select value from pos_stock_addition_entity_int where attribute_id=$conveyance_attribute_id and shop_id=$shop_id and created_at like '$temp_created_at_final%'";
			$result_inner = mysql_query($query_inner);
			$row_inner = mysql_fetch_assoc($result_inner);
			$temp_conveyance_charges = $row_inner['value'];
			$all_add_stock_information_array[$temp_created_at_final]['conveyance_charges'] = $temp_conveyance_charges;


			if($temp_created_at_final == $previous_date)
			{
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity'] += $temp_item_quantity;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity_unitscale'] += $temp_item_unitscale_quantity;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['rate_name'] = $temp_rate_name;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['rate_value'] = $temp_rate_value;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['price'] += $temp_rate_value*$temp_item_quantity;
				$all_add_stock_information_array[$temp_created_at_final]['grand_total'] += $temp_rate_value*$temp_item_quantity;
			}
			else
			{
				if($previous_date != "")
				{
					$all_add_stock_information_array[$previous_date]['grand_total'] += $all_add_stock_information_array[$previous_date]['conveyance_charges'];
				}

				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity'] = 0;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity_unitscale'] = 0;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['price'] = 0;
				$all_add_stock_information_array[$temp_created_at_final]['grand_total'] = 0;


				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity'] += $temp_item_quantity;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity_unitscale'] += $temp_item_unitscale_quantity;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['rate_name'] = $temp_rate_name;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['rate_value'] = $temp_rate_value;
				$all_add_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['price'] += $temp_rate_value*$temp_item_quantity;
				$all_add_stock_information_array[$temp_created_at_final]['grand_total'] += $temp_rate_value*$temp_item_quantity;
			}

			$all_add_stock_information_array['grand_total'] += $temp_rate_value*$temp_item_quantity;

			if($loop_counter == $number_of_rows_in_this_query)
			{
				$all_add_stock_information_array[$temp_created_at_final]['grand_total'] += $all_add_stock_information_array[$temp_created_at_final]['conveyance_charges'];
			}
			$previous_date = $temp_created_at_final;
		}



		$each_date_addition_array = array_keys($all_add_stock_information_array);
	}
	else
	{
		echo "-1|";
	}






	echo "<br><br>";



	/* FETCHING ENTITY_ID FROM pos_user_entity FOR THIS SHOP_ID */
		$temp_vendor_created_this_order_id = $shop_id; //$row_inner['shop_id'];
	/* FETCHING ENTITY_ID FROM pos_user_entity FOR THIS SHOP_ID */



	//$query = "select productquantity, vendor_price, productsku, dod, orderid from panelorderdetails where vendor_id = $shop_id and dod between '$start_date_final_without_time' and '$each_date_final_without_time' order by dod ASC";
	$query = "select * from pos_stock_deduced_entity where shop_id=$shop_id and created_at between '$start_date_final' and '$end_date_final'";
	$result = mysql_query($query);
	$number_of_rows_in_this_query = mysql_num_rows($result);
	if($result)
	{
		$temp_item_id_array = array();
		$all_orders_details_array = array();
		$previous_order_id = "";
		$loop_counter = 0;
		while($row = mysql_fetch_assoc($result))
		{
			$loop_counter++;
			$temp_order_id = $row['order_id'];
			$temp_item_id = $row['item_id'];
			$temp_item_quantity = $row['item_quantity'];
			$temp_created_at = $row['created_at'];
			$temp_created_at_unix = strtotime($temp_created_at);
			$temp_created_at_final = date("Y-m-d", $temp_created_at_unix);

			/* FETCHING VENDOR WHO DELIVERED THIS ORDER */
				mysql_select_db($custom_database);
				$query_inner = "select vendor_id, shippingtype from panelorderdetails where orderid=$temp_order_id limit 1";
				$result_inner = mysql_query($query_inner);
				$row_inner = mysql_fetch_assoc($result_inner);
				$temp_vendor_delivered_this_order = $row_inner['vendor_id'];
				$temp_shipping_type_of_this_order = $row_inner['shippingtype'];
				if(strpos($temp_shipping_type_of_this_order, "Regular") !== false)
				{

				}
				else
				{
					if(strpos($temp_shipping_type_of_this_order, "Midnight") !== false)
					{

					}
				}

				$temp_shipping_charges = 75;

				$temp_vendor_delivered_this_order = 51;
				//$temp_vendor_delivered_this_order = 13;
				mysql_select_db($vendorshop_database);
			/* FETCHING VENDOR WHO DELIVERED THIS ORDER */


			/* CALCULATING ITEM PRICE FOR THIS VENDOR */
				$query_inner = "select price from pos_vendor_item_price where self_shop_id=$temp_vendor_created_this_order_id and vendor_shop_id=$temp_vendor_delivered_this_order and product_id=$temp_item_id";
				//echo $query_inner."<hr>";
				$result_inner = mysql_query($query_inner);
				while($row_inner = mysql_fetch_assoc($result_inner))
				{
					$temp_fetched_item_price = $row_inner['price'];
				}
			/* CALCULATING ITEM PRICE FOR THIS VENDOR */

			$all_orders_details_array[$temp_created_at_final][$temp_order_id]['items'][$temp_item_id]['quantity'] = $temp_item_quantity;
			$all_orders_details_array[$temp_created_at_final][$temp_order_id]['items'][$temp_item_id]['price'] = $temp_fetched_item_price;
			$all_orders_details_array[$temp_created_at_final][$temp_order_id]['items'][$temp_item_id]['total'] = $temp_item_quantity*$temp_fetched_item_price;
			$all_orders_details_array[$temp_created_at_final][$temp_order_id]['grand_total'] += $temp_item_quantity*$temp_fetched_item_price;
			$all_orders_details_array[$temp_created_at_final]['grand_total'] += $temp_item_quantity*$temp_fetched_item_price;
			$all_orders_details_array['grand_total'] += $temp_item_quantity*$temp_fetched_item_price;


			if($previous_order_id == $temp_order_id)
			{

			}
			else
			{
				if($previous_order_id != "")
				{
					$all_orders_details_array[$temp_created_at_final][$temp_order_id]['grand_total'] += $temp_shipping_charges;
					$all_orders_details_array[$temp_created_at_final]['grand_total'] += $temp_shipping_charges;
					$all_orders_details_array['grand_total'] += $temp_shipping_charges;
				}
			}
			if($loop_counter == $number_of_rows_in_this_query)
			{
				$all_orders_details_array[$temp_created_at_final][$temp_order_id]['grand_total'] += $temp_shipping_charges;
				$all_orders_details_array[$temp_created_at_final]['grand_total'] += $temp_shipping_charges;
				$all_orders_details_array['grand_total'] += $temp_shipping_charges;
			}

			$previous_order_id = $temp_order_id;
		}
		$each_date_orders_array = array_keys($all_orders_details_array);
	}
	else
	{
		echo "-1|";
	}


	$all_dates_array = array_merge($each_date_addition_array, $each_date_orders_array);
	//var_dump($all_dates_array);
	//echo "<hr>";
	$all_dates_array = array_unique($all_dates_array);
	//var_dump($all_dates_array);
	//echo "<hr>";

	echo "<table class='table table-bordered stock_report_result_table'>";
	echo "<tr class='stock_report_heading_tr'><td></td><td>Stock Bought</td><td>Stock Sold</td><td>Fuel</td><td>Additional Log</td></tr>";
	$counter = 1;

	$fuel_total = 0;
	$additional_log_total = 0;
	foreach($all_dates_array as $each_date)
	{
		$query_inner1 = " SELECT sum((`cls_odometer`-`op_odometer`)*2.25) as fuel_log FROM `fleet_odometer`  WHERE `vid` in (select vid from   `fleet_vehicles`  where shop_id=$shop_id) and `date` = '$each_date'";
		$result_inner1 = mysql_query($query_inner1);
		$row_inner1 = mysql_fetch_assoc($result_inner1);
		$temp_fuel_log = $row_inner1['fuel_log'];
		$fuel_total += $temp_fuel_log;

		$query_inner2 = "SELECT sum(`delivery_rate`) as additional_log FROM `fleet_additional_log`  WHERE `driver_id` in (select did from  `fleet_driver` where shop_id=$shop_id) and date='$each_date'";
		$result_inner2 = mysql_query($query_inner2);
		$row_inner2 = mysql_fetch_assoc($result_inner2);
		$temp_additional_log = $row_inner2['additional_log'];
		$additional_log_total += $temp_additional_log;

		$temp_date_unix = strtotime($each_date);
		$temp_date_formatted = date("jS M, Y", $temp_date_unix);
		echo "<tr id='tr_$counter' class='tr_clicked' data-toggle='collapse' href='#collapseExample".$counter."' aria-expanded='false' aria-controls='collapseExample".$counter."'>";
		echo "<td>".$temp_date_formatted."</td>";
		echo "<td>&#8377; ".number_format($all_add_stock_information_array[$each_date]['grand_total'])."</td>";
		echo "<td>&#8377; ".number_format($all_orders_details_array[$each_date]['grand_total'])."</td>";
		echo "<td>&#8377; ".number_format($temp_fuel_log, 2)."</td>";
		echo "<td>&#8377; ".number_format($temp_additional_log, 2)."</td>";
		echo "</tr>";

		echo "<tr class='collapse' id='collapseExample".$counter."'>";
		echo "<td colspan='5'>";
		echo "<table class='table' style='margin:0px; width:100%;'>";
		echo "<tr><td>Order ID</td><td>Amount</td></tr>";
		$all_orders_on_this_date_array = array_keys($all_orders_details_array[$each_date]);
		
		foreach($all_orders_on_this_date_array as $each_order_on_this_date)
		{
			if($each_order_on_this_date != "grand_total")
			{
				echo "<tr>";
				echo "<td><form action='$base_module_path/order/vp_ordermain.php' method='POST' target='_blank'><input type='hidden' value='1' name='flag'><input type='hidden' value='$each_order_on_this_date' name='orderid'><input type='hidden' name='display_flag' value='0'><input type='submit' class='btn btn-link button_as_link_class' value='$each_order_on_this_date'></form></td>";
				echo "<td>&#8377; ".number_format($all_orders_details_array[$each_date][$each_order_on_this_date]['grand_total'])."</td>";
				echo "</tr>";
			}
		}
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		$counter++;
	}
	echo "<tr class='stock_report_ending_tr'>";
	echo "<td></td>";
	echo "<td>&#8377; ".number_format($all_add_stock_information_array['grand_total'])."</td>";
	echo "<td>&#8377; ".number_format($all_orders_details_array['grand_total'])."</td>";
	echo "<td>&#8377; ".number_format($fuel_total)."</td>";
	echo "<td>&#8377; ".number_format($additional_log_total)."</td>";
	echo "</tr>";
	echo "</table>";

	function PrintItemName($catched_item_id)
	{
		global $item_id_to_name_mapper;
		return($item_id_to_name_mapper[$catched_item_id]);
	}
?>
