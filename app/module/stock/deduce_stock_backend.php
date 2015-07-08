<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

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
				if($mode == "deduce_stock")
				{
					$insert_flag = 1;
					$update_flag = 1;

					$order_id = $_REQUEST['orderid'];
					
					$query = "START TRANSACTION";
					mysql_query($query);

					$query = "insert into pos_stock_deduced_entity (shop_id, order_id, item_id, item_quantity) values ";

					$item_data_main = $_REQUEST['item_data'];
					$item_data_array = explode("<>", $item_data_main);
					foreach($item_data_array as $each_item)
					{
						$temp_details = explode("|", $each_item);
						$temp_item_id = $temp_details[0];
						$temp_item_quantity = $temp_details[1];
						$query .= "($shop_id, $order_id, $temp_item_id, $temp_item_quantity), ";
						

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
					
				}
			}
		}
	}
?>
