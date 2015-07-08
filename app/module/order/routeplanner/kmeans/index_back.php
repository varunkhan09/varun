<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($custom_database);

	$temp_date_of_delivery = $_POST['temp_dod'];
	$temp_date_of_delivery_unix = strtotime($temp_date_of_delivery);
	$temp_date_of_delivery_final = date("Y-m-d", $temp_date_of_delivery_unix);

	$order_pincode_mapper_array = array();

	$query = "select recipient_pincode, orderid from panelorderdetails where dod='$temp_date_of_delivery_final' and vendor_id ='$shop_id' group by orderid";
	//echo $query."|";
	$result = mysql_query($query);
	if($result)
	{
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$temp_order_id = $row['orderid'];
				$query_inner = "select state from vendor_processing where orderid=$temp_order_id limit 1";
				$result_inner = mysql_query($query_inner);
				$row_inner = mysql_fetch_assoc($result_inner);
				if($row_inner['state'] == "2")
				{
					$temp_delivery_pincodes_set_array[] = $row['recipient_pincode'];
					$order_pincode_mapper_array[$row['recipient_pincode']][] = $temp_order_id;
				}
				else
				{
					
				}
			}
			
			$temp_delivery_pincodes_set_array_final = array_unique($temp_delivery_pincodes_set_array);
			$data = "";
			foreach($temp_delivery_pincodes_set_array_final as $each_pincode)
			{
				$data .= $each_pincode.",";
			}
			$data = rtrim($data, ",");

			$order_pincode_mapper_string = "";
			foreach($order_pincode_mapper_array as $key=>$value)
			{
				$order_pincode_mapper_string .= $key."-";
				foreach($value as $each_value)
				{
					$order_pincode_mapper_string .= $each_value.",";
				}
				$order_pincode_mapper_string = rtrim($order_pincode_mapper_string, ",");
				$order_pincode_mapper_string .= ":";
			}
			$order_pincode_mapper_string = rtrim($order_pincode_mapper_string, ":");

			echo "success|".$data."|".$order_pincode_mapper_string;
		}
		else
		{
			echo "-2|";
		}
	}
	else
	{
		echo "-1|";
	}
?>