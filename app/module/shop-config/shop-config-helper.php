<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
include_once $_SERVER['DOCUMENT_ROOT'] . "/app/etc/dbconfig.php";

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ('shop_config_action' == $action) {
        parse_str($_POST['data'], $data);

        $shop_data = array(
            'user_id' => $data['user_id'],
            'shop_name' => $data['shop_name'],
            'email' => $data['email'],
            'phone_number' => $data['phoneno'],
            'website_url' => $data['website_url'],
            'address' => $data['address'],
            'city' => $data['city'],
            'pincode' => $data['pincode'],
            'contact_person' => $data['contact_person'],
            'create_shop_config' => 1,
        );


        $coulmns_name = array('user_id', 'shop_name', 'website_url', 'address', 'city', 'pincode', 'email', 'phone_number');
        $shop_data_attributes = array();
        $columns = '';
        $values = '';

        foreach ($shop_data as $key => $value) {
            if (in_array($key, $coulmns_name)) {
                $columns = $columns . $key . ',';
                $values = $values . "'" . mysql_real_escape_string($value) . "',";
            } else {
                $shop_data_attributes[$key] = $value;
            }
        }

        // To convert Local time to GMT.
        $date = convertLocalTimezoneToGMT(date('Y-m-d H:i:s'), ini_get('date.timezone'));
        $columns = $columns . 'created_at' . ',';
        $values = $values . "'" . $date . "',";
        $query = "INSERT INTO pos_shop_entity (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        $result = mysql_query($query);


        $q = sprintf("SELECT entity_id FROM pos_shop_entity WHERE user_id='%d' AND is_active= 1 LIMIT 1", $data['user_id']);
        $result = mysql_query($q);

        $shop_id = null;
        if (mysql_num_rows($result)) {
            $r = mysql_fetch_object($result);
            $shop_id = $r->entity_id;
            // set shop id into session.
            if (!isset($_SESSION['loggedin']['user']['shop_id'])) {
				$_SESSION['loggedin']['user']['shop_id'] =  $shop_id;
			}
        }

        //////////OK/////////
        if ($shop_id) {
        	$uid = 0;
        	// Update pos_user_entity for shop_id
        	if(isset($_SESSION['loggedin']['user_id'])){
        		$uid =  (int)$_SESSION['loggedin']['user_id'];
        	}
        	$up =  "UPDATE pos_user_entity SET shop_id = $shop_id WHERE entity_id = $uid";
        	$u_res =  mysql_query($up);
        	

            foreach ($shop_data_attributes as $key => $value) {
                $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
                $result = mysql_query($query);
                $action = "create";

                while ($row = mysql_fetch_assoc($result)) {
                    switch ($row['backend_type']) {
                        case "varchar":
                            pos_shop_entity_varchar($shop_id, $row['attribute_id'], $value, $action);
                            break;
                        // case "text":
                        //     pos_shop_entity_text( $shop_id, $row['attribute_id'], $value ,$action);
                        //     break;
                        // case "decimal":
                        //     pos_shop_entity_decimal($shop_id, $row['attribute_id'], $value, $action);
                        //     break;
                        case "int":
                            pos_shop_entity_int($shop_id, $row['attribute_id'], $value, $action);
                            break;
                        // case "datetime":
                        //     pos_shop_entity_datetime($shop_id, $row['attribute_id'], $value , $action);
                        //     break;
                    }
                }
            }
        }
       
     
	$is_shop_config = 0;
        $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='create_shop_config' LIMIT 1");
        $result = mysql_query($query);

        if (mysql_num_rows($result)) {
            while ($row = mysql_fetch_assoc($result)) {
                if ($row['backend_type'] == 'int') {
                    $id = $row['attribute_id'];
                    $query = "SELECT * FROM pos_shop_entity_int where entity_id = $shop_id AND attribute_id = $id";
                    $result = mysql_query($query);
                    if (mysql_num_rows($result)) {
                        while ($row = mysql_fetch_assoc($result)) {
                            $is_shop_config = $row['value'];
                        }
                    }
                }
            }
        }


        if ($is_shop_config) {
            $dcharge = array(
                'fixedtime_delivery_charge' => $data['fixedtime_delivery_charge'],
                'midnight_delivery_charge' => $data['midnight_delivery_charge'],
                'regular_delivery_charge' => $data['regular_delivery_charge'],
                'delivery_charge_config' => 1
            );

            foreach ($dcharge as $key => $value) {
                $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", mysql_real_escape_string($key));
                $result = mysql_query($query);
                $action = "create";
                while ($row = mysql_fetch_assoc($result)) {
                    switch ($row['backend_type']) {
                        case "varchar":
                            pos_shop_entity_varchar($shop_id, $row['attribute_id'], $value, $action);
                            break;
                        // case "text":
                        //     pos_shop_entity_text( $shop_id, $row['attribute_id'], $value ,$action);
                        //     break;
                        case "decimal":
                            pos_shop_entity_decimal($shop_id, $row['attribute_id'], $value, $action);
                            break;
                        case "int":
                            pos_shop_entity_int($shop_id, $row['attribute_id'], $value, $action);
                            break;
                        // case "datetime":
                        //     pos_shop_entity_datetime($shop_id, $row['attribute_id'], $value , $action);
                        //     break;
                    }
                }
            }
        }


        // To test delivery charge configured or not
        $is_dcharge_config = 0;
        $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='delivery_charge_config' LIMIT 1");
        $result = mysql_query($query);
        if (mysql_num_rows($result)) {
            while ($row = mysql_fetch_assoc($result)) {
                if ($row['backend_type'] == 'int') {
                    $id = $row['attribute_id'];
                    $query = "SELECT * FROM pos_shop_entity_int where entity_id = $shop_id AND attribute_id = $id";
                    $result = mysql_query($query);
                    if (mysql_num_rows($result)) {
                        while ($row = mysql_fetch_assoc($result)) {
                            $is_dcharge_config = $row['value'];
                        }
                    }
                }
            }
        }


        // To insert pincode
        if ($is_dcharge_config) {
            $pin = $data['shop_delivery_pincode'];
            $pincodes = explode(',', $pin);
            $attribute_code = array('pincode', 'delivery_pin_config');
            foreach ($attribute_code as $key) {
                $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", $key);
                $result = mysql_query($query);
                $action = "create";
                while ($row = mysql_fetch_assoc($result)) {
                    if ($key == 'pincode' && $row['backend_type'] === "int") {
                        foreach ($pincodes as $pincode) {
                            pos_shop_entity_int($shop_id, $row['attribute_id'], $pincode, $action);
                        }
                    }
                    if ($key == 'delivery_pin_config' && $row['backend_type'] === "int") {
                        pos_shop_entity_int($shop_id, $row['attribute_id'], 1, $action);
                    }
                }
            }
        }



         // To test pincode configured or not
        $is_pincode_config = 0;
        $query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='delivery_pin_config' LIMIT 1");
        $result = mysql_query($query);
        if (mysql_num_rows($result)) {
            while ($row = mysql_fetch_assoc($result)) {
                if ($row['backend_type'] == 'int') {
                    $id = $row['attribute_id'];
                    $query = "SELECT * FROM pos_shop_entity_int where entity_id = $shop_id AND attribute_id = $id";
                    $result = mysql_query($query);
                    if (mysql_num_rows($result)) {
                        while ($row = mysql_fetch_assoc($result)) {
                            $is_pincode_config = $row['value'];
                        }
                    }
                }
            }
        }


        if($is_pincode_config){
        	$response = array(
        		'statusText' => 'Successfully submitted',
        		'statusCode' => 'OK'
        		);

        	echo json_encode($response);
        }else{
        	$response = array(
        		'statusText' => 'Something went wrong',
        		'statusCode' => 'OK',
        		'whereError' =>  'Between SHOP SetUp TO DELIVERY SetUp'
        		);
        	echo json_encode($response);
        }
    }
}

    function pos_shop_entity_varchar($entity_id, $attribute_id, $value, $action) {
        $in_query = "INSERT INTO pos_shop_entity_varchar (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
        $up_query = "UPDATE pos_shop_entity_varchar SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
        if ($action === "create") {
            $result = mysql_query($in_query);
        } else {
            // update
            $st_query = sprintf("SELECT * FROM pos_shop_entity_varchar WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1", $entity_id, $attribute_id);
            $r = mysql_query($st_query);
            if (mysql_num_rows($r)) {
                $result = mysql_query($up_query);
            } else {
                $result = mysql_query($in_query);
            }
        }
        if (!$result) {
            die(mysql_error());
        }
    }

    function pos_shop_entity_int($entity_id, $attribute_id, $value, $action) {
        $in_query = "INSERT INTO pos_shop_entity_int (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
        $up_query = "UPDATE pos_shop_entity_int SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
        if ($action === "create") {
            $result = mysql_query($in_query);
        } else {
            // update
            $st_query = sprintf("SELECT * FROM pos_shop_entity_int WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1", $entity_id, $attribute_id);
            $r = mysql_query($st_query);
            if (mysql_num_rows($r)) {
                $result = mysql_query($up_query);
            } else {
                $result = mysql_query($in_query);
            }
        }
        if (!$result) {
            die(mysql_error());
        }
    }

    function pos_shop_entity_decimal($entity_id, $attribute_id, $value, $action) {
        $in_query = "INSERT INTO pos_shop_entity_decimal (entity_id, attribute_id, value) VALUES  ($entity_id,$attribute_id, '$value')";
        $up_query = "UPDATE pos_shop_entity_decimal SET value = '$value' WHERE entity_id = $entity_id AND attribute_id = $attribute_id";
        if ($action === "create") {
            $result = mysql_query($in_query);
        } else {
            // update
            $st_query = sprintf("SELECT * FROM pos_shop_entity_decimal WHERE entity_id ='%d' AND attribute_id='%d' LIMIT 1", $entity_id, $attribute_id);
            $r = mysql_query($st_query);
            if (mysql_num_rows($r)) {
                $result = mysql_query($up_query);
            } else {
                $result = mysql_query($in_query);
            }
        }
        if (!$result) {
            die(mysql_error());
        }
    }

    function convertLocalTimezoneToGMT($gmttime, $local_timezone) {
        if (empty($local_timezone)) {
            date_default_timezone_set("Asia/Kolkata");
        } else {
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