<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/module/common/header.php";
if (isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/app/module/track-order/track-device.class.php";
    $trackDevice = new TrackedDevice();
    if (isset($_SESSION['user']['shop_id'])) {
        $shop_id = (int) $_SESSION['user']['shop_id'];
    }
    ?>



    <style type="text/css">
      
        .ui-dialog .ui-state-error { padding: .3em; }
        .validateTips { border: 1px solid transparent; padding: 0.3em; }
        .ui-dialog .ui-dialog-content{ padding-top: 0em;padding-bottom: 0em;}        
        .ui-dialog .ui-dialog-titlebar{width: 100%;};
        .ui-dialog .ui-dialog-buttonpane{margin-top: 0px;}

        label, input { display:block; }

        input.text { margin-bottom:12px; width:100%; padding: .25em; font-size: 16px;font-family: Open Sans; }
        fieldset { padding:0; border:0; margin-top:25px; }

        div#dialog-form{height: 175px;}
        div#device-contain { width: 80%; margin: 10%; font-size: 16px;font-family: Open Sans;}
        div#device-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
        div#device-contain table td, div#device-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
        div#device-contain table td, div#device-contain table th{text-align: left;border:0px !important;}
        
       
        table{max-width:100%;white-space:nowrap;}
        tbody tr td >.txt-dec{text-decoration: none;color: #009ACD;}

        .device-lbl{font-weight: normal;margin-bottom: 0px;font-size: 16px;font-family: Open Sans;}
        
        #topspace{ margin-top: 10%;}
        #btn-create-device{
	        background: #009ACD;
	        font-weight: bold;
	        color: #FFFFFF;
    	}

	   .tracker-device-heading{
	    	background: #009ACD;
	    	height: 50px;
	    	padding: 10px 15px;
	    	font-weight: bold;
	        color: white;
	        border: 0 none;
	   }

    </style>

    <!-- <div id="topspace"></div> -->

    <div id="dialog-form" title="Traking device">
        <form id="track-device-form">
            <fieldset>
                <input type="hidden" id="dev-id" name="id">
                <input type="hidden" id="shop-id" name="shop_id" value="<?php echo $shop_id; ?>">
                <label class="device-lbl" for="device-id">Enter Device Id</label>
                <input type="text" name="device_id" id="device-id" autocomplete="off" class="text ui-widget-content ui-corner-all">
                <label class="device-lbl" for="name">Enter Name</label>
                <input type="text" name="name" id="name" autocomplete="off" class="text ui-widget-content ui-corner-all">
                <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
            </fieldset>
        </form>
    </div>


    <!-- TABLE -->

    <div id="device-contain" class="ui-widget">
   <!-- 	ui-widget ui-widget-content -->
        <table id="device" style="background:#ccc;margin-top:-5px;" class="table table-hover">
         <div class="tracker-device-heading"> Tracker Device List </div>
            <thead>
                <!-- <tr class="ui-widget-header "> -->
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Device Id</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $deviceList = $trackDevice->getDeviceListByShop($shop_id);
                foreach ($deviceList as $key => $device) {
                    $sno = $key + 1;
                    echo "<tr id='device_{$sno}'>   
						<td> {$sno} </td>
						<td> {$device->name} </td>
						<td> {$device->device_id} </td>
						<td> <a class='txt-dec' id='edit_device_{$device->id}' href='javascript:void(0)'>Edit</a> </td>
						<td> <a class='txt-dec' id='delete_device_{$device->id}' href='javascript:void(0)'>Delete</a> </td>
						</tr>";
                }
                ?>
            </tbody>
        </table>

        <button id="btn-create-device">Add device</button>
    </div>
    <div id="trck-msg" style="font-size:12px;"></div>
    <div id="trck-msg-dialog-confirm" style="font-size:12px;"></div>


    <?php
}
?>


<script>
    $(document).ready(function() {

        $("#device-id").keydown(function(e) {
            if (e.shiftKey || e.ctrlKey || e.altKey) {
                e.preventDefault();
            } else {
                var key = e.keyCode;
                if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
                    e.preventDefault();
                }
            }
        });



        $('#device a').click(function() {
            var id, device, token;
            id = $(this).attr('id');
            token = id.split('_');
            if (token[0] === 'edit') {
                /* Device id [AUTO INCREMENTED VALUE] NOT DEVICE ID */
                device = parseInt(token[token.length - 1], 10);

                var base_path = "<?php echo $base_path; ?>";
                var shop_id = "<?php echo $shop_id; ?>";
                $.ajax({
                    url: base_path + "/app/module/track-order/device-tracker-helper.php",
                    type: "POST",
                    data: {
                        action: "get_shop_tracking_device",
                        id: device,
                        shop_id: shop_id  // shop id from session variable
                    },
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);
                        $('#dev-id').val(response.id);
                        $('#shop-id').val(response.shop_id);
                        $('#device-id').val(response.device_id);
                        $('#name').val(response.name);
                        /* To open Dialog box */
                        $("#btn-create-device").trigger("click");


                    }, error: function(xhr, status, erro) {
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });



            } else if (token[0] === 'delete') {

                device = parseInt(token[token.length - 1], 10);
                $("#trck-msg-dialog-confirm").html("Do you really want to delete ?");
                $("#trck-msg-dialog-confirm").dialog({
                    resizable: false,
                    modal: true,
                    title: "<?php echo $base_path ?>" + ' Says...',
                    height: 175,
                    width: 400,
                    buttons: {
                        "Yes": function() {

                            delete_callback(true, device);
                            $(this).dialog('close');
                        },
                        "No": function() {

                            delete_callback(false, device);
                            $(this).dialog('close');
                        }
                    }
                });
            }
        });
        function delete_callback(stat, device) {
            console.log(device);
            var base_path = "<?php echo $base_path; ?>";
            var shop_id = "<?php echo $shop_id; ?>";
            $.ajax({
                url: base_path + "/app/module/track-order/device-tracker-helper.php",
                type: "POST",
                data: {
                    action: "delete_shop_tracking_device",
                    id: device,
                    shop_id: shop_id
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.statusCode == 'OK') {
                        window.location.reload();
                    }
                }
            });
        }
    });




    $(function() {
        var dialog, form;
        var name = $("#name");
        var device_id = $("#device-id");
        var allFields = $([]).add(name).add(device_id);
        function addShopDevice() {
            var valid = true;
            var formData = $('#track-device-form').serialize();
            console.log(formData);

            var base_path = "<?php echo $base_path; ?>";
            $.ajax({
                url: base_path + "/app/module/track-order/device-tracker-helper.php",
                type: "POST",
                data: {
                    action: "save_tracking_device",
                    data: formData,
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    if (response.statusCode == 'OK') {
                        $("#trck-msg").html(response.statusText);
                        $("#trck-msg").dialog({
                            resizable: false,
                            modal: true,
                            title: "<?php echo $base_path ?>" + ' Says...',
                            height: 175,
                            width: 400,
                            buttons: {
                                "OK": function() {
                                    callback(true);
                                    $(this).dialog('close');
                                },
                                "Cancel": function() {
                                    callback(false);
                                    $(this).dialog('close');
                                }
                            }
                        });

                        function callback(value) {
                            dialog.dialog("close");
                            window.location.reload();
                        }

                    } else if (response.statusCode == 'ERROR' || response.statusCode == 'SQL_ERROR') {
                        $("#trck-msg").html(response.statusText);
                        $("#trck-msg").dialog({
                            resizable: false,
                            modal: true,
                            title: "<?php echo $base_path ?>" + ' Says...',
                            height: 175,
                            width: 400,
                            buttons: {
                                "OK": function() {
                                    callback(true);
                                    $(this).dialog('close');
                                },
                                "Cancel": function() {
                                    callback(false);
                                    $(this).dialog('close');
                                }
                            }
                        });
                        function callback(value) {
                            dialog.dialog("close");
                            window.location.reload();
                        }
                    }
                }
            });
            return valid;
        }

        dialog = $("#dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 450,
            modal: true,
            buttons: {
                "Submit": addShopDevice,
                Cancel: function() {
                    dialog.dialog("close");
                }
            },
            close: function() {
                form[ 0 ].reset();
                //allFields.removeClass( "ui-state-error" );
            }
        });

        form = dialog.find("#track-device-form").on("submit", function(event) {
            event.preventDefault();
            addShopDevice();
        });

        $("#btn-create-device").button().on("click", function() {
            dialog.dialog("open");
        });
    });
</script>