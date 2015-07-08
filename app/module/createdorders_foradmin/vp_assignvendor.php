<?php
	include 'vp_dbconfig.php';
	$orderid = $_REQUEST['orderid'];
	$vendorname = $_REQUEST['vendorname'];

	$query = "select vendor_id from vendors where vendor_name = '$vendorname'";
	//echo $query."<br>";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$vendorid = $row['vendor_id'];
	echo "Fetched Vendor ID $vendorid from vendors based on vendor name $vendorname<br>";



	$query = "select vendor_id from vendor_processing where orderid = $orderid limit 1";
	//echo $query."<br>";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	if(mysql_num_rows($result)!=0)
	{
		echo "Got vendorid from vendor_processing based on orderid $orderid i.e. we have a row for this orderid in vendor_processing.<br>";
		$query = "update vendor_processing set vendor_id = $vendorid, state=0 where orderid=$orderid";
		echo $query."<br>";
		$result1 = mysql_query($query);

		$query = "update panelorderdetails set vendor_id=$vendorid where orderid=$orderid";
		echo $query."<br>";
		$result2 = mysql_query($query);

		if($result1 && $result2)
		{
			echo "Updated vendor_processing for vendor_id against orderid.<br>";
			echo "Updated panelorderdetails for vendor_id against orderid.<br>";
			echo "Total Updation Successful.<br>";
		}
		else
		{
			echo "Update in vendor_processing for vendor_id against orderid' or 'Update in panelorderdetails for vendor_id against orderid' Unsuccessful.<br>";
			echo "Total Updation Unsuccessful.<br>";
		}
		/*
		if(strpos($row[0], $vendorid) !== false)
		{
			echo "This order has already been assigned to same vendor.";
		}
		else
		{
			echo "This order has been assigned to a different vendor ".$row[0];
		}
		*/
	}
	else
	{
		echo "Could not get vendorid from vendor_processing based on orderid $orderid i.e. we do not have a row for this orderid in vendor_processing.<br>";
		$query1 = "update panelorderdetails set vendor_id=$vendorid where orderid=$orderid";
		//echo $query1."<br>";
		$result1 = mysql_query($query1);

		$query2 = "insert into vendor_processing (orderid, vendor_id, productid, quantity, unitprice, amount, shipping_charges, state, dod, delivery_type, vendor_price) values ";

		$query0 = "select * from panelorderdetails where orderid=$orderid";
		echo $query0."<br>";
		$result0 = mysql_query($query0);
		while($row0 = mysql_fetch_assoc($result0))
		{
			echo "We have row in panelorderdetails against this orderid.<br>";
			//This code fetches the shipping charges based on information if the order is Regular Delivery or Midnight Delivery...
			$temp_shipping_type = $row0['shippingtype'];
			echo $temp_shipping_type."<br>";
			if(strpos($temp_shipping_type, "Midnight Delivery") !== false)
			{
				echo "Shipping Type got from panelorderdetails matched Midnight Delivery. I should see this.<br>";
				$temp_delivery_type = "Midnight Delivery";
				$query3 = "select midnight_charges from vendor_shippingcharges where vendor_id=$vendor_id";
			}
			else
			{
				if(strpos($temp_shipping_type, "Regular Delivery") !== false)
				{
					echo "Shipping Type got from panelorderdetails matched Regular Delivery. I should see this.<br>";
					$temp_delivery_type = "Regular Delivery";
					$query3 = "select regular_charges from vendor_shippingcharges where vendor_id=$vendor_id";
				}
				else
				{
					echo "Shipping Type got from panelorderdetails did not matched any possible delivery type. I should not see this.<br>";
				}
			}
			//echo $query."<br>";
			$result3 = mysql_query($query3);
			echo $query3."<br>";
			echo mysql_error();
			while($row3 = mysql_fetch_row($result3))
			{
				$temp_shippingcharges = $row3[0];
			}
			//This code fetches the shipping charges based on information if the order is Regular Delivery or Midnight Delivery...




			$temp_quantity = $row0['productquantity'];
			$temp_unitprice = $row0['productunitprice'];
			$temp_amount = $temp_unitprice*$temp_quantity;
			$temp_vendor_price = $row0['vendor_price'];

			$query2 .= "($orderid, $vendorid, ".$row0['productid'].", ".$temp_quantity.", ".$temp_unitprice.", ".$temp_amount.", ".$temp_shippingcharges.", 0, '".$row0['dod']."', '$temp_delivery_type', $temp_vendor_price), ";
		}
		$query2 = rtrim($query2, ", ");
		echo "<br>Final Query executed is :<br>$query2<br>";
		$result2 = mysql_query($query2);
		echo mysql_error();

		if($result1 && $result2)
		{
			echo "Insertion Successful.";
		}
		else
		{
			echo "Insertion Unsuccessful.";
		}
	}
?>