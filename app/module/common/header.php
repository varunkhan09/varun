<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/global_variables.php";
include $base_path_folder."/app/etc/dbconfig.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/module/resource/module-resource.class.php';

/* THIS CODE IS ADDED BY VARUN TO CHECK WHAT IS THIS PAGE'S URL, IF IT IS ADMIN, CHECKS IF ACCESS IS AUTHORIZED OR NOT */
$this_page_url = $_SERVER['REQUEST_URI'];
if (strpos($this_page_url, "admin") !== false) {
    if ($_SESSION['loggedin']['user']['is_admin'] == "0") {
        header("Location: " . $_SERVER['HTTP_HOST']);
    }
}
/* THIS CODE IS ADDED BY VARUN TO CHECK WHAT IS THIS PAGE'S URL, IF IT IS ADMIN, CHECKS IF ACCESS IS AUTHORIZED OR NOT */




/* THIS CODE IS ADDED BY VARUN TO FETCH NUMBER OF ORDERS IN EACH ORDER STATE */
    mysql_select_db($custom_database);
    $query = "select distinct orderid from panelorderdetails where (vendor_id=0 or vendor_id is NULL) and shop_id_created=$shop_id";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $count_nonforwarded_orders = mysql_num_rows($result);

    $query = "select distinct orderid from vendor_processing where state=0 and vendor_id=$shop_id";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $count_unacknowledged_orders = mysql_num_rows($result);


    $query = "select distinct orderid from vendor_processing where state=1 and vendor_id=$shop_id";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $count_acknowledged_orders = mysql_num_rows($result);


    $query = "select distinct orderid from vendor_processing where state=2 and vendor_id=$shop_id";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $count_shipped_orders = mysql_num_rows($result);


    $query = "select distinct orderid from vendor_processing where state=3 and vendor_id=$shop_id";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $count_delivered_orders = mysql_num_rows($result);


    $query = "select distinct orderid from vendor_processing where (state=4 or state=5) and orderid in (select distinct orderid from panelorderdetails where vendor_id=$shop_id or shop_id_created=$shop_id)";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $count_cancelled_orders = mysql_num_rows($result);
    mysql_select_db($vendorshop_database);
/* THIS CODE IS ADDED BY VARUN TO FETCH NUMBER OF ORDERS IN EACH ORDER STATE */







/* Don't comment this section of code it is to prevent any line of page access with any autorized access */
$shop_id = null;
$user_id = null;

if (isset($_SESSION['loggedin'])) {
    $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? (int) $_SESSION['loggedin']['user_id'] : null;
    if (isset($_SESSION['loggedin']['user'])) {
        $shop_id = is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
    }
    if (is_null($shop_id) && $user_id && substr($_SERVER['REQUEST_URI'], -5) != 'shop/') {
        /* Commented by Amarkant @02-06-2015 */
        // $shop_url  = "/user/". $_SESSION['loggedin']['user_id'] . "/shop";      
        // $shop_base_url = $base_path."/app/module/myshop/#".$shop_url;
        // header("Location: $shop_base_url"); 
    }
} else {
    header('Location:' . $base_path);
}
?>


<?php 
if( $_SERVER['REQUEST_URI'] == '/app/module/odoo-app/'){
    $title =  getTitle($_SESSION['x_url']);
}else{
    if($_SERVER['REQUEST_URI']== '/app/module/myshop/'){
        $title =  getTitle($_SESSION['x_url']);
    }else{
       $title =  getTitle($_SERVER['REQUEST_URI']);
    }
}

function getTitle($url){
    $title = "";
    $query = "select module_name from pos_modules where module_url = '".$url."'";
    $result = mysql_query($query);
    if(mysql_num_rows($result)> 0){
         while($row    = mysql_fetch_assoc($result)){
            $title = $row['module_name'];
        }
    }else{
        $title = 'My Floral Store';
    }
    return $title;
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo $title; ?></title>


        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">

        <link href="<?php echo $base_media_css_url; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/bootstrap-tagsinput.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/header.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/index.css" rel="stylesheet">

        <link href="<?php echo $base_media_css_url; ?>/inventory.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/add_stock.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/wastage_stock.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/current_stock.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/manageitems.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/nop_main.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/stock_report.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/myorders_main.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/account_main.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/vendor_payment_main.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/tagmanager.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/myvendors.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/products_items_configuration.css" rel="stylesheet">

        <?php if (0) { ?>
            <!-- THESE FILES ARE ADDED BY VARUN FOR ODOO APP DATA --> 
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
            <!-- THESE FILES ARE ADDED BY VARUN FOR ODOO APP DATA --> 

        <?php } ?>

        <link href="<?php echo $base_media_js_url; ?>/bootstrap/js/jquery-ui.css">
        <link href="<?php echo $base_media_css_url; ?>/datepicker/css/bootstrap-datepicker.css" rel="stylesheet">

        <!-- for tag and clock -->
        <link href="<?php echo $base_media_css_url; ?>/bootstrap/css/a/shop.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/bootstrap/css/a/ng-tags-input.css" type="text/css" rel="stylesheet">    
        <link href="<?php echo $base_media_css_url; ?>/bootstrap/css/a/bootstrap-clockpicker.css" rel="stylesheet">
        <link href="<?php echo $base_media_css_url; ?>/bootstrap-timepicker.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        -->
        <!-- jQuery -->
        <script src="<?php echo $base_media_js_url; ?>/bootstrap/js/jquery-1.10.2.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/bootstrap/js/jquery-ui.js"></script>
        <!--
        <script src="<?php //echo $base_media_js_url;   ?>/bootstrap/js/transition.js"></script>
        <script src="<?php //echo $base_media_js_url;   ?>/bootstrap/js/collapse.js"></script>
        -->
        <script src="<?php echo $base_media_js_url; ?>/datepicker/js/bootstrap-datepicker.js"></script>

        <script src="<?php echo $base_media_js_url; ?>/add_stock.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/wastage_stock.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/deduce_stock.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/nop_main.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/manageitems.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/stock_report.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/account_main.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/attendance_main.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/view_attendance_main.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/vendor_payment_main.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/nop_add_custom_product.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/myvendors.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/products_items_configuration.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/products_items_configuration_popup_front.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/email_configuration.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/stock_edit.js"></script>

        <script src="<?php echo $base_media_js_url; ?>/typeahead.min.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/tagmanager.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/bootstrap-tagsinput.min.js"></script>
        <script src="<?php echo $base_media_js_url; ?>/bootstrap-timepicker.min.js"></script>

        <script src="<?php echo $base_media_js_url; ?>/jquery.checktree.js"></script>

        <!-- Edited 20/06/2015 -->
        <script src="<?php echo $base_media_js_url; ?>/jquery.form.js"></script>
        <!-- End Edited 20/06/2015 -->

        <!-- Uploader JS-->
        <script src="<?php echo $base_module_path; ?>/myshop/dzone/js/uploader.js"></script>
        <link href="<?php echo $base_module_path; ?>/myshop/dzone/css/uploader.css" rel="stylesheet">


        <link rel="stylesheet" href="<?php echo $base_media_js_url; ?>/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/fancybox/source/jquery.fancybox.pack.js"></script>
        <link rel="stylesheet" href="<?php echo $base_media_js_url; ?>/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/fancybox/source/helpers/jquery.fancybox-media.js"></script>
        <link rel="stylesheet" href="<?php echo $base_media_js_url; ?>/fancybox/source/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo $base_media_js_url; ?>/fancybox/source/helpers/jquery.fancybox-thumbs.js"></script>
        <style>
            .nav>li>a {position: relative;display: block;padding: 15px 10px;}
            .index_main_div{margin: 0px;}
            .stk-sidebar {
                position: fixed;
                padding: 10px;
                z-index: 3000;
                color: #ffffff;
                box-shadow: 4px 5px 10px rgba(30, 30, 30, 0.35); 
                cursor: pointer; 
            }
            #stk-admin-sidebar {
                width: auto;
                height: auto;
                right: 0px;
                border: 1px solid #272727;
                background-color:#272727; 
                top: 50%;
                transform:skew(0deg) rotate(90deg) translate(0px, -53px); /*scale(1.1,1.1)*/
            }
            div.stk-admin-link{
                width: 150px;
                height: 150px;
                top: 50%;
                right: 2.75em;
                border: 1px solid #009ACD;
                background-color:#009ACD; 
                transform:skew(0deg) translate(0px, -53px);
                font-size: 12px;
            }
            div.stk-admin-link ul {
                list-style-type: none;
                margin: 5px 0px;
                padding: 0;
            }
            div.stk-admin-link ul li a{
                text-decoration: none;
                color: #ffffff;
                line-height: 1.5 !important;
            }
            div.stk-admin-link ul li a:hover{
                font-weight: bold;
            }

        </style> 

    </head>


    <body>
        <?php
            $user = 0;
            $shop = 0;
            $is_admin = 0;

            if (isset($_SESSION['loggedin']['user_id'])) {
                $user = (int) $_SESSION['loggedin']['user_id'];
            }
            if (isset($_SESSION['loggedin']['user'])) {
                $shop = (int) $_SESSION['loggedin']['user']['shop_id'];
            }
            if (isset($_SESSION['loggedin']['user']['is_admin'])) {
                $is_admin = (int) $_SESSION['loggedin']['user']['is_admin'];
            }


/*
            $to_be_assigned = "javascript:void(0);";
            $awaiting_delivery_pickup = "javascript:void(0);";
            $oh_hold = "javascript:void(0);";
            $out_for_delivery = "javascript:void(0);";
            $order_sent_out = "javascript:void(0);";
*/
        ?>

        <div class="container-fluid header_main_container">

            <?php
           // print "<pre>";
           //  print_r($_SESSION);

            $modules = new ModuleResource();
            $finalResource = array();

            $role_id = $_SESSION['loggedin']['role_id']; // assign role id from session here

            $rootList = $modules->listUserRoleAccessModulesList($role_id);
            //print_r($rootList);

            $root = $rootList[0];
            $child = $rootList[1];
            $resultantData = array();
            foreach ($child as $key => $element) {
                $access_tag = $element['access_tag'];
                $parent_id = $element['id'];
                foreach ($child as $key => $elementData) {
                    if ($elementData['parent'] === $parent_id) {
                        $element[$access_tag][] = $elementData;
                    }
                }
                $resultantData[] = $element;
            }

            foreach ($root as $key => $rootElement) {
                $keys = array_keys($rootElement);
                $temp = $rootElement[$keys['0']];
                $parent_id = $temp['id'];
                $access_tag = $temp['access_tag'];
                foreach ($resultantData as $key => $childElement) {
                    if ($childElement['parent'] == $parent_id) {
                        $rootElement[$access_tag][$access_tag][] = $childElement;
                    }
                }
                $finalResource[] = $rootElement;
            }
            $finalResource[] = array('sign_out' => array('id' => 9999, 'name' => 'Sign Out', 'access_tag' => 'sign_out', 'parent' => 'self', 'root_parent' => 'self', 'url' => '/app/admin/user/logout.php'));

            $icons_css_class = array(
                'my_shop' => 'glyphicon glyphicon-home header_link_icons',
                'inventory' => 'glyphicon glyphicon-gift header_link_icons',
                'my_customers' => 'glyphicon glyphicon-user header_link_icons',
                'orders' => 'glyphicon glyphicon-link header_link_icons',
                'accounts' => 'glyphicon glyphicon-briefcase header_link_icons',
                'tracker' => 'glyphicon glyphicon-map-marker',
                'my_credits' => 'navigation_links blue_links',
                'fleet' => 'glyphicon glyphicon-cog header_link_icons',
                'sign_out' => 'glyphicon glyphicon-off header_link_icons'
            );
            ?>


            <div id="nav" class="row dashboard_div">
                <ul id="navigation" class="col-xs-12 nav navbar-nav">
                    <li style="background:#009ACD;" class="header_flaberry_icon_li">

                         <!-- Edited 20/06/2015  To display shop logo -->
                        <?php 
                            $default_logo = false;
                            $logo_query = "SELECT shop_logo FROM pos_shop_entity WHERE entity_id =  $shop";
                            $res =  mysql_query($logo_query);
                            if(mysql_num_rows($res)){
                                while($row = mysql_fetch_assoc($res)){
                                    if(isset($row['shop_logo'])){  
                                        $logo = $row['shop_logo'];
                                        $f =  $_SERVER['DOCUMENT_ROOT'].$logo;
                                        if(!file_exists($f)){
                                            $default_logo = true;
                                        }
                                    }else{
                                         $default = true;
                                    }
                                }
                            }else{
                                 $default_logo = true;
                            }

                            if($default_logo){
                                $logo = '/media/images/flaberry.png';
                            }
                        ?>
                        <img src="<?php echo "http://".$_SERVER['HTTP_HOST'].$logo ?>" style="width:200px; height:48px; margin:1em 2em 1em 1.5em; background:#009ACD;" id="flaberry_icon_clicked">
                    </li>

                     <!-- End Edited 20/06/2015 -->


                    <?php
                    if (isset($finalResource)) {
                        foreach ($finalResource as $resource) {
                            foreach ($resource as $key => $resData) {
                                if (isset($resData) && is_array($resData)) {
                                    if ($resData['root_parent'] === $resData['parent'] && $resData['parent'] === 'self') {
                                        if (isset($resData['access_tag']) && $key === $resData['access_tag']) {
                                            $parent = $resData['id'];
                                            $class_name = '';
                                            if (isset($icons_css_class[$resData['access_tag']])) {
                                                $class_name = $icons_css_class[$resData['access_tag']];
                                            }
                                            if (isset($resData[$resData['access_tag']])) {
                                                $rupya =  '';
                                                if( isset($resData['access_tag']) && $resData['access_tag'] == 'my_credits'){
                                                    $rupya = '&#8377;';
                                                }
                                                //YESC
                                                echo '<li class="header_li">';
                                                echo '<a href="' . $base_path . $resData['url'] . '" class="navigation_links blue_links"><span class="' . $class_name . '"></span> ' . $resData['name'] . '</a>';
                                                echo '<ul>';
                                               
                                                $childs = $resData[$resData['access_tag']];
                                                $sub_ch_html = '';
                                                foreach ($childs as $key => $childRoot) {
                                                    if (isset($childRoot) && is_array($childRoot) && $parent == $childRoot['parent']) {
                                                        // 3rd CHILD
                                                        $ch_parent = $childRoot['id'];

                                                        // YES - SUBCHILD
                                                        if (isset($childRoot[$childRoot['access_tag']])) {
                                                            $sub_ch_html .= '<li>';
                                                            $sub_ch_html .= '<a href="' . $base_path . $childRoot['url'] . '">' . $childRoot['name'] . '</a>';
                                                            $sub_ch_html .= '<ul>';

                                                            $sub_child = $childRoot[$childRoot['access_tag']];
                                                            foreach ($sub_child as $child) {
                                                                if ($child['parent'] == $ch_parent) {
                                                                    $sub_ch_html .= '<li><a href="' . $base_path . $child['url'] . '">' . $child['name'] . '</a></li>';
                                                                }
                                                            }
                                                            $sub_ch_html .= '</ul>';
                                                            $sub_ch_html .= '</li>';
                                                        } else {
                                                            $sub_ch_html .= '<li><a href="' . $base_path . $childRoot['url'] . '">' . $childRoot['name'] . '</a></li>';
                                                        }
                                                    }
                                                }
                                                echo $sub_ch_html;
                                                echo '</ul>';
                                            } else {
                                                 //NOC
                                                echo '<li class="header_li">';
                                                $rupya =  '';
                                                if( isset($resData['access_tag']) && $resData['access_tag'] == 'my_credits'){
                                                    $rupya = '&#8377;';
                                                    echo '<a href="' . $base_path . $resData['url'] . '" class="navigation_links blue_links"> <span class="' . $class_name . '"></span>' . $rupya.' '.$resData['name'] . '</a>';
                                                
                                                }else{
                                                    echo '<a href="' . $base_path . $resData['url'] . '" class="navigation_links blue_links"> <span class="' . $class_name . '"></span>' . $resData['name'] . '</a>';
                                                }
                                              
                                                echo '</li>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
            </div>

            <div class="container-fluid header_main_container2">
               
                <div class="row" style="background:#DCDCDC;height:30px;padding:5px;margin-top:-20px;">
                    <div class="col-xs-2 col-xs-offset-1 order_status_links">
                        <!-- <a href="<?php //echo $to_be_assigned; ?>" class="blue_links subhhead">Non-Forwarded (<?php echo $count_nonforwarded_orders; ?>)</a> -->
                        <form method='POST' action="<?php echo $base_module_path; ?>/createdorders/index.php">
                            <input type='hidden' name='header_clicked_state' value='non_forwarded'>
                            <input type='submit' class='btn btn-link subhhead blue_links' style='padding:0px; font-size:17px;' value="Non-Forwarded (<?php echo $count_nonforwarded_orders; ?>)">
                        </form>
                    </div>

                    <div class="col-xs-2 text-center order_status_links">
                        <!-- <a href="<?php //echo $awaiting_delivery_pickup; ?>" class="blue_links subhhead">Unacknowledged (<?php echo $count_unacknowledged_orders; ?>)</a> -->
                        <form method='POST' action="<?php echo $base_module_path; ?>/order/index.php">
                            <input type='hidden' name='header_clicked_state' value='unacknowledged'>
                            <input type='submit' class='btn btn-link subhhead blue_links' style='padding:0px; font-size:17px;' value="Unacknowledged (<?php echo $count_unacknowledged_orders; ?>)">
                        </form>
                    </div>

                    <div class="col-xs-2 text-center order_status_links">
                        <!-- <a href="<?php //echo $oh_hold; ?>" class="blue_links subhhead">Acknowledged (<?php echo $count_acknowledged_orders; ?>)</a> -->
                        <form method='POST' action="<?php echo $base_module_path; ?>/order/index.php">
                            <input type='hidden' name='header_clicked_state' value='acknowledged'>
                            <input type='submit' class='btn btn-link subhhead blue_links' style='padding:0px; font-size:17px;' value="Acknowledged (<?php echo $count_acknowledged_orders; ?>)">
                        </form>
                    </div>

                    <div class="col-xs-2 text-center order_status_links">
                        <!-- <a href="<?php //echo $out_for_delivery; ?>" class="blue_links subhhead">Shipped (<?php echo $count_shipped_orders; ?>)</a> -->
                        <form method='POST' action="<?php echo $base_module_path; ?>/order/index.php">
                            <input type='hidden' name='header_clicked_state' value='shipped'>
                            <input type='submit' class='btn btn-link subhhead blue_links' style='padding:0px; font-size:17px;' value="Shipped (<?php echo $count_shipped_orders; ?>)">
                        </form>
                    </div>

                    <div class="col-xs-2 text-center">
                        <!-- <a href="<?php //echo $order_sent_out; ?>" class="blue_links subhhead">Delivered (<?php echo $count_delivered_orders; ?>)</a> -->
                        <form method='POST' action="<?php echo $base_module_path; ?>/order/index.php">
                            <input type='hidden' name='header_clicked_state' value='delivered'>
                            <input type='submit' class='btn btn-link subhhead blue_links' style='padding:0px; font-size:17px;' value="Delivered (<?php echo $count_delivered_orders; ?>)">
                        </form>
                    </div>
                </div>
            </div>

            <div class="stk-sidebar" id='stk-admin-sidebar'>
                &nbsp;&nbsp;&nbsp;&nbsp;Admin Panel&nbsp;&nbsp;&nbsp;&nbsp;
            </div>

            <div id="stk-admin-nav" class="stk-admin-link stk-sidebar">
                <ul>
                    <li><a href="<?php echo $base_path . '/app/admin/user/new-user.php'; ?>"> Add User </a></li>
                    <li><a href="<?php echo $base_path . '/app/admin/manageitems.php'; ?>"> Manage Items </a></li>
                    <li><a href="<?php echo $base_path . '/app/admin/user/add-role.php'; ?>"> Add Role </a></li>
                    <li><a href="<?php echo $base_path.'/app/module/resource/resource.php'; ?>"> Add Resource </a></li>
                    <li><a href="<?php echo $base_path . '/app/admin/products_items_configuration.php'; ?>"> Product Description</a></li>
                    <li><a href="<?php echo $base_path . '/app/admin/email_configuration.php'; ?>">Vendor Email Setup</a></li>
                </ul>
            </div>


            
            <script>
                function logoutUser() {
                    var http = new XMLHttpRequest();
                    var url = "<?php echo $base_path; ?>/app/admin/user/logout.php";
                    var params = "action=logout";
                    http.open("POST", url, true);
                    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    http.onreadystatechange = function() {
                        if (http.readyState == 4 && http.status == 200) {
                            window.location = "<?php echo $base_path; ?>";
                        }
                    }
                    http.send(params);
                }


                /** To show sticky admin link panel **/
                (function() {
                    var is_admin = <?php echo $is_admin; ?>;
                    $('#stk-admin-sidebar').hide();
                    $('#stk-admin-nav').hide();
                    if (is_admin) {
                        $('#stk-admin-sidebar').show();
                        // on click slide and show link 
                        $('#stk-admin-sidebar').on('click', function() {
                            $('#stk-admin-nav').toggle();
                        });
                    }
                })();
                /** To show sticky admin link panel end here**/

                $(document).ready(function() {
                    $(document).on('mouseover', '#flaberry_icon_clicked', function() {
                        $("body").css("cursor", "pointer");
                    });
                    $(document).on('mouseout', '#flaberry_icon_clicked', function() {
                        $("body").css("cursor", "default");
                    });
                    $(document).on('click', '#flaberry_icon_clicked', function() {
                        var user = <?php echo $user ?>;
                        var shop = <?php echo $shop ?>;
                        if (typeof user === 'number' && user !== 0) {
                            if (typeof shop === 'number' && shop !== 0) {
                                window.location = "/";
                            } else {
                                window.location = "javascript:void(0)";
                            }
                        } else {
                            window.location = "javascript:void(0)";
                        }
                    });
                });
            </script>

<?php
    include $base_path_folder."/app/module/notifications/view_notification.php";
?>