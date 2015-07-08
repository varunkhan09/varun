<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";

	$start = $_REQUEST['start'];
	$limit = $_REQUEST['limit'];

	$products = Mage::getModel('catalog/product')->getCollection();//->getSelect()->limitPage("200", "2");
	
	/* THIS CODE CALCUALTES THE NUMBER OF PAGES */
	$products->setPage($start, $limit);
	$divident = $number_of_products/$limit;
	$max_page_number = ceil($divident);
	/* THIS CODE CALCUALTES THE NUMBER OF PAGES */


	//$products->setPage($start, $limit);
	foreach($products as $each_product)
	{
		$product = Mage::getModel('catalog/product')->load($each_product->getId());

		$all_products_array[$product->getId()]['name'] = $product->getName();
		$all_products_array[$product->getId()]['sku'] = $product->getSku();
		$all_products_array[$product->getId()]['vendor_description'] = $product->getVendorDescription();
		$query_inner = "select a.item_id, a.item_quantity, b.item_name from pos_products_item_details a inner join pos_item_entity b on a.item_id = b.entity_id where a.product_id = ".$each_product->getId();
		$result_inner = mysql_query($query_inner);
		$temp_item_details = "";
		while($row_inner = mysql_fetch_assoc($result_inner))
		{
			$temp_item_name = $row_inner['item_name'];
			$temp_item_quantity = $row_inner['item_quantity'];
			$temp_item_details .= "$temp_item_name : $temp_item_quantity<br>";
		}
		$temp_item_details = rtrim($temp_item_details, "<br>");
		$all_products_array[$product->getId()]['item_details'] = $temp_item_details;


		//$all_products_array[$product->getId()]['item_details'] = "";
	}

	echo "<table class='table table-hover text-center products_items_configuration_main_text_div_main_table'>";
	echo "<tr class='stock_report_heading_tr'>";
	echo "<td>Product ID</td>";
	echo "<td>Product Name</td>";
	echo "<td>Vendor Description</td>";
	echo "<td>Item Details</td>";
	echo "<td></td>";
	echo "</tr>";

	$all_products_id_array = array_keys($all_products_array);
	$counter = 1;
	foreach($all_products_id_array as $each_product_id)
	{
		echo "<tr id='product_tr_$counter'>";
		echo "<td>$each_product_id<input type='hidden' id='product_sku_$counter' value='".$all_products_array[$each_product_id]['sku']."'><input type='hidden' id='product_id_$counter' value='$each_product_id'></td>";
		echo "<td>".$all_products_array[$each_product_id]['name']."</td>";
		echo "<td>".$all_products_array[$each_product_id]['vendor_description']."</td>";
		echo "<td>".$all_products_array[$each_product_id]['item_details']."</td>";
		echo "<td><input type='button' class='btn btn-link edit_button_class' id='edit_b_$counter' value='Edit'></td>";
		echo "</tr>";
		$counter++;
	}
	echo "</table>";

	echo "<ul class='pagination products_items_configuration_pagination_div'><li id='pagination_prev_ul'>Prev</li><li><input type='number' id='pagination_page_ul' value='$start' style='width:60px; color:black;'></li><li id='pagination_next_ul'>Next</li></ul>";
?>
