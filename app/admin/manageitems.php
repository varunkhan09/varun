<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($vendorshop_database);
?>

	<div class="row manage_items_page_content text-center">
		<div class="container-fluid">	
			<div class="col-xs-6" id="added_items_div">Items Added : </div>
			<div class="col-xs-6" id="disabled_items_div">Items Disabled : </div>
		</div>

		<div class="container-fluid">
			<div class="col-xs-2 col-xs-offset-4">
				<input type='button' id='add_new_item_b' value="Add New Item" class="buttons">
			</div>

			<div class="col-xs-2">
				<input type='button' id='disable_item_b' value="Disable Item" class="buttons">
			</div>
		</div>





		<div id="add_new_item_div" class="container-fluid each_module_main_division">
			<div class="col-xs-2 col-xs-offset-4 text-right"><label class="normal_black_1">Select a Category : </label></div>
			<div class="col-xs-2 text-left">
				<select class="form-control" id="add_new_item_category_dd">
					<option value=''>Select</option>
					<?php
						$query = "select entity_id, item_category_name from pos_item_category_entity where is_active=1";
						$result = mysql_query($query);
						echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
						while($row = mysql_fetch_assoc($result))
						{
							echo "<option value='".$row['entity_id']."'>".$row['item_category_name']."</option>";
						}
					?>
				</select>
			</div>

			<div id="add_new_item_operation_div" class="container-fluid each_module_operate_division text-center">
				<div class="col-xs-2 col-xs-offset-4 text-right"><label class="normal_black_1">Enter Item Name : </label></div>
				<div class="col-xs-2 text-left"><input type="textbox" id="add_new_item_new_item_t" class="form-control" value=""></div>

				<div class="col-xs-2 col-xs-offset-4 text-right" style="margin-top:10px;"><label class="normal_black_1">Select Sub Category : </label></div>
				<div class="col-xs-2 text-left" style="margin-top:10px;"><select class="form-control" id="add_new_item_sub_category_dd"></select></div>
					
				<div class="col-xs-4 text-center col-xs-offset-4" style="margin-top:10px;"><input type="button" class="buttons" value="Add Item" id="add_new_item_add_item_b"></div>
			</div>
		</div>



		<div id="disable_item_div" class="container-fluid each_module_main_division">
			<div class="col-xs-2 col-xs-offset-4 text-right"><label class="normal_black_1">Select a Category : </label></div>
			<div class="col-xs-2 text-left">
				<select class="form-control" id="disable_item_category_dd">
					<option value=''>Select</option>
					<?php
						$query = "select entity_id, item_category_name from pos_item_category_entity where is_active=1";
						$result = mysql_query($query);
						while($row = mysql_fetch_assoc($result))
						{
							echo "<option value='".$row['entity_id']."'>".$row['item_category_name']."</option>";
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
