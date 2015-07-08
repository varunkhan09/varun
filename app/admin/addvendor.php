<?php
	//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/app/module/common/header.php";
	include "/var/www/varun/app/module/common/header.php";
	//mysql_query("SET AUTOCOMMIT=1");
	
	$query = "select entity_id from pos_item_entity where is_active=1";
	$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result))
	{
		$temp_entity_id = $row['entity_id'];
		$query = "insert into pos_stock_entity (shop_id, item_id) values (13, $temp_entity_id)";
		echo $query."<br>";
		mysql_query($query);
		echo mysql_error();
	}
?>


<!--DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

	<title>Manage Items</title>

	<script src="<?php //echo $base_media_js_url; ?>/bootstrap/js/jquery-latest.min.js"></script>
	<script src="<?php //echo $base_media_js_url; ?>/bootstrap/js/jquery-ui.js"></script>
	<link href="<?php //echo $base_media_css_url; ?>/bootstrap/js/jquery-ui.css">
	<link href="<?php //echo $base_media_css_url; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php //echo $base_media_css_url; ?>/header.css" rel="stylesheet">
	<link href="<?php //echo $base_media_css_url; ?>/manageitems.css" rel="stylesheet">
	<script src="<?php //echo $base_media_js_url; ?>/manageitems.js"></script>
</head>

<body>

<?php //include $base_path_folder."/app/module/common/header.php"; ?>

<div class="row manage_items_page_content text-center">
	<div class="container-fluid">
		<div class="col-xs-2 col-xs-offset-4">
			<input type='button' id='add_new_item_b' value="Add New Item" class="buttons">
		</div>

		<div class="col-xs-2">
			<input type='button' id='disable_item_b' value="Disable Item" class="buttons">
		</div>
	</div>





	<div id="add_new_item_div" class="container-fluid each_module_main_division">
		<div class="col-xs-2 col-xs-offset-4 text-right"><label class="normal_black_1">Please select a Category : </label></div>
		<div class="col-xs-2 text-left">
			<select class="form-control" id="add_new_item_category_dd">
				<option value=''>Select</option>
				<?php
					//$query = "select entity_id, item_category_name from pos_item_category_entity where is_active=1";
					//$result = mysql_query($query);
					//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
					//while($row = mysql_fetch_assoc($result))
					{
						//echo "<option value='".$row['entity_id']."'>".$row['item_category_name']."</option>";
					}
				?>
			</select>
		</div>

		<div id="add_new_item_operation_div" class="container-fluid each_module_operate_division text-center">
			<div class="col-xs-2 col-xs-offset-4 text-right"><label class="normal_black_1">Enter Item Name : </label></div>
			<div class="col-xs-2 text-left"><input type="textbox" id="add_new_item_new_item_t" class="form-control" value=""></div>
			<div class="col-xs-4 text-center col-xs-offset-4" style="margin-top:10px;"><input type="button" class="buttons" value="Add Item" id="add_new_item_add_item_b"></div>
		</div>
	</div>



	<div id="disable_item_div" class="container-fluid each_module_main_division">
		<div class="col-xs-2 col-xs-offset-4 text-right"><label class="normal_black_1">Please select a Category : </label></div>
		<div class="col-xs-2 text-left">
			<select class="form-control" id="disable_item_category_dd">
				<option value=''>Select</option>
				<?php
					//$query = "select entity_id, item_category_name from pos_item_category_entity where is_active=1";
					//$result = mysql_query($query);
					//while($row = mysql_fetch_assoc($result))
					{
						//echo "<option value='".$row['entity_id']."'>".$row['item_category_name']."</option>";
					}
				?>
			</select>
		</div>

		<div id="disable_item_operation_div" class="container-fluid each_module_operate_division text-center">
			<div class="col-xs-2 col-xs-offset-4 text-right"><label class="normal_black_1">Select Item : </label></div>
			<div class="col-xs-2 text-left">
			<select id="disable_item_select_item_dd" class="form-control"></select>
			</div>
			<div class="col-xs-4 text-center col-xs-offset-4" style="margin-top:10px;"><input type="button" class="buttons" value="Disable Item" id="disable_item_disable_item_b"></div>
		</div>
	</div>



	<div id="result_div" class="container-fluid each_module_main_division">
		<label id="result_label" class="normal_black_1"></label>
	</div>
</div>
</body>
</html>