<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";

	$product_id = $_REQUEST['product_id'];
	$product = Mage::getModel('catalog/product')->load($product_id);
	$product_name = $product->getName();
	$product_v_description = $product->getVendorDescription();

	mysql_select_db($magento_database);
	$query = "select value from catalog_product_entity_varchar where entity_id = $product_id and attribute_id = 85";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$product_image_file_name = $row['value'];
	$product_image_url = "http://www.flaberry.com/media/catalog/product/".$product_image_file_name;
	mysql_select_db($vendorshop_database);
?>

<html>
<head>
	
</head>
<body>
	<style>
	.header_main_container
	{
		display:none;
	}

	.header_main_container2
	{
		display:none;
	}
	
	.stk-sidebar
	{
		display:none !important;
	}
	</style>

	<input type='hidden' id='product_id' value='<?php echo $product_id; ?>'>
	<div class="container-fluid text-center content_table_division">
		<center>

			<label><?php echo $product_name; ?></label>
			<br>
			<img src="<?php echo $product_image_url; ?>" class="product_image_class" style="height:150px; width:150px;">
			<br><br>

			<table style="width:96%; text-align:center;" id="items_table">
					<tr class="table_heading_row">
						<td>Item ID</td>
						<td>Product Type</td>
						<td>Quantity</td>
						<td>&nbsp;</td>
					</tr>

					<tr class="table_data_row" id="items_table_tr_1">
						<td>
							<input type="textbox" disabled="disabled" id="item_id_t_1">
						</td>
						
						<td>
							<input type="text" id="item_name_typeahead_1" data-provide="typeahead" class="item_name_t_class">
						</td>

						<td>
							<input type="textbox" id="item_quantity_1">
						</td>

						<td>
							<input type="button" id="remove_item_b_1" class="remove_item_class buttons" value="Remove Item">
						</td>
					</tr>
				</table>
		</center>
	</div>


	<div id="dialog-confirm-msg">
	</div>


	<div class="container-fluid text-center content_table_division" style="width:96%;">
		<center>
			<input type="button" id="add_more_items_b" class="buttons" style="float:right;" value="Add More Items">
		</center>
	</div>

	<div class="container-fluid text-center content_table_division" style="width:96%;">
		<center>
			<br>
			<textarea class="form-control" id="vendor_description_textarea" readonly><?php echo $product_v_description; ?></textarea>
		</center>
	</div>


	<div class="container-fluid text-center content_table_division" style="width:96%; margin-bottom:20px;">
		<center>
			<input type="button" id="add_stock_b" class="buttons" value="Save Product Details">
		</center>
	</div>
</body>
</html>
