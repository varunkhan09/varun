<?php
	ob_start();
	//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/app/module/common/header.php";
	//include "/var/www/varun/app/module/common/header.php";
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($magento_database);
?>
	<style>
		.datepicker-dropdown
		{
			top: 185px !important;
		}
	</style>
	<div class="row stock_report_main_content_div text-center">
		<div class="container-fluid">
			<table class="table table-bordered">
				<tr>
					<td><input type="textbox" id="stock_report_date1_id" class="datepicker" value="" placeholder="Start Date"></td>
					<td><input type="textbox" id="stock_report_date2_id" class="datepicker" value="" placeholder="End Date"></td>
					<td><input type="button" class="buttons" id="stock_report_generate_report_button_id" value="Generate Report"></td>
				</tr>
			</table>
		</div>


		<div class="container-fluid" id="result_div"></div>

	</div>
	</body>
</html>