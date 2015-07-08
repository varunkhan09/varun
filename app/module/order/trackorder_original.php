<?php
        include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
        //include "/var/www/varun/app/module/common/header.php";
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>OTS</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<meta name="author" content="">
		<link href="<?php echo $base_media_css_url; ?>/maps/main.css" rel="stylesheet">
		<link href="<?php echo $base_media_css_url; ?>/maps/demo.css" rel="stylesheet">
	</head>
	<body>
		<div class="header">
			<!-- <div class="logo">
				<img src="img/logo.png" alt="Logo" class="logo-img">
			</div>
			<ul class="nav">
				<li class="nav-link">

				</li>
			</ul> -->
		</div>
		
		<div id="google_canvas"></div>
	
		<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
	
		<?php 
		if(isset($_GET['orderno'])){
			$orderno =  $_GET['orderno'];
		}
		?>




<?php
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, 'https://d.traccar.litvak.su/traccar/rest/login?payload=["amar","amar"]'); 
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$data = curl_exec($ch); 
curl_close($ch);
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $data, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}
$cookie = $cookies['JSESSIONID'];


/*
To get device list
*/
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, 'https://d.traccar.litvak.su/traccar/rest/getDevices'); 
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=$cookie");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$data = curl_exec($ch); 
curl_close($ch);
$response_data = json_decode($data);


//print "<pre>";
$devices = array();
foreach ($response_data as $device_data) {
	$device = new stdClass();
	unset($device_data->iconType);
	$device = $device_data;
	$devices[] = $device;  
}

//print_r($devices);

/*
To get latest location data
*/
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, 'https://d.traccar.litvak.su/traccar/rest/getLatestPositions'); 
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=$cookie");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$data = curl_exec($ch); 
curl_close($ch);
$json_data = json_decode($data);


$device_data_info = array();



foreach($json_data as $device_data){
	$device = $device_data->device;
	$device_info =  new stdClass();
	unset($device_data->device);
	
	$device_info = 	$device_data;
	
	$device_info->name =  $device->name;
	$device_info->deviceId =  $device->id;
	$device_info->uniqueId = $device->uniqueId;
	$device_info->iconType = $device->iconType;
	$device_data_info[]  =  $device_info;
	print_r($device_info);
	if($device->uniqueId == '865980022689423'){
		$latitude = $device_info->latitude;
		$longitude = $device_info->longitude;
		$speed = $device_info->speed;
		break;
	}
}

//print_r($device_data_info);
?>

	    <script>
	    (function() {
	    	if(!!navigator.geolocation) {
	    		var map;
		    	var mapOptions = {
		    		zoom: 15,
		    		mapTypeId: google.maps.MapTypeId.ROADMAP
		    	};
		    	map = new google.maps.Map(document.getElementById('google_canvas'), mapOptions);
	    		navigator.geolocation.getCurrentPosition(function(position) {
		    		var geolocate = new google.maps.LatLng(<?php echo $latitude ;?>, <?php echo $longitude ;?>);
		    		var infowindow = new google.maps.InfoWindow({
		    			map: map,
		    			position: geolocate,
		    			content:
		    				'<h1>Current location of your order :'+<?php echo $orderno; ?>+'</h1>' +
		    				'<h2>Latitude: ' + <?php echo $latitude;?> + '</h2>' +
		    				'<h2>Longitude: ' + <?php echo $longitude ;?> + '</h2>'+
		    				'<h2>Speed: ' + <?php echo $speed ;?> + '</h2>'
		    		});
		    		map.setCenter(geolocate);
	    		});
	    		
	    	} else {
	    		document.getElementById('google_canvas').innerHTML = 'No Geolocation Support.';
	    	}
	    	
	    })();
	    </script>
	
		<!-- Demo Analytics -->
		<script>
			// var _gaq=[['_setAccount','UA-20440416-10'],['_trackPageview']];
			// (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			// g.src='//www.google-analytics.com/ga.js';
			// s.parentNode.insertBefore(g,s)})(document,'script');
		</script>
		

	</body>
</html>
