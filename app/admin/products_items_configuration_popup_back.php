<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

	$product_id = $_REQUEST['product_id'];
	$string = $_REQUEST['string'];
	$string_level1 = explode("<>", $string);

	$query = "select product_id from pos_products_item_details where product_id = $product_id limit 1";
	$result = mysql_query($query);

	if(mysql_num_rows($result)) 					//This Product's Data already exists...
	{
		//$query = "update ";
	}
	else 											//This Product's Data does not exist...
	{
		$query = "insert into pos_products_item_details (product_id, item_id, item_quantity) values ";
		foreach($string_level1 as $each_string_item)
		{
			$temp_level2 = explode("|", $each_string_item);
			$temp_item_id = $temp_level2[0];
			$temp_item_quantity = $temp_level2[1];

			$query .= "($product_id, $temp_item_id, $temp_item_quantity), ";
		}
		$query = rtrim($query, ", ");
		if(mysql_query($query))
		{
			echo "+1|";
		}
		else
		{
			echo "-1|";
		}
	}
?>