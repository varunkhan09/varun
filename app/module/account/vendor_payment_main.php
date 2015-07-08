<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";


	$associated_vendors_array = array();
	mysql_select_db($vendorshop_database);
	$query = "select b.shop_name, b.email, b.entity_id from pos_associated_vendors_entity a right join pos_shop_entity b on a.vendor_shop_id = b.entity_id where a.vendor_shop_id is not NULL and a.self_shop_id = $shop_id";
	$result = mysql_query($query);
	echo mysql_error();
	while($row = mysql_fetch_assoc($result))
	{
		$temp_vendor_shop_name = $row['shop_name'];
		$temp_vendor_shop_email = $row['email'];
		$temp_vendor_shop_id = $row['entity_id'];

		switch($temp_vendor_shop_id)
		{
			case $shop_id:
			{
				$temp_vendor_shop_name = "Self";
				break;
			}

			case $flaberry_self_shop_id:
			{
				$temp_vendor_shop_name = $flaberry_self_shop_name;
				break;
			}

			default:
			{
				break;
			}
		}

		$associated_vendors_array[$temp_vendor_shop_id]['name'] = $temp_vendor_shop_name;
		$associated_vendors_array[$temp_vendor_shop_id]['email'] = $temp_vendor_shop_email;
	}
	$associated_vendors_id_array = array_keys($associated_vendors_array);
?>
	<style>
		.datepicker-dropdown
		{
			top: 185px !important;
			width:auto;
		}
	</style>
	<div class="row account_main_content_div text-center">
		<div class="container-fluid">
			<table class="table table-bordered account_main_control_table">
				<tr>
					<td>
						<input type="textbox" id="account_main_date1_id" class="datepicker" value="" placeholder="Start Date">
					</td>


					<td>
						<input type="textbox" id="account_main_date2_id" class="datepicker" value="" placeholder="End Date">
					</td>


					<td>
						<select id='report_mode_dd'>
							<option value='receivable'>Receivable</option>
							<option value='payable'>Payable</option>
						</select>
					</td>


					<td>
						<select id='vendor_dd'>
							<option value=''>Select Vendor</option>
							<?php
								foreach($associated_vendors_id_array as $each_vendor)
								{
									echo "<option value='$each_vendor'>".$associated_vendors_array[$each_vendor]['name']."</option>";
								}
							?>
						</select>
					</td>


					<td>
						<input type="button" class="buttons" id="account_main_generate_report_button_id" value="Generate Report">
					</td>
				</tr>
			</table>
		</div>


		<div class="container-fluid" id="result_div"></div>

		<div id="dialog-confirm-msg"></div>
	</div>
	</body>
</html>
