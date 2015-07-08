<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
?>
<div ng-app="myshop">
<div class="container-fluid" >
          <div ng-view>

          </div>
</div>
<div>
 <!-- Bootstrap Core JavaScript -->

<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/bootstrap-clockpicker.js"></script>
<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/jquery-clockpicker.js"></script>

<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/angular-1.3.15/angular.min.js"></script>
<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/angular-1.3.15/angular-route.min.js"></script>
 
<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/js-api/ui-bootstrap-tpls-0.12.1.min.js"></script>
<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/js-api/ng-tags-input.js"></script>

<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/js-api/app-router.js"></script>
<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/js-api/app-directive.js"></script>
<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/js-api/app-service.js"></script>
<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/js-api/app-controller.js"></script>
<script src="<?php echo $base_media_js_url; ?>/bootstrap/js/js-api/app-cust.js"></script>
    
</body>
</html>
