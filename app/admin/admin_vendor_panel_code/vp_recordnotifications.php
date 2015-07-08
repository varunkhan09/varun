<?php
	include 'vp_dbconfig.php';
	$orderid = $_REQUEST['orderid'];
	$notification_type = $_REQUEST['notification_type'];
	$username = $_REQUEST['username'];
	$vendor_id = $_REQUEST['vendor_id']; 				//COMMENTING AS VENDOR ID SHOULD BE AVAILABLE FROM DBCONFIG FILE.
	$remark = $_REQUEST['remark'];


	/* THIS CODE FETCHES VENDOR NAME FROM VENDORS BASED ON VENDOR ID PASSED TO IT */
	if($vendor_id != "0")
	{
		$query = "select vendor_name from vendors where vendor_id=$vendor_id";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$vendor_name = $row['vendor_name'];
	}
	else
	{
		if($vendor_id == "0")
		{
			$vendor_name = $username;
		}
	}
	/* THIS CODE FETCHES VENDOR NAME FROM VENDORS BASED ON VENDOR ID PASSED TO IT */


	$query = "insert into vendor_notifications (type, user, value, orderid, display_once) values ";
	if($notification_type == "0")
	{
		$query .= "($notification_type, '$username', $vendor_id, $orderid, 0)";
	}
	else
	{
		if($notification_type == "1")
		{
			$query .= "($notification_type, '$username', $vendor_id, $orderid, 0)";
		}
		else
		{
			if($notification_type == "2" || $notification_type == "3" || $notification_type == "4" || $notification_type == "5")
			{
				$query .= "($notification_type, '', $vendor_id, $orderid, 0)";
			}
			else
			{
				if($notification_type == "6" || $notification_type == "7")
				{
					$query .= "($notification_type, '$remark|$username', $vendor_id, $orderid, 0)";
				}
			}
		}
	}

	echo $query;
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
?>