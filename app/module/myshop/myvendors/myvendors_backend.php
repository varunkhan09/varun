<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

	$mode = $_POST['mode'];

	switch($mode)
	{
		case 'invite_vendor':
		{
			$name = $_POST['name'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];

			mysql_select_db($vendorshop_database);

			/* THIS CODE FINDS THE STATE FOR THIS INVITE */
			$query = "select entity_id from pos_add_vendor_entity where email='$email' and self_shop_id=$shop_id";
			$result1 = mysql_query($query);

			if(mysql_num_rows($result1))						//This user has been invited before...
			{
				echo "-2|";
				die();
			}
			else 												//This user has not been invited by this vendor before...
			{
				$query = "select entity_id from pos_user_entity where email='$email'";
				$result2 = mysql_query($query) or die(mysql_error()."-1|");

				if(mysql_num_rows($result2))
				{
					$row2 = mysql_fetch_assoc($result2);
					$vendor_user_id = $row2['entity_id'];

					$query = "select entity_id from pos_shop_entity where user_id=$vendor_user_id";
					$result3 = mysql_query($query) or die(mysql_error()."-1|");

					if(mysql_num_rows($result3))
					{
						$state = 2;
						$row3 = mysql_fetch_assoc($result3);
						$temp_vendor_shop_id = $row3['entity_id'];
					}
					else
					{
						$state = 1;
					}
				}
				else
				{
					$state = 0;
				}
			}
			/* THIS CODE FINDS THE STATE FOR THIS INVITE */



			switch($state)
			{
				case 0:
				{
					$query_inner1 = "insert into pos_add_vendor_entity (name, email, phone, self_shop_id, status) values ('$name', '$email', '$phone', $shop_id, 0)";
					$result_inner1 = mysql_query($query_inner1) or die(mysql_error()."-1|");
					
					$query_inner1 = "select entity_id from pos_add_vendor_entity where email='$email' and self_shop_id=$shop_id";
					$result_inner1 = mysql_query($query_inner1) or die(mysql_error()."-1|");
					$row_inner1 = mysql_fetch_assoc($result_inner1);
					$temp_invite_id = $row_inner1['entity_id'];

					$query_inner2 = "insert into pos_associated_vendors_entity (self_shop_id, invite_id) values ($shop_id, $temp_invite_id)";
					mysql_query($query_inner2) or die(mysql_error()."-1|");
					break;
				}

				case 1:
				{
					$query_inner1 = "insert into pos_add_vendor_entity (name, email, phone, self_shop_id, status) values ('$name', '$email', '$phone', $shop_id, 1)";
					$result_inner1 = mysql_query($query_inner1) or die(mysql_error()."-1|");

					$query_inner1 = "select entity_id from pos_add_vendor_entity where email='$email' and self_shop_id=$shop_id";
					$result_inner1 = mysql_query($query_inner1) or die(mysql_error()."-1|");
					$row_inner1 = mysql_fetch_assoc($result_inner1);
					$temp_invite_id = $row_inner1['entity_id'];

					$query_inner2 = "insert into pos_associated_vendors_entity (self_shop_id, invite_id) values ($shop_id, $temp_invite_id)";
					mysql_query($query_inner2) or die(mysql_error()."-1|");
					
					break;
				}

				case 2:
				{
					$query_inner1 = "insert into pos_add_vendor_entity (name, email, phone, self_shop_id, status) values ('$name', '$email', '$phone', $shop_id, 2)";
					$result_inner1 = mysql_query($query_inner1) or die(mysql_error()."-1|");

					$query_inner1 = "select entity_id from pos_add_vendor_entity where email='$email' and self_shop_id=$shop_id";
					$result_inner1 = mysql_query($query_inner1) or die(mysql_error()."-1|");
					$row_inner1 = mysql_fetch_assoc($result_inner1);
					$temp_invite_id = $row_inner1['entity_id'];

					$query_inner2 = "insert into pos_associated_vendors_entity (self_shop_id, vendor_shop_id, invite_id) values ($shop_id, $temp_vendor_shop_id, $temp_invite_id)";
					mysql_query($query_inner2) or die(mysql_error()."-1|");
					break;
				}

				default:
				{
					break;
				}
			}


			break;
		}


		default:
		{
			echo "-2";
			break;
		}
	}
?>