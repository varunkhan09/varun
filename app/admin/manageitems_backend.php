<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($vendorshop_database);

	$mode = $_REQUEST['mode'];

	if($mode == "add")
	{
		$value_parent = $_REQUEST['value_parent'];
		$value_child_add = $_REQUEST['value_child_add'];
		$value_child_sub_category_add = $_REQUEST['value_child_sub_category_add'];
		if($value_child_sub_category_add == "")
		{
			$value_child_sub_category_add = "NULL";
		}

		$query = "insert into pos_item_entity (item_name, item_parent, item_sub_category_parent, is_active) values ('$value_child_add', $value_parent, $value_child_sub_category_add, 1)";
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
					echo "-1|";
					echo mysql_error();
				}
			}
			else
			{
				if($mode == "load_sub_category")
				{
					echo "<option value=''>Select</option>";
					$parent = $_REQUEST['parent'];
					$query = "SELECT b.item_sub_category_name, b.entity_id FROM pos_item_category_entity a INNER JOIN pos_item_sub_category_entity b on a.entity_id = b.item_parent where a.entity_id=$parent";
					$result = mysql_query($query);

					if($result)
					{
						if(mysql_num_rows($result) > 0)
						{
							while($row = mysql_fetch_assoc($result))
							{
								echo "<option value='".$row['entity_id']."'>".$row['item_sub_category_name']."</option>";
							}
						}
						else
						{
							echo "0";
						}
					}
					else
					{
						echo "-1|".mysql_error();
					}
				}
			}
		}
	}
?>
