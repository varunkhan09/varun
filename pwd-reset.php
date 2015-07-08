<?php 
session_start();      
include_once $_SERVER['DOCUMENT_ROOT']."/global_variables.php";

$decoded_pin =  base64_decode($_GET['pin']);
$user_id =  base64_decode($_GET['id']);
$length =  strlen($decoded_pin);
if($length === 8){
	include_once($_SERVER["DOCUMENT_ROOT"]."/app/admin/user/new-password.php");
}else{
	echo "You are stranger, and trying to cheat me.";
}



