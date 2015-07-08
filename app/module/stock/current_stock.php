<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	//include "/var/www/varun/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
?>
	
	<div class="body_container">
	<?php
		$query = "SELECT a.item_category_name, d.item_sub_category_name, b.item_name, c.item_quantity, b.entity_id FROM pos_item_category_entity a INNER JOIN pos_item_entity b ON a.entity_id = b.item_parent INNER JOIN pos_stock_entity c ON c.item_id = b.entity_id LEFT JOIN pos_item_sub_category_entity d on d.entity_id = b.item_sub_category_parent where a.is_active=1 and b.is_active=1 and c.shop_id=$shop_id and c.item_quantity != 0";
		$result = mysql_query($query);
		$all_items_details_array = array();
		while($row = mysql_fetch_assoc($result))
		{
			$temp_item_category_name = $row['item_category_name'];
			$temp_item_sub_category_name = $row['item_sub_category_name'];
			if(is_null($temp_item_sub_category_name))
			{
				$temp_item_sub_category_name = "Others";
			}
			$temp_item_name = $row['item_name'];
			$temp_item_entity_id = $row['entity_id'];
			$temp_item_quantity = $row['item_quantity'];

			$all_items_details_array[$temp_item_category_name][$temp_item_sub_category_name][$temp_item_entity_id]['name'] = $temp_item_name;
			$all_items_details_array[$temp_item_category_name][$temp_item_sub_category_name][$temp_item_entity_id]['quantity'] = $temp_item_quantity;
		}
		//var_dump($all_items_details_array);


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
				echo "<div class='flower_details_div'>";
				echo "<div class='flower_name_div'>";
				echo "<label class='heading2_label'>$each_sub_category</label>";
				echo "</div>";
				$counter = 0;
				$items_inside_this_category = array_keys($all_items_details_array[$each_category][$each_sub_category]);
				foreach($items_inside_this_category as $each_item_inside_this_category)
				{
					if($counter == 0)
					{
						echo "<div class='each_flower first_flower'>";
					}
					else
					{
						echo "<div class='each_flower'>";
					}
					echo "<div class='col-xs-12'>";
					echo "<div class='col-xs-4 col-xs-offset-1 data_labels_bold text-right'>".$all_items_details_array[$each_category][$each_sub_category][$each_item_inside_this_category]['name']."</div>";
					echo "<div class='col-xs-4 data_labels_normal text-left'>".$all_items_details_array[$each_category][$each_sub_category][$each_item_inside_this_category]['quantity']."</div>";
					echo "</div>";
					echo "</div>";
					$counter++;
				}
				echo "</div>";
			}
			echo "</div>";
		}
		echo "</div>";
		*/





















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
		echo "</div>";
	?>
	</div>
