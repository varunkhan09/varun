<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	//include "/var/www/varun/app/module/common/header.php";
?>
		<div class="row index_main_div">
			<div class="container panels_main_div text-center">
				<div class="col-xs-3 each_panel_div">
					<a href="current_stock.php" class="blue_links">
						<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/My Order.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Current Stock</div>
					</a>
				</div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="add_stock.php" class="blue_links">
						<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/Accounts.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Add Stock</div>
					</a>
				</div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="wastage_stock.php" class="blue_links">
						<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/Inventory.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Wastage</div>
					</a>
				</div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="stock_report.php" class="blue_links">
						<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/Reports.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Report</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Page Content -->
</body>
</html>