<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/PHPMailer_5.2.4/class.phpmailer.php";

if(isset($_POST['ssd'])){
	$action = mysql_real_escape_string($_POST['ssd']);
	switch ($action) {
		case 'insert_user':
			if(isset($_POST['data'])){
				parse_str($_POST['data'], $data);
				_insert_user($data);
			}
			break;
		case 'login_user':
			if(isset($_POST['data'])){
				parse_str($_POST['data'], $data);
				_login_user($data);
			}
			break;
		case 'user_pwd_forget_recovery':
			if(isset($_POST['data'])){
				parse_str($_POST['data'], $data);
				_user_pwd_forget_recovery($data);
			}
			break;

		case 'reset_user_pwd':
			if(isset($_POST['data'])){
				parse_str($_POST['data'], $data);
				_reset_user_pwd($data);
			}
			break;
		case 'add_user_role':
			if(isset($_POST['data'])){
				parse_str($_POST['data'], $data);
				_add_user_role($data);
			}
			break;

		case 'get_user_role':
			_get_user_role();
			break;

		default:
			# code...
			break;
	}
}

function _get_user_role(){
	$result = array();
	$q = "SELECT * from pos_user_roles ORDER BY role_name ASC";
						
	$res = mysql_query($q);
	if(mysql_num_rows($res)){
		while( $row = mysql_fetch_assoc($res)){
			$result[] = $row;
		}
	}
   echo json_encode($result);

}
function _add_user_role($data){
	$role_name = mysql_real_escape_string($data['role_name']);
	$sql =  "INSERT INTO pos_user_roles (role_name) VALUES ('$role_name')";
	$result = mysql_query($sql);
	if($result){
		$status = array('statusText'=>'Successfully inserted.','statusCode'=> 200);
		echo json_encode($status);
	}else{
		$status = array('statusText'=>'Something wrong.','statusCode'=> 404);
		echo json_encode($status);
	}
}

function _reset_user_pwd($data){
	$reset_attributes = array('password_reset','password_pin');
	$entity_id = $data['entity_id'];
	$pin =  mysql_real_escape_string($data['pin']);
	$pwd = mysql_real_escape_string($data['new_password']);
	$cnfpwd = mysql_real_escape_string($data['new_conf_password']);
	$password = MD5($pwd);
	$cpassword = MD5($cnfpwd);
	$new_randon = "";
	$new_reset_flag = 0;
	$reset_flag = 1;
	
	$update_status = TRUE;
	$pflag_status = TRUE; // pin flag
	$rflag_status = TRUE; // reset flag

	// check user id match with it or not
	$query = "SELECT * FROM pos_user_entity WHERE entity_id = {$entity_id} AND is_active = 1 AND is_adminpanel = 1 LIMIT 1";
	$result = mysql_query($query);
	if(mysql_num_rows($result)){
		foreach ($reset_attributes as $attributes) {
			$query =  "SELECT * FROM pos_attributes WHERE attribute_code = '{$attributes}'";
			$result = mysql_query($query);
			if(mysql_num_rows($result)){
			 	while ($row = mysql_fetch_assoc($result)) {
			 		if($row['module'] == 'user' && $row['backend_type'] == "varchar"){
			 			$attribute_id =  $row['attribute_id'];
			 			$st_query = sprintf("SELECT * FROM pos_user_entity_varchar WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
    					$r = mysql_query( $st_query );
						while ($row = mysql_fetch_assoc($r)) {
							if($row['value'] == $pin){
								$up_query = "UPDATE pos_user_entity_varchar SET value = '$new_randon' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";	
        						$result = mysql_query( $up_query );
        						if(!$result){
        							$update_status =  FALSE;
        						}
							}else{
								$pflag_status =  FALSE;
								// pincode mismatch
							}
						}				
			 		}else if($row['module'] == 'user' && $row['backend_type'] == "int"){
			 			$attribute_id =  $row['attribute_id'];
			 			$st_query = sprintf("SELECT * FROM pos_user_entity_int WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
    					$r = mysql_query( $st_query );
						while ($row = mysql_fetch_assoc($r)) {
							if($row['value'] == $reset_flag){
								$up_query = "UPDATE pos_user_entity_int SET value = '$new_reset_flag' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";	
        						$result = mysql_query( $up_query );
        						if(!$result){
        							$update_status =  FALSE;
        						}
							}else{
								// flag value mismatch
								$rflag_status = FALSE;
							}
						}
			 		}
			 	}
			 }else{
			 	$update_status =  FALSE;
			 }
		}
		if($update_status == TRUE && $rflag_status == TRUE && $pflag_status == TRUE){
			// When EVERY THING OKYE then now update user info.
			$up_query = "UPDATE pos_user_entity SET password = '$password', cpassword = '$cpassword' WHERE entity_id = $entity_id";	
        	$result = mysql_query( $up_query );
        	if($result){
        		echo json_encode(array('statusText'=>'Your password has been reset.', 'statusCode'=>'OK'));
        	}else{
        		echo json_encode(array('statusText'=>'Oops, something went wrong. Please try again ?', 'statusCode'=>'ERROR'));
        	}
		}else{
			echo json_encode(array('statusText'=>'Oops, something went wrong. Please try again ?', 'statusCode'=>'ERROR'));
		}
	}else{
		echo json_encode(array('statusText'=>'Are you stranger...? ', 'statusCode'=>'ERROR'));
	}
}



function _user_pwd_forget_recovery($data){
	$search_txt =  $data['search_txt'];
	$alternate_email = $data['alternate_email'];

	$query = "SELECT * FROM pos_user_entity WHERE ( username LIKE '%$search_txt%' OR email LIKE '%$search_txt%' ) AND is_adminpanel = 1 AND is_active = 1";
	$result =  mysql_query($query);
	$status = true;
	$emails = array();
	$username = "";
	$entity_id = 0;
	$registered_email = "NULL";

	if($result){
		if(mysql_num_rows($result)){
			while ($row = mysql_fetch_assoc($result)) {
				$username = $row['username'];
				$entity_id = $row['entity_id'];
				if(!empty($row['email'])){
					$emails[] = $row['email'];
					$registered_email =  $row['email'];
				}
			}
		}
		if(!empty($alternate_email)){
			$emails[] = $alternate_email;
		}
		if(!empty($emails)){
			$status = send_pwd_recovery_mail($emails, $registered_email,$username,$entity_id );
		}
	}else{
		$status  = false;
	}

	if($status){
		$response =  array(
			'statusText' => 'Your new password reset link emailed to you', 'statusCode'=>'FOUND'
		);
		echo json_encode($response);
	}else{
		$response =  array(
			'statusText' => 'Email or username does not exist.', 'statusCode'=>'NOTFOUND'
		);
		echo json_encode($response);
	}
}

function _login_user( $data ){
	$username = $data['username']; 
	$password = md5($data['password']);
	$query = "SELECT  pv.is_validated, pu.role_id, pu.email, pu.entity_id FROM pos_user_entity as pu, pos_user_validate as pv WHERE pu.entity_id =  pv.user_id  AND pu.is_adminpanel = 1 AND pu.username = '$username' AND pu.password = '$password' LIMIT 1";
	$result = mysql_query($query);
	if($result){
		if(mysql_num_rows($result)){
				while ($row = mysql_fetch_assoc($result)) {
			        if(1){//$row['is_validated'] ){
		        		$_SESSION['loggedin']['user_id'] =  $row['entity_id'];
		        		$_SESSION['loggedin']['email'] =  $row['email'];
		        		$_SESSION['loggedin']['role_id'] = $row['role_id'];
		        		$status = array('statusText'=>'Yor are sucessfully logged in.','statusCode'=> 200);
						echo json_encode($status);
			        }             
			    }

		}else{
			// if login test fail then , set message and redirect to login page.
			$status = array('statusText'=>'Incorrect username/password.','statusCode'=> 404);
			echo json_encode($status);
		}
	}
}

function _insert_user( $data ){
	$sql = "SELECT * FROM pos_user_entity WHERE email = '{$data['email']}'";
	$result = mysql_query($sql);

	if (!$result) {
	   	die('Invalid query: ' . mysql_error());
	}else{
		if(!mysql_num_rows($result)){
			$username = $data['username'];
			$email = $data['email'];
			$password = MD5($data['password']);
			$cpassword = MD5($data['cpassword']);

			$firstname = $data['firstname'];
			$lastname = $data['lastname'];
			
			$date = convertLocalTimezoneToGMT(date('Y-m-d H:i:s'), ini_get('date.timezone'));
			$role_id = 6;//Store Admin// (int)$data['role_id']; 
			$sql = "INSERT INTO pos_user_entity (role_id,firstname,lastname,username,email,password,cpassword,is_adminpanel,created_at,logged_at) VALUES ($role_id,'$firstname','$lastname','$username','$email','$password','$cpassword',1,'$date','$date')";
			$result = mysql_query($sql);

			if($result){
				$sql = "SELECT entity_id FROM pos_user_entity WHERE email = '{$email}' LIMIT 1";
				$result = mysql_query($sql);
				$entity_id = 0;
				if(mysql_num_rows($result)){
					while ($row = mysql_fetch_assoc($result)) {
		              $entity_id = $row['entity_id'] ;             
		            }
				}
				// To insert into pos_user_validate table , will use to validate user by SUPAER ADMIN.

				$sql =  "INSERT INTO pos_user_validate (user_id,created_at) VALUES ($entity_id,'$date')";
				$result = mysql_query($sql);
				$status =  array(
						'user_id' => $entity_id,
						'statusText' => 'Sucessfully Submitted',
						'statusCode' => 200
				);
				echo json_encode($status);
			}else{
				// Error
				$status =  array(
						'statusText' => 'Invalid query: ' . mysql_error(),
						'statusCode' => 417
				);
				echo json_encode($status);
			}

		}else{
			// all ready exist 
				$status =  array(
						'statusText' => 'Conflict Found',
						'statusCode' => 409
				);
				echo json_encode($status);
		}
	}
}



/*
  @param: {$gmttime} current date time
  @param: {$timezoneRequired} local time zone
  return $timestamp GMT time
 */

function convertLocalTimezoneToGMT($gmttime, $local_timezone) {
    date_default_timezone_set($local_timezone);
    $local = date("Y-m-d h:i:s A");
    date_default_timezone_set("GMT");
    $gmt = date("Y-m-d h:i:s A");
    // System time zone
    $system_timezone = date_default_timezone_get();
    date_default_timezone_set($system_timezone);
    $diff = (strtotime($gmt) - strtotime($local));
    $date = new DateTime($gmttime);
    $date->modify("+$diff seconds");
    $timestamp = $date->format("Y-m-d H:i:s");
    return $timestamp;
}




function send_pwd_recovery_mail( $user_emails , $reg_emails ,$username, $user_entity_id){
	$random = random_string();
	$helper_action  = array('password_reset','password_pin');
	foreach ($helper_action as $action) {
		$query =  "SELECT * FROM pos_attributes WHERE attribute_code = '{$action}'";
		$result = mysql_query($query);
		
		 if(mysql_num_rows($result)){
		 	while ($row = mysql_fetch_assoc($result)) {
		 		if($row['module'] == 'user' && $row['backend_type'] == "varchar"){
		 			pos_user_password_reset_entity_varchar($user_entity_id, $row['attribute_id'], $random );
		 		}else if($row['module'] == 'user' && $row['backend_type'] == "int"){
		 			pos_user_password_reset_entity_int($user_entity_id, $row['attribute_id'], 1 );
		 		}
		 	}
		 }
	}

	$encoded_pin =  base64_encode($random);
	$uid =   base64_encode($user_entity_id);
	$server = "http://".$_SERVER['HTTP_HOST']."/pwd-reset.php?pin=$encoded_pin&id=$uid";
	$body = "
		<!DOCTYPE html>
<html>
<head>
</head>
<body>
Hi,
<p>
As per request, your password for My Account is reset successfully and the PIN is <strong>{$random} </strong>.
<br>
Your registered email is <strong> {$reg_emails} </strong> and username is <strong>{$username} </strong>.
</p>
<p>
Please use this PIN to log into My Account - after login you will be prompted to provide new password.
</p>
<p>
Assuring you the best services at all times.
</p>
<p>
<a href='{$server}'> Click Here To Reset Password</a>
</p>
<br
<h5>
Warm Regards,<br>
Flaberry Team
</h5>
</body>

</html>
	";


	try {
		$status = TRUE;
		foreach($user_emails as $email){

			$mail = new PHPMailer(true);
		    $mail->IsSMTP();                                // tell the class to use SMTP
		    $mail->SMTPAuth = TRUE;                         // enable SMTP authentication
		    $mail->Port = 465;                              // set the SMTP server port
		    $mail->Host = "smtp.gmail.com";                 // SMTP server
		    $mail->Username = "amarkantk@flaberry.com";     // SMTP server username
		    $mail->Password = "amarkant";                   // SMTP server password
		    $mail->SMTPSecure = "ssl";
		    $mail->From = "FLOSHOWERS";
		    $mail->FromName = "Team Flaberry";
		    $mail->AddAddress($email);
		    $mail->Subject = 'Your POS account password is reset successfully.';
		    $mail->WordWrap = 2048;                         // set word wrap
		    $mail->Body = $body;
		    $mail->IsHTML(TRUE);                            // send as HTML		
		    if ($mail->Send() == TRUE) {
		    }else{
		    	$status = FALSE;
		    }
		}
		return $status;

	} catch (phpmailerException $e) {
	   // echo $e->errorMessage();
	    return FALSE;
	}
}


function random_string(){
    $character_set_array = array();
    $character_set_array[] = array('count' => 4, 'characters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    $character_set_array[] = array('count' => 4, 'characters' => '0123456789');
    $temp_array = array();
    foreach ($character_set_array as $character_set) {
        for ($i = 0; $i < $character_set['count']; $i++) {
            $temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
        }
    }
    shuffle($temp_array);
    return implode('', $temp_array);
}



function pos_user_password_reset_entity_varchar($entity_id, $attribute_id, $value) {
   // first check any kind of entry of that particular user , if not makes new entry else update with new value
	$st_query = sprintf("SELECT * FROM pos_user_entity_varchar WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
    $r = mysql_query( $st_query );
    if(mysql_num_rows($r)){
    	$up_query = "UPDATE pos_user_entity_varchar SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";	
        $result = mysql_query( $up_query );
    }else{
    	$in_query = "INSERT INTO pos_user_entity_varchar (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
        $result = mysql_query( $in_query );
    }

    if (!$result) {
       die(mysql_error());
    }   
}


function pos_user_password_reset_entity_int($entity_id, $attribute_id, $value ) {
    // first check any kind of entry of that particular user , if not makes new entry else update with new value
	$st_query = sprintf("SELECT * FROM pos_user_entity_int WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
    $r = mysql_query( $st_query );
    if(mysql_num_rows($r)){
    	$up_query = "UPDATE pos_user_entity_int SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";	
        $result = mysql_query( $up_query );
    }else{
    	$in_query = "INSERT INTO pos_user_entity_int (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
        $result = mysql_query( $in_query );
    } 

    if (!$result) {
       die(mysql_error());
    } 
}