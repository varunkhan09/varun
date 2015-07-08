<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/global_variables.php";

$_SESSION['x_url'] = $_SERVER['REQUEST_URI'];

if(isset($_SESSION['loggedin'])){
	$user_id = $_SESSION['loggedin']['user_id'];
	if(isset($_SESSION['loggedin']['user']['shop_id'])){
		$shop_id = $_SESSION['loggedin']['user']['shop_id'];
		header("Location:".$base_path."/app/module/odoo-app/");
	}
}
