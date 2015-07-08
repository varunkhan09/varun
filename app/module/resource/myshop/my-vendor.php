<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/global_variables.php";

if(isset($_SESSION['loggedin'])){
	$user_id = $_SESSION['loggedin']['user_id'];
	if(isset($_SESSION['loggedin']['user']['shop_id'])){
		include_once $_SERVER['DOCUMENT_ROOT']."/app/module/myshop/vendorprice/v-price.php";
	}
}
