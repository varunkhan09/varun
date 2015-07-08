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
	$start_date_final = $start_date_final." 00:00:00";

	$end_date_final = date("Y-m-d", $end_date_unix);
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

			if($loop_counter == $number_of_rows_in_this_query)
			{
				$all_add_stock_information_array[$temp_created_at_final]['grand_total'] += $all_add_stock_information_array[$temp_created_at_final]['conveyance_charges'];
			}
			$previous_date = $temp_created_at_final;
		}



		$each_date_array = array_keys($all_add_stock_information_array);
		echo "<table class='table table-bordered stock_report_result_table'>";
		echo "<tr class='stock_report_heading_tr'><td colspan='2'>Stock Bought</td></tr>";
		$counter = 1;
		$grand_total_add_stock = 0;
		foreach($each_date_array as $each_date)
		{
			$each_date_main = $each_date;
			$each_date_main_unix = strtotime($each_date_main);
			$each_date_final = date("jS M, Y", $each_date_main_unix);
			
			echo "<tr id='tr_$counter' class='tr_clicked' data-toggle='collapse' href='#collapseExample".$counter."' aria-expanded='false' aria-controls='collapseExample".$counter."'>";

			echo "<td id='td_cell_1_$counter' class='td_clicked'>";
			echo "<div class='stock_report_level_1'>";
			echo "<div class='stock_report_level_2' id='row_1'>";
			echo $each_date_final;
			echo "</div>";
			echo "</div>";
			echo "</td>";
			

			echo "<td id='td_cell_2_$counter' class='td_clicked'>";
			echo "&#8377; ".number_format($all_add_stock_information_array[$each_date]['grand_total']);
			echo "</td>";

			echo "</tr>";




			echo "<tr class='collapse' id='collapseExample".$counter."'>";
			echo "<td colspan='2'>";
			echo "<div>";
			echo "<div class='well'>";
			echo "<table class='table' style='margin:0px; width:100%;'>";
			echo "<tr><td>Item Name</td><td>Item Quantity</td><td>Rate</td><td>Per Rate Price</td><td>Total Price</td></tr>";
			$each_date_each_product_array = array_keys($all_add_stock_information_array[$each_date]['items']);
			foreach($each_date_each_product_array as $each_product)
			{
				echo "<tr>";
				echo "<td>".PrintItemName($each_product)."</td>";
				echo "<td>".number_format($all_add_stock_information_array[$each_date]['items'][$each_product]['quantity'])."</td>";
				echo "<td>".$all_add_stock_information_array[$each_date]['items'][$each_product]['rate_name']."</td>";
				echo "<td>&#8377; ".number_format($all_add_stock_information_array[$each_date]['items'][$each_product]['rate_value'])."</td>";
				echo "<td>&#8377; ".number_format($all_add_stock_information_array[$each_date]['items'][$each_product]['price'])."</td>";
				echo "</tr>";
			}
			echo "<td colspan='3'></td><td>Conveyance Charges</td><td>&#8377; ".number_format($all_add_stock_information_array[$each_date]['conveyance_charges'])."</td>";
			echo "</table>";
			echo "</div>";
			echo "</div>";
			echo "</tr>";

			$grand_total_add_stock += $all_add_stock_information_array[$each_date]['grand_total'];

			$counter++;
		}
		echo "<tr class='stock_report_ending_tr'><td colspan='2'>Grand Total : &#8377; ".number_format($grand_total_add_stock)."</td></tr>";
		echo "</table>";
	}
	else
	{
		echo "-1|";
	}






	echo "<br><br>";







	$query = "select * from pos_stock_wastage_entity where created_at between '$start_date_final' and '$end_date_final' and shop_id=$shop_id order by created_at ASC";
	$result = mysql_query($query);
	if($result)
	{
		$all_wastage_stock_information_array = array();
		while($row = mysql_fetch_assoc($result))
		{
			$temp_entity_id = $row['entity_id'];
			$temp_item_id = $row['item_id'];
			$temp_item_quantity = $row['item_quantity'];
			$temp_item_unitscale_quantity = $row['item_unitscale_quantity'];
			$temp_created_at = $row['created_at'];

			$temp_created_at_unix = strtotime($temp_created_at);
			$temp_created_at_final = date("Y-m-d", $temp_created_at_unix);

			/*
			if($temp_created_at_final == $previous_date)
			{
				$all_wastage_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity'] = $temp_item_quantity;
				$all_wastage_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity_unitscale'] = $temp_item_unitscale_quantity;
			}
			else
			{
				$temp_grand_total = 0;
				$all_wastage_stock_information_array[$temp_created_at_final]['grand_total'] = 0;

				$all_wastage_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity'] = $temp_item_quantity;
				$all_wastage_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity_unitscale'] = $temp_item_unitscale_quantity;
			}
			$previous_date = $temp_created_at_final;
			*/

			$all_wastage_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity'] += $temp_item_quantity;
			$all_wastage_stock_information_array[$temp_created_at_final]['items'][$temp_item_id]['quantity_unitscale'] = $temp_item_unitscale_quantity;
		}



		$each_date_array = array_keys($all_wastage_stock_information_array);
		echo "<table class='table table-bordered stock_report_result_table'>";
		echo "<tr class='stock_report_heading_tr'><td>Stock Wasted</td></tr>";
		$counter = 1;
		foreach($each_date_array as $each_date)
		{
			echo "<tr id='tr_$counter' class='tr_clicked' data-toggle='collapse' href='#collapseExample2".$counter."' aria-expanded='false' aria-controls='collapseExample".$counter."'>";
			echo "<td id='td_cell_1_$counter' class='td_clicked'>";
			$each_date_main = $each_date;
			$each_date_main_unix = strtotime($each_date_main);
			$each_date_final = date("jS M, Y", $each_date_main_unix);
			echo $each_date_final;
			echo "</td>";
			echo "</tr>";



			echo "<tr class='collapse' id='collapseExample2".$counter."'>";
			echo "<td colspan='2'>";
			echo "<div>";
			echo "<div class='well'>";
			echo "<table class='table' style='margin:0px; width:100%;'>";
			echo "<tr><td>Item Name</td><td>Item Quantity</td></tr>";
			$each_date_each_product_array = array_keys($all_wastage_stock_information_array[$each_date]['items']);
			foreach($each_date_each_product_array as $each_product)
			{
				echo "<tr>";
				echo "<td>".PrintItemName($each_product)."</td>";
				echo "<td>".$all_wastage_stock_information_array[$each_date]['items'][$each_product]['quantity']."</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</div>";
			echo "</div>";
			echo "</tr>";
			$counter++;
		}
		echo "</table>";
	}
	else
	{
		echo "-1|";
	}















	echo "<br><br>";














	$query = "select * from pos_stock_deduced_entity where created_at between '$start_date_final' and '$end_date_final' and shop_id=$shop_id order by created_at ASC";
	$result = mysql_query($query);
	if($result)
	{
		$alldeduced_stock_information_array = array();
		while($row = mysql_fetch_assoc($result))
		{
			$temp_entity_id = $row['entity_id'];
			$temp_order_id = $row['order_id'];
			$temp_item_id = $row['item_id'];
			$temp_item_quantity = $row['item_quantity'];
			$temp_created_at = $row['created_at'];

			$temp_created_at_unix = strtotime($temp_created_at);
			$temp_created_at_final = date("Y-m-d", $temp_created_at_unix);

			$alldeduced_stock_information_array[$temp_created_at_final]['orders'][$temp_order_id][$temp_item_id] += $temp_item_quantity;

		}



		$each_date_array = array_keys($alldeduced_stock_information_array);
		echo "<table class='table table-bordered stock_report_result_table'>";
		echo "<tr class='stock_report_heading_tr'><td>Stock Sold</td></tr>";
		$counter = 1;
		$counter2 = 1;
		foreach($each_date_array as $each_date)
		{
			echo "<tr id='tr_$counter' class='tr_clicked' data-toggle='collapse' href='#collapseExample3".$counter."' aria-expanded='false' aria-controls='collapseExample".$counter."'>";
			echo "<td id='td_cell_1_$counter' class='td_clicked'>";
			$each_date_main = $each_date;
			$each_date_main_unix = strtotime($each_date_main);
			$each_date_final = date("jS M, Y", $each_date_main_unix);
			echo $each_date_final;
			echo "</td>";
			echo "</tr>";



			echo "<tr class='collapse' id='collapseExample3".$counter."'>";
			echo "<td colspan='2'>";
			echo "<div>";
			echo "<div class='well'>";
			echo "<table class='table' style='margin:0px; width:100%;'>";
			echo "<tr><td>Orders</td></tr>";
			$each_date_each_order_array = array_keys($alldeduced_stock_information_array[$each_date]['orders']);
			foreach($each_date_each_order_array as $each_order)
			{
				echo "<tr id='tr_$counter2' class='tr_clicked' data-toggle='collapse' href='#collapseExample4".$counter2."' aria-expanded='false' aria-controls='collapseExample".$counter2."'>";
				echo "<td>$each_order</td>";
				echo "</tr>";


				echo "<tr class='collapse' id='collapseExample4".$counter2."'>";
				echo "<td colspan='2'>";
				echo "<div>";
				echo "<div class='well'>";
				echo "<table class='table' style='margin:0px; width:100%;'>";
				echo "<tr><td>Item Name</td><td>Item Quantity</td></tr>";
				$each_date_order_products_array = array_keys($alldeduced_stock_information_array[$each_date]['orders'][$each_order]);
				foreach($each_date_order_products_array as $each_product)
				{
					echo "<tr>";
					echo "<td>".PrintItemName($each_product)."</td>";
					echo "<td>".$alldeduced_stock_information_array[$each_date]['orders'][$each_order][$each_product]."</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "</div>";
				echo "</div>";
				echo "</tr>";
				$counter2++;
			}
			echo "</table>";
			echo "</div>";
			echo "</div>";
			echo "</tr>";
			$counter++;
		}
		echo "</table>";
	}
	else
	{
		echo "-1|";
	}

	function PrintItemName($catched_item_id)
	{
		global $item_id_to_name_mapper;
		return($item_id_to_name_mapper[$catched_item_id]);
	}
?>
