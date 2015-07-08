<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($vendorshop_database);

	$first_load = $_REQUEST['first'];

	switch($first_load)
	{
		case "yes":
		{
			$start = $_REQUEST['start'];
			$limit = $_REQUEST['limit'];

			$query = "select b.notification, b.created_at, a.notification_id from pos_notifications_log a inner join pos_notifications b on a.notification_id = b.id where a.shop_id=$shop_id and a.view_flag=1 order by a.view_flag ASC, b.created_at DESC limit $start, $limit";


			$result = mysql_query($query);
			$notifications_array = array();
			while($row = mysql_fetch_assoc($result))
			{
				$temp_notification = $row['notification'];
				$temp_notification_id = $row['notification_id'];

				$notifications_array['data'][] = $temp_notification;
				$notifications_array['ids'][] = $temp_notification_id;
			}
			echo json_encode($notifications_array);
			break;
		}

		case "no":
		{
			$start = $_REQUEST['start'];
			$limit = $_REQUEST['limit'];

			$query = "select b.notification, b.created_at, a.notification_id from pos_notifications_log a inner join pos_notifications b on a.notification_id = b.id where a.shop_id=$shop_id and a.view_flag=0 order by b.created_at DESC limit $start, $limit";
			//echo $query;

			$result = mysql_query($query);
			$notifications_array = array();
			while($row = mysql_fetch_assoc($result))
			{
				$temp_notification = $row['notification'];
				$temp_notification_id = $row['notification_id'];

				$notifications_array['data'][] = $temp_notification;
				$notifications_array['ids'][] = $temp_notification_id;
			}
			echo json_encode($notifications_array);
			break;
		}


		case "load_more":
		{
			$start = $_REQUEST['start'];
			$limit = $_REQUEST['limit'];

			$query = "select b.notification, b.created_at, a.notification_id from pos_notifications_log a inner join pos_notifications b on a.notification_id = b.id where a.shop_id=$shop_id order by a.view_flag ASC, b.created_at DESC limit $start, $limit";
			

			$result = mysql_query($query);
			$notifications_array = array();
			while($row = mysql_fetch_assoc($result))
			{
				$temp_notification = $row['notification'];
				$temp_notification_id = $row['notification_id'];

				$notifications_array['data'][] = $temp_notification;
				$notifications_array['ids'][] = $temp_notification_id;
			}
			echo json_encode($notifications_array);
			break;
		}



		case "mark_read":
		{
			$notification_ids_json_array = $_REQUEST['notification_ids'];
			$notification_ids_array = json_decode($notification_ids_json_array);

			$final_result = true;
			foreach($notification_ids_array as $each_notification)
			{
				$query = "update pos_notifications_log set view_flag = 1 where notification_id=$each_notification and shop_id=$shop_id";
				$result = mysql_query($query);
				$final_result = $final_result and $result;
			}

			if($final_result)
			{
				echo "+1|";
			}
			else
			{
				echo "-1|";
			}
			break;
		}


		default:
		{
			break;
		}
	}
?>