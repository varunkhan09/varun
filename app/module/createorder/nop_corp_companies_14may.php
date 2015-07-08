<?php
	//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/global_variables.php";
	include "/var/www/varun/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($custom_database);
?>

<html>
<head></head>

<body>

<center>
<label style='color:orange; font-size:26px; font-weight:bold;'>Corporate Companies for Order Panel</label>
</center>
<br>
<br>
<?php
if(!isset($_REQUEST['flag']))
{
?>
	<form method='POST'>
		<center>
			<table style='text-align:center; border:2px solid orange;'>
				<tr style='background-color:orange; font-size:24px; color:white;'>
					<td>Name</td>
					<td>Phone</td>
					<td>Email ID</td>
					<td>AddressLine1</td>
					<td>AddressLine2</td>
					<td>City</td>
					<td>State</td>
					<td>Zip Code</td>
					<td>Save</td>
				</tr>

				<tr>
					<td><input type='textbox' name='name_t' id='name_t' style='width:120px;' placeholder='Enter Company Name'></td>
					<td><input type='textbox' name='phone_t' id='phone_t' style='width:110px;' placeholder='Enter Company Phone Number'></td>
					<td><input type='textbox' name='email_t' id='email_t' style='width:120px;' placeholder='Enter Company Email'></td>
					<td><textarea name='address1_t' id='address1_t' style='height:100px; width:100px;' placeholder='Address Line 1'></textarea></td>
					<td><textarea name='address2_t' id='address2_t' style='height:100px; width:100px;' placeholder='Address Line 2'></textarea></td>
					<td>
					<?php
						$query = "select distinct city from pincode_product_map order by city";
						$result = mysql_query($query);
						echo "<select id='city_t' name='city_t' required>";
						while($row = mysql_fetch_row($result))
						{
							$temp = strtolower($row[0]);
							$temp = ucwords($temp);
							echo "<option value='".$temp."'>".$temp."</option>";
						}
						echo "</select>";
					?>
					</td>
					<td>
						<select id='state_t' name='state_t'>
							<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
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

					</td>
					<td><input type='textbox' name='zip_t' id='zip_t' style='width:60px;' placeholder='ZIP Code'></td>
					<td><input type='hidden' name='flag' value='1'><input type='submit' value='Save'></td>
				</tr>
			</table>
		</center>
	</form>

	<br><br><br>

	<center>
		<table style='text-align:center; border:2px solid orange; width:70%;'>
			<tr style='background-color:orange; font-size:24px; color:white;'>
				<td>Name</td>
				<td>Phone</td>
				<td>Email ID</td>
				<td>Edit</td>
				<td>Delete</td>
			</tr>
			<?php
			$query = "select * from corporate_companies_order_data where active=1";
			$result = mysql_query($query);
			$style1 = "background-color:#CAE1FF;";
			$style2 = "background-color:#F0E68C;";
			$counter = 0;
			while($row = mysql_fetch_assoc($result))
			{
				if($counter%2 == "0")
				{
					echo "<tr style='$style1'>";
				}
				else
				{
					echo "<tr style='$style2'>";
				}
				echo "<td>".$row['name']."</td>";
				echo "<td>".$row['phone']."</td>";
				echo "<td>".$row['email']."</td>";
				echo "<td><a href='nop_corp_companies.php?flag=3&id=".$row['id']."'>Edit</a></td>";
				echo "<td><a href='nop_corp_companies.php?flag=2&id=".$row['id']."'>Delete</a></td>";
				echo "</tr>";
				$counter++;
			}
			?>
		</table>
	</center>
<?php
}
else
{
	if($_REQUEST['flag'] == "1")
	{
		$query = "insert into corporate_companies_order_data (name, phone, address1, address2, city, state, zip, email) values ('".$_REQUEST['name_t']."', '".$_REQUEST['phone_t']."', '".$_REQUEST['address1_t']."', '".$_REQUEST['address2_t']."', '".$_REQUEST['city_t']."', '".$_REQUEST['state_t']."', '".$_REQUEST['zip_t']."', '".$_REQUEST['email_t']."')";
		$result = mysql_query($query);

		if($result)
		{
			echo "<center style='padding-top:50px;'><label style='font-size:18px;'>Company Information Saved! Redirecting...</label></center>";
		}
		else
		{
			echo "<center style='padding-top:50px;'><label style='font-size:18px;'>Company Information could not be saved. Redirecting...</label></center>";
		}
		header( "refresh:2;url=nop_corp_companies.php");
	}
	else
	{
		if($_REQUEST['flag'] == "2")
		{
			$query = "update corporate_companies_order_data set active=0 where id=".$_REQUEST['id'];
			$result = mysql_query($query);
			if($result)
			{
				echo "<center style='padding-top:50px;'><label style='font-size:18px;'>Company Deletion Successful. Redirecting...</label></center>";
			}
			else
			{
				echo "<center style='padding-top:50px;'><label style='font-size:18px;'>Company Deletion could not be done. Redirecting...</label></center>";
			}
			header( "refresh:2;url=nop_corp_companies.php");
		}
		else
		{
			if($_REQUEST['flag'] == "3")
			{
				$query = "select * from corporate_companies_order_data where id=".$_REQUEST['id'];
				$result = mysql_query($query);

				while($row = mysql_fetch_assoc($result))
				{
					echo "<center>";
					echo "<form method='post'>";
					echo "<table>";
					echo "<tr style='background-color:orange; font-size:24px; color:white;'>";
					echo "<td>Name</td>";
					echo "<td>Phone</td>";
					echo "<td>Email ID</td>";
					echo "<td>AddressLine1</td>";
					echo "<td>AddressLine2</td>";
					echo "<td>City</td>";
					echo "<td>State</td>";
					echo "<td>Zip Code</td>";
					echo "<td>Update</td>";
					echo "</tr>";

					echo "<tr>";
					echo "<td><input type='textbox' name='update_name_t' id='update_name_t' value='".$row['name']."' style='width:120px;'></td>";
					echo "<td><input type='textbox' name='update_phone_t' id='update_phone_t' value='".$row['phone']."' style='width:110px;'></td>";
					echo "<td><input type='textbox' name='update_email_t' id='update_email_t' value='".$row['email']."' style='width:120px;'></td>";
					echo "<td><textarea name='update_address1_t' id='update_address1_t' style='width:100px; height:100px;'>".$row['address1']."</textarea></td>";
					echo "<td><textarea name='update_address2_t' id='update_address2_t' style='width:100px; height:100px;'>".$row['address2']."</textarea></td>";
					echo "<td>";
					$query = "select distinct city from pincode_product_map order by city";
						$result1 = mysql_query($query);
						echo "<select id='update_city_t' name='update_city_t' required>";
						while($row1 = mysql_fetch_row($result1))
						{
							$temp = strtolower($row1[0]);
							$temp = ucwords($temp);
							if($temp == $row['city'])
							{
								echo "<option value='".$temp."' selected>".$temp."</option>";
							}
							else
							{
								echo "<option value='".$temp."'>".$temp."</option>";
							}
						}
						echo "</select>";

					echo "</td>";
					?>
					<td>
						<select id='update_state_t' name='update_state_t'>
							<option value='Andaman and Nicobar Islands' <?php if($row['state'] == "Andaman and Nicobar Islands"){echo "selected";}?> >Andaman and Nicobar Islands</option>
							<option value='Andhra Pradesh' <?php if($row['state'] == "Andhra Pradesh"){echo "selected";}?> >Andhra Pradesh</option>
							<option value='Arunachal Pradesh' <?php if($row['state'] == "Arunachal Pradesh"){echo "selected";}?> >Arunachal Pradesh</option>
							<option value='Assam' <?php if($row['state'] == "Assam"){echo "selected";}?> >Assam</option>
							<option value='Bihar' <?php if($row['state'] == "Bihar"){echo "selected";}?> >Bihar</option>
							<option value='Chandigarh' <?php if($row['state'] == "Chandigarh"){echo "selected";}?> >Chandigarh</option>
							<option value='Chhattisgarh' <?php if($row['state'] == "Chhattisgarh"){echo "selected";}?> >Chhattisgarh</option>
							<option value='Dadra and Nagar Haveli' <?php if($row['state'] == "Dadra and Nagar Haveli"){echo "selected";}?> >Dadra and Nagar Haveli</option>
							<option value='Daman and Diu' <?php if($row['state'] == "Daman and Diu"){echo "selected";}?> >Daman and Diu</option>
							<option value='Delhi' <?php if($row['state'] == "Delhi"){echo "selected";}?> >Delhi</option>
							<option value='Goa' <?php if($row['state'] == "Goa"){echo "selected";}?> >Goa</option>
							<option value='Gujarat' <?php if($row['state'] == "Gujarat"){echo "selected";}?> >Gujarat</option>
							<option value='Haryana' <?php if($row['state'] == "Haryana"){echo "selected";}?> >Haryana</option>
							<option value='Himachal Pradesh' <?php if($row['state'] == "Himachal Pradesh"){echo "selected";}?> >Himachal Pradesh</option>
							<option value='Jammu and Kashmir' <?php if($row['state'] == "Jammu and Kashmir"){echo "selected";}?> >Jammu and Kashmir</option>
							<option value='Jharkhand' <?php if($row['state'] == "Jharkhand"){echo "selected";}?> >Jharkhand</option>
							<option value='Karnataka' <?php if($row['state'] == "Karnataka"){echo "selected";}?> >Karnataka</option>
							<option value='Kerala' <?php if($row['state'] == "Kerala"){echo "selected";}?> >Kerala</option>
							<option value='Lakshadweep' <?php if($row['state'] == "Lakshadweep"){echo "selected";}?> >Lakshadweep</option>
							<option value='Madhya Pradesh' <?php if($row['state'] == "Madhya Pradesh"){echo "selected";}?> >Madhya Pradesh</option>
							<option value='Maharashtra' <?php if($row['state'] == "Maharashtra"){echo "selected";}?> >Maharashtra</option>
							<option value='Manipur' <?php if($row['state'] == "Manipur"){echo "selected";}?> >Manipur</option>
							<option value='Meghalaya' <?php if($row['state'] == "Meghalaya"){echo "selected";}?> >Meghalaya</option>
							<option value='Mizoram' <?php if($row['state'] == "Mizoram"){echo "selected";}?> >Mizoram</option>
							<option value='Nagaland' <?php if($row['state'] == "Nagaland"){echo "selected";}?> >Nagaland</option>
							<option value='Orissa' <?php if($row['state'] == "Orissa"){echo "selected";}?> >Orissa</option>
							<option value='Pondicherry' <?php if($row['state'] == "Pondicherry"){echo "selected";}?> >Pondicherry</option>
							<option value='Punjab' <?php if($row['state'] == "Punjab"){echo "selected";}?> >Punjab</option>
							<option value='Rajasthan' <?php if($row['state'] == "Rajasthan"){echo "selected";}?> >Rajasthan</option>
							<option value='Sikkim' <?php if($row['state'] == "Sikkim"){echo "selected";}?> >Sikkim</option>
							<option value='Tamil Nadu' <?php if($row['state'] == "Tamil Nadu"){echo "selected";}?> >Tamil Nadu</option>
							<option value='Tripura' <?php if($row['state'] == "Tripura"){echo "selected";}?> >Tripura</option>
							<option value='Uttaranchal' <?php if($row['state'] == "Uttaranchal"){echo "selected";}?> >Uttaranchal</option>
							<option value='Uttar Pradesh' <?php if($row['state'] == "Uttar Pradesh"){echo "selected";}?> >Uttar Pradesh</option>
							<option value='West Bengal' <?php if($row['state'] == "West Bengal"){echo "selected";}?> >West Bengal</option>
						</select>

					</td>
					<?php
					echo "<td><input type='textbox' name='update_zip_t' id='update_zip_t' value='".$row['zip']."' style='width:60px;'></td>";
					echo "<td><input type='hidden' name='flag' value='4'><input type='hidden' name='id' value='".$_REQUEST['id']."'><input type='submit' value='Update'></td>";
					echo "</tr>";
					echo "</table>";
					echo "</form>";
				}
			}
			else
			{
				if($_REQUEST['flag'] == "4")
				{
					$query = "update corporate_companies_order_data set name='".$_REQUEST['update_name_t']."', phone='".$_REQUEST['update_phone_t']."', address1='".$_REQUEST['update_address1_t']."', address2='".$_REQUEST['update_address2_t']."', city='".$_REQUEST['update_city_t']."', state='".$_REQUEST['update_state_t']."', zip='".$_REQUEST['update_zip_t']."' where id=".$_REQUEST['id'];
					$result = mysql_query($query);
					if($result)
					{
						echo "<center style='padding-top:50px;'><label style='font-size:18px;'>Company Details Update Successful. Redirecting...</label></center>";
					}
					else
					{
						echo "<center style='padding-top:50px;'><label style='font-size:18px;'>Company Details could not be Updated. Redirecting...</label></center>";
					}
					header( "refresh:2;url=nop_corp_companies.php");
				}
			}
		}
	}
}
?>
</body>
</html>
