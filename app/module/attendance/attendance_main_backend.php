<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

	$mode = $_REQUEST['mode'];

	switch($mode)
	{
		case "add_attendance":
		{
			$data = $_REQUEST['attendance_data'];
			$employee_id = $_REQUEST['employee_id'];

			$query = "insert into pos_attendance_data (shop_id, employee_id, attendance_date, in_time, out_time) values ";
			$all_date_and_time_array = explode("<>", $data);
			foreach($all_date_and_time_array as $each_date_and_time)
			{
				$temp_array_1 = explode("|", $each_date_and_time);
				$temp_attendance_date = $temp_array_1[0];

				$temp_array_2 = explode("-", $temp_array_1[1]);
				$temp_in_time = $temp_array_2[0];
				$temp_out_time = $temp_array_2[1];

				$temp_in_time_unix = strtotime($temp_in_time);
				$temp_out_time_unix = strtotime($temp_out_time);

				echo $temp_in_time_unix;
				echo "<br>";
				echo $temp_out_time_unix;

				$temp_in_time_final = date("H:i", $temp_in_time_unix);
				$temp_out_time_final = date("H:i", $temp_out_time_unix);

				echo "<br><br>";
				echo $temp_in_time_final;
				echo "<br>";
				echo $temp_out_time_final;

				$query .= "($shop_id, $employee_id, '$temp_attendance_date', '$temp_in_time_final', '$temp_out_time_final'), ";
			}
			$query = rtrim($query, ", ");
			//echo $query;

			if(mysql_query($query))
			{
				echo "+1|";
			}
			else
			{
				echo "-1|";
			}
			break;
		}
	}
?>
