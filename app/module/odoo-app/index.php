<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
?>
<ul style="height: 100vh; font-size:14px;" class="nav navbar-nav side-nav odoo_app_left_ul">

<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#vehicles-link">Vehicles<i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="vehicles-link" class="collapse">
        <li><a href="#/vehicles">Vehicles</a></li>
        <li><a href="#/model">Vehicle Model</a></li>
        <li><a href="#/vehicle-flogs-list">Vehicles Fuel Logs</a></li>
        <li><a href="#/vehicle-fservice-list">Vehicles Service Logs</a></li>
        <li><a href="#/vehicles-clogs-list">Vehicles Cost Logs</a></li>
        <li><a href="#/vehicles-ologs-list">Vehicles Odometer Logs</a></li>
        <li><a href="#/vehicles-alogs-list">Vehicles Additional Logs</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#driver-link">Driver<i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="driver-link" class="collapse">
        <li><a href="#/driver">Drivers</a></li>
    </ul>
</li>

 <li>
    <a href="javascript:;" data-toggle="collapse" data-target="#report-link">Report<i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="report-link" class="collapse">
        <li><a href="#/report">Generate Report</a></li>
    </ul>
</li>

</ul>


<div ng-app="fb-odoo-app" class='col-xs-offset-2 col-xs-10 odoo_app_main_div'>

	<div id="page-wrapper">
             <div class="container-fluid">
                        <!-- Page Heading -->
                        <div class="row">                        
                            <div id="main">
                                <div ng-view> </div> 
                            </div>
                       </div>
                     <!-- /.row -->
                 </div>
             </div>
</div>

 <link href="<?php echo $base_media_css_url; ?>/odoo-app/css/datetimepicker.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/odoo-app/css/fb-admin.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/odoo-app/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo $base_media_css_url; ?>/odoo-app/css/odoo_app_index.css" rel="stylesheet">

        <script src="<?php echo $base_media_js_url; ?>/odoo-app/angular/angular.min.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/angular/angular-ui.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/angular/angular-route.min.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/ui-bootstrap-tpls-0.12.1.min.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/odoo-router.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/odoo-controller.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/odoo-service.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/odoo-directive.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/driver-controller.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/model-controller.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/odometer-controller.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/vehicle-cost-controller.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/vehicle-fuel-controller.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/vehicle-service-controller.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/vehicle-additional.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/odoo-app/odoo-app-angular-js/report.js"></script> 
        
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/odoo-app/js/jspdf/jspdf.js"></script>
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/odoo-app/js/jspdf/libs/sprintf.js"></script>
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/odoo-app/js/jspdf/libs/base64.js"></script>
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/odoo-app/js/jspdf/libs/Deflate/adler32cs.js"></script>
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/odoo-app/js/jspdf/jspdf.debug.js"></script>
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/odoo-app/js/jspdf/jspdf.plugin.cell.js"></script>
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/odoo-app/js/excel-download.js"></script>
