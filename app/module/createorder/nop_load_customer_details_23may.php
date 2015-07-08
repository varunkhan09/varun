<?php
//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/global_variables.php";
//include "/var/www/varun/global_variables.php";
include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
include $base_path_folder."/app/etc/dbconfig.php";
include $base_path_folder."/app/etc/mageinclusion.php";
mysql_select_db($magento_database);

if($_REQUEST['flag'] == "1")
{
	mysql_select_db($vendorshop_database);
	$email = $_REQUEST['email'];
	$query = "select firstname, lastname, telephone, address, pincode, city, state, country from pos_customers_entity where email='$email' and shop_id='$shop_id'";
	$result = mysql_query($query);
	if($result && mysql_num_rows($result))
	{
		$row = mysql_fetch_assoc($result);
		$temp_firstname = $row['firstname'];
		$temp_lastname = $row['lastname'];
		$temp_telephone = $row['telephone'];
		$temp_address = $row['address'];
		$temp_pincode = $row['pincode'];
		$temp_city = $row['city'];
		$temp_state = $row['state'];
		$temp_country = $row['country'];

		echo $temp_firstname."|".$temp_lastname."|".$temp_telephone."|".$temp_address."|".$temp_city."|".$temp_state."|".$temp_pincode."|".$email;
	}
	else
	{
		echo "-1";
	}
}

else
{
	if($_REQUEST['flag'] == "2")
	{
		mysql_select_db($custom_database);
		$query = "select * from corporate_companies_order_data where name = '".$_REQUEST['companyname']."'";
		$result = mysql_query($query);

		while($row = mysql_fetch_assoc($result))
		{
			echo $row['name'];
			echo "|";
			echo $row['name'];
			echo "|";
			echo $row['phone'];
			echo "|";
			echo $row['name'];
			echo "|";
			echo $row['address1'].", ".$row['address2'];
			echo "|";
			echo $row['city'];
			echo "|";
			echo $row['state'];
			echo "|";
			echo $row['zip'];
			echo "|";
			echo $row['email'];
		}
	}
}
?>