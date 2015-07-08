<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	//include 'vp_dbconfig.php';
	//include "vp_authorize.php";
	//include "stock/global_variables.php";
?>

<?php
	include "vp_footer.php";
?>

<div id='loading_div' style='display:none; position:fixed; width:100%; height:100%; text-align:center; vertical-align:middle; background-color:gray; opacity:0.5;'>
	<img src='http://www.theappmadeinheaven.com/resources/images/PleaseWait.gif' style='margin-top:20%; width:5%; height:10%;'>
</div>







<div class='base_div'>
	<div style='float:left; display:none; text-align:left; margin:5px 0px 0px 5px;'>
		<form method='POST' action='vp_logout.php' style='margin:0px; padding:0px;'><input type='submit' value='Logout' class='buttons'></form>
	</div>

	<img src="images/flaberry.png" style="display:none; height: 50px; width: 250px; margin-top: 10px;">
	<div style='float:right; display:none; text-align:right; position:fixed; right:0px; margin:0px 10px 0px 0px;'>
		<label style='color:#009ACD; font-size:18px;'>Unacknowledged Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='unacknowledged_orders'></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Acknowledged Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='acknowledged_orders'></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Shipped Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='shipped_orders'></label>
		<br>
		<label style='color:#009ACD; font-size:18px;'>Delivered Orders</label> : <label style='color:#009ACD; font-weight:bold; font-size:18px;' id='delivered_orders'></label>
		<br><br>
	</div>
	<!--
	<br>
	<br>
	<br>
	-->
	<!--
	<form method='POST' style='margin:0px; padding:0px;' id='filter_form'>
	<input type='hidden' name='filters_flag' value='1'>
	-->
	<table class='filter_table' cellspacing="0">
		<tr class='table_heading_row_small'>
			<td style='padding:0px;'>Select Delivery Date<label style='color:red; font-size:15px;'><sup>*</sup></label></td>
			<td style='padding:0px;'>Select Delivery Type</td>
			<td style='padding:0px;'>Select Order Status</td>
			<!-- <td style='padding:0px;'>Select by Vendor</td> -->
			<!-- <td style='padding:0px;'>Select by Delivery City</td> -->
			<td style='padding:0px;'>Load by Order ID</td>
			<td style='padding:0px;'>Load Orders</td>
		</tr>

		<tr style='height:40px;'>
			<td style='padding:0px;'><input type='textbox' id='filter_dod_t' name='filter_dod_t' class='datepicker'></td>

			<td style='padding:0px;'>
				<select name='filter_type_dd' id='filter_type_dd'>
				<option value='-1'>Select</option>
				<option value='Regular Delivery'>Regular Delivery</option>
				<option value='Midnight Delivery'>Midnight Delivery</option>
				</select>
			</td>

			<td style='padding:0px;'>
				<select name='filter_status_dd' id='filter_status_dd'>
					<option value='4'>All</option>
					<option value='0'>Orders to Accept</option>
					<option value='1'>Accepted Orders</option>
					<option value='2'>Shipped Orders</option>
					<option value='3'>Delivered Orders</option>
				</select>
			</td>


			<!--
			<td style='padding:0px;'>
				<select name='filter_vendor_dd' id='filter_vendor_dd'>
					<option value='-1'>Select</option>
					<?php
					/*
					$query = "select vendor_id, vendor_name from vendors";
					$result = mysql_query($query);

					while($row = mysql_fetch_assoc($result))
					{
						echo "<option value='".$row['vendor_id']."'>".$row['vendor_name']."</option>";
					}
					*/
					?>
				</select>
			</td>
			-->

			<!--
			<td style='padding:0px;'>
				<select name='filter_city_dd'>
					<option value='-1'>Select</option>
					<?php
					//$query = "select distinct city from pincode_product_map";
					//$result = mysql_query($query);

					//while($row = mysql_fetch_assoc($result))
					{
						//$temp_city = $row['city'];
						//$temp_city = strtolower($temp_city);
						//$temp_city = ucwords($temp_city);
						//echo "<option value='$temp_city'>$temp_city</option>";
					}
					?>
				</select>
			</td>
			-->


			<td style='padding:0px;'>
				<input type='textbox' name='filter_orderid_t' id='filter_orderid_t' placeholder='Enter Order ID'>
			</td>

			<td style='padding:0px;'><input type='button' id='filter_form_button' value='Load Orders' class='buttons'></td>
		</tr>
	</table>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class='orders_loader' id='orders_loader'></div>

</body>