<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/global_variables.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/app/module/common/header.php";

if(isset($_SESSION['loggedin'])){
	$user_id = $_SESSION['loggedin']['user_id'];
	if(isset($_SESSION['loggedin']['user']['shop_id'])){
		$shop_id = $_SESSION['loggedin']['user']['shop_id'];
        header("Location:".$base_path."/app/module/myshop/#/user/$user_id/shop/$shop_id/edit-dcharge");
	}
}
