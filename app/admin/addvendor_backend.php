<?php
	//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/global_variables.php";
	include "/var/www/varun/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

	$mode = $_REQUEST['mode'];

	if($mode == "add")
	{
		$value_parent = $_REQUEST['value_parent'];
		$value_child_add = $_REQUEST['value_child_add'];

		$query = "insert into pos_item_entity (item_name, item_parent, is_active) values ('$value_child_add', $value_parent, 1)";
		$result = mysql_query($query);

		if($result)
		{
			echo "+1";
		}
		else
		{
			echo "-1|".mysql_error();
		}
	}
	else
	{
		if($mode == "load_items")
		{
			$value_parent = $_REQUEST['value_parent'];
			$query = "select entity_id, item_name from pos_item_entity where item_parent=$value_parent AND is_active=1";
			$result = mysql_query($query);
			if($result)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo "<option value='".$row['entity_id']."'>".$row['item_name']."</option>";
				}
			}
			else
			{
				echo "-1";
			}
		}
		else
		{
			if($mode == "disable")
			{
				$value_child_disable = $_REQUEST['value_child_disable'];
				$query = "update pos_item_entity set is_active = 0 where entity_id=$value_child_disable";
				$result = mysql_query($query);
				if($result)
				{
					echo "+1";
				}
				else
				{
					echo "-1";
					echo mysql_error();
				}
			}
		}
	}
?>