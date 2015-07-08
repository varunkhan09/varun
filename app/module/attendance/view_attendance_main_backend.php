<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($vendorshop_database);

	$query_inner = "select firstname, lastname, entity_id from pos_user_entity where shop_id=$shop_id";
	$result_inner = mysql_query($query_inner);
	$employee_id_to_name_array = array();
	while($row_inner = mysql_fetch_assoc($result_inner))
	{
		$employee_id_to_name_array[$row_inner['entity_id']] = $row_inner['firstname']." ".$row_inner['lastname'];
	}
	$mode = $_REQUEST['mode'];

	switch($mode)
	{
		case "load_attendance":
		{
			//$start_date = $_REQUEST['start'];
			//$end_date = $_REQUEST['end'];
			$employee = $_REQUEST['employee'];
			$shift_hours = $_REQUEST['shift_hours'];
			
			$month = $_REQUEST['month'];
			$year = date("Y/m/d");
			$year_unix = strtotime($year);
			$year = date("Y", $year_unix);
			$start_date = "$month/01/$year";
			$number_of_days_in_this_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);
			$end_date = "$month/$number_of_days_in_this_month/$year";

			$query_inner = "select attribute_id from pos_attributes where attribute_code = 'salary'";
			$result_inner = mysql_query($query_inner);
			$row_inner = mysql_fetch_assoc($result_inner);
			$salary_attribute_id = $row_inner['attribute_id'];
			$query_inner = "select value from pos_user_entity_decimal where attribute_id=$salary_attribute_id and entity_id=$employee";
			$result_inner = mysql_query($query_inner);
			$row_inner = mysql_fetch_assoc($result_inner);
			$salary_of_this_employee = $row_inner['value'];
			// echo $salary_of_this_employee."<br>";
			// echo $number_of_days_in_this_month."<br>";
			// echo $shift_hours."<br>";

			$salary_of_this_employee_per_day = $salary_of_this_employee/$number_of_days_in_this_month;
			$salary_of_this_employee_per_hour = $salary_of_this_employee/($number_of_days_in_this_month*$shift_hours);
			$salary_of_this_employee_per_half_an_hour = $salary_of_this_employee_per_hour/2;
			//echo $salary_of_this_employee_per_hour;

			$unix_shift_hours = $shift_hours*60*60;
			$unix_30_minutes = 1800;
			$unix_60_minutes = 3600;

			$start_date_unix = strtotime($start_date);
			$end_date_unix = strtotime($end_date);

			$start_date_final = date("Y-m-d", $start_date_unix);
			$end_date_final = date("Y-m-d", $end_date_unix);

			if($employee == "All")
			{
				$query = "select * from pos_attendance_data where shop_id=$shop_id and (attendance_date between '$start_date_final' and '$end_date_final')";
			}
			else
			{
				$query = "select * from pos_attendance_data where shop_id=$shop_id and (attendance_date between '$start_date_final' and '$end_date_final') and employee_id=$employee";
			}
			$query .= " order by attendance_date";
			//echo "<br>".$query;
			$result = mysql_query($query);

			if($result)
			{
				if(mysql_num_rows($result) != 0)
				{
					echo "<table class='table table-bordered stock_report_result_table text-center'>";
					echo "<tr class='stock_report_heading_tr'><td>Name</td><td>Date</td><td>In Time</td><td>Out Time</td><td>Time</td></tr>";
					$total_overtime_undertime_sum = 0;
					$total_time_sum = 0;
					$number_of_days_attended_office = 0;
					while($row = mysql_fetch_assoc($result))
					{
						$temp_number_of_60_minutes = 0;
						$temp_number_of_30_minutes = 0;

						$number_of_days_attended_office++;

						$temp_attendance_date_unix = strtotime($row['attendance_date']);
						$temp_attendance_date_final = date("j F", $temp_attendance_date_unix);

						$temp_in_time_unix = strtotime($row['in_time']);
						//echo "Time In Unix : $temp_in_time_unix<br>";
						$temp_in_time_final = date("g:i A", $temp_in_time_unix);

						$temp_out_time_unix = strtotime($row['out_time']);
						//echo "Time Out Unix : $temp_out_time_unix<br>";
						$temp_out_time_final = date("g:i A", $temp_out_time_unix);

						//$temp_remaining = $temp_total_time_per_day-($temp_number_of_60_minutes*$unix_60_minutes)-($temp_number_of_30_minutes*$unix_30_minutes);

						echo "<tr class='tr_clicked'>";
							//echo "<td>";
							$temp_total_time_per_day = $temp_out_time_unix-$temp_in_time_unix;
							//echo "Total Time I spend today : $temp_total_time_per_day<br>";

							$temp_total_time_per_day_hours = $temp_total_time_per_day/3600;
							$temp_total_time_per_day_hours = floor($temp_total_time_per_day_hours);
							$temp_total_time_per_day_minutes = ($temp_total_time_per_day-($temp_total_time_per_day_hours*3600))/60;


							//echo "Total time per day unix : $temp_total_time_per_day";
							$temp_remaining = $temp_total_time_per_day-$unix_shift_hours;

							if($temp_remaining < 0)
							{
								$temp_undertime_flag = 1;
								$temp_overtime_flag = 0;
								//echo "Undertime";
								$temp_undertime_hours = $temp_remaining/3600;
								$temp_undertime_hours = floor($temp_undertime_hours);
								$temp_undertime_minutes = ($temp_remaining-($temp_undertime_hours*3600))/60;
							}
							else
							{
								$temp_undertime_flag = 0;
								$temp_overtime_flag = 1;
								//echo "Overtime";
							}
							$total_overtime_undertime_sum = $total_overtime_undertime_sum + $temp_remaining;
							//echo "<br>Total time after removing 8 hours : $temp_remaining<br>";
							$temp_number_of_60_minutes = $temp_remaining/$unix_60_minutes;
							//echo $temp_number_of_60_minutes."<br>";
							$temp_number_of_60_minutes = floor($temp_number_of_60_minutes);
							//echo $temp_number_of_60_minutes."<br>";
							//echo "<br>Number of 60 minutes : $temp_number_of_60_minutes";
							$temp_remaining = $temp_remaining-($temp_number_of_60_minutes*$unix_60_minutes);
							//echo "<br>Time remaining after removing extra 60 minutes : $temp_remaining";

							$temp_number_of_60_minutes = intval($temp_number_of_60_minutes);
							
							if(0 <= $temp_remaining && $temp_remaining <= 1200)
							{
								$temp_number_of_30_minutes = 0;
							}
							else
							{
								if(1201 <= $temp_remaining && $temp_remaining <= 2700)
								{
									$temp_number_of_30_minutes = 1;
								}
								else
								{
									if(2701 <= $temp_remaining && $temp_remaining <= 3540)
									{
										$temp_number_of_60_minutes++;
									}
									else
									{
										echo "If I am here...it is an error...";
									}
								}
							}
							//echo "60 minutes : ".$temp_number_of_60_minutes;
							/*
							$temp_number_of_30_minutes = $temp_remaining/$unix_30_minutes;
							$temp_number_of_30_minutes = floor($temp_number_of_30_minutes);
							echo "<br>Number of 30 minutes after removing 60 minutes : $temp_number_of_30_minutes";
							*/
							//echo "</td>";

							echo "<td>".$employee_id_to_name_array[$row['employee_id']]."</td>";
							echo "<td>".$temp_attendance_date_final."</td>";
							echo "<td>".$temp_in_time_final."</td>";
							echo "<td>".$temp_out_time_final."</td>";
							//echo "<td>".$temp_total_time_per_day_hours." hours, ".$temp_total_time_per_day_minutes." minutes</td>";
							echo "<td>";
							if($temp_undertime_flag == 1)
							{
								echo $temp_undertime_hours." hours, ".$temp_undertime_minutes." minutes</td>";
							}
							else
							{
								echo $temp_number_of_60_minutes." hours, ".($temp_number_of_30_minutes*30)." minutes</td>";
							}

/*
							echo "<td>";
							if($temp_overtime_flag == 1)
							{
								echo $temp_number_of_60_minutes." hours, ".($temp_number_of_30_minutes*30)." minutes</td>";
							}
*/
						echo "</tr>";
					}
					echo "<tr class='stock_report_ending_tr'>";
						echo "<td>".$employee_id_to_name_array[$employee]."</td>";
						echo "<td></td>";
						echo "<td></td>";
						echo "<td></td>";
						$total_overtime_undertime_sum_hours = $total_overtime_undertime_sum/3600;
						$total_overtime_undertime_sum_hours = floor($total_overtime_undertime_sum_hours);
						$total_overtime_undertime_sum_minutes = ($total_overtime_undertime_sum-($total_overtime_undertime_sum_hours*3600))/60;
						echo "<td>".$total_overtime_undertime_sum_hours." hours, ".$total_overtime_undertime_sum_minutes." minutes</td>";
					echo "</tr>";
					echo "</table>";

					echo "<br><br>";
					echo "Number of days in this month : ".$number_of_days_in_this_month."<br>";
					echo "Number of days ".$employee_id_to_name_array[$employee]." attended office : ".$number_of_days_attended_office."<br>";
					echo "Number of working hours per day : ".$shift_hours."<br>";
					echo "Salary Per Month : ₹ ".number_format($salary_of_this_employee)."<br>";
					echo "Salary Per Day : ₹ ".number_format($salary_of_this_employee_per_day)."<br>";
					echo "Salary Per Hour : ₹ ".number_format($salary_of_this_employee_per_hour)."<br>";
					$salary_of_this_employee_for_attended_days = $number_of_days_attended_office*$salary_of_this_employee_per_day;
					$final_salary_of_this_employee = $salary_of_this_employee_for_attended_days;
					echo "Salary for attended days : ₹ ".number_format($salary_of_this_employee_for_attended_days)."<br>";
					echo "Extra Time : ".$total_overtime_undertime_sum_hours." hours, ".$total_overtime_undertime_sum_minutes." minutes<br>";
					$number_of_total_overtime_undertime_sum_hours = $total_overtime_undertime_sum_hours;
					//$salary_of_this_employee_for_extra_time = $total_overtime_undertime_sum_hours*$salary_of_this_employee_per_hour;

					if(0 <= $total_overtime_undertime_sum_minutes && $total_overtime_undertime_sum_minutes <= 20)
					{
						$number_of_total_overtime_undertime_sum_minutes = 0;
					}
					else
					{
						if(21 <= $total_overtime_undertime_sum_minutes && $total_overtime_undertime_sum_minutes <= 45)
						{
							$number_of_total_overtime_undertime_sum_minutes = 1;
						}
						else
						{
							if(46 <= $total_overtime_undertime_sum_minutes && $total_overtime_undertime_sum_minutes <= 60)
							{
								$number_of_total_overtime_undertime_sum_hours++;
							}
						}
					}

					if($total_overtime_undertime_sum_hours > 0)
					{
						$salary_of_this_employee_for_extra_time = ($number_of_total_overtime_undertime_sum_hours*$salary_of_this_employee_per_hour)+($number_of_total_overtime_undertime_sum_minutes*$salary_of_this_employee_per_half_an_hour);
					}
					else
					{
						$salary_of_this_employee_for_extra_time = ($number_of_total_overtime_undertime_sum_hours*$salary_of_this_employee_per_hour)-($number_of_total_overtime_undertime_sum_minutes*$salary_of_this_employee_per_half_an_hour);
					}
					echo "Salary for Extra Time : ₹ ".$salary_of_this_employee_for_extra_time."<br>";

					$final_salary_of_this_employee = $salary_of_this_employee_for_attended_days + $salary_of_this_employee_for_extra_time;

					echo "Final Salary : ₹ ".number_format($final_salary_of_this_employee)."<br>";
				}
				else
				{
					echo "-2|";
				}
			}
			else
			{
				echo "-1|";
			}
			break;
		}
	}



	/*
		0 to 20 => 0
		21 to 45 => 30
		46 to 59 => 60
	*/
?>