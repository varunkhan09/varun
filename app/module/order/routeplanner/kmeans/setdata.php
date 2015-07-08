<?php
if (isset($_POST['pincodes']))
{
	$pincode_order_mapper_data = $_POST['pincode_order_mapper'];
	$pincode_order_mapper_array_1 = explode(":", $pincode_order_mapper_data);
	$pincode_order_mapper_final = array();
	$pincode_order_mapper_final_string = array();
	foreach($pincode_order_mapper_array_1 as $each_pincode_order_mapper_unit_1)
	{
		$each_pincode_order_mapper_unit_1_array = explode("-", $each_pincode_order_mapper_unit_1);
		$temp_pincode = $each_pincode_order_mapper_unit_1_array[0];
		$temp_orders = $each_pincode_order_mapper_unit_1_array[1];

		$temp_orders_array = explode(",", $temp_orders);
		foreach($temp_orders_array as $each_order)
		{
			$pincode_order_mapper_final[$temp_pincode][] = $each_order;
			$pincode_order_mapper_final_string[$temp_pincode] .= "$each_order, ";
		}
		$pincode_order_mapper_final_string[$temp_pincode] = rtrim($pincode_order_mapper_final_string[$temp_pincode], ", ");
	}


	$result = explode(',' , $_POST["pincodes"]);
	$result1 = count($result);
}

$var = array();
$n = $result1-1;

$latlong = array(array());

$j = 0;


for($i=0; $i<=$n; $i++)
{
	$var[$i] = getLatLongDetailsOfPincode($result[$i]);
	if(empty($var[$i]['lat']) && empty($var[$i]['lng']))
	{
		//echo $result[$i]. " is an invalid pincode<br>";
		echo "Order(s) ".$pincode_order_mapper_final_string[$result[$i]]." have invalid pincode(".$result[$i].").<br><br>";
	}
	else
	{
		$latlong[$j][0] = $var[$i]['lat'];
		$latlong[$j][1] = $var[$i]['lng'];
		$j++;
	}
}

$dataarray =json_encode($latlong);
echo "<script> var data =".$dataarray."\n;console.log('Given data: ' + data);</script>";



function getLatLongDetailsOfPincode($pincode) {
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=India&components=country:India|postal_code:' . $pincode . '&mode=driving&sensor=false';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $geoloc = curl_exec($ch);
    $json = json_decode($geoloc);
    $pincode_geo_coordinate = array("lat" => $json->results[0]->geometry->location->lat, "lng" => $json->results[0]->geometry->location->lng);
   return $pincode_geo_coordinate;
}

?>