<?php
	include "global_variables.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

    <title>Home</title>

    <link href="<?php echo $base_media_url; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="<?php echo $base_media_url; ?>/css/header.css" rel="stylesheet"> -->

    <link href="http://varun.floshowers.com:8882/vendorpanel/media/css/header.css" rel="stylesheet">
	<link href="<?php echo $base_media_url; ?>/css/index.css" rel="stylesheet">
</head>

<body>

<?php include "header.php"; ?>

	<!-- Page Content -->
		<div class="row index_main_div">
			<div class="container panels_main_div text-center">
				<div class="col-xs-3 each_panel_div">
					<a href="../" class="blue_links" target="_blank">
						<div class="each_panel_image_div"><img src="images/My Order.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">My Orders</div>
					</a>
				</div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="http://www.google.com" class="blue_links">
						<div class="each_panel_image_div"><img src="images/Accounts.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Accounts</div>
					</a>
				</div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="inventory.php" class="blue_links">
						<div class="each_panel_image_div"><img src="images/Inventory.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Inventory</div>
					</a>
				</div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="../createorder/nop_main.php" class="blue_links" target="_blank">
						<div class="each_panel_image_div"><img src="images/Create.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Create Order</div>
					</a>
				</div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="http://www.google.com" class="blue_links">
						<div class="each_panel_image_div"><img src="images/Send Order.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Send Order</div>
					</a>
				</div>

				<div class="col-xs-3 each_panel_div text-center">
					<a href="http://www.google.com" class="blue_links">
						<div class="each_panel_image_div"><img src="images/Fleet.png" class="panel_image"></div>
						<div class="each_lanel_label_div blue_links_homepage">Fleet</div>
					</a>
				</div>

				<!--
				<div class="col-xs-6 col-sm-4 col-md-2 each_panel_div text-center">
					<div class="each_panel_image_div"><img src="" class="panel_image"></div>
				</div>

				<div class="col-xs-6 col-sm-4 col-md-2 each_panel_div text-center">
					<div class="each_panel_image_div"><img src="" class="panel_image"></div>
				</div>
				-->
			</div>
		</div>
	</div>
	<!-- Page Content -->


	<!--
		<div class="row carousel-holder">
			<div class="col-md-12">
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
						<li data-target="#carousel-example-generic" data-slide-to="1"></li>
						<li data-target="#carousel-example-generic" data-slide-to="2"></li>
					</ol>

					<div class="carousel-inner">
						<div class="item active">
							<img class="slide-image" src="http://placehold.it/800x300" alt="">
						</div>
				
						<div class="item">
							<img class="slide-image" src="http://placehold.it/800x300" alt="">
						</div>

						<div class="item">
							<img class="slide-image" src="http://placehold.it/800x300" alt="">
						</div>
					</div>

					<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</a>
				
					<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</a>
				</div>
			</div>
		</div>
	-->
	<!-- /.container -->

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<!-- jQuery -->
	<script src="<?php echo $base_media_url; ?>/bootstrap/js/jquery.js"></script>
	<script src="<?php echo $base_media_url; ?>/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
