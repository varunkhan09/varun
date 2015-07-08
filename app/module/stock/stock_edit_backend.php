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

	$mode = $_REQUEST['mode'];

	switch($mode)
	{
		case "load_details":
		{
			$temp_date = $_REQUEST['date'];
			$temp_date_unix = strtotime($temp_date);
			$temp_date_final = date("Y-m-d", $temp_date_unix);

			$items_details_array = array();
			$original_added_item_data = "";
			$original_wasted_item_data = "";
			$original_deduced_item_data = "";



			/* FETCHING ALL ADD STOCK RECORDS */
				echo "<form id='edited_added_item_form' method='POST'>";
				$query = "select item_id, item_unitscale_quantity from pos_stock_addition_entity where shop_id=$shop_id and created_at like '$temp_date_final%'";
				$result = mysql_query($query);
				while($row = mysql_fetch_assoc($result))
				{
					$temp_item_id = $row['item_id'];
					$temp_item_unitscale_quantity = $row['item_unitscale_quantity'];
					$items_details_array['addition'][$temp_item_id] += $temp_item_unitscale_quantity;
				}
				$added_items_details = array_keys($items_details_array['addition']);
				echo "<table class='table table-bordered text-center'>";
				echo "<tr class='stock_report_heading_tr'><td colspan='2'>Stock Added</td></tr>";
				foreach($added_items_details as $each_added_item_id)
				{
					echo "<tr>";
					echo "<td class='table_cell_1'>".$item_id_to_name_mapper[$each_added_item_id]."</td>";
					echo "<td class='table_cell_2'><input type='textbox' name='".$each_added_item_id."' id='".$each_added_item_id."' class='each_item_stock_textbox' value='".$items_details_array['addition'][$each_added_item_id]."'></td>";
					echo "</tr>";

					$original_added_item_data .= $each_added_item_id."=".$items_details_array['addition'][$each_added_item_id].",";
				}
				echo "</table>";
				echo "</form>";
			/* FETCHING ALL ADD STOCK RECORDS */



			echo "<br><br>";



			/* FETCHING ALL WASTAGE STOCK RECORDS */
				echo "<form id='edited_wasted_item_form' method='POST'>";
				$query = "select item_id, item_unitscale_quantity from pos_stock_wastage_entity where shop_id=$shop_id and created_at like '$temp_date_final%'";
				$result = mysql_query($query);
				while($row = mysql_fetch_assoc($result))
				{
					$temp_item_id = $row['item_id'];
					$temp_item_unitscale_quantity = $row['item_unitscale_quantity'];
					$items_details_array['wastage'][$temp_item_id] += $temp_item_unitscale_quantity;
				}
				$wasted_items_details = array_keys($items_details_array['wastage']);
				echo "<table class='table table-bordered'>";
				echo "<tr class='stock_report_heading_tr'><td colspan='2'>Stock Wasted</td></tr>";
				foreach($wasted_items_details as $each_wasted_item_id)
				{
					echo "<tr>";
					echo "<td class='table_cell_1'>".$item_id_to_name_mapper[$each_wasted_item_id]."</td>";
					echo "<td class='table_cell_2'><input type='textbox' name='".$each_wasted_item_id."' id='".$each_wasted_item_id."' class='each_item_stock_textbox' value='".$items_details_array['wastage'][$each_wasted_item_id]."'></td>";
					echo "</tr>";

					$original_wasted_item_data .= $each_wasted_item_id."=".$items_details_array['wastage'][$each_wasted_item_id].",";
				}
				echo "</table>";
				echo "</form>";
			/* FETCHING ALL WASTAGE STOCK RECORDS */



			echo "<br><br>";




			/* FETCHING ALL DEDUCED STOCK RECORDS */
				echo "<form id='edited_deduced_item_form' method='POST'>";
				$query = "select order_id, item_id, item_quantity from pos_stock_deduced_entity where shop_id=$shop_id and created_at like '$temp_date_final%'";
				$result = mysql_query($query);
				while($row = mysql_fetch_assoc($result))
				{
					$temp_order_id = $row['order_id'];
					$temp_item_id = $row['item_id'];
					$temp_item_unitscale_quantity = $row['item_quantity'];
					$items_details_array['deduced'][$temp_order_id][$temp_item_id] += $temp_item_unitscale_quantity;
				}
				$deduced_items_details = array_keys($items_details_array['deduced']);
				echo "<table class='table table-bordered'>";
				echo "<tr class='stock_report_heading_tr'><td colspan='2'>Stock Deduced</td></tr>";
				
				$counter = 1;
				foreach($deduced_items_details as $each_deduced_order_id)
				{
					echo "<tr data-toggle='collapse' href='#collapseExample".$counter."' class='collapsable_row' aria-expanded='false' aria-controls='collapseExample".$counter."'>";
						echo "<td>Order Number $each_deduced_order_id</td>";
					echo "</tr>";


					echo "<tr class='collapse' id='collapseExample".$counter."'>";
						echo "<td colspan='2'>";
							echo "<table class='table table-bordered inner_table'>";
							$all_items_in_this_order = array_keys($items_details_array['deduced'][$each_deduced_order_id]);
							foreach($all_items_in_this_order as $each_item_in_this_order)
							{
								echo "<tr>";
									echo "<td class='table_cell_1'>".$item_id_to_name_mapper[$each_item_in_this_order]."</td>";
									echo "<td class='table_cell_2'><input type='textbox' name='".$each_deduced_order_id."_".$each_item_in_this_order."' id='".$each_deduced_order_id."_".$each_item_in_this_order."' class='each_item_stock_textbox' value='".$items_details_array['deduced'][$each_deduced_order_id][$each_item_in_this_order]."'></td>";
								echo "</tr>";
								$original_deduced_item_data .= $each_deduced_order_id."_".$each_item_in_this_order."=".$items_details_array['deduced'][$each_deduced_order_id][$each_item_in_this_order].",";
							}
							echo "</table>";
						echo "</td>";
					echo "</tr>";
					$counter++;
				}

				$original_added_item_data = rtrim($original_added_item_data, ",");
				$original_wasted_item_data = rtrim($original_wasted_item_data, ",");
				$original_deduced_item_data = rtrim($original_deduced_item_data, ",");

				echo "</table>";
				echo "</form>";
			/* FETCHING ALL DEDUCED STOCK RECORDS */



			echo "<br><br>";
			echo "<center><input type='button' class='buttons' id='save_stock_details_b' value='Save Details'></center>";
			echo "<br><br>";

?>
			<script>
				original_added_item_data = '<?php echo $original_added_item_data; ?>';
				original_wasted_item_data = '<?php echo $original_wasted_item_data; ?>';
				original_deduced_item_data = '<?php echo $original_deduced_item_data; ?>';
			</script>
<?php
			break;
		}




		case "update_details":
		{
			var_dump();
			$temp_date = $_REQUEST['date'];
			$temp_date_unix = strtotime($temp_date);
			$temp_date_final = date("Y-m-d", $temp_date_unix);
			$temp_date_final_with_zeros .= " 00:00:00";

			$type_of = $_REQUEST['type_of'];
			switch($type_of)
			{
				case "add_stock":
				{
					$original_added_item_data = $_REQUEST['original_added_item_data'];
					$edited_added_item_log = $_REQUEST['edited_added_item_log'];

					$edited_added_item_log_array = explode(",", $edited_added_item_log);
					foreach($edited_added_item_log_array as $each_added_item)
					{
						$temp_each_item_entry_array = explode("=", $each_added_item);
						$query_update = "update pos_stock_addition_entity set item_unitscale_quantity = ".$temp_each_item_entry_array[1]." where item_id=".$temp_each_item_entry_array[0]." and shop_id=$shop_id and created_at like '$temp_date_final%'";
						//echo $query_update."<br>";
						mysql_query($query_update);
						//echo mysql_error();
					}

					$query_insert = "insert into pos_stock_edit_log (shop_id, previous_item_log, edited_item_log, updated_for_date) values ($shop_id, '$original_added_item_data', '$edited_added_item_log', 'add_stock', '$temp_date_final')";
					break;
				}

				default:
				{
					echo "-1|";
					break;
				}
			}


			$result_insert = mysql_query($query_insert);

			if($result_insert)
			{
				echo "+1|";
			}
			else
			{
				echo "-1|";
			}
			break;
/*
			$original_wasted_item_data = $_REQUEST['original_wasted_item_data'];
			$edited_wasted_item_data = $_REQUEST['edited_wasted_item_data'];

			$original_deduced_item_data = $_REQUEST['original_deduced_item_data'];
			$edited_deduced_item_data = $_REQUEST['edited_deduced_item_data'];
*/


/*
			$query = "insert into pos_stock_edit_log (shop_id, previous_item_log, edited_item_log, updated_stock, updated_for_date) values ";
			if(!empty($original_added_item_data) && !empty($edited_added_item_log))
			{
				$query .= "($shop_id, '$original_added_item_data', '$edited_added_item_log', 'add_stock', '$temp_date_final'), ";
			}
*/


/*
			if(!empty($original_wasted_item_data) && !empty($edited_wasted_item_data))
			{
				$query .= "($shop_id, '$original_wasted_item_data', '$edited_wasted_item_data', 'wastage_stock', '$temp_date_final'), ";
			}

			if(!empty($original_deduced_item_data) && !empty($edited_deduced_item_data))
			{
				$query .= "($shop_id, '$original_deduced_item_data', '$edited_deduced_item_data', 'deduce_stock', '$temp_date_final'), ";
			}
*/
		}





		default:
		{
			break;
		}
	}
?>