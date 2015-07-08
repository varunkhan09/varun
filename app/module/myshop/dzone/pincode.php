<?php
session_start();
    
// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);


//define('ROOT_PATH', dirname(__FILE__));
//include_once ROOT_PATH . '/' . "../sql.config.php";


include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";


$postdata = @file_get_contents("php://input");
$request = json_decode($postdata);

if(is_array($request->pincode) && !empty($request->pincode)){
	// array of pincode

	$pincodes = $request->pincode;
	$entity_id = $request->shop_id;
	$user_id = $request->vendor_id;
  
	//print_r($pincode);
	$attribute_code = 'pincode';
	$query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", $attribute_code);
	$result = mysql_query($query);
	$action = "create";
	while ($row = mysql_fetch_assoc($result)) {
        if($row['backend_type'] === "int"){
        	foreach ($pincodes as $pincode) {
        		pos_shop_entity_int($entity_id, $row['attribute_id'], $pincode, $action);
        	}	
	    }	
    } 		           
}


function pos_shop_entity_int($entity_id, $attribute_id, $value ,$action) {

    $in_query = "INSERT INTO pos_shop_entity_int (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_shop_entity_int SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        // check if exist then do not enter 
        $st_query = sprintf("SELECT * FROM pos_shop_entity_int WHERE entity_id ='%d' AND attribute_id='%d' AND value='%s' LIMIT 1",$entity_id,$attribute_id,mysql_real_escape_string($value));
        $r = mysql_query( $st_query );
        $num = mysql_num_rows($r);
        
        if(!$num){
            $result = mysql_query($in_query);
        }  
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_shop_entity_int WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
        $r = mysql_query( $st_query );
        if(mysql_num_rows($r)){
            $result = mysql_query( $up_query );
        }else{
            $result = mysql_query( $in_query );
        }  
    }
}
