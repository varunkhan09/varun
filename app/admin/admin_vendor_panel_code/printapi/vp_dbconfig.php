<?php
	date_default_timezone_set("Asia/Kolkata");
	
	
	include_once '../../../../floshowers/app/Mage.php';
	Mage::app();
	mysql_connect('localhost', 'developer', 'flaberry1122') or die(mysql_error());
	mysql_select_db('varun') or die(mysql_error());
	
	/*
	include_once '../../../../../../../app/Mage.php';
	Mage::app();
	mysql_connect('localhost', 'root', 'password') or die(mysql_error());
	mysql_select_db('operations') or die(mysql_error());
	*/
	//$vendor_id = $_SESSION['vendor_id'];
	//$vendor_id = 65;
	//$vendor_name = "Varun Kumar";
?>
