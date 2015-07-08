<?php
	include 'vp_dbconfig.php';

	$query = "select vendor_name from vendors where vendor_id = $vendor_id";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$vendor_name = $row['vendor_name'];
	$admin_name = "Admin";
	$start = $_REQUEST['start'];
	$limit = $_REQUEST['limit'];

	$user = $_REQUEST['user'];

	if($user == "vendor")
	{
		$query = "select * from vendor_notifications where display_once in (0, 2) and value=$vendor_id or user like '%|$vendor_id' order by FIND_IN_SET(display_once, '0, 2') limit $start, $limit";
		//echo $query;
		$result = mysql_query($query);
		//echo mysql_error()."<br><br>";
		
		$notifications_array = array();
		
		while($row = mysql_fetch_assoc($result))
		{
			//echo "<br><br>I am in while.<br>";
			$id = $row['id'];
			$type = $row['type'];
			$user = $row['user'];
			$value = $row['value'];

			$orderid = $row['orderid'];
			$display_once = $row['display_once'];


			//echo "ID : ".$id."<br>TYPE IS : ".$type."<br>USER : ".$user."<br>VALUE : ".$value."<br>ORDERID : ".$orderid."<br>DISPLAY ONCE : ".$display_once."<br>";
			if($type == "0")
			{
				//echo "Landed 0.";
				$temp_notification = "$user sent <b>$orderid</b> to $vendor_name for Acknowledgement.";
			}
			else
			{
				if($type == "1")
				{
					//echo "Landed 1.";
					$temp_notification = "$user has updated the vendor price for Order <b>$orderid</b>.";
				}
				else
				{
					if($type == "2")
					{
						//echo "Landed 2.";
						$temp_notification = "$vendor_name has Acknowledged the Order <b>$orderid</b>.";
					}
					else
					{
						if($type == "3")
						{
							//echo "Landed 3.";
							$temp_notification = "$vendor_name has Shipped the Order <b>$orderid</b>.";
						}
						else
						{
							if($type == "4")
							{
								//echo "Landed 4.";
								$temp_notification = "$vendor_name has Delivered the Order <b>$orderid</b>.";
							}
							else
							{
								if($type == "5")
								{
									//echo "Landed 5.";
									$temp_notification = "$vendor_name has Rejected the Order <b>$orderid</b>.";
								}
								else
								{
									if($type == "6")
									{
										//echo "Landed 6.";
										$comment_main = explode("|", $user);
										$comment = $comment_main[0];
										if(is_numeric($comment[1]))
										{
											$temp_notification = "$vendor_name has added Comment '<b>$comment</b>' in Order <b>$orderid</b>.";
										}
										else
										{
											$temp_notification = "$admin_name has added Comment '<b>$comment</b>' in Order <b>$orderid</b>. ";
										}
									}
									else
									{
										if($type == "7")
										{
											//echo "Landed 7.";
											$remark_main = explode("|", $user);
											$remark = $remark_main[0];
											if(is_numeric($remark[1]))
											{
												$temp_notification = "$vendor_name has added a Remark '<b>$remark</b>' while accepting the Order <b>$orderid</b>.";
											}
											else
											{
												$temp_notification = "$admin_name has added a Remark '<b>$remark</b>' while accepting the Order <b>$orderid</b>.";
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

			$notifications_array[] = $temp_notification;
		}
	}
	else
	{
		$username = $_REQUEST['username'];

		$query = "select * from vendor_notifications where display_once in (0, 1) and order by FIND_IN_SET(display_once, '0, 1') limit $start, $limit";
		$result = mysql_query($query);

		$notifications_array = array();
		
		while($row = mysql_fetch_assoc($result))
		{
			$id = $row['id'];
			$type = $row['type'];
			$user = $row['user'];
			$value = $row['value'];
			$vendor_name = $user_name;



			$orderid = $row['orderid'];
			$display_once = $row['display_once'];

			if($type == "0")
			{
				//echo "Landed 0.";
				$temp_notification = "$user sent <b>$orderid</b> to $vendor_name for Acknowledgement.";
			}
			else
			{
				if($type == "1")
				{
					//echo "Landed 1.";
					$temp_notification = "$user has updated the vendor price for Order <b>$orderid</b>.";
				}
				else
				{
					if($type == "2")
					{
						//echo "Landed 2.";
						$temp_notification = "$vendor_name has Acknowledged the Order <b>$orderid</b>.";
					}
					else
					{
						if($type == "3")
						{
							//echo "Landed 3.";
							$temp_notification = "$vendor_name has Shipped the Order <b>$orderid</b>.";
						}
						else
						{
							if($type == "4")
							{
								//echo "Landed 4.";
								$temp_notification = "$vendor_name has Delivered the Order <b>$orderid</b>.";
							}
							else
							{
								if($type == "5")
								{
									//echo "Landed 5.";
									$temp_notification = "$vendor_name has Rejected the Order <b>$orderid</b>.";
								}
								else
								{
									if($type == "6")
									{
										//echo "Landed 6.";
										$comment_main = explode("|", $user);
										$comment = $comment_main[0];
										if(is_numeric($comment[1]))
										{
											$temp_notification = "$vendor_name has added Comment '<b>$comment</b>' in Order <b>$orderid</b>.";
										}
										else
										{
											$temp_notification = "$admin_name has added Comment '<b>$comment</b>' in Order <b>$orderid</b>. ";
										}
									}
									else
									{
										if($type == "7")
										{
											//echo "Landed 7.";
											$remark_main = explode("|", $user);
											$remark = $remark_main[0];
											if(is_numeric($remark[1]))
											{
												$temp_notification = "$vendor_name has added a Remark '<b>$remark</b>' while accepting the Order <b>$orderid</b>.";
											}
											else
											{
												$temp_notification = "$admin_name has added a Remark '<b>$remark</b>' while accepting the Order <b>$orderid</b>.";
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
		}
	}

	//$notifications_array['limit_to_set'] = $start+$limit+1;

	echo json_encode($notifications_array);
?>