<?php
session_start();
session_destroy();
$base_path = "http://".$_SERVER['HTTP_HOST'];


header("Location:".$base_path);
exit;

// if(isset($_POST) && $_POST['action'] === 'logout' && isset($_SESSION['loggedin'])){
// 	include_once $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
// 	include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";
	
// 	if(isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])){
// 		$user_id = (int) $_SESSION['loggedin']['user_id'];
// 		if(is_numeric($user_id)){
// 			// check user exist in database or not.
// 			$sql = "SELECT * FROM pos_user_entity WHERE email='{$_SESSION['loggedin']['email']}' AND entity_id = {$user_id} LIMIT 1";
// 			$result = mysql_query($sql);
// 			if(mysql_num_rows($result) > 0){
// 				unset($_SESSION['loggedin']);
// 				session_destroy();
// 			}	
// 		}
// 	}
// }else{
// 	$referrer = $_SERVER['HTTP_REFERER'];
// 	if ( !preg_match($base_path."/app/admin/user/login.php",$referrer)) {
// 		header("Location: $base_path");
// 	} 
// }




