<?php 
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT']."/global_variables.php"; 
?>


    <style type="text/css">    
    .panel_top_space{
        margin-top: 40px;
    }
    </style>

    <!-- Page Content -->
    <div class="container panel_top_space">
        <div class="row action-plate">
            <div class="col-xs-12">          
              <div class="row">

                <!-- for shop -->

                <div class="col-xs-3 each_panel_div text-center">

                    <?php 
                        $shop_href =  "#/"; 
                        if(isset($_SESSION['loggedin'])){
                            $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
                            $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
                            if(!is_null($shop_id)){
                                 $shop_href =  "#/user/{$_SESSION['loggedin']['user_id']}/shop/{$shop_id}";
                            }
                        }
                    ?>

                    <a href="<?php echo $shop_href ; ?>" class="blue_links">
                        <div class="each_panel_image_div">
                            <img src="<?php echo $base_media_image_url; ?>/shopping-bag.png" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            Shop Information
                        </div>
                    </a>
                </div>

                 <!-- for shop end -->


                <!--  <div class="col-xs-3 each_panel_div text-center">
                     <?php 
                        // $payment_href = "javascript:void(0);";
                        //  if(isset($_SESSION['loggedin'])){
                        //     $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
                        //     $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
                        //     if(!is_null($user_id) && (!is_null($shop_id))){
                        //          $payment_href =  "#/user/$user_id/shop/$shop_id/payment";
                        //     }
                        // }
                    ?>

                    <a href="<?php// echo $payment_href ; ?>" class="blue_links">
                        <div class="each_panel_image_div">
                            <img src="<?php //echo $base_media_image_url; ?>/payment.png" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            Payment Option                        
                        </div>
                    </a>
                </div>

 -->
                 <div class="col-xs-3 each_panel_div text-center">
                    <?php 
                        $delivery_href = "javascript:void(0);";
                         if(isset($_SESSION['loggedin'])){
                            $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
                            $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
                            if(!is_null($user_id) && (!is_null($shop_id))){
                                 $delivery_href =  "#/user/$user_id/shop/$shop_id/delivery";
                            }
                        }
                    ?>
                    <a href="<?php echo $delivery_href ; ?>" class="blue_links">
                        <div class="each_panel_image_div">
                            <img src="<?php echo $base_media_image_url; ?>/delivery-512.png" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            Delivery Management
                        </div>
                    </a>
                </div>


                 <!-- <div class="col-xs-3 each_panel_div text-center">
                    <?php 
                        // $ctlog_href = "javascript:void(0);";
                        //  if(isset($_SESSION['loggedin'])){
                        //     $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
                        //     $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
                        //     if(!is_null($user_id) && (!is_null($shop_id))){
                        //          $ctlog_href =  "#/user/$user_id/shop/$shop_id/catalog";
                        //     }
                        // }
                    ?>

                    <a href='<?php //echo $ctlog_href; ?>' class="blue_links">
                        <div class="each_panel_image_div">
                            <img src="<?php //echo $base_media_image_url; ?>/i-catalog.png" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            Catalog
                        </div>
                    </a>
                </div> -->


                <!--  <div class="col-xs-3 each_panel_div text-center">
                    <?php 
                        // $pos_href = "javascript:void(0);";
                        //  if(isset($_SESSION['loggedin'])){
                        //     $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
                        //     $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
                        //     if(!is_null($user_id) && (!is_null($shop_id))){
                        //          $pos_href =  "#/user/$user_id/shop/$shop_id/pos";
                        //     }
                        // }
                    ?>

                    <a href='<?php //echo $pos_href; ?>' class="blue_links">
                        <div class="each_panel_image_div">
                            <img src="<?php //echo $base_media_image_url; ?>/i-pos.jpg" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            POS
                        </div>
                    </a>
                </div>
 -->

                <div class="col-xs-3 each_panel_div text-center">
                    <?php
                            $emp_href = "javascript:void(0);";
                            if(isset($_SESSION['loggedin'])){
                                $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
                                $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;

                                if(!is_null($user_id) && (!is_null($shop_id))){
                                     $emp_href =  "#/user/$user_id/shop/$shop_id/emp";
                                }
                            }
                    ?>

                    <a href='<?php echo $emp_href; ?>' class="blue_links">
                        <div class="each_panel_image_div">
                            <img src="<?php echo $base_media_image_url; ?>/i-employee.png" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            Employee
                        </div>
                    </a>
                </div>

                <div class="col-xs-3 each_panel_div text-center">
                    <?php 
                        $report_href = "javascript:void(0);";
                         if(isset($_SESSION['loggedin'])){
                            $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
                            $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
                            if(!is_null($user_id) && (!is_null($shop_id))){
                                 $report_href =  "#/user/$user_id/shop/$shop_id/report";
                            }
                        }
                    ?>

                    <a href='<?php echo $report_href; ?>' class="blue_links">
                        <div class="each_panel_image_div">
                            <img src="<?php echo $base_media_image_url; ?>/i-report.jpg" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            Report
                        </div>
                    </a>
                </div>


                </div>
            </div>
       </div>
    </div>
    <!-- /.container -->


