<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/global_variables.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/app/module/common/header.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/app/etc/dbconfig.php";

if(isset($_SESSION['loggedin']['user']['shop_id'])){
	header("Location: ".$base_path);
}

$contact_person = "";
$user_id = null;
if (isset($_SESSION['loggedin'])) {
    $user_id = isset($_SESSION['loggedin']['user_id']) ? $_SESSION['loggedin']['user_id'] : null;
    if (!is_null($user_id)) {
        $query = "SELECT firstname,lastname from pos_user_entity where entity_id = $user_id";
        $result = mysql_query($query);
        if (mysql_num_rows($result)) {
            $row = mysql_fetch_object($result);
            $contact_person = $row->firstname . " " . $row->lastname;
        }
    }
}


// when shop is not present then show config page to set config else not 
if (!isset($_SESSION['loggedin']['user']['shop_id'])) {
?>

<style>
    /*custom font*/
   /* @import url(http://fonts.googleapis.com/css?family=Montserrat);
*/
    /*basic reset*/
    * {margin: 0; padding: 0;}

    html {
        height: 100%;
    }
    /*form styles*/
    #shop-config-form {
        width: 70%;
        margin: 30px auto;
        text-align: center;
        position: relative;
    }
    #shop-config-form fieldset {
        background: white;
        border: 0 none;
        border-radius: 3px;
        box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.2);
        /*padding: 15px 30px;*/
        padding: 20px 50px;
        box-sizing: border-box;
        width: 83%;
        margin: 0 10%;
        /*stacking fieldsets above each other*/
        position: absolute;
    }
    /*Hide all except first fieldset*/
    #shop-config-form fieldset:not(:first-of-type) {
        display: none;
    }
    /*inputs*/
    #shop-config-form input, #shop-config-form textarea {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-bottom: 5px;
        width: 100%;
        box-sizing: border-box;
        /*font-family: montserrat;*/
        color: #2C3E50;
        font-size: 13px;
    }
    /*buttons*/
    #shop-config-form .action-button {
        width: 100px;
        background: #009ACD;
        font-weight: bold;
        color: white;
        border: 0 none;
        /*border-radius: 1px;*/
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px;
    }
    #shop-config-form .action-button:hover, #shop-config-form .action-button:focus {
        /*box-shadow: 0 0 0 2px white, 0 0 0 3px #009ACD;*/
    }
    /*headings*/
    .shop-config-fset-title {
        font-size: 15px;
        text-transform: uppercase;
        color: #009ACD;
        font-weight: bold;
        margin-bottom: 5px;
        margin-top: 2px;
    }
    .fs-subtitle {
        font-weight: normal;
        font-size: 13px;
        color: #666;
        margin-bottom: 20px;
    }
    /*shop-config-progress-bar*/
    #shop-config-progress-bar {
        margin-bottom: 20px;
        overflow: hidden;
        /*CSS counters to number the steps*/
        counter-reset: step;
    }
    #shop-config-progress-bar li {
        list-style-type: none;
        /*color: white;*/
        text-transform: uppercase;
        font-size: 14px;
        font-weight: 700;
        width: 33.33%;
        float: left;
      /*  margin-top: 2.5%;*/
        position: relative;
    }
    #shop-config-progress-bar li:before {
        content: counter(step);
        counter-increment: step;
        width: 20px;
        line-height: 20px;
        display: block;
        font-size: 10px;
        color: #333;
        background: white;
        border-radius: 3px;
        margin: 0 auto 5px auto;
    }
    /*shop-config-progress-bar connectors*/
    #shop-config-progress-bar li:after {
        content: '';
        width: 100%;
        height: 5px;
        background: white;
        position: absolute;
        left: -50%;
        top: 9px;
        z-index: -1; /*put it behind the numbers*/
    }
    #shop-config-progress-bar li:first-child:after {
        /*connector not needed before the first step*/
        content: none; 
    }
    /*marking active/completed steps green*/
    /*The number of the step and the connector before it = green*/
    #shop-config-progress-bar li.active:before,  #shop-config-progress-bar li.active:after{
        background: #009ACD;
        color: white;
    }
    .required{
    	font-size:.7em; color:#FF0000; float:left;
    }
</style>
<!-- multistep form -->
<br><br><br><br>
<form id="shop-config-form">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <!-- shop-config-progress-bar -->
    <ul id="shop-config-progress-bar">
        <li class="active">Shop Setup</li>
        <li>Delivery Charge Setup</li>
        <li>Delivery Pincode Setup</li>
    </ul>

    <!-- fieldsets -->
    <fieldset>
        <h4 class="shop-config-fset-title">CREATE YOUR SHOP</h4>
        <span class="required">*required.</span>
        <input required type="text" name="shop_name" placeholder="Shop name" onkeypress="return textonly(event);" />
       
        <span class="required">*required.</span>
        <input required type="text" name="email" placeholder="Email" />
        
        <span class="required">*required.</span>
        <input required type="text" maxlength="15" name="phoneno" placeholder="Phone number" onkeypress="return phoneText(event);"/>
        <input type="text" name="website_url" placeholder="Website URL" />
        
        <input type="text" name="address" placeholder="Address" />
        <input type="text" name="city" placeholder="City" />
        <input type="text" maxlength="6" name="pincode" placeholder="Pincode" onkeypress="return numberonly(event);"/>
        <input type="text" readonly name="contact_person" placeholder="Contact person" value="<?php echo $contact_person; ?>" onkeypress="return textonly(event);"/>

        <input type="button" name="next" class="next action-button" value="Next" />

    </fieldset>

    <fieldset>

        <h4 class="shop-config-fset-title">DELIVERY CHARGE SETUP</h4>
        <span class="required">*required.</span>

        <input required class="price" type="text" name="regular_delivery_charge" placeholder="Regular Delivery Charge" />
        <span class="required">*required.</span>

        <input required class="price" type="text" name="midnight_delivery_charge" placeholder="Midnight Delivery Charge" />
        <span class="required">*required.</span>

        <input required class="price" type="text" name="fixedtime_delivery_charge" placeholder="FixedTime Delivery Charge" />
        

        <input type="button" name="previous" class="previous action-button" value="Previous" />
        <input type="button" name="next" class="next action-button" value="Next" />
    </fieldset>


    <fieldset>

        <h4 class="shop-config-fset-title">DELIVERY PINCODE SETUP</h4>
        <h6 style="float:left;color:red;">Note: Pincode must be separated by commas (,) and number only</h6>
        <textarea name="shop_delivery_pincode" placeholder="Enter delivery pincode"></textarea>


        <input type="button" name="previous" class="previous action-button" value="Previous" />
        <input id="btn-submit" type="button" name="submit" class="submit action-button" value="Submit" />
    </fieldset>
</form>

<?php 
}
?>

<script src="<?php echo $base_media_js_url; ?>/jquery.easing.min.js"></script>
<script>

                    $("input[class=price]").keydown(function(e) {
                        if (e.shiftKey || e.ctrlKey || e.altKey) {
                            e.preventDefault();
                        } else {
                            var key = e.keyCode;
                            if (!((key == 8) || (key == 46) || (key == 190) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
                                e.preventDefault();
                            }
                        }
                    });

                    function numberonly(e) {
                        var code;
                        if (!e)
                            var e = window.event;
                        if (e.keyCode)
                            code = e.keyCode;
                        else if (e.which)
                            code = e.which;
                        var character = String.fromCharCode(code);
                        var AllowRegex = /^\d+$/; // /^[\b0-9\s-]$/;
                        if (AllowRegex.test(character))
                            return true;
                        return false;
                    }

                    $("textarea[name=shop_delivery_pincode]").keydown(function(e) {
                        if (e.shiftKey || e.ctrlKey || e.altKey) {
                            e.preventDefault();
                        } else {
                            var key = e.keyCode;

                            if (!((key == 8) || (key == 46) || (key == 188) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
                                e.preventDefault();
                            }
                        }
                    });

                    function textonly(e) {
                        var code;
                        if (!e)
                            var e = window.event;
                        if (e.keyCode)
                            code = e.keyCode;
                        else if (e.which)
                            code = e.which;
                        if (code === 45)
                            return false;
                        var character = String.fromCharCode(code);
                        var AllowRegex = /^[\ba-zA-Z\s-]$/;
                        if (AllowRegex.test(character))
                            return true;
                        return false;
                    }

                    function phoneText(e) {
                        var code;
                        if (!e)
                            var e = window.event;
                        if (e.keyCode)
                            code = e.keyCode;
                        else if (e.which)
                            code = e.which;
                        var character = String.fromCharCode(code);
                        var AllowRegex = /^[\b+0-9\s-]$/;
                        if (AllowRegex.test(character))
                            return true;
                        return false;
                    }

//jQuery time
                    var current_fs, next_fs, previous_fs; //fieldsets
                    var left, opacity, scale; //fieldset properties which we will animate
                    var animating; //flag to prevent quick multi-click glitches

                    $(".next").click(function() {
                        if (animating)
                            return false;
                        animating = true;

                        current_fs = $(this).parent();
                        next_fs = $(this).parent().next();

                        //activate next step on shop-config-progress-bar using the index of next_fs
                        $("#shop-config-progress-bar li").eq($("fieldset").index(next_fs)).addClass("active");

                        //show the next fieldset
                        next_fs.show();
                        //hide the current fieldset with style
                        current_fs.animate({opacity: 0}, {
                            step: function(now, mx) {
                                //as the opacity of current_fs reduces to 0 - stored in "now"
                                //1. scale current_fs down to 80%
                                scale = 1 - (1 - now) * 0.2;
                                //2. bring next_fs from the right(50%)
                                left = (now * 50) + "%";
                                //3. increase opacity of next_fs to 1 as it moves in
                                opacity = 1 - now;
                                current_fs.css({'transform': 'scale(' + scale + ')'});
                                next_fs.css({'left': left, 'opacity': opacity});
                            },
                            duration: 1000,
                            complete: function() {
                                //alert('hi');
                                current_fs.hide();
                                animating = false;

                            },
                            //this comes from the custom easing plugin
                            easing: 'easeInOutBack'
                        });
                    });

                    $(".previous").click(function() {
                        if (animating)
                            return false;
                        animating = true;

                        current_fs = $(this).parent();
                        previous_fs = $(this).parent().prev();

                        //de-activate current step on shop-config-progress-bar
                        $("#shop-config-progress-bar li").eq($("fieldset").index(current_fs)).removeClass("active");

                        //show the previous fieldset
                        previous_fs.show();
                        //hide the current fieldset with style
                        current_fs.animate({opacity: 0}, {
                            step: function(now, mx) {
                                //as the opacity of current_fs reduces to 0 - stored in "now"
                                //1. scale previous_fs from 80% to 100%
                                scale = 0.8 + (1 - now) * 0.2;
                                //2. take current_fs to the right(50%) - from 0%
                                left = ((1 - now) * 50) + "%";
                                //3. increase opacity of previous_fs to 1 as it moves in
                                opacity = 1 - now;
                                current_fs.css({'left': left});
                                previous_fs.css({'transform': 'scale(' + scale + ')', 'opacity': opacity});
                            },
                            duration: 1000,
                            complete: function() {
                                current_fs.hide();
                                animating = false;
                            },
                            //this comes from the custom easing plugin
                            easing: 'easeInOutBack'
                        });
                    });


                    $("#btn-submit").click(function() {
                        var formData = $('#shop-config-form').serialize();
                     //   console.log(formData);
                        var base_path = "<?php echo $base_path ?>";
                        var vendor_id = "<?php echo $user_id; ?>";
                        $.ajax({
                            url: base_path + "/app/module/shop-config/shop-config-helper.php",
                            type: "POST",
                            data: {
                                action: "shop_config_action",
                                data: formData,
                            },
                            dataType: "JSON",
                            success: function(response) {
                                console.log(response);
                                if(response.statusCode == "OK"){
                                	var path = "<?php echo $base_path; ?>"+"/home.php";
                                	window.location= path;
                                }
                            }
                        });
                    });

</script>

