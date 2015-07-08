<?php
	date_default_timezone_set("Asia/Kolkata");
	
	/*
	include_once '/var/www/floshowers/app/Mage.php';
	Mage::app();
	mysql_connect('localhost', 'developer', 'flaberry1122') or die(mysql_error());
	mysql_select_db('varun') or die(mysql_error());
	*/
	
	//include_once '../../../../../../app/Mage.php';
	include_once '../../../../../../../../app/Mage.php';
	Mage::app();
	mysql_connect('localhost', 'root', 'password') or die(mysql_error());
	mysql_select_db('operations') or die(mysql_error());
	

	session_start();
	$vendor_id = $_SESSION['vendor_id'];
	$vendor_loggedin = $_SESSION['vendor_loggedin'];

	//$vendor_id = 65;
	//$vendor_name = "Varun Kumar";

	//include "/var/www/varun/global_variables.php";
	include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver/global_variables.php";
?>
