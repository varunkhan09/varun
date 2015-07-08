<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

	mysql_select_db($vendorshop_database);
	$item_id_to_name_mapper = array();
	$query = "select entity_id, item_name from pos_item_entity";
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result))
	{
		$item_id_to_name_mapper[$row['entity_id']] = $row['item_name'];
	}


	mysql_select_db($custom_database);
	$start_date = $_REQUEST['start_date'];
	$end_date = $_REQUEST['end_date'];
	$report_mode = $_REQUEST['report_mode'];
	$selected_vendor_shop_id = $_REQUEST['selected_vendor'];

	$start_date_unix = strtotime($start_date);
	$end_date_unix = strtotime($end_date);

	$start_date_final = date("Y-m-d", $start_date_unix);
	$end_date_final = date("Y-m-d", $end_date_unix);



	switch($report_mode)
	{
		case "receivable":
		{
			$query = "select distinct orderid, dod from panelorderdetails where vendor_id = $shop_id and shop_id_created = $selected_vendor_shop_id and dod between '$start_date_final' and '$end_date_final'";
			break;
		}

		case "payable":
		{
			$query = "select distinct orderid, dod from panelorderdetails where vendor_id = $selected_vendor_shop_id and shop_id_created = $shop_id and dod between '$start_date_final' and '$end_date_final'";
			break;
		}

		default:
		{
			break;
		}
	}

	$all_orders_details_array = array();
	$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result))
	{
		$temp_order_id = $row['orderid'];
		$temp_dod = $row['dod'];
		mysql_select_db($custom_database);
		$query_inner = "select productid, productsku, productquantity, productunitprice, vendor_price from panelorderdetails where orderid = $temp_order_id";
		$result_inner = mysql_query($query_inner);
		while($row_inner = mysql_fetch_assoc($result_inner))
		{
			$temp_product_id = $row_inner['productid'];
			$temp_product_sku = $row_inner['productsku'];
			$temp_product_quantity = $row_inner['productquantity'];
			$temp_vendor_price = $row_inner['vendor_price'];
			$temp_product_unit_price = $row_inner['productunitprice'];
			$temp_price = $temp_product_unit_price*$temp_product_quantity;

			$all_orders_details_array[$temp_dod][$temp_order_id][$temp_product_id]['vendor_price'] = $temp_vendor_price;
			$all_orders_details_array[$temp_dod][$temp_order_id][$temp_product_id]['price'] = $temp_price;
			$all_orders_details_array[$temp_dod][$temp_order_id]['vendor_price_total'] += $temp_vendor_price;
			$all_orders_details_array[$temp_dod][$temp_order_id]['price_total'] += $temp_price;
			$all_orders_details_array[$temp_dod]['vendor_price_total'] += $temp_vendor_price;
			$all_orders_details_array[$temp_dod]['price_total'] += $temp_price;
			$all_orders_details_array['vendor_price_total'] += $temp_vendor_price;
			$all_orders_details_array['price_total'] += $temp_price;


			if(strpos($temp_product_sku, "custom:") !== false)
			{
				$query_inner2 = "select item_id, item_quantity from pos_order_items_entity where product_id = $temp_product_id and order_id = $temp_order_id";
			}
			else
			{
				$query_inner2 = "select item_id, item_quantity from pos_products_item_details where product_id = $temp_product_id";
			}
			mysql_select_db($vendorshop_database);
			$result_inner2 = mysql_query($query_inner2);
			while($row_inner2 = mysql_fetch_assoc($result_inner2))
			{
				$temp_item_id = $row_inner2['item_id'];
				$temp_item_quantity = $row_inner2['item_quantity'];

				$all_orders_details_array[$temp_dod][$temp_order_id]['items'][$temp_item_id] += $temp_item_quantity;
			}
		}
	}

	//var_dump($all_orders_details_array);

	$all_dates_array = array_keys($all_orders_details_array);
	echo "<table class='table table-bordered stock_report_result_table'>";
	echo "<tr class='stock_report_heading_tr'><td>Date of Delivery</td><td>Order ID</td><td>Price</td></tr>";
	$counter = 1;
	$total_order_counter = 0;
	foreach($all_dates_array as $each_date)
	{
		if(strpos($each_date, 'vendor_price_total') !== false || strpos($each_date, 'price_total') !== false)
		{

		}
		else
		{
			$each_date_unix = strtotime($each_date);
			$each_date_formatted = date("jS M, Y", $each_date_unix);

			$all_orders_on_this_date_array = array_keys($all_orders_details_array[$each_date]);
				foreach($all_orders_on_this_date_array as $each_order_on_this_date)
				{
					if(strpos($each_order_on_this_date, 'vendor_price_total') !== false || strpos($each_order_on_this_date, 'price_total') !== false)
					{

					}
					else
					{
						$total_order_counter++;
						echo "<tr id='tr_$counter' class='tr_clicked' data-toggle='collapse' href='#collapseExample".$counter."' aria-expanded='false' aria-controls='collapseExample".$counter."'>";
						echo "<td>$each_date_formatted</td>";
						echo "<td>$each_order_on_this_date</td>";
						echo "<td>₹ ".number_format($all_orders_details_array[$each_date][$each_order_on_this_date]['vendor_price_total'])."</td>";
						echo "</tr>";

						$all_ordered_items_details_array = array_keys($all_orders_details_array[$each_date][$each_order_on_this_date]['items']);
						echo "<tr class='collapse' id='collapseExample".$counter."'>";
							echo "<td colspan='3'>";
								echo "<table class='table' style='margin:0px; width:100%;'>";
									foreach($all_ordered_items_details_array as $each_order_items_details)
									{
										echo "<tr>";
											echo "<td colspan='1'>".$item_id_to_name_mapper[$each_order_items_details]."</td>";
											echo "<td colspan='1'>".$all_orders_details_array[$each_date][$each_order_on_this_date]['items'][$each_order_items_details]."</td>";
										echo "</tr>";
									}
								echo "</table>";
							echo "</td>";
						echo "</tr>";
						$counter++;
					}
				}
			echo "</tr>";
		}
	}
	echo "<tr class='stock_report_ending_tr'><td></td><td>Total $total_order_counter Orders</td><td>₹ ".number_format($all_orders_details_array['vendor_price_total'])."</td></tr>";
?>