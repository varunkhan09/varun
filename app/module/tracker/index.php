<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	//include "/var/www/varun/app/module/common/header.php";
?>
<style>
	.panels_main_div
	{
		margin-right:0px;
	}
</style>

		<div class="row index_main_div">
			<div class="col-xs-12 panels_main_div text-center">
				<div class="col-xs-3 col-xs-offset-3 each_panel_div">
					<a href="<?php echo $base_module_path; ?>/order/routeplanner/kmeans/index.php" class="blue_links">
						<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/RoutePlanner.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Route Planner</div>
					</a>
				</div>

				<div class="col-xs-1" style="height:250px; width:2px; background-color:#009ACD; padding:0px;"></div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="<?php echo $base_module_path; ?>/order/trackorder.php" class="blue_links">
						<div class="each_panel_image_div"><img src="<?php echo $base_media_image_url; ?>/TrackOrder.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Track an Order</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>