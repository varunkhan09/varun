<?php
require_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

class TrackedDevice {
	private $id;
	private $name;
	private $shop_id;
	private $device_id;
	private $devices;
	private $is_active;
	
  	public function __construct() {
  		$this->id = 0;
  		$this->name = null;
  		$this->shop_id = 0;
  		$this->device_id = 0;
  		$this->is_active = 0;
  		$this->devices = array();
   	}


   	public function getDeviceList(){
   		$query =  "SELECT * FROM pos_shop_order_track WHERE is_active = 1 ORDER BY id ASC";
		$result = mysql_query($query);
		if(mysql_num_rows($result)){
			while ($row = mysql_fetch_assoc($result)) {
				$device = new stdClass();
				$device->id = $row['id'];
				$device->name = $row['name'];
				$device->shop_id = $row['shop_id'];
				$device->device_id = $row['device_id'];
				$device->is_active = $row['is_active'];
				$this->devices[] = $device;
			}
		}
		return $this->devices;
   	}

   	public function getDeviceListByShop( $shop_id ){
   		$query =  "SELECT * FROM pos_shop_order_track WHERE shop_id = $shop_id AND is_active = 1 ORDER BY id ASC";
  		$result = mysql_query($query);
  		if(mysql_num_rows($result)){
  			while ($row = mysql_fetch_assoc($result)) {
  				$device = new stdClass();
  				$device->id = $row['id'];
  				$device->name = $row['name'];
  				$device->shop_id = $row['shop_id'];
  				$device->device_id = $row['device_id'];
  				$device->is_active = $row['is_active'];
  				$this->devices[] = $device;
  			}
  		}
  		return $this->devices;
   	}

    public function getDeviceInfoOfShop( $id, $shop_id ){
      $query =  "SELECT * FROM pos_shop_order_track WHERE shop_id = $shop_id AND id={$id} AND is_active = 1 ";
      $result = mysql_query($query);
      if(mysql_num_rows($result)){
        $device = array();

        $row = mysql_fetch_array($result);
        $device['id'] =  $row['id'];
        $device['name'] = $row['name'];
        $device['device_id'] = $row['device_id'];
        $device['shop_id'] = $row['shop_id'];
        
        $this->json_response($device);
        

      }else{
        $response =  array('statusText'=>'Invalid input','statusCode'=>'ERROR');
        $this->json_response($response);
      }
    }




   	public function saveShopDevice( $shop_id , $device = array()){
   		$columns='';
   		$values='';
   		$columns = $columns . 'shop_id' . ',';
    	$values = $values . "'" . $shop_id . "',";
   		if(is_array($device) && !empty($device)){
   			$column_name =  array('device_id','name');
   			foreach ($device as $key => $value) {
   				if(in_array($key, $column_name)){
   					$columns = $columns . $key . ',';
            		$values = $values . "'" . mysql_real_escape_string($value) . "',";
   				}
   			}
   			$query = "INSERT INTO pos_shop_order_track (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
   			$result = mysql_query($query) or die(mysql_error());
   			if($result){
   				$status = array('statusText'=> 'Successfully submitted','statusCode'=>'OK');
   				$this->json_response($status);
   			}else{
   				$status = array('statusText'=> 'Oops! Something went wrong','statusCode'=>'SQL_ERROR');
   				$this->json_response($status);
   			}
   		}else{
   			$response =  array('statusText'=>'Invalid input','statusCode'=>'ERROR');
   			$this->json_response($response);
   		}
   	}


   	public function updateShopDevice( $shop_id , $device=array()){
   		if(is_array($device) && !empty($device)){
   			// check device id for shop ; if exist then update
   			$id = '';
   			if(!empty($device['id'])){
   				$id = $device['id'];
   			}
   			
   			$squery = "SELECT * FROM pos_shop_order_track WHERE id = {$id} AND shop_id = {$shop_id}";
   			$result =  mysql_query($squery);

   			if($result){
   				if(mysql_num_rows($result)){
   					$column_name =  array('device_id','name');
   					$count = count($column_name);
   					$update = "UPDATE pos_shop_order_track SET ";

   					foreach ($device as $key => $value) {
				        if (in_array($key, $column_name)) {
				            if($count > 1){
				                $update = $update . $key."='".mysql_real_escape_string($value)."', ";
				            }elseif($count == 1){
				                $update = $update . $key."='".mysql_real_escape_string($value)."'";
				            }
				            $count--;
				        }
				    }
				    // Update Here
				    $update_query = $update. " WHERE id = {$id} AND shop_id = {$shop_id}";
				    $result =  mysql_query($update_query);
				    if($result){
		   				$status = array('statusText'=> 'Successfully submitted','statusCode'=>'OK');
		   				$this->json_response($status);
		   			}else{
		   				$status = array('statusText'=> 'Oops! Something went wrong','statusCode'=>'SQL_ERROR');
		   				$this->json_response($status);
		   			}
   				}
   			}
   		}else{
   			$response =  array('statusText'=>'Invalid input','statusCode'=>'ERROR');
   			$this->json_response($response);
   		}
   	}

   	public function deleteShopDevice($id , $shop_id ){
   		if($id && $shop_id){
     			 $squery = "SELECT * FROM pos_shop_order_track WHERE id = {$id} AND shop_id = {$shop_id}";
  			   $result =  mysql_query($squery);
    			 if(mysql_num_rows($result)){
      				$update = "UPDATE pos_shop_order_track SET is_active = 0 WHERE id = {$id} AND shop_id = {$shop_id}";
      				$result = mysql_query($update);
      				  if($result){
      	   				$status = array('statusText'=> 'Successfully submitted','statusCode'=>'OK');
      	   				$this->json_response($status);
      	   			}else{
      	   				$status = array('statusText'=> 'Oops! Something went wrong','statusCode'=>'SQL_ERROR');
      	   				$this->json_response($status);
      	   			}
    			 }
   		}else{
   			$response =  array('statusText'=>'Invalid input','statusCode'=>'ERROR');
   			$this->json_response($response);
   		}
   	}


   	public function json_response($response){
   		if(is_array($response)){
   			echo json_encode($response);
   		}
   	}
}