<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($vendorshop_database);

	$query = "select firstname, lastname, entity_id from pos_user_entity where shop_id=$shop_id";
	$result = mysql_query($query);
?>

	<style>
	input[type='date']
	{
		line-height:18px;
	}
	</style>

	<div class="row add_stock_page_content">
		<div class="container-fluid text-center" style="margin-top:20px;">
			<select id="employee_dd">
			<option value="">Select Employee</option>
			<?php
				while($row = mysql_fetch_assoc($result))
				{
					echo "<option value='".$row['entity_id']."'>".$row['firstname']." ".$row['lastname']."</option>";
				}
			?>
			</select>
		</div>

		<div class="container-fluid text-center content_table_division">
			<center>
				<table style="width:60%; text-align:center;" id="items_table">
					<tr class="table_heading_row">
						<td>Date</td>
						<td>In Time</td>
						<td>Out Time</td>
						<td>&nbsp;</td>
					</tr>

					<tr class="table_data_row" id="items_table_tr_1">
						<td>
							<input type="date" id="input_date_t_1" value="" placeholder="Select Date">
						</td>

						<td>
							<input type="text" id="in_time_1" class="item_name_t_class">
						</td>
						
						<td>
							<input type="text" id="out_time_1" class="item_name_t_class">
						</td>

						<td>
							<input type="button" id="remove_item_b_1" class="remove_item_class buttons" value="Remove">
						</td>
					</tr>
				</table>
			</center>
		</div>
		<div id="dialog-confirm-msg"></div>


		<div class="container-fluid text-center content_table_division" style="width:60%;">
			<center>
				<input type="button" id="save_attendance_b" class="buttons" value="Save">
				<input type="button" id="add_more_items_b" class="buttons" style="float:right;" value="Add More">
			</center>
		</div>
	</div>
</body>
<!-- Page Content -->
