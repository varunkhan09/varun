<?php
	//include $_SERVER['document_root']."/global_variables.php";
	//include $base_path."/app/etc/dbconfig.php";

	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
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
	</style>

















	<div class="container-fluid text-center content_table_division">
		<center>
			<table style="width:96%; text-align:center;" id="items_table">
				<tr class="table_heading_row">
					<td>Item ID</td>
					<td>Product Type</td>
					<td>Quantity</td>
					<td>Current Stock</td>
					<td>&nbsp;</td>
				</tr>

				<tr class="table_data_row" id="items_table_tr_1_add_stock">
					<td>
						<input type="textbox" disabled="disabled" id="item_id_t_1_add_stock">
					</td>
					
					<td>
						<input type="text" id="item_name_typeahead_1" data-provide="typeahead" class="item_name_t_class_add_stock">
					</td>

					<td>
						<input type="textbox" id="item_quantity_1_add_stock">
					</td>
					
					<td>
						<label class="normal_black_1" id="current_stock_label_1_add_stock"></label>
					</td>

					<td>
						<input type="button" id="remove_item_b_1_add_stock" class="remove_item_class_add_stock buttons" value="Remove Item">
					</td>
				</tr>
			</table>
		</center>
	</div>


	<div id="dialog-confirm-msg">
	</div>


	<div class="container-fluid text-center content_table_division" style="width:96%;">
		<center>
			<input type="button" id="add_more_items_b_add_stock" class="buttons" style="float:right;" value="Add More Items">
		</center>
	</div>

	<div class="container-fluid text-center content_table_division" style="width:96%;">
		<center>
			Enter Description of the product
			<br>
			<textarea class="form-control" id="vendor_description_textarea"></textarea>
		</center>
	</div>


	<div class="container-fluid text-center content_table_division" style="width:96%;">
		<center>
			<input type="button" id="add_stock_b_add_stock" class="buttons" value="Add this Custom Product">
		</center>
	</div>
</body>
</html>