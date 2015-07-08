<?php
	//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/global_variables.php";
	include "/var/www/varun/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($magento_database);
	$product_id = $_POST['product_id'];
	$product = Mage::getModel('catalog/product')->load($product_id);

	$varun = $product->getVendorDescription();
	$varun1 = $product->getPrice();
	$varun2 = $product->getName();

	if($varun == "")
	{
		$varun = "Description not Available.";
	}

	$varun_final = $varun."|".$varun1."|".$varun2;
	echo $varun_final;
?>