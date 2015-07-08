<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/app/module/track-order/track-device.class.php';

if(isset($_POST['action'])){
	$action  = $_POST['action'];
	if('save_tracking_device' == $action){
		parse_str($_POST['data'], $device);
		$id =  !empty($device['id']) ?  (int)$device['id'] : null;
		if($id && !is_null($id)){
			$trackDevice = new TrackedDevice();
			$shop_id = (int)$device['shop_id'];
			echo $trackDevice->updateShopDevice($shop_id , $device);

		}else{
			$trackDevice = new TrackedDevice();
			echo $trackDevice->saveShopDevice($device['shop_id'], $device);
		}
		
	}elseif('get_shop_tracking_device' == $action){
		$shop_id = (int)$_POST['shop_id'];
		$id = (int)$_POST['id'];
		$trackDevice = new TrackedDevice();
		echo $trackDevice->getDeviceInfoOfShop( $id, $shop_id );
	}
	elseif('delete_shop_tracking_device' == $action){
		$shop_id = (int)$_POST['shop_id'];
		$id = (int)$_POST['id'];
		$trackDevice = new TrackedDevice();
		echo $trackDevice->deleteShopDevice( $id, $shop_id );
	}
}

