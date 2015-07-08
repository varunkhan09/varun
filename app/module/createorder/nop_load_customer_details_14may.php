<?php
//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/global_variables.php";
include "/var/www/varun/global_variables.php";
include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
mysql_select_db($magento_database);

if($_REQUEST['flag'] == "1")
{
	$customer = Mage::getModel("customer/customer");
	$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
	$customer->loadByEmail($_REQUEST['email']);

	$addresses = $customer->getAddresses();
	if(!is_null($addresses))
	{
		$count = 0;
		foreach($addresses as $address)
		{
			$count = 1;
			$data = $address->toArray();
			echo $data['firstname'];
			echo "|";
			echo $data['lastname'];
			echo "|";
			echo $data['telephone'];
			echo "|";
			echo $data['company'];
			echo "|";

			
			//$street = $data['street'];
			/*
			$varun_counter = substr_count($varun, " ");
			$varun_counter_half = $varun_counter/2;
			$varun_address_array = explode(" ", $varun);
			$varun_line1 = '';
			$varun_line2 = '';
			for($x=0; $x<=$varun_counter; $x++)
			{
				if($x<=$varun_counter_half)
				{
					$varun_line1 .= $varun_address_array[$x]." ";
				}
				else
				{
					$varun_line2 .= $varun_address_array[$x]." ";
				}
			}
			echo $varun_line1;
			echo "|";
			*/
			echo $data['street'];
			echo "|";
			echo $data['city'];
			echo "|";
			echo $data['region'];
			echo "|";
			echo $data['postcode'];
			echo "|";
			echo $_REQUEST['email'];
			if($count == 1)
			{
				break;
			}
		}
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