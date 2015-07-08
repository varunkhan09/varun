<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($custom_database);

	$orderid = $_REQUEST['orderid'];

	/* THIS CODE IS ADDED TO DEDUCT ITEM STOCK FROM VENDOR WHO DELIVERED THIS ORDER */
		$query = "select vendor_id, productid, productquantity, productsku from panelorderdetails where orderid=$orderid";
		$result = mysql_query($query);
		echo "<br>".$query;
		//echo "<br>".mysql_error();
		$ordered_product_details = array();
		mysql_select_db($vendorshop_database);
		while($row = mysql_fetch_assoc($result))
		{
			$attendant_shop_id = $row['vendor_id'];
			$temp_product_id = $row['productid'];
			$temp_product_quantity = $row['productquantity'];
			$temp_product_sku = $row['productsku'];

			echo "<br><br><br><br>";
			print_r($row);
			echo "<br>";

			if(strpos($temp_product_sku, "custom:") !== false) 											//This product is a Custom Product...
			{
				echo "It is Custom Product<br>";
				$query_inner = "select item_id, item_quantity from pos_order_items_entity where shop_id=$attendant_shop_id and order_id=$orderid and product_id=$temp_product_id";
				$result_inner = mysql_query($query_inner);
				echo "<br>".$query_inner;
				//echo "<br>".mysql_error()."<br><br>";

				while($row_inner = mysql_fetch_assoc($result_inner))
				{
					echo "<br>";
					print_r($row_inner);
					echo "<br>";

					$temp_item_id = $row_inner['item_id'];
					$temp_item_quantity = $row_inner['item_quantity'];

					$total_item_quantity = $temp_product_quantity*$temp_item_quantity;

					if(isset($deduction_array[$temp_item_id]))
					{
						$deduction_array[$temp_item_id] += $total_item_quantity;
					}
					else
					{
						$deduction_array[$temp_item_id] = $total_item_quantity;
					}
				}
			}
			else 																						//This product is a Normal Product...
			{
				echo "It is Normal Product<br>";
				$query_inner = "select item_id, item_quantity from pos_products_item_details where product_id=$temp_product_id";
				$result_inner = mysql_query($query_inner);
				echo "<br>".$query_inner;
				//echo "<br>".mysql_error()."<br><br>";

				while($row_inner = mysql_fetch_assoc($result_inner))
				{
					echo "<br>";
					print_r($row_inner);
					echo "<br>";

					$temp_item_id = $row_inner['item_id'];
					$temp_item_quantity = $row_inner['item_quantity'];

					$total_item_quantity = $temp_product_quantity*$temp_item_quantity;

					if(isset($deduction_array[$temp_item_id]))
					{
						$deduction_array[$temp_item_id] += $total_item_quantity;
					}
					else
					{
						$deduction_array[$temp_item_id] = $total_item_quantity;
					}
				}
			}
		}
		echo "<br><br><br>";
		var_dump($deduction_array);
		echo "<br><br>";
		$varun = array_keys($deduction_array);
		$query_inner2 = "insert into pos_stock_deduced_entity (shop_id, order_id, item_id, item_quantity) values ";
		foreach($varun as $each_item)
		{
			$query = "update pos_stock_entity set item_quantity=item_quantity-".$deduction_array[$each_item]." where item_id=$each_item and shop_id=$attendant_shop_id";
			echo $query."<br>";
			$query_inner2 .= "($attendant_shop_id, $orderid, $each_item, ".$deduction_array[$each_item]."), ";
			mysql_query($query);
		}
		$query_inner2 = rtrim($query_inner2, ", ");
		mysql_query($query_inner2);
		echo "<br><br>";
		echo $query_inner2;
		echo "<br>".mysql_error();

		mysql_select_db($custom_database);
	/* THIS CODE IS ADDED TO DEDUCT ITEM STOCK FROM VENDOR WHO DELIVERED THIS ORDER */
?>