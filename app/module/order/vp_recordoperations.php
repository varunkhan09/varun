<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($custom_database);
	$orderid = $_REQUEST['orderid'];
	$comment_type = $_REQUEST['comment_type'];
	$user_name = "Varun";
	$vendor_name = "Kumar";

	$comments_array = array();
	$comments_array["0"] = "$user_name sent this order for Acknowledgement from Vendor.";
	$comments_array["1"] = "$user_name has updated Vendor Price.";
	$comments_array["2"] = "$vendor_name has Acknowledged the Order.";
	$comments_array["3"] = "$vendor_name has Shipped the Order.";
	$comments_array["4"] = "$vendor_name has Delivered the Order.";
	$comments_array["5"] = "$vendor_name has Rejected the Order.";
	
	$comment = $comments_array[$comment_type];
	$order = Mage::getModel("sales/order")->load($orderid, 'increment_id');
	$order->addStatusToHistory($order->getStatus(), $comment, false);
	$order->save();
?>