<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	mysql_select_db($vendorshop_database);

	$query = "select attribute_id from pos_attributes where attribute_code='order_forward_email'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$order_forward_email_attribute_id = $row['attribute_id'];
	$query = "select email_id, email_pass from pos_email_configuration where email_role=".$order_forward_email_attribute_id." and shop_id=".$shop_id;
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$order_forward_email_id = $row['email_id'];
	$order_forward_email_pass = $row['email_pass'];
	if(is_null($order_forward_email_id) && is_null($order_forward_email_pass))
	{
		$new_user = 1;
	}
	else
	{
		$new_user = 0;
	}
?>

	<div class="row stock_report_main_content_div text-center">
		Email to be used for Vendor Order Forwarding<br><br>
		Email ID : <input type='textbox' id='order_forward_email_t' value="<?php echo $order_forward_email_id; ?>" placeholder='Enter Email ID'>
		<br>
		<br>
		Password : <input type='textbox' id='order_forward_pass_t' value="<?php echo $order_forward_email_pass; ?>" placeholder='Enter Password'>
		<br>
		<br>
		<input type='hidden' id='new_entry_flag' value="<?php echo $new_user; ?>">
		<input type='button' id='order_forward_save_b' class='buttons' value='Save Configuration'>
	</div>

	<div id="dialog-confirm-msg"></div>

</body>
</html>