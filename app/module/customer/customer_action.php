<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

if(isset($_POST['ssd'])){
	$action = mysql_real_escape_string($_POST['ssd']);
	$flag = true;
	if(isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])){
		if(isset($_SESSION['loggedin']['user']['shop_id'])){
			switch ($action) {
				  case 'save_customer':
    					if(isset($_POST['data'])){
    						  parse_str($_POST['data'], $data);
    						  $response = _save_customer($data);
    					}
					    break;

          case 'delete_customer':
              if(isset($_POST['customer_id'])){
                $id =  (int) $_POST['customer_id'];
                if($id > 0 ){
                  _delete_customer($id);
                }
              }else{
                $status = array("message" => "Opps !.. something wrong.","statusText"=>"ERROR",'statusCode'=>404);
                echo json_encode( $status );
              }
              break;

				case 'update_customer':
              if(isset($_POST['data'])){
                  parse_str($_POST['data'], $data);
                  $response = _update_customer($data);
              }
              break;
				default:
					# code...
					break;
			}
		}else{
			$flag = false;
		}

	}else{
		$flag = false;
	}

	if(! $flag){
		$responseStatus = array("statusText"=>"Opps!..Something Wrong.", "statusCode"=>"404");
		echo json_encode($responseStatus);
	}
}


function _delete_customer($id){
      $query = "UPDATE pos_customers_entity SET is_active = 0 WHERE customer_id = $id";
      $result = mysql_query($query);
      if($result){
        $status = array("message" => " Deactivated...","statusText"=>"OK",'statusCode'=>200);
         echo json_encode( $status );
      }else{
        $status = array("message" => " Not Deactivated...","statusText"=>"ERROR",'statusCode'=>404);
        echo json_encode( $status );
      }
}

function _update_customer($cust_data){
    $coulmns_name =  array('shop_id','firstname','lastname','email','dob','gender','address','city','state','pincode','country','telephone');
    $columns = '';
    $values = '';
     // Query builder & attributes filter here.
    $update_string = "UPDATE pos_customers_entity SET ";
    $customer_id = $cust_data['customer_id'];
    
    $count = count($coulmns_name);
    foreach ($cust_data as $key => $value) {
        if (in_array($key, $coulmns_name)) {
            if($count > 1){
                if($key == "dob" && !empty($value)){
                    $dob = convertTo_YYYY_MM_DD_format($value);
                    $update_string = $update_string . $key."='".$dob."', ";
                }
                elseif($key == "shop_id" && !empty($value)){
                    $update_string = $update_string . $key."=".mysql_real_escape_string($value).",  ";
                }
                elseif($key =="customer_id" && !empty($value)){
                }else{
                  $update_string = $update_string . $key."='".mysql_real_escape_string($value)."', ";
                }  
            }elseif($count == 1){
                $update_string = $update_string . $key."='".mysql_real_escape_string($value)."' ";
            }
            $count--;
        } 
    }

    $update_query = $update_string. " WHERE customer_id = {$customer_id}";

   // echo $update_query;
    $result = mysql_query($update_query);
    $responseStatus = array();
     if($result){
        $responseStatus = array("statusText"=>"Sucessfully Submitted.", "statusCode"=>"200");
     }else{
        $responseStatus = array("statusText"=>"Opps!..Something Wrong.", "statusCode"=>"404");
     }
     echo json_encode($responseStatus);

}



function _save_customer($cust_data){
	$coulmns_name =  array('shop_id','firstname','lastname','email','dob','gender','address','city','state','pincode','country','telephone');
	$columns = '';
	$values = '';
  
	 // Query builder & attributes filter here.
    foreach ($cust_data as $key => $value) {
        if (in_array($key, $coulmns_name)) {
          	 if($key == "dob" && !empty($value)){
                  $columns = $columns . $key . ',';
                  $values = $values . "'" . convertTo_YYYY_MM_DD_format($value) . "',";
            }else{
          		if($key =="shop_id" && !empty($value)){
          			$columns = $columns . $key . ',';
              	$values = $values . "" . mysql_real_escape_string($value) . ",";
          		}else{
          			$columns = $columns . $key . ',';
              	$values = $values . "'" . mysql_real_escape_string($value) . "',";
          		}
          	}
        }
    }

    // To convert Local time to GMT.
    // $date = convertLocalTimezoneToGMT(date('Y-m-d H:i:s'), ini_get('date.timezone'));
    // $columns = $columns . 'created_at' . ',';
    // $values = $values . "'" . $date . "',";

   $query = "INSERT INTO pos_customers_entity (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
  //echo $query;
   $result =  mysql_query($query);

   $responseStatus = array();
   if($result){
   		$responseStatus = array("statusText"=>"Sucessfully Submitted.", "statusCode"=>"200");
   }else{
   		$responseStatus = array("statusText"=>"Opps!..Something Wrong.", "statusCode"=>"404");
   }
	echo json_encode($responseStatus);
}



function convertTo_YYYY_MM_DD_format( $date ){
	$date = DateTime::createFromFormat('m/d/Y', $date);
	return $date->format('Y-m-d');
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
