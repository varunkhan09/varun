<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

// checked if user is logged in 
if(!empty($_SESSION['loggedin']['email']) && !empty($_SESSION['loggedin']['user_id'])){
	$user_id = $_SESSION['loggedin']['user_id'];
	$email	= $_SESSION['loggedin']['email'];

	// query to verify is shop exist of loggedin user ????
	$sql =  "SELECT shop_id FROM pos_user_entity WHERE entity_id = $user_id  AND email = '$email' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
	if(is_null($row['shop_id']) || empty($row['shop_id'])){

		// commented @02-06-2015 by Amarkant
		/* 
		$shop_url  = "/user/". $_SESSION['loggedin']['user_id'] . "/shop";		
		$shop_base_url = $base_path."/app/module/myshop/#".$shop_url;
		header("Location: $shop_base_url"); 
		*/

		/*
		 * If shop is not created then , it will redirect to shop config page.
		 */
		$user_id = isset($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;

		$sql = "SELECT * FROM pos_shop_entity WHERE user_id = $user_id";
		$result =  mysql_query($sql);
		$shop_id = 0;
		if(mysql_num_rows($result)){
			while ($row = mysql_fetch_assoc($result)) {
				$shop_id =  $row['entity_id'];
			}
		}
		if($shop_id){
			$_SESSION['loggedin']['user']['shop_id'] = $shop_id;
			header("Location: ".$base_path."/home.php");

		}

		header('Location:'.$base_path."/shop-config.php");

	}elseif((!is_null($row['shop_id'])) || (is_numeric($row['shop_id']))){


		 /* THIS CODE IS ADDED BY VARUN TO SET IS_ADMIN FLAG IN SESSION */
               $QUERY = "SELECT role,shop_id from pos_user_entity where entity_id=$user_id and email='$email' limit 1";
               $RESULT = mysql_query($QUERY);
               $row = mysql_fetch_assoc($RESULT);
               $_SESSION['loggedin']['user']['is_admin'] = $row['role'];

        /* THIS CODE IS ADDED BY VARUN TO SET IS_ADMIN FLAG IN SESSION */



		// user is logged in and user have shop id.
		$_SESSION['loggedin']['user']['shop_id'] = $row['shop_id'];
		header("Location: ".$base_path."/home.php");
	}
}