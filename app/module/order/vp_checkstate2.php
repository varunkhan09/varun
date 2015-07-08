<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($custom_database);

	$orderid = $_REQUEST['orderid'];
	$query = "select vendor_id from vendor_processing where orderid=$orderid limit 1";
	$result = mysql_query($query);
	while($row = mysql_fetch_row($result))
	{
		echo $row[0];
	}
?>