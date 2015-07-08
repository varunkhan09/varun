<?php
	include $_SERVER['DOCUMENT_ROOT'].'/global_variables.php';
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";



	function FetchStatusLabelFromStatus($catched_label)
	{
		switch($catched_label)
		{
			case '0':
			{
				return("Invited");
				break;
			}

			case '1':
			{
				return("Given to Installation");
				break;
			}

			case '2':
			{
				return("Approved");
				break;
			}

			default:
			{
				break;
			}
		}
	}
?>

	<div class="row add_stock_page_content">
		<div class="container text-center">
			<div class="myvendors_panel_heading text-center">
				<strong class="headingname">My Vendors</strong>
			</div>

			<div class='myvendors_body_div text-center'>
				<table class='myvendors_body_table table table-hover' id='myvendors_table'>
				<tr class='myvendors_body_table_header_div'>
					<td>S. No.</td>
					<td>Name</td>
					<td>Email</td>
					<td>Phone Number</td>
					<td>Status</td>
					<td></td>
				</tr>

				<?php
					$counter=1;
					mysql_select_db($vendorshop_database);
					$query = "select a.status, a.entity_id, b.self_shop_id, b.vendor_shop_id, c.shop_name, c.email, c.phone_number from pos_add_vendor_entity a inner join pos_associated_vendors_entity b on a.entity_id = b.invite_id left join pos_shop_entity c on b.vendor_shop_id = c.entity_id where b.self_shop_id = $shop_id";
					$result = mysql_query($query);
					while($row = mysql_fetch_assoc($result))
					{
						if(!is_null($row['vendor_shop_id']))
						{
							echo "<tr id='table_row_$counter'>";
							echo "<td>$counter</td>";
							echo "<td>".$row['shop_name']."</td>";
							echo "<td>".$row['email']."</td>";
							echo "<td>".$row['phone_number']."</td>";
							echo "<td>".FetchStatusLabelFromStatus($row['status'])."</td>";
							echo "</tr>";
							$counter++;
						}
						else
						{
							$query = "select * from pos_add_vendor_entity where entity_id = ".$row['entity_id'];
							$result_inner1 = mysql_query($query);

							$row_inner = mysql_fetch_assoc($result_inner1);

							$temp_name = $row_inner['name'];
							$temp_email = $row_inner['email'];
							$temp_phone = $row_inner['phone'];
							$temp_status = $row_inner['status'];

							echo "<tr id='table_row_$counter'>";
							echo "<td>$counter</td>";
							echo "<td>$temp_name</td>";
							echo "<td>$temp_email</td>";
							echo "<td>$temp_phone</td>";
							echo "<td>".FetchStatusLabelFromStatus($temp_status)."</td>";
							echo "</tr>";
							$counter++;
						}
					}
				?>
				</table>
			</div>

			<div class='myvendors_operations_div text-right'>
				<input type='button' class='buttons' value='Invite Vendor' id='myvendors_invite_b'>
			</div>

			<div id='myvendors_below_operations_div_id' class='myvendors_below_operations_div text-center'>
				<table class='table'>
					<tr class='myvendors_body_table_header_div text-center'>
						<td>Name</td>
						<td>Email ID</td>
						<td>Phone Number</td>
						<td></td>
					</tr>

					<tr>
						<td><input type='textbox' class='form-control' id='addvendor_name_t'></td>
						<td><input type='textbox' class='form-control' id='addvendor_email_t'></td>
						<td><input type='textbox' class='form-control' id='addvendor_phone_t'></td>
						<td><input type='button' class='buttons' id='addvendor_add_b' value='Send Invite'></td>
					</tr>
				</table>
			</div>
		</div>		
	</div>
