<?php
session_start();

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

    
define('ROOT_PATH', dirname(__FILE__));

include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

/** Include PHPExcel_IOFactory */
include_once  '../PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php';

try{
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){

		$file_name		= strip_tags($_FILES['upload_file']['name']);
		$file_id 		= strip_tags($_POST['upload_file_ids']);
		$file_size 		= $_FILES['upload_file']['size'];
		$files_path		= ROOT_PATH.'/uploaded_files/';
		$vendor_id = 0;
		$shop_id = 0;

		if(isset($_SESSION['loggedin']) && isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])){
			$vendor_id =  (int) $_SESSION['loggedin']['user_id'];
			
			if(isset($_SESSION['loggedin']['user']['shop_id'])){
				$shop_id =  (int)$_SESSION['loggedin']['user']['shop_id'];

				
				$time_stamp = time().'_'.date('ymd').'_'.$vendor_id.'_'.$shop_id.'_';
				$file_location 	= $files_path .$time_stamp. $file_name;

				if ($_FILES["upload_file"]["error"] > 0) {
					echo "Error : ".$_FILES["upload_file"]["error"]." ";
				}else{
					if(move_uploaded_file(strip_tags($_FILES['upload_file']['tmp_name']), $file_location)){
					
						$objPHPExcel = PHPExcel_IOFactory::load($file_location);
					    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
						$arrayCount = count($allDataInSheet);	
						$column_name =  $allDataInSheet[1]["A"];
						$extension = pathinfo($file_location, PATHINFO_EXTENSION);


						if($extension==="xlsx" || $extension ==="xls"){
							if(strtolower($column_name) === "pincode"){
								$query = sprintf("SELECT * FROM pos_attributes WHERE attribute_code='%s' LIMIT 1", strtolower($column_name));
								$result = mysql_query($query);
								$action = "create";
								$entity_id = $shop_id; 

								while ($row = mysql_fetch_assoc($result)) {
							        if($row['backend_type'] === "int"){
							        	for($i=2;$i<=$arrayCount;$i++){
							        		$pincode = trim($allDataInSheet[$i]["A"]);
							        			

							        		//$c1 = trim($allDataInSheet[$i]["A"]);
							        		//$c2 = trim($allDataInSheet[$i]["B"]);
							        		pos_shop_entity_int($entity_id, $row['attribute_id'], $pincode, $action);
							        	}
								    }	
							    } 	
							}
							//unlink($file_location);
							echo $file_id;
						}else{
							echo 'system_error';
						}	
					}else{
						echo 'system_error';
					}
				}
			}else{
				echo 'system_error';
			}
		}else{
			echo 'system_error';
		}
	}else{
		echo 'system_error';
	}

}catch(Exception $e) {
    die('Error loading file '.$e->getMessage());
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
