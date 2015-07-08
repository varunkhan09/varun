<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	
	mysql_select_db($vendorshop_database);
	$query = "select entity_id, shop_name from pos_shop_entity";
	$result = mysql_query($query);
	echo mysql_error();
	$all_shops_array = array();
	while($row = mysql_fetch_assoc($result))
	{
		$all_shops_array[$row['entity_id']]['name'] = $row['shop_name'];
	}


	$notification_type = $_REQUEST['notf_type'];
	switch($notification_type)
	{
		case "order_created":
		{
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = $shop_id;

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_created]['name']."</b> has created an Order <a href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();

			$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
			mysql_query($query);
			$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
			mysql_query($query);
			break;
		}


		case "order_forwarded":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = $shop_id;

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_created]['name']."</b> has forwarded the Order <a href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> to <b>".$all_shops_array[$shop_id_accepted]['name']."</b>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}


		case "order_accepted":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = GetShopCreatedId($order_id);

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_accepted]['name']."</b> has accepted Order <a href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> from <b>".$all_shops_array[$shop_id_created]['name']."</b>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}



		case "order_accepted_by_admin":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = GetShopCreatedId($order_id);

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id]['name']."</b> has accepted the Order <a href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> on behalf of <b>".$all_shops_array[$shop_id_accepted]['name']."</b> from <b>".$all_shops_array[$shop_id_created]['name']."</b>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();

			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}


		case "order_accepted_with_remarks":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = GetShopCreatedId($order_id);
			$remarks = $_REQUEST['remarks'];

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_accepted]['name']."</b> has added a remark \'<b>$remarks</b>\' while accepting the Order <a href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> from <b>".$all_shops_array[$shop_id_created]['name']."</b>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}


		case "order_rejected":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = GetShopCreatedId($order_id);
			$rejection_reason = $_REQUEST['rejection_reason'];

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_accepted]['name']."</b> has rejected the Order <a href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> from <b>".$all_shops_array[$shop_id_created]['name']."</b> with reason \'<b>$rejection_reason</b>\'.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}


		case "order_rejected_by_admin":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = GetShopCreatedId($order_id);
			$rejection_reason = $_REQUEST['rejection_reason'];

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id]['name']."</b> has rejected the Order <a target='_blank' href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> on behalf of <b>".$all_shops_array[$shop_id_accepted]['name']."</b> from <b>".$all_shops_array[$shop_id_created]['name']."</b> with reason \'<b>$rejection_reason</b>\'.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}



		case "order_shipped":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = GetShopCreatedId($order_id);

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_accepted]['name']."</b> has shipped the Order <a target='_blank' href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> from <b>".$all_shops_array[$shop_id_created]['name']."</b>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}




		case "order_delivered":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = GetShopCreatedId($order_id);

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_accepted]['name']."</b> has delivered the Order <a target='_blank' href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> from <b>".$all_shops_array[$shop_id_created]['name']."</b>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}




		case "order_cancelled":
		{
			$order_id = $_REQUEST['order_id'];
			$shop_id_accepted = GetShopServingId($order_id);
			$shop_id_created = $shop_id;

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_created]['name']."</b> has cancelled the Order <a target='_blank' href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}



		case "order_closed":
		{
			$order_id = $_REQUEST['order_id'];
			$shop_id_accepted = GetShopServingId($order_id);
			$shop_id_created = $shop_id;

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_created]['name']."</b> has closed the Order <a target='_blank' href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}



		case "added_comment":
		{
			$shop_id_accepted = $_REQUEST['shop_id_accepted'];
			$order_id = $_REQUEST['order_id'];
			$shop_id_created = GetShopCreatedId($order_id);
			$comment = $_REQUEST['comment'];

			mysql_select_db($vendorshop_database);
			$query = "insert into pos_notifications (notification) values ('<b>".$all_shops_array[$shop_id_accepted]['name']."</b> has added a comment \'<b>$comment</b>\' in the Order <a target='_blank' href=\'$base_module_path/common/view_order_common.php?orderid=$order_id\'>$order_id</a> from <b>".$all_shops_array[$shop_id_created]['name']."</b>.')";
			$result = mysql_query($query);
			echo mysql_error();
			$notification_id = mysql_insert_id();



			if($shop_id_accepted == $shop_id_created)
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			else
			{
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_created, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($shop_id_accepted, $notification_id)";
				mysql_query($query);
				$query = "insert into pos_notifications_log (shop_id, notification_id) values ($flaberry_self_shop_id, $notification_id)";
				mysql_query($query);
			}
			break;
		}

		default:
		{
			break;
		}
	}



	function GetShopCreatedId($catched_order_id)
	{
		global $custom_database;
		global $vendorshop_database;

		mysql_select_db($custom_database);
		$query = "select shop_id_created from panelorderdetails where orderid=$catched_order_id limit 1";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		return($row['shop_id_created']);
	}


	function GetShopServingId($catched_order_id)
	{
		global $custom_database;
		global $vendorshop_database;

		mysql_select_db($custom_database);
		$query = "select vendor_id from panelorderdetails where orderid=$catched_order_id limit 1";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		return($row['vendor_id']);
	}
?>