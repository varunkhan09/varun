<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

if(isset($_GET)){
	$term = trim(strip_tags($_GET['term']));
	$searchKey =  $_GET['searchkey'];
	switch ($searchKey) {
		case 'name':
			$searchData = searchByName($term);
			echo json_encode($searchData);
			break;
		case 'email':
			$searchData = searchByEmail($term);
			echo json_encode($searchData);
			break;
		case 'role':
			$searchData = searchByUserRoleName($term);
			echo json_encode($searchData);
			break;
		case 'shop':
			$searchData = searchByShopName($term);
			echo json_encode($searchData);
			break;
		default:
			break;
	}
}

function searchByName($term){
	$query = "SELECT u.entity_id,u.firstname,u.lastname,u.email, s.shop_name , r.role_id, r.role_name from pos_user_entity as u, pos_shop_entity as s, pos_user_roles r where s.entity_id = u.shop_id and r.role_id = u.role_id AND ( u.lastname LIKE '%{$term}%' OR u.firstname LIKE '%{$term}%' )";
	$result = mysql_query($query);
	$data= array();
	if(mysql_num_rows($result))	{
		while($row = mysql_fetch_assoc($result)){
			$data[] = $row;
		}
	}
	return $data;
}


function searchByEmail($term){
	$query = "SELECT u.entity_id,u.firstname,u.lastname,u.email, s.shop_name , r.role_id, r.role_name from pos_user_entity as u, pos_shop_entity as s, pos_user_roles r where s.entity_id = u.shop_id and r.role_id = u.role_id AND u.email LIKE '%".$term."%'";
	$result = mysql_query($query);
	$data= array();
	if(mysql_num_rows($result))	{
		while($row = mysql_fetch_assoc($result)){
			$data[] = $row;
		}
	}
	return $data;
}

function searchByShopName($term){
	$query = "SELECT u.entity_id,u.firstname,u.lastname,u.email, s.shop_name , r.role_id, r.role_name from pos_user_entity as u, pos_shop_entity as s, pos_user_roles r where s.entity_id = u.shop_id and r.role_id = u.role_id AND s.shop_name LIKE '%".$term."%'";
	$result = mysql_query($query);
	$data= array();
	if(mysql_num_rows($result))	{
		while($row = mysql_fetch_assoc($result)){
			$data[] = $row;
		}
	}
	return $data;
}

function searchByUserRoleName($term){
	$query = "SELECT u.entity_id,u.firstname,u.lastname,u.email, s.shop_name , r.role_id, r.role_name from pos_user_entity as u, pos_shop_entity as s, pos_user_roles r where s.entity_id = u.shop_id and r.role_id = u.role_id AND r.role_name LIKE '%".$term."%'";
	$result = mysql_query($query);
	$data= array();
	if(mysql_num_rows($result))	{
		while($row = mysql_fetch_assoc($result)){
			$data[] = $row;
		}
	}
	return $data;
}