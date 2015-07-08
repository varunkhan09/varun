<?php
	include 'vp_dbconfig.php';

	$orderid = $_REQUEST['orderid'];
	$productsdata = $_REQUEST['productdetails'];

	$varun = array();
	$varun = json_decode($productsdata, true);
	$product_id_array = array_keys($varun);

	//var_dump($varun);

	foreach($product_id_array as $each_product)
	{
		$query = "update vendor_processing set vendor_price=".$varun[$each_product]." where orderid=$orderid and productid=".$each_product;
		//echo $query."<br>";
		$result1 = mysql_query($query);
		//echo mysql_error();

		//echo "<br><br>";
		//echo $query."<br>";
		$query = "update panelorderdetails set vendor_price=".$varun[$each_product]." where orderid=$orderid and productid=".$each_product;
		$result2 = mysql_query($query);
		//echo mysql_error();
	}

	if($result1 && $result2)
	{
		echo "+1";
	}
	else
	{
		echo "-1";
	}
?>