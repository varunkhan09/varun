
<?php 
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT']."/global_variables.php"; 
?>

<!-- Page Content -->
    <div class="container">
        <div class="row action-plate" >
            <div class="col-xs-12" style="margin-top: 10%;">          
                <!-- Blank DIV for orientation in center-->    
                <div class="col-xs-3"></div>

                <div class="col-xs-3 each_panel_div text-center">

                    <?php 
                        $dzone_href = "#/dzone";
                      //  $dcharge_href = "#/dcharge";
                         if(isset($_SESSION['loggedin'])){
                            $user_id = is_numeric($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
                            $shop_id =  is_numeric($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;
                            if(!is_null($shop_id)){
                                $dzone_href =  "#/user/{$_SESSION['loggedin']['user_id']}/shop/{$shop_id}/dzone";
                               // $dcharge_href =  "#/user/{$_SESSION['loggedin']['user_id']}/shop/{$shop_id}/dcharge";
                            }
                        }
                    ?>

                    <a ng-href="<?php echo $dzone_href; ?>" class="blue_links">
                        <div class="each_panel_image_div">
                            <img ng-src="{{image_url}}/location-1.png" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            Delivery Zone
                        </div>
                    </a>
                </div>

                 <div class="col-xs-3 each_panel_div text-center">
                    <a ng-href="{{delivery_charge_url}}" class="blue_links">
                        <div class="each_panel_image_div">
                            <img ng-src="{{image_url}}/payment-1.png" class="panel_image">
                        </div>
                        <div class="each_lanel_label_div blue_links_homepage">
                            Delivery Charge
                        </div>
                    </a>
                </div>
            </div>
       </div>
    </div>
    <!-- /.container -->
