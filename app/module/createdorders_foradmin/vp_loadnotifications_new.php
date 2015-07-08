<?php
	include 'vp_dbconfig.php';

	$start = $_REQUEST['start'];
	$limit = $_REQUEST['limit'];
	$user = $_REQUEST['user'];

	$notifications_array = array();

	if($user == "vendor")
	{
		$vendor_name = FetchVendorNameFromId($vendor_id);


		$query = "select * from vendor_notifications where value=$vendor_id or user like '%|$vendor_id' order by created_at DESC, display_once ASC limit $start, $limit";
		//$query = "select * from vendor_notifications where display_once in (0, 2) and value=$vendor_id or user like '%|$vendor_id' order by FIND_IN_SET(display_once, '0, 2') limit $start, $limit";
		//echo $query;
		$result = mysql_query($query);
		//echo mysql_error()."<br><br>";

		$time_db_without_time_unix_previous = "";
		while($row = mysql_fetch_assoc($result))
		{
			//echo "<br><br>I am in while.<br>";
			$id = $row['id'];
			$type = $row['type'];
			$user = $row['user'];
			$value = $row['value'];
			
			//unset($time);
			$time_db = $row['created_at'];
			$time_db_unix = strtotime($time_db);
			$time_db_without_time = date("d M Y", $time_db_unix);
			$time_db_without_time_unix_now = strtotime($time_db_without_time);

			$time = date("g:sA", $time_db_unix);

			$orderid = $row['orderid'];
			$display_once = $row['display_once'];


			//echo "ID : ".$id."<br>TYPE IS : ".$type."<br>USER : ".$user."<br>VALUE : ".$value."<br>ORDERID : ".$orderid."<br>DISPLAY ONCE : ".$display_once."<br>";
			if($type == "0")
			{
				//echo "Landed 0.";
				$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$user</b> sent Order <b>$orderid</b> to <b>$vendor_name</b> for Acknowledgement.<br>$time</a>";
			}
			else
			{
				if($type == "1")
				{
					//echo "Landed 1.";
					$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$user</b> has updated the vendor price for Order <b>$orderid</b>.<br>$time</a>";
				}
				else
				{
					if($type == "2")
					{
						//echo "Landed 2.";
						$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has Acknowledged the Order <b>$orderid</b>.<br>$time</a>";
					}
					else
					{
						if($type == "3")
						{
							//echo "Landed 3.";
							$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has Shipped the Order <b>$orderid</b>.<br>$time</a>";
						}
						else
						{
							if($type == "4")
							{
								//echo "Landed 4.";
								$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has Delivered the Order <b>$orderid</b>.<br>$time</a>";
							}
							else
							{
								if($type == "5")
								{
									//echo "Landed 5.";
									$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has Rejected the Order <b>$orderid</b>.<br>$time</a>";
								}
								else
								{
									if($type == "6")
									{
										//echo "Landed 6.";
										$comment_main = explode("|", $user);
										$comment = $comment_main[0];
										if(is_numeric($comment_main[1]))
										{
											$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has added Comment '<b>$comment</b>' in Order <b>$orderid</b>.<br>$time</a>";
										}
										else
										{
											$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>".$comment_main[1]."</b> has added Comment '<b>$comment</b>' in Order <b>$orderid</b>.<br>$time</a>";
										}
									}
									else
									{
										if($type == "7")
										{
											//echo "Landed 7.";
											$remark_main = explode("|", $user);
											$remark = $remark_main[0];
											if(is_numeric($remark_main[1]))
											{
												$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has added a Remark '<b>$remark</b>' while accepting the Order <b>$orderid</b>.<br>$time</a>";
											}
											else
											{
												$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>".$remark_main[1]."</b> has added a Remark '<b>$remark</b>' while accepting the Order <b>$orderid</b>.<br>$time</a>";
											}
										}
										else
										{
											//echo "Landed Here.";
										}
									}
								}
							}
						}
					}
				}
			}

			if($display_once == "0")
			{
				$query1 = "update vendor_notifications set display_once = 1 where id=$id";
			}
			else
			{
				if($display_once == "2")
				{
					$query1 = "update vendor_notifications set display_once = 3 where id=$id";
				}
			}
			mysql_query($query1);

			$notifications_array["data"][] = $temp_notification;
		}
	}
	else
	{
		if($user == "admin")
		{
			if($_REQUEST['first'] == "yes")
			{
				$query = "select * from vendor_notifications order by created_at DESC, display_once ASC limit $start, $limit";
			}
			else
			{
				if($_REQUEST['first'] == "no")
				{
					$query = "select * from vendor_notifications where display_once in (0, 1) order by created_at DESC, display_once ASC limit $start, $limit";
				}
				else
				{
					if($_REQUEST['first'] == "no2")
					{
						$query = "select * from vendor_notifications order by created_at DESC, display_once ASC limit $start, $limit";
					}
				}
			}
			//echo $query;
			//$query = "select * from vendor_notifications where display_once in (0, 1) order by FIND_IN_SET(display_once, '0, 1') limit $start, $limit";
			//echo $query."<br>";
			$result = mysql_query($query);
			//echo mysql_error();
			
			while($row = mysql_fetch_assoc($result))
			{
				$id = $row['id'];
				$type = $row['type'];
				$user = $row['user'];
				$value = $row['value'];
				$vendor_name = FetchVendorNameFromId($value);

				//unset($time);
				$time_db = $row['created_at'];
				$time_db_unix = strtotime($time_db);
				
				//$time_db_without_time = date("d M Y", $time_db_unix);
				//$time_db_without_time_unix_now = strtotime($time_db_without_time);

				$time = date("g:sA, d M'Y", $time_db_unix);


				$orderid = $row['orderid'];
				$display_once = $row['display_once'];

				if($type == "0")
				{
					//echo "Landed 0.";
					$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$user</b> sent Order <b>$orderid</b> to <b>$vendor_name</b> for Acknowledgement.<br>$time</a>";
				}
				else
				{
					if($type == "1")
					{
						//echo "Landed 1.";
						$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$user</b> has updated the vendor price for Order <b>$orderid</b>.<br>$time</a>";
					}
					else
					{
						if($type == "2")
						{
							//echo "Landed 2.";
							if($value == "0")
							{
								$vendor_name = "Flaberry - Self";
							}
							$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has Acknowledged the Order <b>$orderid</b>.<br>$time</a>";
						}
						else
						{
							if($type == "3")
							{
								//echo "Landed 3.";
								if($value == "0")
								{
									$vendor_name = "Flaberry - Self";
								}
								$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has Shipped the Order <b>$orderid</b>.<br>$time</a>";
							}
							else
							{
								if($type == "4")
								{
									//echo "Landed 4.";
									if($value == "0")
									{
										$vendor_name = "Flaberry - Self";
									}
									$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has Delivered the Order <b>$orderid</b>.<br>$time</a>";
								}
								else
								{
									if($type == "5")
									{
										//echo "Landed 5.";
										$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has Rejected the Order <b>$orderid</b>.<br>$time</a>";
									}
									else
									{
										if($type == "6")
										{
											//echo "Landed 6.";
											$comment_main = explode("|", $user);
											$comment = $comment_main[0];

											$vendor_name = FetchVendorNameFromId($comment_main[1]);

											if(is_numeric($comment_main[1]))
											{
												$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has added Comment '<b>$comment</b>' in Order <b>$orderid</b>.<br>$time</a>";
											}
											else
											{
												$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>".$comment_main[1]."</b> has added Comment '<b>$comment</b>' in Order <b>$orderid</b>.<br>$time</a>";
											}
										}
										else
										{
											if($type == "7")
											{
												//echo "Landed 7.";
												$remark_main = explode("|", $user);
												$remark = $remark_main[0];

												$vendor_name = FetchVendorNameFromId($remark_main[1]);

												if(is_numeric($remark_main[1]))
												{
													$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>$vendor_name</b> has added a Remark '<b>$remark</b>' while accepting the Order <b>$orderid</b>.<br>$time</a>";
												}
												else
												{
													$temp_notification = "<a href='vp_ordermain.php?orderid=$orderid&flag=1&display_flag=0' target='_blank' style='text-decoration:none; color:black;'>- <b>".$remark_main[1]."</b> has added a Remark '<b>$remark</b>' while accepting the Order <b>$orderid</b>.<br>$time</a>";
												}
											}
											else
											{
												//echo "Landed Here.";
											}
										}
									}
								}
							}
						}
					}
				}

				if($display_once == "0")
				{
					$query1 = "update vendor_notifications set display_once = 2 where id=$id";
				}
				else
				{
					if($display_once == "1")
					{
						$query1 = "update vendor_notifications set display_once = 3 where id=$id";
					}
				}
				mysql_query($query1);
				$notifications_array["data"][] = $temp_notification;
			}
		}
	}
	echo json_encode($notifications_array);


	function FetchVendorNameFromId($catched_vendor_id)
	{
		$query = "select vendor_name from vendors where vendor_id = $catched_vendor_id";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$vendor_name = $row['vendor_name'];
		return($vendor_name);
	}
?>