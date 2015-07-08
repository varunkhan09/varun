<?php
	session_start();

        $base_path = "http://".$_SERVER['HTTP_HOST'];
        $base_media_url = $base_path."/media";
        $base_media_css_url = $base_path."/media/css";
        $base_media_js_url = $base_path."/media/js";
        $base_media_image_url = $base_path."/media/images";
        $base_files_url = $base_path."/app/module";
        $base_module_path = $base_path."/app/module";


        $base_path_folder = $_SERVER['DOCUMENT_ROOT'];
        $path_to_mageapi = "/var/www/html/magento/app";

        $shop_id = isset($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
        /* THIS VARIABLE IS USED BY CREATE ORDER PANEL TO RECORD USER NAME CREATING THE ORDER */
        $user = explode('@', $_SESSION['loggedin']['email']);
        $user_name = $user[0];
        /* THIS VARIABLE IS USED BY CREATE ORDER PANEL TO RECORD USER NAME CREATING THE ORDER */

        $vendor_id=$shop_id;
	$flaberry_self_shop_id = 1;
        $flaberry_self_shop_name = "Flaberry";
?>

