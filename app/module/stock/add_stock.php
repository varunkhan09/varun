<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
?>


	<div class="row add_stock_page_content">
		<div class="container-fluid text-center">
			<input type="textbox" id="input_date_t_add_stock" class="datepicker" value=""><span id="calender_icon" class="input-group-addon" style="display:inline; font-size:13px;"><i class="glyphicon glyphicon-th"></i></span>
			<input type="button" id="load_stock_details_b" class="buttons" value="Load Stock Details">
		</div>

		<div id="load_item_details_div" class="container-fluid text-center content_table_division"></div>


		<div class="container-fluid text-center content_table_division">
			<center>
				<table style="width:96%; text-align:center;" id="items_table">
					<tr class="table_heading_row">
						<td>Item ID</td>
						<td>Product Type</td>
						<td>Quantity</td>
						<td>Rate</td>
						<td>Current Stock</td>
						<td>&nbsp;</td>
					</tr>

					<tr class="table_data_row" id="items_table_tr_1_add_stock">
						<td>
							<input type="textbox" disabled="disabled" id="item_id_t_1_add_stock">
						</td>
						
						<td>
							<input type="text" id="item_name_t2_1_add_stock" data-provide="typeahead" class="item_name_t_class_add_stock">
						</td>

						<td>
							<input type="textbox" id="item_quantity_1_add_stock">
						</td>
						
						<td>
							<select class="form-control item_rate_dd_class_add_stock" id="item_rate_dd_1_add_stock"></select>
							<br>
							<input type="textbox" class="form-control item_rate_value_class_add_stock" id="item_rate_value_1_add_stock" placeholder="Price/Unit Rate">
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

		<div id="dialog-confirm-msg"></div>




		<div id="dialog_form_varun" title="Add Travelling Charges" style="display:none;">
			<center>
				<form>
					<input type="textbox" id="travelling_form_t" class="form-control" placeholder="Enter Travelling Charges">
					<br>
					<input type="button" id="travelling_form_b" class="buttons" value="Save">
					<br>
				</form>
			</center>
		</div>

		<div class="container-fluid text-center content_table_division" style="width:96%;">
			<center>
				<input type="button" id="add_stock_b_add_stock" class="buttons" value="Add Stock">
				<input type="button" id="add_more_items_b_add_stock" class="buttons" style="float:right;" value="Add More Items">
			</center>
		</div>

	</div>






	<!--<script src="<?php echo $base_media_js_url; ?>/dropdownselector/js/bootstrap-select.js"></script>-->
</body>
</html>
<!-- Page Content -->
