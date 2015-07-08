<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
	include_once $_SERVER['DOCUMENT_ROOT'].'/app/module/myshop/vendorprice/vendor.class.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/app/module/myshop/vendorprice/item.class.php';

	$vendorObj = new Vendor();
	$itemObj = new Item();

    $shop_id = isset($_SESSION['loggedin']['user']['shop_id']) ? $_SESSION['loggedin']['user']['shop_id'] : null;

?>

<style>
    .form-item {
        margin: 10% 10% 2% 10%;
    }
    div#dialog-confirm-vmsg{
    	font-size: 13px;
    }
</style>


<div class="panel panel-default form-item">
        <div class="panel-heading text-center">
            <strong class="headingname">Vendor's Item</strong>
        </div>
        <div class="panel-body" id="vendors-items">
           
            <div id="v-list" class="col-xs-6">
             	<div class="form-group">
                    <h4 style="color:#009ACD;"><strong>Vendors</strong></h4>
                    
             	<!-- 	<label for="cmb-vlist">Vendors </label> -->
	            	<div id="cmb-vlist">
        			 <select id="vendor-list" class="form-control" name="vendorlist">
        			 	<option value="">--Select--</option>
        			 		<?php 
                            $vendorlist = $vendorObj->getShopVendorsList($shop_id);
        			 		//$vendorlist = $vendorObj->getVendorsList();
        			 		foreach ($vendorlist as $key => $vendor) {
        			 			$id =  $key+1;
                                echo "<option id= vendor-{$id} value='".$vendor->shop_id."'>".$vendor->shop_name."</option>";
        						//echo "<option id= vendor-{$id} value='".$vendor->entity_id."'>".$vendor->firstname." ".$vendor->lastname."</option>";
        			 		}
        			 		?>
        			 </select>
	            	</div>
             	</div>
            </div>

            <div id="item-list">
	            <div class="col-xs-12 col-sm-6 col-lg-8">
                <h4 style="color:#009ACD;"><strong>Item's List</strong></h4>
	            <form id="vendors_items_form" name="vendors_items_form">
	            	<?php
                    //print_r($_SESSION);
	            		$itemlist = $itemObj->getItemsList();
	            		foreach ($itemlist as $key => $item) {
        			 	?>
        			 		<div class="form-group" id="item-list-<?php echo $item->entity_id; ?>">
        			 			<span for="item-price-<?php echo $item->entity_id ;?>" class="control-label col-sm-6">
        			 			<?php echo $item->item_name; ?>
        			 			</span>
        			 			<div class="col-sm-3">
										<input type="text" class="form-control" name="<?php echo $item->entity_id.'_'.$item->id ;?>" id="item-price-<?php echo $item->entity_id ;?>" value="<?php echo $item->price;?>" placeholder="Price">
        			 			</div>
        			 		</div>
        			 	<?php		
        			 	}
	            	?>
	            	</form>
	            </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-lg-8" id="save_vendor_items">
            	<input type="button" id="btn_save_vendor_items" class="btn buttons" value="Submit">  
            </div>
        </div>

        <!-- confirmation dialog box -->
        <div id="dialog-confirm-vmsg"></div>
</div>


<script type="text/javascript">

$(document).ready(function(){
	$("input[type=text]").keydown(function (e) {
	if (e.shiftKey || e.ctrlKey || e.altKey) {
	e.preventDefault();
	} else {
			var key = e.keyCode;
			if (!((key == 8) || (key == 46)|| (key == 190) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
			e.preventDefault();
			}
		}
	});


	 $("#save_vendor_items input[type=button]").click(function(e){
	 	var vendor_id;
	 	$('#vendor-list').each(function () {
    		vendor_id = $(this).find('option:selected').val();
		});
        var base_path =  "<?php echo $base_path ?>";
	 	var form_data =  $("#vendors_items_form" ).serialize();
	 	 $.ajax({
                url: base_path+"/app/module/myshop/vendorprice/vendor-helper.php",
                type: "POST",
                data: {
                    action: "save_vendor_items",
                    data: form_data,
                    vendor_id: vendor_id, 
                },
                dataType: "JSON",
                success: function (response) {
                	console.log(response);
                    if(response.code == 200 && response.text == 'OK'){
                    	$("#dialog-confirm-vmsg").html('Successfully submitted');
                        $("#dialog-confirm-vmsg").dialog({
                            resizable: false,
                            modal: true,
                            title: "<?php echo $base_path?>"+ ' Says...',
                            height: 175,
                            width: 400,
                            buttons: {
                                "OK": function () {
                                    $(this).dialog('close');
                                },
                                "Cancel": function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }else{
                    	$("#dialog-confirm-vmsg").html('Opps !.. Something wrong, Try again.');
                        $("#dialog-confirm-vmsg").dialog({
                            resizable: false,
                            modal: true,
                            title: "<?php echo $base_path?>"+ ' Says...',
                            height: 175,
                            width: 400,
                            buttons: {
                                "OK": function () {
                                    $(this).dialog('close');
                                    window.location.reload();
                                },
                                "Cancel": function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                }
         });
	 })


	$("#vendor-list").change(function () {
        var base_path =  "<?php echo $base_path ?>";
      	var vendor_id = $(this).find('option:selected').val();
     	$.ajax({
                url: base_path+"/app/module/myshop/vendorprice/vendor-helper.php",
                type: "POST",
                data: {
                    action: "set_vendor_id_to_load_items",
                    vendor_id: vendor_id, 
                },
                dataType: "JSON",
                success: function (response) {
                	if(!response.vendor_id){
                		window.location.reload();
                	}else{
                		response.data.forEach(function(item) {
	                		var id = 0;
	                		if(item.id !== null || item.id === undefined){
	                			id =  item.id;
	                		}
	                		$("#item-price-"+item.entity_id).removeAttr("name");
	                		$("#item-price-"+item.entity_id).val(item.price);
	                		$("#item-price-"+item.entity_id).attr('name', item.entity_id+'_'+id);
						});
                	}
                }
        });
    });
})
</script>
