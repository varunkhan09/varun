<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	$base_path."/app/etc/dbconfig.php";
	$base_path."/app/etc/mageinclusion.php";

	$file_url = $_GET['url'];

	$xml_object = simplexml_load_file($file_url) or die("Error: Cannot create object");

	$sender_firstname = ''.$xml_object->sender->first_name;
	$sender_lastname = ''.$xml_object->sender->last_name;
	$sender_phone = ''.$xml_object->sender->phone;
	$sender_email = ''.$xml_object->sender->email;
	$sender_address = ''.$xml_object->sender->address;
	$sender_zip = ''.$xml_object->sender->zip;
	$sender_city = ''.$xml_object->sender->city;
	$sender_state = ''.$xml_object->sender->state;
	$sender_country = ''.$xml_object->sender->country;
	$sender_company = ''.$xml_object->sender->company;

	$recipient_firstname = ''.$xml_object->recipient->first_name;
	$recipient_lastname = ''.$xml_object->recipient->last_name;
	$recipient_phone = ''.$xml_object->recipient->phone;
	$recipient_address = ''.$xml_object->recipient->address;
	$recipient_zip = ''.$xml_object->recipient->zip;
	$recipient_city = ''.$xml_object->recipient->city;
	$recipient_state = ''.$xml_object->recipient->state;
	$recipient_country = ''.$xml_object->recipient->country;
	$recipient_company = ''.$xml_object->recipient->company;

	$delivery_info_delivery_date = ''.$xml_object->deliveryinfo->delivery_date;
	$delivery_info_delivery_city = ''.$recipient_city;
	
	$delivery_info_delivery_type = ''.$xml_object->deliveryinfo->delivery_type;
	$delivery_info_message_from = ''.$xml_object->deliveryinfo->message->messagefrom;
	$delivery_info_message_to = ''.$xml_object->deliveryinfo->message->messageto;
	$delivery_info_message_body = ''.$xml_object->deliveryinfo->message->messagebody;
	$delivery_info_specialinstructions = ''.$xml_object->deliveryinfo->specialinstructions;

	$number_of_products = ''.$xml_object->products->number_of_products;

	for($x=0; $x<$number_of_products; $x++)
	{
		$temp_product_type = ''.$xml_object->products->productinfo->product[$x]->type;
		echo $temp_product_type;
		if($temp_product_type == 'Catalog')
		{
			$products[$x]['type'] = 'catalog';
			$products[$x]['sku'] = ''.$xml_object->products->productinfo->product[$x]->sku;
			$products[$x]['quantity'] = ''.$xml_object->products->productinfo->product[$x]->quantity;
			$products[$x]['id'] = ''.$xml_object->products->productinfo->product[$x]->id;
		}
		else
		{
			if($temp_product_type == 'Custom')
			{
				$products[$x]['type'] = 'custom';
				$products[$x]['sku'] = ''.$xml_object->products->productinfo->product[$x]->sku;
				$products[$x]['quantity'] = ''.$xml_object->products->productinfo->product[$x]->quantity;
				$products[$x]['description'] = ''.$xml_object->products->productinfo->product[$x]->description;
				$products[$x]['price'] = ''.$xml_object->products->productinfo->product[$x]->price;
				$products[$x]['id'] = ''.$xml_object->products->productinfo->product[$x]->id;
			}
		}
	}
	//var_dump($products);
	//$recipient_ = $xml_object->recipient->;
	echo "<br><br>";

?>
<html>
<head></head>
<body>
	<form method="POST" id="varun" name="varun_form" action="nop_main.php">
		<input type='hidden' name='flag' value='1'>
		
		<input type='hidden' name='sender_first_name_t' value='<?php echo $sender_firstname; ?>'>
		<input type='hidden' name='sender_last_name_t' value='<?php echo $sender_lastname; ?>'>
		<input type='hidden' name='sender_phone_t' value='<?php echo $sender_phone; ?>'>
		<input type='hidden' name='sender_email' value='<?php echo $sender_email; ?>'>
		<input type='hidden' name='sender_address_line_1_t' value='<?php echo $sender_address; ?>'>
		<input type='hidden' name='sender_zip_t' value='<?php echo $sender_zip; ?>'>
		<input type='hidden' name='sender_city_t' value='<?php echo $sender_city; ?>'>
		<input type='hidden' name='sender_state_dd' value='<?php echo $sender_state; ?>'>
		<input type='hidden' name='sender_country_dd' value='<?php echo $sender_country; ?>'>
		<input type='hidden' name='sender_company_t' value='<?php echo $sender_company; ?>'>
		
		<input type='hidden' name='recipient_first_name_t' value='<?php echo $recipient_firstname; ?>'>
		<input type='hidden' name='recipient_last_name_t' value='<?php echo $recipient_lastname; ?>'>
		<input type='hidden' name='recipient_phone_t' value='<?php echo $recipient_phone; ?>'>
		<input type='hidden' name='recipient_address_line_1_t' value='<?php echo $recipient_address; ?>'>
		<input type='hidden' name='recipient_zip_t' value='<?php echo $recipient_zip; ?>'>
		<input type='hidden' name='recipient_city_t' value='<?php echo $recipient_city; ?>'>
		<input type='hidden' name='recipient_state_dd' value='<?php echo $recipient_state; ?>'>
		<input type='hidden' name='recipient_country_dd' value='<?php echo $recipient_country; ?>'>
		<input type='hidden' name='recipient_company_t' value='<?php echo $recipient_company; ?>'>

		<input type='hidden' name='number_of_products_in_order' value='<?php echo $number_of_products; ?>'>
		<?php
			for($x=0; $x<$number_of_products; $x++)
			{
				$temp_product_type = $products[$x]['type'];

				if($temp_product_type == 'catalog')
				{
					echo "<input type='hidden' name='product_id_$x' value='".$products[$x]['id']."'>";
					echo "<input type='hidden' name='quantity_t_$x' value='".$products[$x]['quantity']."'>";

				}
				else
				{
					if($temp_product_type == 'custom')
					{
						echo "<input type='hidden' name='product_name_$x' value=''>";
						echo "<input type='hidden' name='product_description_textarea_$x' value='".$products[$x]['description']."'>";
						echo "<input type='hidden' name='quantity_t_$x' value='".$products[$x]['quantity']."'>";
						echo "<input type='hidden' name='price_t_$x' value='".$products[$x]['price']."'>";
						echo "<input type='hidden' name='product_id_$x' value='".$products[$x]['id']."'>";
					}
				}
			}
		?>
		<input type='hidden' name='delivery_details_date_id' value='<?php echo $delivery_info_delivery_date; ?>'>
		<input type='hidden' name='delivery_type_dd' value='<?php echo $delivery_info_delivery_type; ?>'>
		<input type='hidden' name='delivery_details_special_instructions' value='<?php echo $delivery_info_specialinstructions; ?>'>
		<input type='hidden' name='delivery_details_card_to_t' value='<?php echo $delivery_info_message_to; ?>'>
		<input type='hidden' name='delivery_details_card_from_t' value='<?php echo $delivery_info_message_from; ?>'>
		<input type='hidden' name='delivery_details_card_message' value='<?php echo $delivery_info_message_body; ?>'>
	</form>

	<script>
		document.varun_form.submit();
	</script>
</body>
</html>