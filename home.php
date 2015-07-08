
<?php
ob_start();
session_start();

if(!isset($_SESSION['loggedin']['user']['shop_id'])){
	include_once $_SERVER['DOCUMENT_ROOT'] . "/global_variables.php";
	header('Location:'.$base_path."/shop-config.php");
}




include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	if(isset($_SESSION['loggedin']['user_id']))
	{
?>

	<!-- Page Content -->
	<div class="row index_main_div">
		<center>
			<div class="container panels_main_div text-center">
					<div class="col-xs-3 each_panel_div">
						<a href="<?php echo $base_files_url; ?>/order/index.php" class="blue_links">
							<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/My Order.png" class="panel_image"></div>
							<div class="each_lanel_label_div blue_links_homepage">My Orders</div>
						</a>
					</div>



					<div class="col-xs-3 each_panel_div text-center">
						<a href="<?php echo $base_files_url; ?>/account/account_main.php" class="blue_links">
							<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/Accounts.png" class="panel_image"></div>
							<div class="each_lanel_label_div blue_links_homepage">Accounts</div>
						</a>
					</div>


					<div class="col-xs-3 each_panel_div text-center">
						<a href="<?php echo $base_files_url; ?>/stock/inventory.php" class="blue_links">
							<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/Inventory.png" class="panel_image"></div>
							<div class="each_lanel_label_div blue_links_homepage">Inventory</div>
						</a>
					</div>


					<div class="col-xs-3 each_panel_div text-center">
						<a href="<?php echo $base_files_url; ?>/createorder/nop_main.php" class="blue_links">
							<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/Create.png" class="panel_image"></div>
							<div class="each_lanel_label_div blue_links_homepage">Create Order</div>
						</a>
					</div>


					<div class="col-xs-3 each_panel_div text-center">
						<a href="<?php echo $base_files_url; ?>/createdorders/index.php" class="blue_links">
							<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/Send Order.png" class="panel_image"></div>
							<div class="each_lanel_label_div blue_links_homepage">Sent Order</div>
						</a>
					</div>

					<?php

						// echo "<pre>";
						// print_r($_SESSION);


					 ?>
					 
					<div class="col-xs-3 each_panel_div text-center">
						<a href="<?php echo $base_files_url; ?>/odoo-app" class="blue_links">
							<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/Fleet.png" class="panel_image"></div>
							<div class="each_lanel_label_div blue_links_homepage">Fleet</div>
						</a>
					</div>
				</div>

		</center>
	</div>
</body>
</html>

<?php
	}
?>