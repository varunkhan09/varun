<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($vendorshop_database);

	$mode = $_REQUEST['mode'];

	switch($mode)
	{
		case "vendornamefromid":
		{
			$selected_vendor = $_REQUEST['selected_vendor'];
			$query = "select shop_name from pos_shop_entity where email='$selected_vendor'";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			echo $row['shop_name'];
			break;
		}

		default:
		{
			break;
		}
	}
?>