<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php");
if(isset($_SESSION['loggedin']['user']['shop_id'])){
	$shop_id =  (int)$_SESSION['loggedin']['user']['shop_id'];
	if(isset($_FILES['uploadedimage']['name'])){
		$type =  $_FILES["uploadedimage"]["type"];
		if($type== 'image/jpeg' || $type == 'image/bmp' || $type == 'image/gif' || $type == 'image/png'){
			$logo_name =  "/media/shoplogo/".$shop_id.'_'.$_FILES["uploadedimage"]["name"];
			$target_path = $_SERVER['DOCUMENT_ROOT'].$logo_name;
			if(move_uploaded_file($_FILES["uploadedimage"]["tmp_name"], $target_path)){
				$query = "UPDATE pos_shop_entity SET shop_logo = '{$logo_name}' WHERE entity_id = {$shop_id}";
				mysql_query($query);
				echo $query;
			}
		}
	}
}