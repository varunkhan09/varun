<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
?>

<html>
<head>
	<style>
		.main_body_div
		{
			margin-top:140px;
		}
	</style>
</head>
<body>

	<div class="col-xs-12 main_body_div text-center">
		<input type='textbox' id='dod_t' name='dod_t' class='datepicker' placeholder="Select Date of Delivery">
		<br><br>
		<input type='number' id='no_of_delivery_boys_t' placeholder="Number of Delivery Boys">
		<br><br>
		<input type="button" class="buttons" id="routeplanner_submit_b" value="Find Route">
	</div>


	<form id="submit_this_form" method="POST" action="index2.php"></form>
</body>
	<script src="<?php echo $base_media_js_url; ?>/routeplannermain.js"></script>
</head>