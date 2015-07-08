<?php
session_start();


define('ROOT_PATH', dirname(__FILE__));
define('SALT','flaberry@1122');
define('LOG_NUM',0);

include $_SERVER['DOCUMENT_ROOT']."/global_variables.php"; 
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

if($_SERVER["QUERY_STRING"] === "get_roles"){
    $query = "SELECT * FROM pos_user_roles";
    $result_data = array();
    $result = mysql_query($query);
    $num = mysql_num_rows($result);
    if($num){
        while ($row = mysql_fetch_assoc($result)) {
            $result_data[] =  $row;
        }
    }

     echo json_encode( $result_data );

}elseif($_SERVER["QUERY_STRING"] === "get_employees_list"){
    $result_data = array();

    if(isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])){
        // check for shop id., if yes then retrive employee
        if(isset($_SESSION['loggedin']['user']['shop_id'])){
            $shop_id = (int) $_SESSION['loggedin']['user']['shop_id'];
            $query = "SELECT u.entity_id, u.firstname, u.lastname, r.role_id, r.role_name FROM pos_user_entity as u, pos_user_roles as r, pos_shop_entity as s WHERE u.role_id = r.role_id AND u.shop_id = s.entity_id AND u.is_active = 1 AND s.entity_id =  $shop_id ORDER BY u.entity_id ASC";
         
            $result = mysql_query($query);
            $num = mysql_num_rows($result);
            if($num){
                while ($row = mysql_fetch_assoc($result)) {
                    $result_data[] =  $row;
                }
            }
        }
    }
     echo json_encode( $result_data );

}elseif ($_SERVER["QUERY_STRING"] === "save_employee"){

    $postdata = @file_get_contents("php://input");
    $emp_data = json_decode($postdata);
    $coulmns_name = array('user_id', 'role_id', 'shop_id', 'firstname', 'lastname', 'email','address','city','state','pincode','phone_number');
    $emp_data_attributes = array();
    $columns = '';
    $values = '';

    // Query builder & attributes filter here.
    foreach ($emp_data as $key => $value) {
        if (in_array($key, $coulmns_name)) {
            $columns = $columns . $key . ',';
            $values = $values . "'" . mysql_real_escape_string($value) . "',";
        } else {
            // To shop attributes fields
            $emp_data_attributes[$key] = $value;
        }
    }

    $username="";
    if(!empty($emp_data->email)){
        $username = mysql_real_escape_string($emp_data->email);
        $columns = $columns . 'username' . ',';
        $values = $values . "'" . $username . "',";
    }


    $password =md5(SALT);
    
    if(!empty($username)){
    	$columns = $columns . 'password' . ',';
    	$values = $values . "'" . $password . "',";
    }

    $columns = $columns . 'log_num' . ',';
    $values = $values . "'" . LOG_NUM . "',";

     // To convert Local time to GMT.
    $date = convertLocalTimezoneToGMT(date('Y-m-d H:i:s'), ini_get('date.timezone'));
    $columns = $columns . 'created_at' . ',';
    $values = $values . "'" . $date . "',";

    // default date 
    $columns = $columns . 'logged_at' . ',';
    $values = $values . "'" . $date . "',";

    $query = "INSERT INTO pos_user_entity (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
    $result = mysql_query($query);

  
    //$fleet_driver_coulmns_name = array('shop_id','name','lastname','email','street1','city','state','zipcode','phoneno');




    if((int)$emp_data->role_id == 4){

        // fetch inserted record id. DONOT DELETE ANY LINE @THIS POINT

        $f= mysql_real_escape_string($emp_data->firstname); 
        $l= mysql_real_escape_string($emp_data->lastname);// last name
        $e = mysql_real_escape_string($emp_data->email);// email;
        $ph = mysql_real_escape_string($emp_data->phone_number);// phoneno.
        $add = mysql_real_escape_string($emp_data->address) ; //
        $s_query = "SELECT entity_id FROM pos_user_entity WHERE ";
        if(isset($emp_data->email)){
            $s_query .=" email = '$e' AND ";
        }
        if(isset($emp_data->lastname)){
            $s_query .=" lastname = '$l' AND ";
        }
        if(isset($emp_data->phone_number)){
            $s_query .=" phone_number = '$ph' AND ";
        }
        if(isset($emp_data->address)){
            $s_query .=" address = '$add' AND ";
        }
        if(isset($emp_data->firstname)){
            $s_query .=" firstname = '$f' ";
        }
        $femp_id = 0;
        $fs_result = mysql_query($s_query );
        if(mysql_num_rows($fs_result) > 0){
            while ($row = mysql_fetch_assoc($fs_result)) {
                $femp_id  =  $row['entity_id'];
            }
        }
        
        $fleet_columns = '';
        $fleet_values = '';

        $fleet_columns = $fleet_columns . 'shop_id' . ',';
        $fleet_values = $fleet_values . "'" .  $emp_data->shop_id . "',";

        $fleet_columns = $fleet_columns . 'emp_id' . ',';
        $fleet_values = $fleet_values . "'" .  $femp_id . "',";


        $fleet_columns = $fleet_columns . 'name' . ',';
        $fleet_values = $fleet_values . "'" .  $emp_data->firstname . "',";

        $fleet_columns = $fleet_columns . 'lastname' . ',';
        $fleet_values = $fleet_values . "'" .  $emp_data->lastname . "',";

        $fleet_columns = $fleet_columns . 'email' . ',';
        $fleet_values = $fleet_values . "'" .  $emp_data->email . "',";

        $fleet_columns = $fleet_columns . 'street1' . ',';
        $fleet_values = $fleet_values . "'" .  $emp_data->address . "',";

        $fleet_columns = $fleet_columns . 'city' . ',';
        $fleet_values = $fleet_values . "'" .  $emp_data->city . "',";

        $fleet_columns = $fleet_columns . 'state' . ',';
        $fleet_values = $fleet_values . "'" .  $emp_data->state . "',";

        $fleet_columns = $fleet_columns . 'country' . ',';
        $fleet_values = $fleet_values . "'INDIA',";

        $fleet_columns = $fleet_columns . 'job_profile' . ',';
        $fleet_values = $fleet_values . "'Driver',";

        $fleet_columns = $fleet_columns . 'zipcode' . ',';
        $fleet_values = $fleet_values . "'" .  $emp_data->pincode . "',";
        
        if(isset($emp_data->phone_number )){
            $fleet_columns = $fleet_columns . 'phoneno' . ',';
            $fleet_values = $fleet_values . "'" .  $emp_data->phone_number . "',";
        }

        if(isset($emp_data->alt_phone_number )){
            $fleet_columns = $fleet_columns . 'mobileno' . ',';
            $fleet_values = $fleet_values . "'" .  $emp_data->alt_phone_number . "',";
        }

        $fleet_columns = $fleet_columns . 'created_at' . ',';
        $fleet_values = $fleet_values . "'" .  $date . "',";

        $fleet_query = "INSERT INTO fleet_driver (" . trim($fleet_columns, ',') . ") VALUES(" . trim($fleet_values, ',') . ")";
        $fleet_result = mysql_query($fleet_query);
    }
   



   

    if($result){
        $q = sprintf("SELECT entity_id FROM pos_user_entity WHERE email='%s' AND is_active = 1 AND is_adminpanel = 0 LIMIT 1", mysql_real_escape_string($emp_data->email));
        $result = mysql_query($q);

         if (!$result) {
            die(mysql_error());
        } else {

        	$entity_id = 0;
           	$num = mysql_num_rows($result);

           	if($num){
           		while ($row = mysql_fetch_assoc($result)) {
                    $entity_id = $row['entity_id'];
                }

                foreach ($emp_data_attributes as $key => $value) {
                	$query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
                    $result = mysql_query($query);
                    $action = "create";

                    while ($row = mysql_fetch_assoc($result)) {
                        switch ($row['backend_type']) {
                            case "varchar":
                                pos_user_entity_varchar( $entity_id, $row['attribute_id'], $value ,$action);
                                break;
                            case "text":
                                pos_user_entity_text( $entity_id, $row['attribute_id'], $value ,$action);
                                break;
                            case "decimal":
                                pos_user_entity_decimal($entity_id, $row['attribute_id'], $value, $action);
                                break;
                            case "int":
                                pos_user_entity_int($entity_id, $row['attribute_id'], $value , $action);
                                break;
                            case "datetime":
                                pos_user_entity_datetime($entity_id, $row['attribute_id'], $value , $action);
                                break;
                        }
                    }// while end here
                }// for end.

           	}
        }
    }


}elseif ($_SERVER["QUERY_STRING"] === "update_employee_info") {
	# code...
	$postdata = @file_get_contents("php://input");
    $emp_data = json_decode($postdata);

    $coulmns_name = array('role_id', 'shop_id', 'firstname', 'lastname', 'email','address','city','state','pincode','phone_number');
    $emp_data_attributes = array();

     // Query builder & attributes filter here.
    $update_string = "UPDATE pos_user_entity SET ";
    $count = count($coulmns_name);

    // check here if eamil is present create username & password....

    foreach ($emp_data as $key => $value) {
        if (in_array($key, $coulmns_name)) {
            if($count > 1){
                $update_string = $update_string . $key."='".mysql_real_escape_string($value)."', ";
            }elseif($count == 1){
                $update_string = $update_string . $key."='".mysql_real_escape_string($value)."'";
            }
            $count--;
        } else {
            // To shop attributes fields
            $emp_data_attributes[$key] = $value;
        }
    }

    $user_id = $emp_data->entity_id;
    $update_query = $update_string. " WHERE entity_id = {$user_id}";
    $result = mysql_query($update_query);

   // Update driver Info Here fleet_driver Table Start here 

    if((int)$emp_data->role_id == 4){
        $fleet_driver_update = array(
            'shop_id' => $emp_data->shop_id,
            'name' => $emp_data->firstname,
            'lastname' =>  $emp_data->lastname,
            'email'=> $emp_data->email,
            'street1'=> $emp_data->address,
            'city' => $emp_data->city,
            'state' => $emp_data->state,
            'country' => "INDIA",
            'job_profile' => "Driver",
            'zipcode'=> $emp_data->pincode ,
            'phoneno' => $emp_data->phone_number ,
            'mobileno' => $emp_data->alt_phone_number
        );

        $fleet_update_string = "UPDATE fleet_driver SET ";
        $fleet_count =  count($fleet_driver_update);
        foreach ($fleet_driver_update as $key => $value) {
            if($fleet_count > 1){
                $fleet_update_string = $fleet_update_string . $key."='".mysql_real_escape_string($value)."', ";
            }elseif($fleet_count == 1){
                $fleet_update_string = $fleet_update_string . $key."='".mysql_real_escape_string($value)."'";
            }
            $fleet_count--;
        }

        $femp_id = $emp_data->entity_id;
        $fshop_id =  $emp_data->shop_id;
        $fleet_update_string = $fleet_update_string. " WHERE emp_id = {$femp_id} AND shop_id = {$fshop_id}";

        //echo $fleet_update_string;
        $fu_result = mysql_query($fleet_update_string);

        //  echo $fu_result;
    }
    
  // Update driver Info Here fleet_driver Table End here 

    if($result){
        $unused = array('username','password','cpassword','created_at','updated_at','logged_at','role','is_adminpanel','is_active','entity_id','log_num');
        foreach ($unused as $key => $value) {
            if(array_key_exists($value, $emp_data_attributes)){
              unset($emp_data_attributes[$value]);
            }
        }

         foreach ($emp_data_attributes as $key => $value) {
            $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
            $result = mysql_query($query);

            $action = "update";
            $entity_id =  $user_id;

            while ($row = mysql_fetch_assoc($result)) {
                        switch ($row['backend_type']) {
                            case "varchar":
                                pos_user_entity_varchar($entity_id, $row['attribute_id'], $value,$action);
                                break;
                            case "text":
                                pos_user_entity_text($entity_id, $row['attribute_id'], $value,$action);
                                break;
                            case "decimal":
                                pos_user_entity_decimal($entity_id, $row['attribute_id'], $value,$action);
                                break;
                            case "int":
                                pos_user_entity_int($entity_id, $row['attribute_id'], $value,$action);
                                break;
                            case "datetime":
                                pos_user_entity_datetime($entity_id, $row['attribute_id'], $value,$action);
                                break;
                        }
                    }// while end here

        }  
    }
    
}else{
    // To edit or Update shop information.
    parse_str($_SERVER["QUERY_STRING"], $query);
    if(array_key_exists('get_employee_info', $query)){
        extract_employee_information(mysql_real_escape_string($query['emp_id'])); 
    }elseif (array_key_exists('deactivate_employee', $query)) {
    	deactivate_employee(mysql_real_escape_string($query['emp_id']));
    }
}



function pos_user_entity_varchar($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_user_entity_varchar (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_user_entity_varchar SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_user_entity_varchar WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
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


function pos_user_entity_int($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_user_entity_int (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_user_entity_int SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_user_entity_int WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
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


function pos_user_entity_text($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_user_entity_text (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_user_entity_text SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_user_entity_text WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
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


function pos_user_entity_decimal($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_user_entity_decimal (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_user_entity_decimal SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_user_entity_decimal WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
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


function pos_user_entity_datetime($entity_id, $attribute_id, $value ,$action) {
    $in_query = "INSERT INTO pos_user_entity_datetime (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
    $up_query = "UPDATE pos_user_entity_datetime SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
    if($action === "create"){
        $result = mysql_query($in_query);
    }else{
       // update
        $st_query = sprintf("SELECT * FROM pos_user_entity_datetime WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1",$entity_id,$attribute_id);
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




function extract_employee_information($emp_entity_id){
    $q = sprintf("SELECT * FROM pos_user_entity,pos_user_roles WHERE pos_user_roles.role_id = pos_user_entity.role_id AND  pos_user_entity.entity_id='%d' AND pos_user_entity.is_active= 1 LIMIT 1",$emp_entity_id);
    $result = mysql_query($q);
    $num = mysql_num_rows($result);
    if($num){
        $emp_row = mysql_fetch_assoc($result);

        unset($emp_row['cpassword']);
        unset($emp_row['password']);

        $q_varchar = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_user_entity_varchar as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$emp_entity_id);
        $result = mysql_query($q_varchar);

        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $emp_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }

        $q_int = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_user_entity_int as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$emp_entity_id);
        $result = mysql_query($q_int);
        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $emp_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }
        
        $q_text = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_user_entity_text as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$emp_entity_id);
        $result = mysql_query($q_text);
        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $emp_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }

        $q_decimal = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_user_entity_decimal as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$emp_entity_id);
        $result = mysql_query($q_decimal);
        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $emp_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }

        $q_datetime = sprintf("SELECT p.attribute_code,pv.value FROM pos_attributes as p , pos_user_entity_datetime as pv WHERE p.attribute_id = pv.attribute_id and pv.entity_id='%d' ",$emp_entity_id);
        $result = mysql_query($q_datetime);
        if(mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)) {
                if(!empty($row['value'])){
                    $emp_row[$row['attribute_code']] = $row['value']; 
                }              
            }
        }
        
         echo json_encode( $emp_row );
    }
}



function deactivate_employee($emp_id){
     $query = "UPDATE pos_user_entity SET is_active = 0 WHERE entity_id = $emp_id";
     $result = mysql_query($query);
     if($result){
        $status = array("message" => " Deactivated...","statusText"=>"OK");
         echo json_encode( $status );
     }else{
        $status = array("message" => " Not Deactivated...","statusText"=>"ERROR");
        echo json_encode( $status );
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
