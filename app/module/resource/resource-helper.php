<?php 
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/app/module/resource/module-resource.class.php';

if(isset($_POST['action'])){
	$action = $_POST['action'];
	switch ($action) {
		case 'get_all_module_list':
			get_all_module_list();
			break;
		case 'get_child_module':
			get_child_module($_POST['module_id']);
			break;
		case 'get_resource_by_id':
			get_resource_by_id($_POST['module_id']);
			break;
		case 'get_sub_child_module':
			get_sub_child_module($_POST['module_parent'],$_POST['module_main_parent']);
			break;
		case 'save_resource_in_pos_modules':
			parse_str($_POST['data'], $data);
			save_resource_in_pos_modules($data);
			break;
		default:
			break;
	}
}

function buildResource($data){

	$module_parent =  explode('_', $data['resource_parent']);
	$module_main_parent =  explode('_', $data['resource_main_parent']);
	$temp =  strtolower($data['resource_name']);
	$arr = explode(' ', $temp);
	$module_backend_code = implode('_', $arr);
	$module_parent_cnt = count($module_parent);
	$module_main_parent_cnt = count($module_main_parent);
	$module_url =  !empty($data['resource_url']) ? trim($data['resource_url']) : NULL;
	if(!empty($data['resource_child'])){
		$module_ch_parent =  explode('_', $data['resource_child']);
		$cnt =  count($module_ch_parent);
		$resource = array(
			'module_name' => $data['resource_name'],
			'module_backend_code' => $module_backend_code,
			'module_parent'=> $module_ch_parent[$cnt-1],
			'module_main_parent' => $module_main_parent[$module_main_parent_cnt-1],
			'module_url'=> $module_url
		);

	}else{
		$resource = array(
			'module_name' => $data['resource_name'],
			'module_backend_code' => $module_backend_code,
			'module_parent'=> $module_parent[$module_parent_cnt-1],
			'module_main_parent' => $module_main_parent[$module_main_parent_cnt-1],
			'module_url'=> $module_url
		);
	}
	return $resource;
}

function get_sub_child_module($pid,$mpid){
	$modules =  new ModuleResource();
	$result = $modules->getSubChildModuleById($pid,$mpid);
	echo json_encode($result);
}

function get_resource_by_id($module_id){
	$modules =  new ModuleResource();
	$result = $modules->getResourceById($module_id);
	echo json_encode($result);
}

function get_all_module_list(){
	$modules =  new ModuleResource();
	$result = $modules->getAllModules();
	echo json_encode($result);
}

function get_child_module($module_id){
	$modules =  new ModuleResource();
	$result = $modules->getChildModuleById($module_id);
	echo json_encode($result);
}

function save_resource_in_pos_modules($data){

	$response = array();
	if(!empty($data['resource_edit_id'])){
		//UPDATE  HERE 
		if($data['resource_main_parent']== 'self'){
			$data['resource_parent'] =  'self';
		}

		$edited_module_id =  (int)$data['resource_edit_id'];
		$resource = buildResource($data);
		$modules =  new ModuleResource();
		$status = $modules->updateModuleResource($resource, $edited_module_id);

		if($status == -1){
			$response = array('statusCode'=> "ERROR", 'statusText'=>"Conflict ! resource all ready exists.");
		}else if($status ==  TRUE){
			$response = array('statusCode'=> "OK", 'statusText'=>"Sucessfully Submitted");
		}else{
			$response = array('statusCode'=> "ERROR", 'statusText'=>"Oops ! Something wrong");
		}

	}else{
		// New Resource entry

		if(empty($data['resource_parent']) && empty($data['resource_main_parent'])) {
			// it means want to entry as root resource
			$data['resource_parent'] =  "self";
			$data['resource_main_parent'] = "self";
		}
		
		

		$resource = buildResource($data);
		//print_r($data);
		//print_r($resource);
		//exit();
		$modules =  new ModuleResource();
		$status = $modules->saveModuleResource($resource);

		if($status == -1){
			$response = array('statusCode'=> "ERROR", 'statusText'=>"Conflict ! resource all ready exists.");
		}else if($status ==  TRUE){
			$response = array('statusCode'=> "OK", 'statusText'=>"Sucessfully Submitted");
		}else{
			$response = array('statusCode'=> "ERROR", 'statusText'=>"Oops ! Something wrong");
		}
	}
	echo json_encode($response);
}
