<?php
session_start();

include_once($_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php");

if($_SERVER["QUERY_STRING"] === "save_user_shop"){
    $postdata = @file_get_contents("php://input");
    $shop_data = json_decode($postdata);

    // user id.
    $user_id =  $shop_data->user_id;

    $coulmns_name = array('shop_name', 'website_url', 'address', 'city', 'pincode', 'email', 'phone_number');
    $shop_data_attributes = array();

    $columns = '';
    $values = '';

    // Query builder & attributes filter here.
    foreach ($shop_data as $key => $value) {
        if (in_array($key, $coulmns_name)) {
            $columns = $columns . $key . ',';
            $values = $values . "'" . mysql_real_escape_string($value) . "',";
        } else {
            // To shop attributes fields
            $shop_data_attributes[$key] = $value;
        }
    }

    $columns = $columns . 'user_id' . ',';
    $values = $values . "'" . $user_id . "',";

    // To convert Local time to GMT.
    $date = convertLocalTimezoneToGMT(date('Y-m-d H:i:s'), ini_get('date.timezone'));
    $columns = $columns . 'created_at' . ',';
    $values = $values . "'" . $date . "',";

    // insert shop information 

    $query = "INSERT INTO pos_shop_entity (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
    $result = mysql_query($query);


///  session update shop 
    if ($result) {

        $q = sprintf("SELECT entity_id FROM pos_shop_entity WHERE user_id = '%d' AND is_active= 1 LIMIT 1", mysql_real_escape_string($shop_data->user_id));
        $result = mysql_query($q);

        if (!$result) {
            die(mysql_error());
        } else {
            $entity_id = 0;
            if (mysql_num_rows($result) > 0) {

                while ($row = mysql_fetch_assoc($result)) {
                    $entity_id = $row['entity_id'];
                }

                // UPDATE HERE USER INFORMATION TABLE WITH SHOP ID.
                 $up_query = "UPDATE pos_user_entity SET shop_id = '$entity_id' WHERE entity_id = $user_id";
                 $result = mysql_query($up_query);

                 // update user session for shop id, when user created shop. 
                 if($result){
                    if(isset($_SESSION['loggedin']['user_id'])){
                        $_SESSION['loggedin']['user']['shop_id'] =  $entity_id;
                    }else{
                        $_SESSION['loggedin']['user_id'] =  $shop_data->user_id;
                        $_SESSION['loggedin']['user']['shop_id'] =  $entity_id;
                    }
                 }

                // for attributes fields.
                foreach ($shop_data_attributes as $key => $value) {
                    $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
                    $result = mysql_query($query);
                    $action = "create";

                    while ($row = mysql_fetch_assoc($result)) {
                        switch ($row['backend_type']) {
                            case "varchar":
                                pos_shop_entity_varchar( $entity_id, $row['attribute_id'], $value ,$action);
                                break;
                            case "text":
                                pos_shop_entity_text( $entity_id, $row['attribute_id'], $value ,$action);
                                break;
                            case "decimal":
                                pos_shop_entity_decimal($entity_id, $row['attribute_id'], $value, $action);
                                break;
                            case "int":
                                pos_shop_entity_int($entity_id, $row['attribute_id'], $value , $action);
                                break;
                            case "datetime":
                                pos_shop_entity_datetime($entity_id, $row['attribute_id'], $value , $action);
                                break;
                        }
                    }// while end here
                }// foreach end hete
            }
        }
    }






}elseif ($_SERVER["QUERY_STRING"] === "update_shop_info") {
    $postdata = @file_get_contents("php://input");
    $shop_data = json_decode($postdata);

    $coulmns_name = array('shop_name', 'website_url', 'address', 'city', 'pincode', 'email', 'phone_number','is_active');
    $shop_data_attributes = array();
    $shop_id = $shop_data->entity_id;

    // Query builder & attributes filter here.
    $update_string = "UPDATE pos_shop_entity SET ";
   

    $count = count($coulmns_name);
    foreach ($shop_data as $key => $value) {
        if (in_array($key, $coulmns_name)) {
            if($count > 1){
                $update_string = $update_string . $key."='".mysql_real_escape_string($value)."', ";
            }elseif($count == 1){
                $update_string = $update_string . $key."='".mysql_real_escape_string($value)."'";
            }
            $count--;
        } else {
            // To shop attributes fields
            $shop_data_attributes[$key] = $value;
        }
    }

    $update_query = $update_string. " WHERE entity_id = {$shop_id}";
    $result = mysql_query($update_query);
    if ($result) {
         // removed unused data
        $unused =  array('created_at','updated_at','entity_id');
        foreach ($unused as $key => $value) {
            if(array_key_exists($value, $shop_data_attributes)){
              unset($shop_data_attributes[$value]);
            }
        }


        foreach ($shop_data_attributes as $key => $value) {
            $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
            $result = mysql_query($query);

            $action = "update";
            $entity_id = $shop_id;

            while ($row = mysql_fetch_assoc($result)) {
                switch ($row['backend_type']) {
                    case "varchar":
                        pos_shop_entity_varchar($entity_id, $row['attribute_id'], $value,$action);
                        break;
                    case "text":
                        pos_shop_entity_text($entity_id, $row['attribute_id'], $value,$action);
                        break;
                    case "decimal":
                        pos_shop_entity_decimal($entity_id, $row['attribute_id'], $value,$action);
                        break;
                    case "int":
                        pos_shop_entity_int($entity_id, $row['attribute_id'], $value,$action);
                        break;
                    case "datetime":
                        pos_shop_entity_datetime($entity_id, $row['attribute_id'], $value,$action);
                        break;
                }
            }// while end here

        }
    }



}elseif ($_SERVER["QUERY_STRING"] === "save_shop") {
    $postdata = @file_get_contents("php://input");
    $shop_data = json_decode($postdata);

    $coulmns_name = array('shop_name', 'website_url', 'address', 'city', 'pincode', 'email', 'phone_number');
    $shop_data_attributes = array();

    $columns = '';
    $values = '';

    // Query builder & attributes filter here.
    foreach ($shop_data as $key => $value) {
        if (in_array($key, $coulmns_name)) {
            $columns = $columns . $key . ',';
            $values = $values . "'" . mysql_real_escape_string($value) . "',";
        } else {
            // To shop attributes fields
            $shop_data_attributes[$key] = $value;
        }
    }

    // To convert Local time to GMT.
    $date = convertLocalTimezoneToGMT(date('Y-m-d H:i:s'), ini_get('date.timezone'));
    $columns = $columns . 'created_at' . ',';
    $values = $values . "'" . $date . "',";


    $query = "INSERT INTO pos_shop_entity (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
    $result = mysql_query($query);

    if ($result) {
        $q = sprintf("SELECT entity_id FROM pos_shop_entity WHERE email='%s' AND is_active= 1 LIMIT 1", mysql_real_escape_string($shop_data->email));
        $result = mysql_query($q);

        if (!$result) {
            die(mysql_error());
        } else {
            $entity_id = 0;
            // Insert and get inserted shop record id.
            $num = mysql_num_rows($result);
            if ($num) {
                while ($row = mysql_fetch_assoc($result)) {
                    $entity_id = $row['entity_id'];
                }

                // for attributes fields.
                foreach ($shop_data_attributes as $key => $value) {
                    $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
                    $result = mysql_query($query);
                    $action = "create";

                    while ($row = mysql_fetch_assoc($result)) {
                        switch ($row['backend_type']) {
                            case "varchar":
                                pos_shop_entity_varchar( $entity_id, $row['attribute_id'], $value ,$action);
                                break;
                            case "text":
                                pos_shop_entity_text( $entity_id, $row['attribute_id'], $value ,$action);
                                break;
                            case "decimal":
                                pos_shop_entity_decimal($entity_id, $row['attribute_id'], $value, $action);
                                break;
                            case "int":
                                pos_shop_entity_int($entity_id, $row['attribute_id'], $value , $action);
                                break;
                            case "datetime":
                                pos_shop_entity_datetime($entity_id, $row['attribute_id'], $value , $action);
                                break;
                        }
                    }// while end here
                }// foreach end hete
            }
        }
    }
}else{
   
    // To edit or Update shop information.
    parse_str($_SERVER["QUERY_STRING"], $query);
    if(array_key_exists('get_shop_info', $query)){
     // print_r($query['shop_id']);
        extract_shop_information(mysql_real_escape_string($query['shop_id'])); 
    }
}





function extract_shop_information($shop_id){
    $q = sprintf("SELECT * FROM pos_shop_entity WHERE entity_id='%d' AND is_active= 1 LIMIT 1",$shop_id);
    $result = mysql_query($q);
    $num = mysql_num_rows($result);
    if($num){
        $shop_row = mysql_fetch_assoc($result);

        $q_varchar = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_shop_entity_varchar as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$shop_id);
        $result = mysql_query($q_varchar);

        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $shop_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }

        $q_int = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_shop_entity_int as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$shop_id);
        $result = mysql_query($q_int);
        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $shop_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }
        
        $q_text = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_shop_entity_text as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$shop_id);
        $result = mysql_query($q_text);
        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $shop_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }

        $q_decimal = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_shop_entity_decimal as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$shop_id);
        $result = mysql_query($q_decimal);
        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $shop_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }

        $q_datetime = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_shop_entity_datetime as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$shop_id);
        $result = mysql_query($q_datetime);
        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $shop_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }

//print_r($shop_row);

//exit();
         echo json_encode( $shop_row );
    }
}





function pos_shop_entity_varchar($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_shop_entity_varchar (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_shop_entity_varchar SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_shop_entity_varchar WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
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

function pos_shop_entity_int($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_shop_entity_int (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_shop_entity_int SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        $result = mysql_query($in_query);
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
    if (!$result) {
       die(mysql_error());
    }
}


function pos_shop_entity_text($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_shop_entity_text (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_shop_entity_text SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_shop_entity_text WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
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


function pos_shop_entity_datetime($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_shop_entity_datetime (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_shop_entity_datetime SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_shop_entity_datetime WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
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


/*
  @param: {$gmttime} current date time
  @param: {$timezoneRequired} local time zone
  return $timestamp GMT time
 */

function convertLocalTimezoneToGMT($gmttime, $local_timezone) {
    if(empty($local_timezone)){
        date_default_timezone_set("Asia/Kolkata");
    }else{
        date_default_timezone_set($local_timezone);
    }
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
