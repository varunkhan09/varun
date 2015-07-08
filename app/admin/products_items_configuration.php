<?php
	ob_start();
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
?>

<div class="fixed_loading_div" id="fixed_loading_div">
	<img class="fixed_loading_div_image" src="<?php echo $base_media_image_url; ?>/loading.gif">
</div>

<div class="row products_items_configuration_main_text_div">
	<div class="row" id="product_items_configuration_result_div">
	</div>
</div>