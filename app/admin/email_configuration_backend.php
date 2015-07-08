<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";

	$query = "select attribute_id from pos_attributes where attribute_code='order_forward_email'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$order_forward_email_attribute_id = $row['attribute_id'];

	$mode = $_REQUEST['mode'];

	switch($mode)
	{
		case "save":
		{
			$order_forward_email_id = $_REQUEST['email'];
			$order_forward_email_pass = $_REQUEST['pass'];
			$new_user = $_REQUEST['new_user'];

			if($new_user == "1")
			{
				$query = "insert into pos_email_configuration (email_role, shop_id, email_id, email_pass) values ($order_forward_email_attribute_id, $shop_id, '$order_forward_email_id', '$order_forward_email_pass')";
			}
			else
			{
				if($new_user == "0")
				{
					$query = "update pos_email_configuration set email_id='$order_forward_email_id', email_pass='$order_forward_email_pass' where email_role=$order_forward_email_attribute_id and shop_id=$shop_id";
				}
			}

			echo $query;
			$result = mysql_query($query);

			if($result)
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