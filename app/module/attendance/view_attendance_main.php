<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($vendorshop_database);

	$query = "select firstname, lastname, entity_id from pos_user_entity where shop_id=$shop_id";
	$result = mysql_query($query);
?>

	<style>
		.datepicker-dropdown
		{
			top: 185px !important;
		}

		.table
		{
			margin-left: 15%;
			width: 70%;
		}
	</style>

	<div class="row add_stock_page_content">
<!-- 		
		<div class="container-fluid text-center" style="margin-top:20px;">
			<select id="employee_dd">
			<option value="All">All</option>
			<?php
			/*
				while($row = mysql_fetch_assoc($result))
				{
					echo "<option value='".$row['entity_id']."'>".$row['firstname']." ".$row['lastname']."</option>";
				}
			*/
			?>
			</select>
		</div>
 -->
		<div class="container-fluid text-center" style="margin-top:20px;">
<!-- 
			<div class="col-xs-2">
				<input type="textbox" id="start_date_t" class="datepicker" value="" placeholder="Start Date">
			</div>

			<div class="col-xs-2">
				<input type="textbox" id="end_date_t" class="datepicker" value="" placeholder="End Date">
			</div>
 -->
			<div class="col-xs-2 col-xs-offset-2">
				<select id="month_dd">
					<option value="">Select a Month</option>
					<option value='01'>January</option>
					<option value='02'>February</option>
					<option value='03'>March</option>
					<option value='04'>April</option>
					<option value='05'>May</option>
					<option value='06'>June</option>
					<option value='07'>July</option>
					<option value='08'>August</option>
					<option value='09'>September</option>
					<option value='10'>October</option>
					<option value='11'>November</option>
					<option value='12'>December</option>
				</select>
			</div>

			<div class="col-xs-2">
				<select id="employee_dd">
				<option value="">Select an Employee</option>
				<?php
					while($row = mysql_fetch_assoc($result))
					{
						echo "<option value='".$row['entity_id']."'>".$row['firstname']." ".$row['lastname']."</option>";
					}
				?>
				</select>
			</div>

			<div class="col-xs-2">
				<input type="textbox" id="shift_hours_t" value="" placeholder="Enter Shift Hours" style="width:100%;">
			</div>

			<div class="col-xs-2">
				<input type='button' class='buttons' value='Load Attendance' id='load_attendance_b'>
			</div>
		</div>


		<div id="load_results_div" style="margin-top:20px;"></div>

		
		<div id="dialog-confirm-msg"></div>
	</div>
</body>