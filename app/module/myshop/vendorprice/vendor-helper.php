<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/app/module/myshop/vendorprice/item.class.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/module/myshop/vendorprice/vendor-item-process.class.php';


if(isset($_POST['action'])){
	$action  = $_POST['action'];
	if('save_vendor_items' == $action){
		parse_str($_POST['data'], $data);
		$filtered_item_data =  array();
		foreach ($data as $key => $value) {
			$price = floatval($value);
			if($price > 0){
				$filtered_item_data[$key] =  $price;
			}
		}
		if(is_array($filtered_item_data) && !empty($filtered_item_data)){
			process_to_save_vendor_items($_POST['vendor_id'],$filtered_item_data);
		}
	}elseif('set_vendor_id_to_load_items' == $action){
		
		$itemObj = new Item();
		$itemlist = $itemObj->getItemsListByVendorId($_POST['vendor_id']);
		$response = array('code'=>200, 'text'=>"OK",'vendor_id'=> $_POST['vendor_id'],'data'=>$itemlist);
		echo json_encode($response);
	}
}




function process_to_save_vendor_items($vendor_id, $vendor_items){
	
	$shop_id =  null;
	if(isset($_SESSION['loggedin']['user']['shop_id'])){
		$shop_id =  (int) $_SESSION['loggedin']['user']['shop_id'];
	}
	$vendorItems =  new VendorItems();
	$status = $vendorItems->saveVendorsItems($vendor_id,$shop_id,$vendor_items);
	$response = array();
	if($status == true){
		$response = array('code'=>200, 'text'=>"OK");
	}else{
		$response = array('code'=>404, 'text'=>"ERROR");
	}
	echo json_encode($response);
}
