<?php 
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/app/admin/panel-access/panel-users.class.php';

if(isset($_POST['action'])){
	$action = $_POST['action'];
	switch ($action) {
		case 'get_shop_users':
			get_shop_users($_POST['shop_id']);
			break;
		case 'save_users_assigned_resource':
			parse_str($_POST['resource_data'], $resource);
			parse_str($_POST['users_data'], $users);
			save_users_assigned_resource($resource,$users,$_POST['shop_id'],$_POST['role_id']);
			break;
		default:
			break;
	}
}


function get_shop_users($shop_id){
	$pusers = new PanelUsers();
	$users = $pusers->getPanelUsers($shop_id);
	echo json_encode($users);
}


function save_users_assigned_resource($resource,$users, $shop_id,$role_id){
	$resource = parseResourceUserData($resource,$users,$shop_id);
	$pusers = new PanelUsers();
	$status = $pusers->saveUserResource($resource,$role_id,$users);
	if($status == TRUE){
		$response = array('statusCode'=> "OK", 'statusText'=>"Sucessfully Submitted");
	}else{
		$response = array('statusCode'=> "ERROR", 'statusText'=>"Oops ! Something wrong");
	}
	echo json_encode($response);
}

function parseResourceUserData($resource,$users,$shop_id){
	$parsedResource =  array();
	if(is_array($users) && !empty($users)){
		foreach ($users as $key => $user) {
			foreach ($resource as $key => $res) {
				//$temp = array('user_id' => $user,'shop_id' => $shop_id,'access_tag'=> $key,'resource_id' => $res);
				$temp = array('resource_id' => $res);
				$parsedResource[] =  $temp;
			}
		}
	}
	return $parsedResource;
}