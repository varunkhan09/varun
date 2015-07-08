<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
?>
	
	<div class="body_container">
	<?php
		$query_addition_stock = "select item_id, item_unitscale_quantity, created_at from pos_stock_addition_entity where shop_id = $shop_id";
		$result_addition_stock = mysql_query($query_addition_stock);


		$query_wastage_stock = "select item_id, item_unitscale_quantity, created_at from pos_stock_wastage_entity where shop_id = $shop_id";
		$result_wastage_stock = mysql_query($query_wastage_stock);


		$query_deduce_stock = "select item_id, item_quantity, created_at from pos_stock_deduced_entity where shop_id = $shop_id";
		$result_deduce_stock = mysql_query($query_deduce_stock);


		$total_stock = array();
		$stock_operations = array();
		$stock_log = array();

		/* ADDING THE STOCK FOR ADDITION STOCK */
		//echo "Addition Stock<br>";
		while($row = mysql_fetch_assoc($result_addition_stock))
		{
			$temp_item_id = $row['item_id'];
			$temp_item_quantity = $row['item_unitscale_quantity'];
			$temp_date = $row['created_at'];
			$temp_date_array = explode(" ", $temp_date);
			$temp_date = $temp_date_array[0];

			//echo "Added <u>$temp_item_quantity</u> on <b>$temp_item_id</b> on $temp_date<br>";
			
			if(isset($total_stock[$temp_item_id]))
			{
				//echo "Found Previous occurrence, Adding it.<br>";
				$total_stock[$temp_item_id] += $temp_item_quantity;
				$stock_operations[$temp_item_id] .= "+".$temp_item_quantity;
			}
			else
			{
				//echo "Adding it in new object.<br>";
				$total_stock[$temp_item_id] = $temp_item_quantity;
				$stock_operations[$temp_item_id] = $temp_item_quantity;
			}


			if(isset($stock_log[$temp_date][$temp_item_id]))
			{
				$stock_log[$temp_date]['addition'][$temp_item_id] += $temp_item_quantity;
			}
			else
			{
				$stock_log[$temp_date]['addition'][$temp_item_id] = $temp_item_quantity;
			}
		}

		//echo "<br><br>";
		/* ADDING THE STOCK FOR ADDITION STOCK */


		/* SUBTRACTING THE STOCK FOR WASTAGE STOCK */
		//echo "Wastage Stock<br>";
		while($row = mysql_fetch_assoc($result_wastage_stock))
		{
			$temp_item_id = $row['item_id'];
			$temp_item_quantity = $row['item_unitscale_quantity'];
			$temp_date = $row['created_at'];
			$temp_date_array = explode(" ", $temp_date);
			$temp_date = $temp_date_array[0];

			//echo "Wasted <u>$temp_item_quantity</u> on <b>$temp_item_id</b> on $temp_date<br>";
			
			if(isset($total_stock[$temp_item_id]))
			{
				//echo "Found Previous occurrence, Wasting it.<br>";
				$total_stock[$temp_item_id] -= $temp_item_quantity;
				$stock_operations[$temp_item_id] .= "-".$temp_item_quantity;
			}
			else
			{
				//echo "Wasting it in new object.<br>";
				$total_stock[$temp_item_id] = 0;
				$total_stock[$temp_item_id] -= $temp_item_quantity;
				$stock_operations[$temp_item_id] = "0";
				$stock_operations[$temp_item_id] .= "-".$temp_item_quantity;
			}


			if(isset($stock_log[$temp_date][$temp_item_id]))
			{
				$stock_log[$temp_date]['reduce'][$temp_item_id] -= $temp_item_quantity;
			}
			else
			{
				$stock_log[$temp_date]['reduce'][$temp_item_id] = 0;
				$stock_log[$temp_date]['reduce'][$temp_item_id] -= $temp_item_quantity;
			}
		}

		//echo "<br><br>";
		/* SUBTRACTING THE STOCK FOR WASTAGE STOCK */


		/* DEDUCING THE STOCK FOR DEDUCED STOCK */
		//echo "Deduce Stock<br>";
		while($row = mysql_fetch_assoc($result_deduce_stock))
		{
			$temp_item_id = $row['item_id'];
			$temp_item_quantity = $row['item_quantity'];
			$temp_date = $row['created_at'];
			$temp_date_array = explode(" ", $temp_date);
			$temp_date = $temp_date_array[0];

			//echo "Deduced <u>$temp_item_quantity</u> on <b>$temp_item_id</b> on $temp_date<br>";
			
			if(isset($total_stock[$temp_item_id]))
			{
				//echo "Found Previous occurrence, Concatenating it.<br>";
				$total_stock[$temp_item_id] -= $temp_item_quantity;
				$stock_operations[$temp_item_id] = "0";
				$stock_operations[$temp_item_id] .= "-".$temp_item_quantity;
			}
			else
			{
				//echo "Deducing it in new object.<br>";
				$total_stock[$temp_item_id] = 0;
				$total_stock[$temp_item_id] -= $temp_item_quantity;
			}


			if(isset($stock_log[$temp_date][$temp_item_id]))
			{
				$stock_log[$temp_date]['reduce'][$temp_id] -= $temp_item_quantity;
			}
			else
			{
				$stock_log[$temp_date]['reduce'][$temp_item_id] = 0;
				$stock_log[$temp_date]['reduce'][$temp_item_id] -= $temp_item_quantity;
			}
		}
		/* DEDUCING THE STOCK FOR DEDUCED STOCK */


		//var_dump($stock_log);
		//echo "<hr>";
		//var_dump($stock_operations);
		/* FETCHING ITEM'S DISPLAY LABELS */
		$item_id_array = array_keys($total_stock);
		$item_id_string = "";
		foreach($item_id_array as $each_item_id)
		{
			$item_id_string .= "$each_item_id, ";
		}
		$item_id_string = rtrim($item_id_string, ", ");
		//$query = "select a.entity_id, a.item_name, b.item_sub_category_name, c.item_category_name from pos_item_entity a left join pos_item_sub_category_entity b on a.item_sub_category_parent = b.entity_id inner join pos_item_category_entity c on b.item_parent = a.item_parent where a.entity_id in ($item_id_string)";
		$query = "select a.entity_id, a.item_name, b.item_sub_category_name, c.item_category_name from pos_item_entity a left join pos_item_sub_category_entity b on a.item_sub_category_parent = b.entity_id left join pos_item_category_entity c on a.item_parent = c.entity_id where a.entity_id in ($item_id_string)";
		$result = mysql_query($query);

		$all_items_details_new_array = array();
		while($row = mysql_fetch_assoc($result))
		{
			$temp_entity_id = $row['entity_id'];
			$temp_item_name = $row['item_name'];
			$temp_item_sub_category_name = $row['item_sub_category_name'];
			if($temp_item_sub_category_name == "")
			{
				$temp_item_sub_category_name = "Others";
			}
			//echo $temp_item_sub_category_name."<br>";
			$temp_item_category_name = $row['item_category_name'];





			if(strpos($temp_item_category_name, "Flower") !== false)
			{
				$all_items_details_new_array["Products"]["Flowers"][$temp_item_sub_category_name][$temp_entity_id]['name'] = $temp_item_name;
				$all_items_details_new_array["Products"]["Flowers"][$temp_item_sub_category_name][$temp_entity_id]['quantity'] = $total_stock[$temp_entity_id];
			}
			else
			{
				if(strpos($temp_item_category_name, "Cakes") !== false)
				{
					$all_items_details_new_array["Products"]["Cakes"][$temp_item_sub_category_name][$temp_entity_id]['name'] = $temp_item_name;
					$all_items_details_new_array["Products"]["Cakes"][$temp_item_sub_category_name][$temp_entity_id]['quantity'] = $total_stock[$temp_entity_id];
				}
				else
				{
					if(strpos($temp_item_category_name, "Chocolate") !== false)
					{
						$all_items_details_new_array["Products"]["Chocolates"][$temp_item_sub_category_name][$temp_entity_id]['name'] = $temp_item_name;
						$all_items_details_new_array["Products"]["Chocolates"][$temp_item_sub_category_name][$temp_entity_id]['quantity'] = $total_stock[$temp_entity_id];
					}
					else
					{
						if(strpos($temp_item_category_name, "Plants") !== false)
						{
							$all_items_details_new_array["Products"]["Plants"][$temp_item_sub_category_name][$temp_entity_id]['name'] = $temp_item_name;
							$all_items_details_new_array["Products"]["Plants"][$temp_item_sub_category_name][$temp_entity_id]['quantity'] = $total_stock[$temp_entity_id];
						}
						else
						{
							$all_items_details_new_array["Packing"][$temp_item_sub_category_name][$temp_entity_id]['name'] = $temp_item_name;
							$all_items_details_new_array["Packing"][$temp_item_sub_category_name][$temp_entity_id]['quantity'] = $total_stock[$temp_entity_id];
						}
					}
				}
			}





			/*
			if(is_null($temp_item_sub_category_name))
			{
				$temp_item_sub_category_name = "Others";
			}			
			$all_items_details_new_array[$temp_item_category_name][$temp_item_sub_category_name][$temp_entity_id]['name'] = $temp_item_name;
			$all_items_details_new_array[$temp_item_category_name][$temp_item_sub_category_name][$temp_entity_id]['quantity'] = $total_stock[$temp_entity_id];
			*/
		}
		/* FETCHING ITEM'S DISPLAY LABELS */


/*
		$item_categories_array = array_keys($all_items_details_array);
		foreach($item_categories_array as $each_category)
		{
			echo "<div class='row current_stock_page_content'>";
			echo "<div class='container-fluid current_stock_heading_division'>";
			echo "<label class='heading1_label'>$each_category</label>";
			echo "</div>";
			$items_inside_this_category_sub_category = array_keys($all_items_details_array[$each_category]);
			foreach($items_inside_this_category_sub_category as $each_sub_category)
			{
				echo "<div class='col-xs-3 text-center current_stock_level1'>";
				echo "<div class='row'>";
				echo "<label class='heading2_label'>$each_sub_category</label>";
				echo "</div>";
				$counter = 0;
				$items_inside_this_category = array_keys($all_items_details_array[$each_category][$each_sub_category]);
				foreach($items_inside_this_category as $each_item_inside_this_category)
				{
					if($counter == 0)
					{
						echo "<div class='row text-center'>";
					}
					else
					{
						echo "<div class='row text-center'>";
					}
					echo "<div class='col-xs-12 text-center'>";
					echo "<div class='col-xs-6 col-xs-offset-1 text-right data_labels_bold'>".$all_items_details_array[$each_category][$each_sub_category][$each_item_inside_this_category]['name']."</div>";
					echo "<div class='col-xs-5 text-left data_labels_normal'>".$all_items_details_array[$each_category][$each_sub_category][$each_item_inside_this_category]['quantity']."</div>";
					echo "</div>";
					echo "</div>";
					$counter++;
				}
				echo "</div>";
			}
			echo "</div>";
		}



		echo "<hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr><hr>";
*/


		
		$item_categories_array = array_keys($all_items_details_new_array);
		foreach($item_categories_array as $each_category)
		{
			if($each_category == "Products")
			{
				echo "<div class='row current_stock_page_content'>";
				echo "<div class='container-fluid current_stock_heading_division'>";
				echo "<label class='heading1_label'>$each_category</label>";
				echo "</div>";
				$items_inside_this_category_sub_category = array_keys($all_items_details_new_array[$each_category]);
				foreach($items_inside_this_category_sub_category as $each_sub_category)
				{
					echo "<div class='col-xs-3 text-center current_stock_level1'>";
					echo "<div class='row'>";
					echo "<label class='heading2_label'>$each_sub_category</label>";
					echo "</div>";
					$counter = 0;
					$items_categories_inside_this_category = array_keys($all_items_details_new_array[$each_category][$each_sub_category]);
					foreach($items_categories_inside_this_category as $each_item_category_inside_this_category)
					{
						echo "<div class='row text-center' style='margin-top:15px; font-size:18px;'>";
						echo "<u>".$each_item_category_inside_this_category."</u>";
						$items_in_this_category = array_keys($all_items_details_new_array[$each_category][$each_sub_category][$each_item_category_inside_this_category]);
						foreach($items_in_this_category as $each_item_in_this_category)
						{
							if($counter == 0)
							{
								echo "<div class='row text-center'>";
							}
							else
							{
								echo "<div class='row text-center'>";
							}
							echo "<div class='col-xs-12 text-center'>";
							echo "<div class='col-xs-7 text-right data_labels_bold'>".$all_items_details_new_array[$each_category][$each_sub_category][$each_item_category_inside_this_category][$each_item_in_this_category]['name']." : </div>";
							echo "<div class='col-xs-3 text-left data_labels_normal'>".$all_items_details_new_array[$each_category][$each_sub_category][$each_item_category_inside_this_category][$each_item_in_this_category]['quantity']."</div>";
							echo "</div>";
							echo "</div>";
							$counter++;
						}
						echo "</div>";
					}
					echo "</div>";
				}
				echo "</div>";
			}
			else
			{
				echo "<div class='row current_stock_page_content'>";
				echo "<div class='container-fluid current_stock_heading_division'>";
				echo "<label class='heading1_label'>$each_category</label>";
				echo "</div>";
				$items_inside_this_category_sub_category = array_keys($all_items_details_new_array[$each_category]);
				foreach($items_inside_this_category_sub_category as $each_sub_category)
				{
					echo "<div class='col-xs-12 text-center current_stock_level1'>";
					echo "<div class='row'>";
					echo "<label class='heading2_label'>$each_sub_category</label>";
					echo "</div>";
					$counter = 0;
					$items_inside_this_category = array_keys($all_items_details_new_array[$each_category][$each_sub_category]);
					foreach($items_inside_this_category as $each_item_inside_this_category)
					{
						if($counter == 0)
						{
							echo "<div class='col-xs-3 text-center'>";
						}
						else
						{
							echo "<div class='col-xs-3 text-center'>";
						}
						echo "<div class='col-xs-12 text-center'>";
						echo "<div class='col-xs-9 text-right data_labels_bold'>".$all_items_details_new_array[$each_category][$each_sub_category][$each_item_inside_this_category]['name']." : </div>";
						echo "<div class='col-xs-3 text-left data_labels_normal'>".$all_items_details_new_array[$each_category][$each_sub_category][$each_item_inside_this_category]['quantity']."</div>";
						echo "</div>";
						echo "</div>";
						$counter++;
					}
					echo "</div>";
				}
				echo "</div>";
			}
		}
		echo "</div>";
	?>
	</div>
