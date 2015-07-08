<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	//include "/var/www/varun/app/module/common/header.php";
?>

	<div class="row add_stock_page_content">
		<div class="container-fluid text-center">
			<center>
				<input type="textbox" class="form-control" id="order_id_t" style="width:200px;" placeholder="Enter Order ID">
			</center>
		</div>


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

					<tr class="table_data_row" id="items_table_tr_1">
						<td>
							<input type="textbox" disabled="disabled" id="item_id_t_1">
						</td>
						
						<td>
							<!--<input type="textbox" class="add_stock_product_type_textbox" id="item_name_t">-->
							<input type="text" id="item_name_t2_1" data-provide="typeahead" class="item_name_t_class">
							<!--<select class="form-control item_name_dd_class" id="item_name_dd_1"></select>-->
							<!--<select id="item_name_dd_1" class="selectpicker item_name_dd_class" data-live-search="true" title="Please select an option ..."></select>-->
						</td>

						<td>
							<input type="textbox" id="item_quantity_1">
						</td>

						<td>
							<label class="normal_black_1" id="current_stock_label_1"></label>
						</td>

						<td>
							<input type="button" id="remove_item_b_1" class="remove_item_class buttons" value="Remove Item">
						</td>
					</tr>
				</table>
			</center>
		</div>

		<div id="dialog-confirm-msg"></div>

		<div class="container-fluid text-center content_table_division" style="width:96%;">
			<center>
				<input type="button" id="deduce_stock_b" class="buttons" value="Save Order Item Details">
				<input type="button" id="add_more_items_b" class="buttons" style="float:right;" value="Add More Items">
			</center>
		</div>

	</div>
</body>
<!-- Page Content -->
