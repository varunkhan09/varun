<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($custom_database);

	$orderid = $_REQUEST['orderid'];

	$query = "select orderid from panelorderdetails where vendor_id=".$shop_id." limit 1";
	$result = mysql_query($query);
	if($result)
	{
		if(mysql_num_rows($result) == 1)
		{
			header("Location: ".$base_module_path."/order/vp_ordermain.php?flag=1&orderid=".$orderid);
		}
	}
	else
	{
		echo "This Order ID does not exist.";
	}


	$query = "select orderid from panelorderdetails where shop_id_created=".$shop_id." or shop_id_by=".$shop_id." limit 1";
	echo $query;
	$result = mysql_query($query);
	if($result)
	{
		if(mysql_num_rows($result) == 1)
		{
			header("Location: ".$base_module_path."/createdorders/vp_ordermain.php?flag=1&display_flag=0&orderid=".$orderid);
		}
	}
	else
	{
		echo "This Order ID does not exist.";
	}
?>