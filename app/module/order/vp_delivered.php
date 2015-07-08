<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($custom_database);

	$orderid = $_REQUEST['orderid'];
?>
<html>
<head>
	<script src="<?php echo $base_media_js_url; ?>/jquery-latest.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

	<style>
		.heading
		{
			 font-size:32px; color:#009ACD; font-weight:bold;
		}

		.data_labels_normal
		{
			font-family: Open Sans, sans-serif; font-size:15px;
		}

		.data_labels_bold
		{
			font-family: Open Sans, sans-serif; font-weight: bold; font-size:14px;
		}

		.heading_labels
		{
			font-size:16px; color:#00688B; font-family: Open Sans;
		}
		.main_buttons
		{
			margin:20px 0px 10px 0px; border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
		}
		.main_buttons:hover
		{
			background-color: #00688B; cursor:pointer;
		}
	</style>

	<script>
	function FillCurrentTime()
	{
		var currentTime = new Date();
		var currentHours = currentTime.getHours();
		var currentMinutes = currentTime.getMinutes();

		return currentHours+":"+currentMinutes;
	}
	</script>
</head>

<body>
<center>
<label class='heading'>Order Delivery Details</label>
</center>
<div id='main_div' style='float:left; display:inline; width:96%; margin:0% 0% 0% 2%; background-color:#F7F7F7; border:2px solid #00B2EE; border-radius: 4px; text-align:center;'>
<?php

if(!isset($_REQUEST['flag']))
{
?>	
	<div id='main_div1' style='float:left; display:inline; width:100%; margin:10px 0px 10px 0px;'>
		<label class='heading_labels'>Please fill the following details to mark the Order Delivered :</label>
	</div>
	<div id='main_div2'>
		<form method='POST' style='margin:0px; padding:0px;'>
			<label class='data_labels_normal'>Delivery Time</label><sup style='color:red; font-size:12px;'>*</sup> : <input type='time' name='delivery_time' id='delivery_time' required='required' value='<?php echo date("H:i", strtotime(date("H:i"))+19800); ?>'>
			<br>
			<label class='data_labels_normal'>Recipient Name</label><sup style='color:red; font-size:12px;'>*</sup> : <input type='textbox' name='delivery_name' id='delivery_name' required='required'>
			<br>
			<input type='hidden' name='flag' value='1'>
			<input type='hidden' name='orderid' value='<?php echo $orderid; ?>'>
			<input type='submit' value='Make Order Shipped' class='main_buttons'>
		</form>
	</div>
<?php
}
else
{
	if($_REQUEST['flag'] == "1")
	{
		$orderid = $_REQUEST['orderid'];
		$delivery_time = $_REQUEST['delivery_time'];
		$delivery_name = $_REQUEST['delivery_name'];

		$query = "update vendor_processing set delivery_time='$delivery_time', delivery_name='$delivery_name', state=3 where orderid=$orderid";
		$result = mysql_query($query);
		echo mysql_error();


		/* THIS CODE IS ADDED TO DEDUCT ITEM STOCK FROM VENDOR WHO DELIVERED THIS ORDER */
			$query = "select shop_id_created, productid, productquantity, productsku, dod from panelorderdetails where orderid=$orderid";
			$result = mysql_query($query);
			echo "<br>".$query;
			echo "<br>".mysql_error();
			$ordered_product_details = array();
			mysql_select_db($vendorshop_database);
			while($row = mysql_fetch_assoc($result))
			{
				$attendant_shop_id = $row['shop_id_created'];
				$temp_product_id = $row['productid'];
				$temp_product_quantity = $row['productquantity'];
				$temp_product_sku = $row['productsku'];
				$temp_dod = $row['dod'];

				echo "<br><br><br><br>";
				print_r($row);
				echo "<br>";

				if(strpos($temp_product_sku, "custom:") !== false) 											//This product is a Custom Product...
				{
					echo "It is Custom Product<br>";
					//$query_inner = "select item_id, item_quantity from pos_order_items_entity where order_id=$orderid and product_id=$temp_product_id";
					$query_inner = "select item_id, item_quantity from pos_order_items_entity where shop_id=$attendant_shop_id and order_id=$orderid and product_id=$temp_product_id";
					$result_inner = mysql_query($query_inner);
					echo "<br>".$query_inner;
					echo "<br>".mysql_error()."<br><br>";

					while($row_inner = mysql_fetch_assoc($result_inner))
					{
						echo "<br>";
						print_r($row_inner);
						echo "<br>";

						$temp_item_id = $row_inner['item_id'];
						$temp_item_quantity = $row_inner['item_quantity'];

						$total_item_quantity = $temp_product_quantity*$temp_item_quantity;

						if(isset($deduction_array[$temp_item_id]))
						{
							$deduction_array[$temp_item_id] += $total_item_quantity;
						}
						else
						{
							$deduction_array[$temp_item_id] = $total_item_quantity;
						}
					}
				}
				else 																						//This product is a Normal Product...
				{
					echo "It is Normal Product<br>";
					$query_inner = "select item_id, item_quantity from pos_products_item_details where product_id=$temp_product_id";
					$result_inner = mysql_query($query_inner);
					echo "<br>".$query_inner;
					echo "<br>".mysql_error()."<br><br>";

					while($row_inner = mysql_fetch_assoc($result_inner))
					{
						echo "<br>";
						print_r($row_inner);
						echo "<br>";

						$temp_item_id = $row_inner['item_id'];
						$temp_item_quantity = $row_inner['item_quantity'];

						$total_item_quantity = $temp_product_quantity*$temp_item_quantity;

						if(isset($deduction_array[$temp_item_id]))
						{
							$deduction_array[$temp_item_id] += $total_item_quantity;
						}
						else
						{
							$deduction_array[$temp_item_id] = $total_item_quantity;
						}
					}
				}
			}
			echo "<br><br><br>";
			var_dump($deduction_array);
			echo "<br><br>";

			$temp_dod_final = $temp_dod." 00:00:00";

			$varun = array_keys($deduction_array);
			$query_inner2 = "insert into pos_stock_deduced_entity (shop_id, order_id, item_id, item_quantity, created_at) values ";
			foreach($varun as $each_item)
			{
				$query = "update pos_stock_entity set item_quantity=item_quantity-".$deduction_array[$each_item]." where item_id=$each_item and shop_id=$shop_id";
				echo $query."<br>";
				$query_inner2 .= "($attendant_shop_id, $orderid, $each_item, ".$deduction_array[$each_item].", '$temp_dod_final'), ";
				mysql_query($query);
			}
			$query_inner2 = rtrim($query_inner2, ", ");
			mysql_query($query_inner2);

			mysql_select_db($custom_database);
		/* THIS CODE IS ADDED TO DEDUCT ITEM STOCK FROM VENDOR WHO DELIVERED THIS ORDER */



		
		if($result)
		{
			echo "<label class='heading_labels'>Order has been marked Delivered.</label><br><br>";
			?>
			<script>
			/* THIS CODE RECORDS THIS OPERATION */
				$.ajax({
					type:"POST",
					url:"<?php echo $base_module_path; ?>/notifications/record_notification.php",
					data:
					{
						notf_type:'order_delivered',
						shop_id_accepted:'<?php echo $shop_id; ?>',
						order_id:'<?php echo $orderid; ?>',
					}
				});
			/* THIS CODE RECORDS THIS OPERATION */


			$(document).ready(function(){
				$.ajax({
					url:'vp_sendsmsemail.php',
					type:'POST',
					data:
					{
						order_id:"<?php echo $_REQUEST['orderid']; ?>",
						status:'3'
					},

					success:function(message)
					{
						if(message == "0")
						{
							alert("Customer Email and SMS notification failed.");
						}
						else
						{
							if(message == "1")
							{
								alert("Customer SMS sent Email failed.");
							}
							else
							{
								if(message == "2")
								{
									alert("Customer Email sent sms failed.");
								}
								else
								{
									if(message == "3")
									{
										alert("Customer Email and SMS notification success.");
									}
								}
							}
						}
					}
				});
			});
			</script>
			<?php
		}
		else
		{
			echo "<label class='heading_labels'>Could not mark the order Delivered. Please try again.</label><br><br>";
			echo mysql_error();
		}
	}
}
?>
</div>
</body>
</html>