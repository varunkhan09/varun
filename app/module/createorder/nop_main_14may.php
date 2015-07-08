<?php
	ob_start();
	//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/app/module/common/header.php";
	include "/var/www/varun/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($magento_database);
?>

<?php
/* THIS CODE FETCHES ALL CUSTOMER'S FULL NAME AND EMAIL ID */
if(!isset($_REQUEST['flag']))
{
	$all_users_array = array();
	$users = mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('email')->addAttributeToSelect('firstname')->addAttributeToSelect('lastname');
	$result = '';
	foreach ($users as $user)
	{
		$users_info = $user->getData();
		$result .= $users_info['firstname']." ".$users_info['lastname']."(".$users_info['email'].")"."<>";
	}
	$result = rtrim($result, "<>");
	echo "<input type='hidden' id='all_users' value=\"".$result."\">";
/* THIS CODE FETCHES ALL CUSTOMER'S FULL NAME AND EMAIL ID */
?>
<style>
	.datepicker
	{
		top : 640px !important;
	}

	.tt-hint
	{
		width:260px;
	}
</style>


<form method='post' style='border:0px; margin:0px; display:inline; float:left;'>
	<div id='main_div' style='float:left; width:86%; margin:140px 0% 10% 6%; background-color:#F1F1F1; padding:0px 10px 0px 10px;'>
		<div id='main_div_row1' style='float:left; width:100%;'>
			<div id='main_div_row1_1' style='float:left; width:33%;'>
				<b>Employee taking the order : </b><?php echo $user_name; ?>
			</div>

			<div id='main_div_row1_2' style='float:left; width:33%;'>
				<b>Order Type:</b>
				<select id='ordertype_dd' name='ordertype_dd' class='thisbuttonfont'>
					<option value='Select' selected='selected'>---Select---</option>
					<?php
					mysql_select_db($custom_database);
					$query = "select name from corporate_companies_order_data where active=1";
					$result = mysql_query($query);
					echo mysql_error();
					while($row = mysql_fetch_row($result))
					{
						echo "<option value='".$row[0]."'>".$row[0]."</option>";
					}
					mysql_select_db($magento_database);
					?>
				</select>
			</div>

			<div id='main_div_row1_3' style='float:left; width:33%;'>
				<b>Order Source:</b>
				<input type='radio' id='ordersource_walkin_id' name='ordersource_radio' value='Walk-In' checked='checked'><label for='ordersource_walkin_id'>Walk-In</label>
				<input type='radio' id='ordersource_phone_id' name='ordersource_radio' value='Phone'><label for='ordersource_phone_id'>Phone</label>
			</div>
		</div>

















	<div style='float:left; width:100%; padding:15px 0px 0px 0px; border-bottom:3px solid #00688B;'>
		<div id='main_div_row2' style='float:left; width:48%; border-right:3px solid #00688B; height:400px;' class='hoverable'>
			<div id='main_div_row2_1' style='float:left; width:100%; text-align:left;'>
				<label style='font-size:24px; color:#009ACD;'>Customer Information</label>
			</div>

			<div id='main_div_row6_1_2' style='float:left; width:98%; height:3px; background-color:#00688B;'></div>

			<div id='main_div_row2_2' style='float:right; width:98%; text-align:left;'>
				<input type='radio' id='personal_account_id' name='account_radio' value='Personal Account' checked='checked'><label for='personal_account_id' style='font-size:18px; font-weight:bold;'>Personal Account</label>
				<input type='radio' id='company_account_id' name='account_radio' value='Company Account'><label for='company_account_id' style='font-size:18px; font-weight:bold;'>Company Account</label>
			</div>

			<div id='main_div_row3_1' style='float:left; width:98%; text-align:left; border-bottom-color: #00688B; border-bottom-width: 1px; border-bottom-style: solid;'>
				<div style='display:inline; float:left; width:18%;'>Search</div><input type='textbox' id='search_sender_t' name='search_sender_t' style='width:260px;'>&nbsp;&nbsp;<input type='button' id='search_sender_b' value='Load Details' class='thisbuttonfont buttons'>
			</div>

			<div id='main_div_row3_2_1_1_1' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>First Name<sup style='color:red; font-size:12px;'>*</sup></div><input type='textbox' id='sender_first_name_t' name='sender_first_name_t' style='width:50%;' required>
			</div>

			<div id='main_div_row3_2_1_1_2' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>Last Name<sup style='color:red; font-size:12px;'>*</sup></div><input type='textbox' id='sender_last_name_t' name='sender_last_name_t' style='width:50%;' required>
			</div>

			<div id='main_div_row3_2_2_1_1' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>Phone<sup style='color:red; font-size:12px;'>*</sup></div><input type='textbox' id='sender_phone_t' name='sender_phone_t' style='width:50%;' required>
			</div>

			<div id='main_div_row3_2_2_3_3' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>Email</div><input type='textbox' name='sender_email' id='sender_email' style='width:50%;'>
			</div>

			<div id='main_div_row3_2_1_2' style='float:left; width:98%; vertical-align:middle;'>
				<div style='display:inline; float:left; width:18%;'>Address<sup style='color:red; font-size:12px;'>*</sup></div><textarea id='sender_address_line_1_t' name='sender_address_line_1_t' style='width:50%;' required></textarea><input type='hidden' id='sender_address_line_2_t' name='sender_address_line_2_t'>
			</div>
	
			<div id='main_div_row3_2_2_3_1' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>Zip<sup style='color:red; font-size:12px;'>*</sup></div><input type='textbox' id='sender_zip_t' name='sender_zip_t' style='width:50%;' required>
			</div>

			<div id='main_div_row3_2_1_3_1' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>City<sup style='color:red; font-size:12px;'>*</sup></div>
				<?php
					mysql_select_db($custom_database);
					$query = "select distinct city from pincode_product_map order by city";
					$result = mysql_query($query);
					echo "<select id='sender_city_t' name='sender_city_t' class='thisbuttonfont' required style='width:50%;'>";
					while($row = mysql_fetch_row($result))
					{
						$temp = strtolower($row[0]);
						$temp = ucwords($temp);
						echo "<option value='".$temp."'>".$temp."</option>";
					}
					echo "</select>";
					mysql_select_db($magento_database);
				?>
			</div>

			<div id='main_div_row3_2_1_3_2' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>State<sup style='color:red; font-size:12px;'>*</sup></div>
				<select id='sender_state_dd' name='sender_state_dd' class='thisbuttonfont' style='width:50%;'>
					<option value="Andaman and Nicobar Islands" selected='selected'>Andaman and Nicobar Islands</option>
					<option value="Andhra Pradesh">Andhra Pradesh</option>
					<option value="Arunachal Pradesh">Arunachal Pradesh</option>
					<option value="Assam">Assam</option>
					<option value="Bihar">Bihar</option>
					<option value="Chandigarh">Chandigarh</option>
					<option value="Chhattisgarh">Chhattisgarh</option>
					<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
					<option value="Daman and Diu">Daman and Diu</option>
					<option value="Delhi">Delhi</option>
					<option value="Goa">Goa</option>
					<option value="Gujarat">Gujarat</option>
					<option value="Haryana">Haryana</option>
					<option value="Himachal Pradesh">Himachal Pradesh</option>
					<option value="Jammu and Kashmir">Jammu and Kashmir</option>
					<option value="Jharkhand">Jharkhand</option>
					<option value="Karnataka">Karnataka</option>
					<option value="Kerala">Kerala</option>
					<option value="Lakshadweep">Lakshadweep</option>
					<option value="Madhya Pradesh">Madhya Pradesh</option>
					<option value="Maharashtra">Maharashtra</option>
					<option value="Manipur">Manipur</option>
					<option value="Meghalaya">Meghalaya</option>
					<option value="Mizoram">Mizoram</option>
					<option value="Nagaland">Nagaland</option>
					<option value="Orissa">Orissa</option>
					<option value="Pondicherry">Pondicherry</option>
					<option value="Punjab">Punjab</option>
					<option value="Rajasthan">Rajasthan</option>
					<option value="Sikkim">Sikkim</option>
					<option value="Tamil Nadu">Tamil Nadu</option>
					<option value="Tripura">Tripura</option>
					<option value="Uttaranchal">Uttaranchal</option>
					<option value="Uttar Pradesh">Uttar Pradesh</option>
					<option value="West Bengal">West Bengal</option>
				</select>
			</div>

			<div id='main_div_row3_2_2_3_2' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>Country</div>
				<select id='sender_country_dd' name='sender_country_dd' class='thisbuttonfont' style='width:50%;'>
					<option value='IN' selected='selected'>India</option>
				</select>
			</div>

			<div id='main_div_row3_2_2_1_2' style='float:left; width:98%;'>
				<div style='display:inline; float:left; width:18%;'>Company</div><input type='textbox' id='sender_company_t' name='sender_company_t' style='width:50%;'>
			</div>
		</div>










		<div id='main_div_row3_3' style='float:left; width:48%; text-align:left; padding-left:1%; height:400px;' class='hoverable'>
			<div id='main_div_row3_3_1' style='float:left; width:100%; text-align:left;'>
				<label style='font-size:24px; color:#009ACD;'>Recipient Information</label>
			</div>

			<div id='main_div_row6_1_2' style='float:left; width:100%; height:3px; background-color:#00688B;'></div>

			<div id='main_div_row3_3_2' style='float:right; width:100%; text-align:left;'>
				<input type='radio' id='recipient_address_id' name='recipient_radio' class='recipient_radio_class' value='Enter Recipient Address' checked='checked'><label for='recipient_address_id' style='font-size:18px; font-weight:bold;'>Enter Recipient Address</label>
				<input type='radio' id='common_address_id' name='recipient_radio' class='recipient_radio_class' value='Use Common Address'><label for='common_address_id' style='font-size:18px; font-weight:bold;'>Use Common Address</label>
			</div>
			<div id='division_to_disable'>
				<div id='main_div_row4_1' style='float:left; width:100%; text-align:left; border-bottom-color: #00688B; border-bottom-width: 1px; border-bottom-style: solid;'>
					<div style='display:inline; float:left; width:18%;'>Search</div><input type='textbox' id='search_recipient_t' name='search_recipient_t' style='width:50%;'>&nbsp;&nbsp;<input type='button' id='search_sender_b' value='Load Details' class='thisbuttonfont buttons'>
				</div>


				<div id='main_div_row4_2_1_1_1' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>First Name<sup style='color:red; font-size:12px;'>*</sup></div><input type='textbox' id='recipient_first_name_t' name='recipient_first_name_t' style='width:50%;' required>
				</div>

				<div id='main_div_row4_2_1_1_2' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>Last Name<sup style='color:red; font-size:12px;'>*</sup></div><input type='textbox' id='recipient_last_name_t' name='recipient_last_name_t' style='width:50%;' required>
				</div>

				<div id='main_div_row4_2_2_1_1' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>Phone<sup style='color:red; font-size:12px;'>*</sup></div><input type='textbox' id='recipient_phone_t' name='recipient_phone_t' style='width:50%;' required>
				</div>

				<div id='main_div_row4_2_1_2' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>Address<sup style='color:red; font-size:12px;'>*</sup></div><textarea id='recipient_address_line_1_t' name='recipient_address_line_1_t' style='width:50%;' required></textarea><input type='hidden' id='recipient_address_line_2_t' name='recipient_address_line_2_t'>
				</div>
				
				<div id='main_div_row4_2_2_3_1' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>Zip<sup style='color:red; font-size:12px;'>*</sup></div><input type='textbox' id='recipient_zip_t' name='recipient_zip_t' style='width:50%;' required>
				</div>
					
				<div id='main_div_row4_2_1_3_1' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>City<sup style='color:red; font-size:12px;'>*</sup></div>
					<?php
					mysql_select_db($custom_database);
					$query = "select distinct city from pincode_product_map order by city";
					$result = mysql_query($query);
					echo "<select id='recipient_city_t' name='recipient_city_t' class='thisbuttonfont' required style='width:50%;'>";
					while($row = mysql_fetch_row($result))
					{
						$temp = strtolower($row[0]);
						$temp = ucwords($temp);
						echo "<option value='".$temp."'>".$temp."</option>";
					}
					echo "</select>";
					mysql_select_db($magento_database);
					?>
				</div>

				<div id='main_div_row4_2_1_3_2' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>State<sup style='color:red; font-size:12px;'>*</sup></div>
					<select id='recipient_state_dd' name='recipient_state_dd' class='thisbuttonfont' style='width:50%;'>
						<option value="Andaman and Nicobar Islands" selected='selected'>Andaman and Nicobar Islands</option>
						<option value="Andhra Pradesh">Andhra Pradesh</option>
						<option value="Arunachal Pradesh">Arunachal Pradesh</option>
						<option value="Assam">Assam</option>
						<option value="Bihar">Bihar</option>
						<option value="Chandigarh">Chandigarh</option>
						<option value="Chhattisgarh">Chhattisgarh</option>
						<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
						<option value="Daman and Diu">Daman and Diu</option>
						<option value="Delhi">Delhi</option>
						<option value="Goa">Goa</option>
						<option value="Gujarat">Gujarat</option>
						<option value="Haryana">Haryana</option>
						<option value="Himachal Pradesh">Himachal Pradesh</option>
						<option value="Jammu and Kashmir">Jammu and Kashmir</option>
						<option value="Jharkhand">Jharkhand</option>
						<option value="Karnataka">Karnataka</option>
						<option value="Kerala">Kerala</option>
						<option value="Lakshadweep">Lakshadweep</option>
						<option value="Madhya Pradesh">Madhya Pradesh</option>
						<option value="Maharashtra">Maharashtra</option>
						<option value="Manipur">Manipur</option>
						<option value="Meghalaya">Meghalaya</option>
						<option value="Mizoram">Mizoram</option>
						<option value="Nagaland">Nagaland</option>
						<option value="Orissa">Orissa</option>
						<option value="Pondicherry">Pondicherry</option>
						<option value="Punjab">Punjab</option>
						<option value="Rajasthan">Rajasthan</option>
						<option value="Sikkim">Sikkim</option>
						<option value="Tamil Nadu">Tamil Nadu</option>
						<option value="Tripura">Tripura</option>
						<option value="Uttaranchal">Uttaranchal</option>
						<option value="Uttar Pradesh">Uttar Pradesh</option>
						<option value="West Bengal">West Bengal</option>
					</select>
				</div>

				<div id='main_div_row4_2_2_3_2' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>Country<sup style='color:red; font-size:12px;'>*</sup></div>
					<select id='recipient_country_dd' name='recipient_country_dd' class='thisbuttonfont' style='width:50%;'>
						<option value='IN' selected='selected'>India</option>
					</select>
				</div>

				<div id='main_div_row4_2_2_1_2' style='float:left; width:100%;'>
					<div style='display:inline; float:left; width:18%;'>Company</div><input type='textbox' id='recipient_company_t' name='recipient_company_t' style='width:50%;'>
				</div>
			</div>
		</div>

		<div id='main_div_row4_3' style='float:left; width:100%; text-align:left; padding:15px 0px 0px 0px;'>
			<div id='main_div_row4_3_1' style='float:left; width:50%; text-align:left;'>
				<label style='font-size:24px; color:#009ACD;'>Merchandise</label>
			</div>
		</div>
	</div>















		<div id='main_div_row5' style='float:left; width:100%; padding:10px 0px 0px 0px;'>
			<div id='main_div_row5_1' style='float:left; width:100%; text-align:left; background-color:#F1F1F1;'>
				<table style='width:100%; text-align:center;' id='merchandise_table' cellspacing="1">
					<tr style='background-color:#009ACD; color:white; font-size:20px;'>
						<td>Product Name</td>
						<td>Enter Product Below or Select from Catalog</td>
						<td>Classification</td>
						<td>Quantity</td>
						<td>Item Price</td>
						<td>Price</td>
						<td>Remove</td>
					</tr>

					<tr id='product_row_1' class='hoverable'>
						<td class='thistd'>
							<input type='textbox' id='product_name_1' name='product_name_1' style='width:100%;'>
						</td>
						<td class='thistd'>
							<textarea id='product_description_textarea_1' name='product_description_textarea_1' class='product_description_textarea' style='width:100%; height:90px;' required></textarea>
						</td>
						<td class='thistd'>
							<select id='classification_dd_1' name='classification_dd_1' class='classification_dd thisbuttonfont'>
								<option value='Full' selected='selected'>---Select---</option>
								<option value='dark-ivory'>Dark Ivory</option>
								<option value='plant'>Plants</option>
								<option value='flower'>Flowers</option>
								<option value='combo'>&nbsp;&nbsp;&nbsp;&nbsp;- Combo</option>
								<option value='basket'>&nbsp;&nbsp;&nbsp;&nbsp;- Basket</option>
								<option value='vase'>&nbsp;&nbsp;&nbsp;&nbsp;- Vase</option>
								<option value='chocolate'>Chocolates</option>
								<option value='cake'>Cakes</option>
							<?php
							/*
							$categories = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('id')->addAttributeToSelect('name')->addAttributeToSelect('is_active');
							foreach ($categories as $category)
							{
							    if ($category->getIsActive() && $category->getName() != "Default Category") 
								{
									$name = $category->getName();
									echo "<option value='$name'>$name</option>";
								}
							}
							*/
							?>
							</select>
							<br><br>
							<input type='button' id='catalog_button_1' name='catalog_button_1' class='catalog_button thisbuttonfont buttons' value='View Full Catalog'>
						</td>
						<td class='thistd'>
							<input type='number' id='quantity_t_1' name='quantity_t_1' class='quantity_t' style='width:60px;' min='0' value='1' required>
						</td>
						<td class='thistd'>
							<input type='number' id='price_t_1' name='price_t_1' class='price_t' style='width:60px;' min='0' value='0' required>
						</td>
						<td class='thistd'>
							<input type='number' id='total_price_t_1' name='total_price_t_1' class='total_price_t' style='width:60px;' min='0' value='0' disabled='disabled'>
							<input type='hidden' id='product_id_1' name='product_id_1'  value='0'>
						</td>
						<td class='thistd'>
							<input type='button' value='Remove' id='remove_product_1' class='remove_c thisbuttonfont buttons'>
						</td>
					</tr>
				</table>
			</div>

			<div id='main_div_row5_2' style='float:left; width:100%; text-align:center; padding:5px 0px 0px 0px;'><input type='button' value='Add New Item' id='add_new_item_b' class='thisbuttonfont buttons'>
			</div>
		</div>










		<div id='main_div_row5point1' style='float:left; text-align:right; width:100%; padding:10px 0px 0px 0px;'>
			Grand Total : <input type='number' name='grant_total_t' id='grand_total_t' value='0' style='width:70px;' required disabled='disabled'>
		</div>









		<div id='main_div_row6' style='float:left; width:100%; padding:10px 0px 0px 0px; text-align:left;'>
			<div id='main_div_row6_1' style='float:left; width:50%; height:200px;' class='hoverable'>
				<div id='main_div_row6_1_1' style='float:left; width:100%;'>
					<label style='font-size:24px; color:#009ACD;'>Delivery Details</label>
				</div>

				<div id='main_div_row6_1_2' style='float:left; width:100%; height:3px; background-color:#00688B;'></div>

				<div id='main_div_row6_1_3' style='float:left; width:100%;'>
					<div id='main_div_row6_1_3_1' style='float:left; width:100%;'>
						<div id='main_div_row6_1_3_1_1' style='float:left; width:47%;'>Date : <input type='textbox' class='datepicker_nopmain' id='delivery_details_date_id' name='delivery_details_date_id' required></div>
						

						<div id='main_div_row6_1_3_2_2' style='float:left; width:50%;'>
							Delivery : 
							<select id='delivery_type_dd' name='delivery_type_dd' class='thisbuttonfont'>
								<option value='Regular Delivery_0' selected='selected'>Regular Delivery</option>
								<option value='Midnight Delivery_300'>Midnight Delivery</option>
							</select>
						</div>
					</div>

					<div id='main_div_row6_1_3_3' style='float:left; width:100%;'>
						Special Instructions
						<br>
						<textarea id='delivery_details_special_instructions' name='delivery_details_special_instructions' style='width:84%; height:80px;'></textarea>
					</div>
				</div>
			</div>

			<div id='main_div_row6_2' style='float:right; width:50%; height:200px;' class='hoverable'>
				<div id='main_div_row6_2_1' style='float:left; width:100%;'>
					<label style='font-size:24px; color:#009ACD;'>Enclosure Card</label>
				</div>

				<div id='main_div_row6_2_2' style='float:left; width:100%; height:3px; background-color:#00688B;'></div>

				<div id='main_div_row6_2_3' style='float:left; width:100%;'>
					<div id='main_div_row6_2_3_1' style='float:left; width:100%;'>
						Type : 
						<select id='delivery_details_card_type_dd' name='delivery_details_card_type_dd' class='thisbuttonfont' style='width:15%;'></select>
						To :
						<input type='textbox' id='delivery_details_card_to_t' name='delivery_details_card_to_t' style='width:20%;' placeholder="To">
						From : 
						<input type='textbox' id='delivery_details_card_from_t' name='delivery_details_card_from_t' style='width:20%;' placeholder="From">
					</div>

					<div id='main_div_row6_2_3_1' style='float:left; width:100%;'>
						<select id='delivery_details_card_message_type_dd' name='delivery_details_card_message_type_dd' style='width:80%;' class='thisbuttonfont'>
							<option value='Select a Card Message' selected='selected'>Select a Card Message</option>
						</select>
					</div>

					<div id='main_div_row6_2_3_1' style='float:left; width:100%;'>
						Message
						<br>
						<textarea id='delivery_details_card_message' name='delivery_details_card_message' style='width:80%; height:80px;'></textarea>
					</div>
				</div>
			</div>
		</div>



		<div id='main_div_row7' style='float:left; width:100%; padding:10px 0px 0px 0px; text-align:left; text-align:center;'>
			<input type='submit' value='Submit' id='submit_b' class='thisbuttonfont buttons'>
		</div>
	</div>
	<input type='hidden' id='flag' name='flag' value='1'>
	<input type='hidden' id='number_of_products_in_order' name='number_of_products_in_order' value='1'>
</form>
<!-- </center> -->
<?php
}
else
{
	if($_REQUEST['flag'] == '1')
	{
		echo "<div id='main_div' style='float:left; width:86%; margin:140px 0% 10% 6%; background-color:#F1F1F1; padding:0px 10px 0px 10px;'>";
		$custom_product_array = array();
		$counter = 0;
		for($x=1; $x<6; $x++)
		{
			$sku = "custom:custom-product-".$x;
			//echo $sku;
			$original_custom_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
			//var_dump($original_custom_product);
			$original_custom_product_id = Mage::getModel('catalog/product')->getIdBySku($sku);
				//$original_custom_product->getId();
			$original_custom_product_sku = $sku;
			$custom_product_array[$counter]['id'] = $original_custom_product_id;
			$custom_product_array[$counter]['sku'] = $original_custom_product_sku;
			$counter++;
		}

		$employee = $_REQUEST['employee_dd'];
		$order_type = $_REQUEST['ordertype_dd'];
		$order_source = $_REQUEST['ordersource_radio'];
		$sender_search = $_REQUEST['search_sender_t'];
		$recipient_search = $_REQUEST['search_recipient_t'];
		$recipient_address_type = $_REQUEST['recipient_radio'];


		//All Details of SENDER...
		$sender_account_type = $_REQUEST['account_radio'];
		$sender_first_name = $_REQUEST['sender_first_name_t'];
		$sender_last_name = $_REQUEST['sender_last_name_t'];
		$sender_address_line_1 = $_REQUEST['sender_address_line_1_t'];
		$sender_address_line_2 = $_REQUEST['sender_address_line_2_t'];
		$sender_postal_code = $_REQUEST['sender_zip_t'];
		$sender_city = $_REQUEST['sender_city_t'];
		$sender_state = $_REQUEST['sender_state_dd'];
		$sender_country = $_REQUEST['sender_country_dd'];
		$sender_phone = $_REQUEST['sender_phone_t'];
		$sender_company = $_REQUEST['sender_company_t'];
		$sender_email = $_REQUEST['sender_email'];
		//All Details of SENDER...


		//All Details of RECIPIENT...
		$recipient_first_name = $_REQUEST['recipient_first_name_t'];
		$recipient_last_name = $_REQUEST['recipient_last_name_t'];
		$recipient_address_line_1 = $_REQUEST['recipient_address_line_1_t'];
		$recipient_address_line_2 = $_REQUEST['recipient_address_line_2_t'];
		$recipient_postal_code = $_REQUEST['recipient_zip_t'];
		$recipient_city = $_REQUEST['recipient_city_t'];
		$recipient_state = $_REQUEST['recipient_state_dd'];
		$recipient_country = $_REQUEST['recipient_country_dd'];
		$recipient_phone = $_REQUEST['recipient_phone_t'];
		$recipient_company = $_REQUEST['recipient_company_t'];
		//All Details of RECIPIENT...

		if($recipient_address_type == "Use Common Address")
		{
			$recipient_city = $sender_city;
		}


		//All Details of DELIVERY...
		$delivery_date = $_REQUEST['delivery_details_date_id'];

		$delivery_date_array = explode("/", $delivery_date);
		$delivery_date = $delivery_date_array[2]."-".$delivery_date_array[0]."-".$delivery_date_array[1];
		$delivery_date_array = explode("-", $delivery_date);
		
		$delivery_type = $_REQUEST['delivery_type_dd'];
		$delivery_special_instructions = $_REQUEST['delivery_details_special_instructions'];
		$delivery_message_card_type = $_REQUEST['delivery_details_card_type_dd'];
		$delivery_message_card_message_type = $_REQUEST['delivery_details_card_message_type_dd'];
		$delivery_message_card_to = $_REQUEST['delivery_details_card_to_t'];
		$delivery_message_card_from = $_REQUEST['delivery_details_card_from_t'];
		$delivery_message_card_message = $_REQUEST['delivery_details_card_message'];
		//All Details of DELIVERY...


		//All Details of ALL PRODUCTS...
		$product_total_number = $_REQUEST['number_of_products_in_order'];
		$normal_product_details_array = array();
		$custom_product_details_array = array();
		$custom_product_counter = 0;
		for($x=1; $x<=$product_total_number; $x++)
		{
			if(isset($_REQUEST['product_id_'.$x]))
			{
				$temp_product_id = $_REQUEST['product_id_'.$x];
				
				if($temp_product_id != "0")
				{
					$temp_product = Mage::getModel('catalog/product')->load($temp_product_id);
					$temp_sku = $temp_product->getSku();
					$temp_vendor_description = $temp_product->getVendorDescription();
					$temp_product_price = $temp_product->getPrice();

					$normal_product_details_array[$temp_product_id]['quantity'] = $_REQUEST['quantity_t_'.$x];
					$normal_product_details_array[$temp_product_id]['sku'] = $temp_sku;
					$normal_product_details_array[$temp_product_id]['unitprice'] = $temp_product_price;
					$normal_product_details_array[$temp_product_id]['vendordescription'] = $temp_vendor_description;
				}
				else
				{
					$custom_product_details_array[$custom_product_counter]['vendordescription'] = $_REQUEST['product_description_textarea_'.$x];
					$custom_product_details_array[$custom_product_counter]['quantity'] = $_REQUEST['quantity_t_'.$x];
					$custom_product_details_array[$custom_product_counter]['unitprice'] = $_REQUEST['price_t_'.$x];
					$custom_product_details_array[$custom_product_counter]['sku'] = $temp_sku;
					$custom_product_counter++;
				}
			}
			else
			{
				//echo "I skipped row $x<br>";
				continue;
			}
		}

		/*
		echo "<br><br>Normal Products : ";
		var_dump($normal_product_details_array);
		echo "<br><br>Custom Products : ";
		var_dump($custom_product_details_array);
		echo "<br>";
		*/
		$normal_product_ids = array_keys($normal_product_details_array);
		$custom_product_ids = array_keys($custom_product_details_array);
		//All Details of ALL PRODUCTS...
		/*
		echo "<br><br>Normal Products IDs : ";
		var_dump($normal_product_ids);
		echo "<br><br>Custom Products IDs : ";
		var_dump($custom_product_ids);
		echo "<br>";
		*/
		$storeId = Mage::app()->getStore()->getId();
		$websiteId = Mage::app()->getWebsite()->getId();
		$quote = Mage::getModel('sales/quote')->setStoreId($storeId);
		if (!$quote)
		{
			Mage::log("Error in quote!");
			return;
		}

		$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
		$currency = Mage::getModel('directory/currency')->load($currency_code);
		$quote->setForcedCurrency($currency);


		$customer = Mage::getModel('customer/customer');
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
		//$email = 'varunkhan09@flaberry.com';
		$password = "nanotech";

		$customer->loadByEmail($sender_email);

		//var_dump($customer);
		//echo "<br><br>".$customer->getId()."<br><br>";


		if($customer->getId())
		{
			//echo "Hi, this email id exists.<br>";
		}
		else
		{
			//echo "This email ID is not existing.<br>";
			$customer->setEmail($sender_email);
			$customer->setFirstname('Testing');
			$customer->setLastname('Testing');
			$customer->setPassword($password);
		}

		$customer->save();
		$customer->setConfirmation(null);
		$customer->save();
		Mage::getSingleton('customer/session')->loginById($customer->getId());
		$quote->assignCustomer($customer);


		//$quote->setCustomerEmail($sender_email);
		//$quote->setCustomer($customer);
		//$quote->setSendCconfirmation(1);
		if (isset($_SERVER['REMOTE_ADDR'])) 
		{
			$quote->setRemoteIp($_SERVER['REMOTE_ADDR']);
		}




		if(strpos($delivery_type, 'Midnight Delivery') !== false)
		{
			//echo "<br>It is Midnight Delivery<br>";
			$shipping_method = "flatrate_flatrate";
		}
		else
		{
			//echo "<br>It is Regular Delivery<br>";
			$shipping_method = "freeshipping_freeshipping";
		}

		if($recipient_address_type == "Use Common Address")
		{
			$recipient_postal_code = $sender_postal_code;
		}
		




		$query = "insert into panelorderdetails (orderid, productid, productsku, productquantity, productunitprice, productvdescription, order_source, account_type, dod, shippingtype, recipient_pincode) values ";
		foreach($normal_product_ids as $normal_product_ids_inside_foreach)
		{
			//echo "<br><br>Product ID : $normal_product_ids_inside_foreach";
			$normal_product_inside_foreach = Mage::getModel('catalog/product')->load($normal_product_ids_inside_foreach);
			$normal_product_name_inside_foreach = $normal_product_inside_foreach->getName();
			$normal_product_options_inside_foreach = $normal_product_inside_foreach->getOptions();
		
			if(!empty($normal_product_options_inside_foreach))
			{
				//echo "<br>This product has custom options<br>";
				$product_info = array();
				$product_info['qty'] = $normal_product_details_array[$normal_product_ids_inside_foreach]['quantity'];
				$options = array();

				foreach($normal_product_options_inside_foreach as $each_option)
				{
					$option_label = $each_option->getTitle();
					$option_id = $each_option->getId();

					if(strpos($option_label, "Date of Delivery(DD/MM/YYYY)") !== false)
					{
						$options[$option_id]['year'] = $delivery_date_array[0];
						$options[$option_id]['month'] = $delivery_date_array[1];
						$options[$option_id]['day'] = $delivery_date_array[2];
						$product_info['options'][$option_id] = $options[$option_id];
					}
					else
					{
						if(strpos($option_label, "Shipping Type") !== false)
						{
							//echo "<br>I AM IN HERE!<br>Option ID : $option_id<br>Delivery Type from POST : $delivery_type<br>";
							$values = $each_option->getValues();
							foreach($values as $each_value)
							{
								//echo $each_value['title']."<br>";
								if(strpos($delivery_type, $each_value['title']) !== false)
								{
									//echo "<br>I FOUND A MATCH!";
									$product_info['options'][$option_id] = $each_value['option_type_id'];
									break;
								}
							}
							//echo "<br>";
						}
						else
						{
							if(strpos($option_label, "DELIVERY CITY") !== false)
							{
								$values = $each_option->getValues();
								$temp_array = array();
								foreach($values as $each_value)
								{
									$temp_array[$each_value['title']] = $each_value['option_type_id'];
								}
								
								$temp_array_keys = array_keys($temp_array);
								if(in_array($recipient_city, $temp_array_keys))
								{
									$product_info['options'][$option_id] = $temp_array[$recipient_city];
								}
								else
								{
									echo "<b>$normal_product_name_inside_foreach</b> can't be delivered in <b>$recipient_city</b>.<br>Order cannot be created.";
									exit();
								}
							}
						}
					}
				}
			}
			else
			{
				//echo "<br>This product does not has custom options<br>";
				//echo " I am in ELSE<br>";
				$product_info = array('qty'=>(int)$normal_product_details_array[$normal_product_ids_inside_foreach]['quantity']);
			}
			//echo "Product Quantity : ".$normal_product_details_array[$normal_product_ids_inside_foreach]['quantity']."<br>";
			$quote->addProduct($normal_product_inside_foreach, new Varien_Object($product_info));
			//echo "product_info<br>";
			//var_dump($product_info);
			//echo "<br>";
			$query .= "(varun, ".$normal_product_ids_inside_foreach.", '".$normal_product_details_array[$normal_product_ids_inside_foreach]['sku']."', ".$normal_product_details_array[$normal_product_ids_inside_foreach]['quantity'].", '".$normal_product_details_array[$normal_product_ids_inside_foreach]['unitprice']."', '".$normal_product_details_array[$normal_product_ids_inside_foreach]['vendordescription']."', '".$order_source."', '".$sender_account_type."', '".$delivery_date."', '".$delivery_type."', '".$recipient_postal_code."'), ";
		}

		//echo "<br><br>VARUN : $original_custom_product_id";

		$counter = 0;
		foreach($custom_product_ids as $custom_product_ids_inside_foreach)
		{
			//echo "<br><br>Product ID : $custom_product_ids_inside_foreach<br>";
			//echo "Custom Product Description : ".$custom_product_details_array[$custom_product_ids_inside_foreach]['vendordescription'];
			//echo "<br>Custom Product Unit Price : ".$custom_product_details_array[$custom_product_ids_inside_foreach]['unitprice'];
			//echo "<br>Custom Product SKU : ".$custom_product_details_array[$custom_product_ids_inside_foreach]['sku'];

			//echo $custom_product_array[$counter]['sku'];
			$product_id = Mage::getModel('catalog/product')->getIdBySku($custom_product_array[$counter]['sku']);


			$custom_product = Mage::getModel('catalog/product')->load($product_id);
			$custom_product->setData('vendor_description', $custom_product_details_array[$custom_product_ids_inside_foreach]['vendordescription']);
			$custom_product->setData('price', $custom_product_details_array[$custom_product_ids_inside_foreach]['unitprice']);
			if($custom_product->save())
			{
				$custom_product_inside_foreach = Mage::getModel('catalog/product')->load($product_id);
				$custom_product_options_inside_foreach = $custom_product_inside_foreach->getOptions();
				$product_info = array('qty'=>(int)$custom_product_details_array[$custom_product_ids_inside_foreach]['quantity']);

				$options = array();

				foreach($custom_product_options_inside_foreach as $each_option)
				{
					$option_label = $each_option->getTitle();
					$option_id = $each_option->getId();

					if(strpos($option_label, "Date of Delivery(DD/MM/YYYY)") !== false)
					{
						$options[$option_id]['year'] = $delivery_date_array[0];
						$options[$option_id]['month'] = $delivery_date_array[1];
						$options[$option_id]['day'] = $delivery_date_array[2];
						$product_info['options'][$option_id] = $options[$option_id];
					}
					else
					{
						if(strpos($option_label, "Shipping Type") !== false)
						{
							//echo "<br>I AM IN HERE!<br>Option ID : $option_id<br>Delivery Type from POST : $delivery_type<br>";
							$values = $each_option->getValues();
							foreach($values as $each_value)
							{
								//echo $each_value['title']."<br>";
								if(strpos($delivery_type, $each_value['title']) !== false)
								{
									//echo "<br>I FOUND A MATCH!";
									$product_info['options'][$option_id] = $each_value['option_type_id'];
									break;
								}
							}
							//echo "<br>";
						}
						else
						{
							if(strpos($option_label, "DELIVERY CITY") !== false)
							{
								$values = $each_option->getValues();
								$temp_array = array();
								foreach($values as $each_value)
								{
									$temp_array[$each_value['title']] = $each_value['option_type_id'];
								}
								$temp_array_keys = array_keys($temp_array);
								if(in_array($recipient_city, $temp_array_keys))
								{
									$product_info['options'][$option_id] = $temp_array[$recipient_city];
								}
								else
								{
									echo "<b>".$custom_product_details_array[$custom_product_ids_inside_foreach]['vendordescription']."</b> can't be delivered in <b>$recipient_city</b>.<br>Order cannot be created.";
									exit();
								}
							}
						}
					}
				}
			}
			else
			{
				echo "<br>Unable to save custom product <b>\"".$custom_product_details_array[$custom_product_ids_inside_foreach]['vendordescription']."\"</b>. Order cannot be created.";
				exit();
			}

			//var_dump($product_info);

			
		

			//echo "<br>Product Quantity : ".$custom_product_details_array[$custom_product_ids_inside_foreach]['quantity']."<br>";
			$quote->addProduct($custom_product_inside_foreach, new Varien_Object($product_info));



			$query .= "(varun, ".$custom_product_array[$counter]['id'].", '".$custom_product_array[$counter]['sku']."', ".$custom_product_details_array[$custom_product_ids_inside_foreach]['quantity'].", '".$custom_product_details_array[$custom_product_ids_inside_foreach]['unitprice']."', '".$custom_product_details_array[$custom_product_ids_inside_foreach]['vendordescription']."', '".$order_source."', '".$sender_account_type."', '".$delivery_date."', '".$delivery_type."', '".$recipient_postal_code."'), ";
			$counter++;
		}

		$billingAddressData = array(
			'customer_address_id' => '',
			'prefix' => '',
			'firstname' => $sender_first_name,
			'middlename' => '',
			'lastname' => $sender_last_name,
			'suffix' => '',
			'company' => $sender_company,
			'street' => array(
			'0' => $sender_address_line_1,
			'1' => ''
			),
			'city' => $sender_city,
			'country_id' => 'IN',
			'region' => $sender_state,
			'postcode' => $sender_postal_code,
			'telephone' => $sender_phone,
			'fax' => '',
			'vat_id' => '',
			'email' => $sender_email,
			'save_in_address_book' => 1
		);


		$shippingAddressData = array(
			'customer_address_id' => '',
			'prefix' => '',
			'firstname' => $recipient_first_name,
			'middlename' => '',
			'lastname' => $recipient_last_name,
			'suffix' => '',
			'company' => $recipient_company,
			'street' => array(
			'0' => $recipient_address_line_1,
			'1' => ''
			),
			'city' => $recipient_city,
			'country_id' => 'IN',
			'region' => $recipient_state,
			'postcode' => $recipient_postal_code,
			'telephone' => $recipient_phone,
			'fax' => '',
			'vat_id' => '',
			'save_in_address_book' => 1
		);

		if($recipient_address_type == "Use Common Address")
		{
			$shippingAddressData = $billingAddressData;
		}
		$billingAddress = $quote->getBillingAddress()->addData($billingAddressData);
		$shippingAddress = $quote->getShippingAddress()->addData($shippingAddressData);

		//$payment_method = 'cashondelivery';
		$payment_method = 'ccavenuepay';

		
		$shippingAddress->setCollectShippingRates(true)->collectShippingRates()->setShippingMethod($shipping_method)->setPaymentMethod($payment_method);
		$quote->getPayment()->importData(array('method' => $payment_method));
		$quote->collectTotals()->save();
		$service = Mage::getModel('sales/service_quote', $quote);
		$service->submitAll();
		$order = $service->getOrder();
		//echo "<br>I at least reached here once.";
		$varun_order = $order->getIncrementId();
		//echo "<br>I at least reached here twice.";
		//var_dump($varun_order);
		echo "<center><br>Created order is : ".$varun_order."<br></center>";
		//echo "<br>I at least reached here thrice.";
	
		$comment = 'This order is programatically added by '.$user_name;	
		$order->addStatusToHistory($order->getStatus(), $comment , false);
		//$order->save();
		//$order->addStatusToHistory($order->getStatus(), $delivery_special_instructions, false);
		$order->save();



		/* THIS CODE LOADS THE MESSAGE ON CARD */
		$order2 = Mage::getModel('sales/order')->loadByIncrementId($varun_order);
		$giftMessage = Mage::getModel('giftmessage/message'); 
		$giftMessage->setCustomerId($customer->getId());
		$giftMessage->setSender($delivery_message_card_from); 
		$giftMessage->setRecipient($delivery_message_card_to); 
		$giftMessage->setMessage($delivery_message_card_message); 
		$giftObj = $giftMessage->save(); 
		$order2->setGiftMessageId($giftObj->getId());
		$order2->save();
		/* THIS CODE LOADS THE MESSAGE ON CARD */


		/* THIS CODE SAVES THE SPECIAL INSTRUCTIONS IN CHECKOUT DATABASE TABLE */
		$onestep=Mage::getModel('onestepcheckout/onestepcheckout');
		$onestep->setSalesOrderId($order2->getId());
		$onestep->setMwCustomercommentInfo($delivery_special_instructions);
		$onestep->save();
		/* THIS CODE SAVES THE SPECIAL INSTRUCTIONS IN CHECKOUT DATABASE TABLE */
		

		mysql_select_db($custom_database);
		$query = str_replace("varun", $varun_order, $query);
		$query = rtrim($query, ", ");
		//echo "<br>$query";
		mysql_query($query) or die("<br><br>".mysql_error());


		unset($quote);
		unset($customer);
		unset($service);
		unset($order2);
		echo "</div>";
	}
}
?>
</body>
	<script>
	$(document).ready(function(){
		$(document).on('click', '.recipient_radio_class', function(){
			var value = $(this).val();
			//alert(value);
			if(value == "Use Common Address")
			{
				$('#division_to_disable *').prop('disabled', true);
				$('#division_to_disable *').css('opacity', "0.7");
			}
			else
			{
				$('#division_to_disable *').prop('disabled', false);
				$('#division_to_disable *').css('opacity', "1.0");
			}
		});
	});
	</script>
</html>
