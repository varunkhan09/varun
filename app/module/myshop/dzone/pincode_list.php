<?php
session_start();

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

parse_str($_SERVER["QUERY_STRING"], $query);

if(array_key_exists('get_listed_pincode', $query)){
    extract_pincode_list(mysql_real_escape_string($query['shop_id'])); 
}

function extract_pincode_list($entity_id){
	$key="pincode";
	$no_record = array('status' => 'Record not found','code' => 404);

	$pincode_array = array();
	
	$query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
 	$result = mysql_query($query);
 	$row = mysql_fetch_assoc($result);
 	$attribute_id =  mysql_real_escape_string($row['attribute_id']);
 	
 	if($row['backend_type'] === "int"){
 		$query = sprintf("SELECT * FROM pos_shop_entity_int WHERE entity_id ='%d' AND attribute_id='%d' ",$entity_id,$attribute_id);
     	$r = mysql_query( $query );
     	if(mysql_num_rows($r)){
     		$count =0;
     		while ($row = mysql_fetch_assoc($r)) {
     			if(!empty($row['value'])){
     				$pincode_array[] = $row['value']; 
     				$count = $count + 1;
     			}   			
     		}
			$imploded = implode(', ', $pincode_array);
			$record_found = array('status' => $count.' Record found','pincode_list'=> $imploded,'code' => 200);
			echo json_encode($record_found);
     	}else{
     		echo json_encode($no_record);
     	}
 	}else{
 		echo json_encode($no_record);
 	}
}