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

	$mode = $_REQUEST['mode'];

	if($mode == "load_all_products")
	{
		$query = "select item_name, entity_id from pos_item_entity where is_active=1";
		$result = mysql_query($query);
		if($result)
		{
			$all_items_array = array();

			while($row = mysql_fetch_assoc($result))
			{
				$all_items_array[$row['entity_id']] = $row['item_name'];
			}
			$all_item_array_json = json_encode($all_items_array);
			echo $all_item_array_json;
		}
		else
		{
			echo "-1|";
		}
	}
	else
	{
		if($mode == "load_all_rates")
		{
			$query = "select rate_to_unitscale_conversion_number, rate_name from pos_item_rates_entity where is_active=1";
			$result = mysql_query($query);
			if($result)
			{
				$all_rates_array = array();

				while($row = mysql_fetch_assoc($result))
				{
					$all_rates_array[$row['rate_to_unitscale_conversion_number']] = $row['rate_name'];
				}
				$all_rate_array_json = json_encode($all_rates_array);
				echo $all_rate_array_json;
			}
			else
			{
				echo "-1|";
			}
		}
		else
		{
			if($mode == "current_stock")
			{
				$item_id = $_REQUEST['item_selected'];
				$query = "select item_quantity from pos_stock_entity where item_id=$item_id and shop_id=$shop_id";
				$result = mysql_query($query);
				if($result)
				{
					$row = mysql_fetch_assoc($result);
					$item_quantity = $row['item_quantity'];
					if($item_quantity == "")
					{
						$item_quantity=0;
					}
					echo $item_quantity;
				}
				else
				{
					echo "-1|";
				}
			}
			else
			{
				if($mode == "add_stock")
				{
					$insert_flag = 1;
					$update_flag = 1;

					$input_date = $_REQUEST['input_date'];
					$input_date_timestamp_unix = strtotime($input_date);
					$input_date_timestamp = date("Y-m-d H:i:s", $input_date_timestamp_unix);

					//$query = "SET AUTOCOMMIT=0";
					//mysql_query($query);
					$query = "START TRANSACTION";
					mysql_query($query);

					if($input_date != "none")
					{
						$query = "insert into pos_stock_wastage_entity (shop_id, item_id, item_quantity, item_unitscale_quantity, created_at) values ";
					}
					else
					{
						$query = "insert into pos_stock_wastage_entity (shop_id, item_id, item_quantity, item_unitscale_quantity) values ";
					}
					$item_data_main = $_REQUEST['item_data'];
					$item_data_array = explode("<>", $item_data_main);
					foreach($item_data_array as $each_item)
					{
						$temp_details = explode("|", $each_item);
						$temp_item_id = $temp_details[0];
						$temp_item_quantity = $temp_details[1];
						if($input_date != "none")
						{
							$query .= "($shop_id, $temp_item_id, $temp_item_quantity, $temp_item_quantity, '$input_date_timestamp'), ";
						}
						else
						{
							$query .= "($shop_id, $temp_item_id, $temp_item_quantity, $temp_item_quantity), ";
						}

						$query_update = "update pos_stock_entity set item_quantity = item_quantity-$temp_item_quantity where shop_id=$shop_id and item_id=$temp_item_id;";
						echo $query_update."<br><br>";
						$temp_update_flag = mysql_query($query_update);
						echo mysql_error()."<br><br>";
						$update_flag = $update_flag && $temp_update_flag;
					}
					$query = rtrim($query, ", ");
					echo $query."<br><br>";
					$result = mysql_query($query);
					echo mysql_error()."<br><br>";
					$insert_flag = $insert_flag && $result;
					//echo $insert_flag." : ".$update_flag,"<br><br>";
					if($insert_flag && $update_flag)
					{
						echo "I am in Commit<br>";
						mysql_query("COMMIT");
						echo "+1|";
					}
					else
					{
						echo "I am in Rollback<br>";
						mysql_query("ROLLBACK");
						echo "-1|";
					}
				}
				else
				{
					if($mode == "load_date_items_details")
					{
						$date_to_load_stock = $_REQUEST['date'];
						$date_to_load_stock_unix = strtotime($date_to_load_stock);
						$date_to_load_stock_final = date("Y-m-d", $date_to_load_stock_unix);
						$date_to_load_stock_view = date("jS F, Y", $date_to_load_stock_unix);

						$query = "select item_id, item_unitscale_quantity from pos_stock_wastage_entity where shop_id=$shop_id and created_at like '$date_to_load_stock_final%'";
						$result = mysql_query($query);
						if(mysql_num_rows($result) != 0)
						{
							$items_details_array = array();
							$original_wastage_stock_serialized = "";
							while($row = mysql_fetch_assoc($result))
							{
								$temp_item_id = $row['item_id'];
								$temp_item_unitscale_quantity = $row['item_unitscale_quantity'];
								$items_details_array['wastage'][$temp_item_id] += $temp_item_unitscale_quantity;
							}
							$added_items_details = array_keys($items_details_array['wastage']);
							echo "<form id='edited_wasted_item_form' method='POST'>";
							echo "<table class='table table-bordered text-center'>";
							echo "<tr class='stock_report_heading_tr'><td colspan='2'>Stock Wasted on $date_to_load_stock_view</td></tr>";
							foreach($added_items_details as $each_added_item_id)
							{
								echo "<tr>";
								echo "<td class='table_cell_1'>".$item_id_to_name_mapper[$each_added_item_id]."</td>";
								echo "<td class='table_cell_2'><input type='textbox' name='".$each_added_item_id."' id='".$each_added_item_id."' class='each_item_stock_textbox' value='".$items_details_array['wastage'][$each_added_item_id]."'></td>";
								echo "</tr>";

								$original_wastage_stock_serialized .= $each_added_item_id."=".$items_details_array['wastage'][$each_added_item_id].",";
							}
							$original_wastage_stock_serialized = rtrim($original_wastage_stock_serialized, ",");
							echo "</table>";
							echo "</form>";
							echo "<input type='button' class='buttons' id='update_wastage_stock_b' value='Update Stock Details'>";
?>
							<script>
								original_wastage_stock_serialized = '<?php echo $original_wastage_stock_serialized; ?>';
							</script>
<?php
						}
						else
						{
							echo "-2|";
						}
					}
					else
					{

					}
				}
			}
		}
	}
?>
