<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($custom_database);

	$orderid = $_REQUEST['orderid'];
	$query = "select MAX(state) from vendor_processing where orderid=$orderid";
	$result = mysql_query($query);
	while($row = mysql_fetch_row($result))
	{
		echo $row[0];
	}
?>
