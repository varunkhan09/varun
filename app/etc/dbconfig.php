<?php
        date_default_timezone_set("Asia/Kolkata");
        
        /* To set live database use $live = true, else keep false*/
        $live = false; 
      
        /****************************************/
        /* Please Don't change name of variable */
        /****************************************/

        if($live)
        {
            $server = 'localhost';
            $user = 'developer';
            $password = 'flaberry1122';
        }
        else
        {
            $server = 'localhost';
            $user = 'root';
            $password = 'password';
        }

        $magento_database = "floshowers";
        $vendorshop_database = "vendorshop_24june";
        $custom_database = "operations";

	    if($shop_id >= 51 )
        {
            $vendorshop_database = "vendorshop_24june";
        }

        mysql_connect($server, $user, $password) or die(mysql_error());
        mysql_select_db($vendorshop_database) or die(mysql_error());
?>

