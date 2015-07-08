<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";
if($_SERVER["QUERY_STRING"] === "update_delivery_charge"){

$postdata = @file_get_contents("php://input");
$data = json_decode($postdata);
$entity_id =  $data->shop_id;

$update_data = array();

$update_data['fixedtime'] =  array('id' => $data->fixedtime_id , 'attribute_code'=> 'fixedtime_delivery_charge' ,'value'=> $data->fixedtime_delivery_charge);
$update_data['regular'] =  array('id' => $data->regular_id , 'attribute_code'=> 'regular_delivery_charge' , 'value'=> $data->regular_delivery_charge);
$update_data['midnight'] =  array('id' => $data->midnight_id ,'attribute_code'=> 'midnight_delivery_charge' , 'value'=> $data->midnight_delivery_charge);

foreach ($update_data as $key => $updata) {
    $id = $updata['id'];
    $value =  $updata['value'];
    if(isset($id)){
        $up_query = "UPDATE pos_shop_entity_decimal SET value = '{$value}' WHERE entity_id = $entity_id AND id = $id";
        mysql_query($up_query);
    }else{
         // insert here
        $attribute_code = $updata['attribute_code'];
        $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", $attribute_code);
        $result = mysql_query($query);
        $action = "create";
        while ($row = mysql_fetch_assoc($result)) {
            if($row['backend_type'] == "decimal"){
               pos_shop_entity_decimal($entity_id, $row['attribute_id'], $value, $action);
            }
        }
    }
}


}else if($_SERVER["QUERY_STRING"] === "save_delivery_charge"){
		$postdata = @file_get_contents("php://input");
		$data = json_decode($postdata);
		// SHOP ID 

		$entity_id =  $data->shop_id;
        $flag = true;
		foreach ($data as $key => $value) {
			if($key !== "shop_id"){
				$query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
             	$result = mysql_query($query);
             	$action = "create";
            	while ($row = mysql_fetch_assoc($result)) {
            		if($row['backend_type'] === "decimal"){
                        /* 
                        verify user id is belongs to entity (shop) id.
                        if yes 
                            then check role
                                if role is [ Admin,Store Admin, Store Manager ], autorized role to add
                                    then allow to add delivery charge 
                                else
                                    prevent form insert / delete / update operation.

                         [ Note: store user role to the session also, currently user id, email, and shop id]
                        */

                         if((int) $_SESSION['loggedin']['user']['shop_id'] === (int) $entity_id){
                            // flag to sucessful entry check
                            pos_shop_entity_decimal($entity_id, $row['attribute_id'], $value, $action);
                         }else{
                            $flag = false;
                         }
	             		    
	           		}	
            	}
			}
		}

        if($flag){
            $response =  array("statusText"=> "Successfully Submitted.", "statusCode" => 200);
            echo json_encode($response);
        }else{
            $response =  array("statusText"=> "Something wrong , Please try again.", "statusCode" => 404);
            echo json_encode($response);
        }
}else{

    parse_str($_SERVER["QUERY_STRING"], $query);
    if(array_key_exists('get_shop_delivery_charge_info', $query)){
        extract_shop_delivery_charge_info(mysql_real_escape_string($query['shop_id'])); 
    }
}


function extract_shop_delivery_charge_info( $shop_id){
    $query =  "SELECT * FROM pos_shop_entity_decimal WHERE entity_id = {$shop_id}";
    $result = mysql_query($query);
    $dcharge_result_data = array();
    if(mysql_num_rows($result)){
        while ($row = mysql_fetch_assoc($result)) {
            $dcharge_result_data[$row['attribute_id']]['id'] =  $row['id'];
            $dcharge_result_data[$row['attribute_id']]['entity_id'] =  $row['entity_id'];
            $dcharge_result_data[$row['attribute_id']]['attribute_id'] =  $row['attribute_id'];
            $dcharge_result_data[$row['attribute_id']]['value'] =  $row['value'];
            $attribute_id = $row['attribute_id'];
            $query =  "SELECT attribute_code FROM pos_attributes WHERE attribute_id = {$attribute_id} LIMIT 1";
            $pos_result = mysql_query($query);
             if(mysql_num_rows($pos_result)){
                  while ($row1 = mysql_fetch_assoc($pos_result)) {
                     $dcharge_result_data[$row['attribute_id']]['attribute_code'] =  $row1['attribute_code'];
                  }
             }
        }
    }
    echo json_encode($dcharge_result_data);
}


function pos_shop_entity_decimal($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_shop_entity_decimal (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_shop_entity_decimal SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_shop_entity_decimal WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
        $r = mysql_query( $st_query );
        if(mysql_num_rows($r)){
            $result = mysql_query( $up_query );
        }else{
            $result = mysql_query( $in_query );
        }  
    }
    
    if (!$result) {
       die(mysql_error());
    }
}

