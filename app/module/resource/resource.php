<?php
/* @var $_SERVER type */
include_once $_SERVER['DOCUMENT_ROOT'] . "/global_variables.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/app/module/common/header.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/module/resource/module-resource.class.php';

if (isset($_SESSION['loggedin']['user']['is_admin'])) {
    $modules = new ModuleResource();
    ?>

    <!-- STYLESHEET-->	
    <style type="text/css">
        div.res-top{
            margin-top: 9%;
            padding: 0px 10px 10px 10px;
        }
        .row{
            margin: 0;
        }
        .resource{
            border-left: 2px solid #ccc;
            min-height: 350px;
            padding: 10px;
        }
        .resource-form-div{
            border:1px solid #ccc;
            padding:10px;
            border-radius: 0 0 5px 5px;
        }
        h4.resource-title{
            margin: -10px -10px 10px -10px;
            padding-bottom: 10px;
        }
        .resource-header-title{
            font-weight: bold;
            background-color: #009acd;
            color:#fff;
            padding:10px;
            margin: 0px -10px 0px -10px;
        }
        .resource-form{
            font-weight: bold;
        }
        
        .resource-list{
             position: absolute;
            background: white;
            height: 500px;
            width:99%;
            overflow-y: scroll;
            border:1px solid #ccc;
            margin: 0 -10px 0 -10px;
            padding:10px;
        }
        .resource-title{
            font-weight: bold;
            background-color: #009acd;
            color:#fff;
            padding:10px;
        }
        .resource-header{
            margin-top:0px;
        }
         .list-module{
           border-bottom: 2px dashed #ccc;
           padding-bottom:10px; 
       }
       .module-name{
        font-weight: bold;
        padding-bottom: 5px;
       } 
       .module-name-url{
            min-width: 85%;
            min-height: 2px;
            float:left;
        }
        .btn-edit-module{
            background-color: #009acd;
            color:#fff;
            width:100px;
        font-weight: bold;
        }
        .required{
        	color: #FF0000;
        	font-weight: bold;
        }
        .lbl-sub-module{
            font-weight: normal;
            padding-top:5px;
        }
        #btn-new-resource{
            width:135px;
            background-color: #009acd;
            color:#fff;
            font-weight: bold;
        }
    </style>
    <!-- STYLESHEET END -->	

    <!-- FORM DESIGN -->
    <div class="row res-top">

        <div class="col-xs-6 col-md-4 resource-form-div">

            <form id="resource-form" role="form" name='resource_entry_form'>
                <h4 class="resource-title">Resource Form</h4>
                <input type="hidden" id="is_edit_resource" name="resource_edit_id" value="">
                <div class="form-group">
                    <lable for='res_name' >Name <span class='required'>*</span></lable>
                    <input type="text" class="form-control" autocomplete="off" id="res_name" name="resource_name">
                </div>
                
                <div class="form-group">
                    <lable for='res_name' >Url </lable>
                    <input type="text" class="form-control" autocomplete="off" id="res_url" name="resource_url">
                </div>
                
                <div class="form-group" id="main-parent-resource">
                    <lable for='res_name'>Select Parent</lable>
                    <select name="resource_parent"  class="main-parent-resource-module form-control">
                        <option value="">--Select--</option>
                        <?php
                        $moduleList = $modules->getModuleList();
                        foreach ($moduleList as $key => $value) {
                            echo "<option id=" . 'moduleid_' . $value->module_id . " value=" . $value->module_backend_code . "_" . $value->module_id . ">{$value->module_name}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <input type="hidden" class="form-control" autocomplete="off" id="resource_main_parent" name="resource_main_parent" value="">
                </div>
                
                <div class="form-group">
                    <input type="button" class="form-control" name="btn_create_new_res" id="btn-new-resource" value="Submit">
                </div>
            </form>
        </div>
        
        <div class="col-xs-12 col-sm-6 col-md-8 resources">
            <div id='resources'>
                <div class="resource-header">
                   <h4 class="resource-header-title">Resource List</h4>
                </div>
                <div class="resource-list">
                </div>
            </div>
        </div>
    </div>
    <!-- FORM DESIGN END-->
    <?php
}
?>

<script type="text/javascript">
    (function() {
        initResource = function() {
            var base_path = "<?php echo $base_path ?>";
            $.ajax({
                url: base_path + "/app/module/resource/resource-helper.php",
                type: "POST",
                data: {
                    action: "get_all_module_list"
                },
                dataType: "JSON",
                success: function(response) {
                    //console.log(response);
                    if(response !== null || resource !== undefined){
                    	var resLen = response.length;
                    	if(resLen){
                    		var edit_mid = 0; // int type value 
                    		edit_mid =  $('#is_edit_resource').val();

                    		if(edit_mid){
                    		//	alert(edit_mid);
                    		    var st = '.list-module #module_url_'+edit_mid;

                    		    $.each(response, function(i, item) {
                		    		if(item.module_id === edit_mid){
                		    			$(st).html(item.module_url);
                		    		}

                		    		if(i+1 === resLen){
		                    			// reset form
		                    			$('#resource-form')[0].reset();
		                    		}
                    		    })
                    		}else{
                    			$('.resource-list').html('');
                    			$.each(response, function(i, item) {
		                        //$("<div class='list-module' id='module_" + item.module_id + "'><div class='module-name'>" + item.module_name + "</div><div class='module-name-url'>" + item.module_url + "</div><div class='module-action'><button id='edit_module_" + item.module_id + "' class='btn-edit-module'> Edit </button></div></div>").appendTo('.resource-list');
		                        	$('.resource-list').append("<div class='list-module' id='module_" + item.module_id + "'><div class='module-name'>" + item.module_name + "</div><div id='module_url_" + item.module_id + "' class='module-name-url'>" + item.module_url + "</div><div class='module-action'><button id='edit_module_" + item.module_id + "' class='btn-edit-module btn btn-default'> Edit </button></div></div>");
		                    	
		                    		if(i+1 === resLen){
		                    			// reset form
		                    			$('#resource-form')[0].reset();
		                    		}

		                    	});
                    		}	 
                    	}
                    }          
                }
            });
        };
        initResource();
    })();


    (function() {
        $("input#btn-new-resource").on('click', function(e) {
            e.preventDefault();

            var res_name = $('#res_name').val();
            if(res_name === null || res_name === undefined || !res_name){
            	alert('Resource name can not be empty');
            	return false;
            }


            var formData = $('form[name=resource_entry_form]').serialize();
            var base_path = "<?php echo $base_path ?>";
            $.ajax({
                url: base_path + "/app/module/resource/resource-helper.php",
                type: "POST",
                data: {
                    action: "save_resource_in_pos_modules",
                    data: formData
                },
                dataType: "JSON",
                success: function(response) {
                    if ($("#child-module-res").length > 0) {
                        $('#child-module-res').remove();
                    }

                	initResource();
                }
            });
        });
    })();


    (function() {
        $('.main-parent-resource-module').on('change', function() {
            if ($("#child-module-res").length > 0) {
                $('#child-module-res').remove();
            }
            var parent_module, pm_array;
            var base_path = "<?php echo $base_path ?>";
            parent_module = this.value;
            pm_array = parent_module.split('_');
            $('#resource_main_parent').val(parent_module);
            $.ajax({
                url: base_path + "/app/module/resource/resource-helper.php",
                type: "POST",
                data: {
                    action: "get_child_module",
                    module_id: pm_array[pm_array.length - 1]
                },
                dataType: "JSON",
                success: function(response) {
                    //console.log(response);
                    if (response !== null || response !== undefined) {
                        if (response.length) {
                            $('div#main-parent-resource').append('<div id="child-module-res" class="child-module-resource"><label class="lbl-sub-module"> Select Sub Module</label><select name="resource_child" class="child-resource-module form-control"></select></div>');
                            $('.child-resource-module').append($('<option>', {
                                value: "",
                                text: "--Select--"
                            }));

                            $.each(response, function(i, item) {
                                $('.child-resource-module').append($('<option>', {
                                    value: item.module_backend_code + '_' + item.module_id,
                                    text: item.module_name
                                }));
                            });
                        } else {
                            if ($("#child-module-res").length > 0) {
                                $('#child-module-res').remove();
                            }
                        }
                    }
                }
            });
        });
    })();



    $(document).ready(function() {
        $(document).on('click', '.btn-edit-module', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var ids = id.split('_');
            var len = ids.length;
            $('#is_edit_resource').val(ids[len - 1]);

            var base_path = "<?php echo $base_path ?>";
            $.ajax({
                url: base_path + "/app/module/resource/resource-helper.php",
                type: "POST",
                data: {
                    action: "get_resource_by_id",
                    module_id: ids[len - 1]
                },
                dataType: "JSON",
                success: function(response) {
                    if (response !== null || response !== undefined) {
                        if (response.length) {
                            var data = response[0];
                            //console.log(data);
                            $('#resource_main_parent').val(data.module_main_parent);
                            if (data.module_main_parent === data.module_parent && data.module_parent === "self") {
                                $('#res_name').val(data.module_name);
                                $('#res_url').val(data.module_url);
                                var selectedIndex = data.module_backend_code + '_' + data.module_id;
                                $('.main-parent-resource-module').val(selectedIndex);

                                // To remove child dropdown 2nd level 
                                if ($("#child-module-res").length > 0) {
                                    $('#child-module-res').remove();
                                }

                            }

                            if (data.module_main_parent === data.module_parent && data.module_parent !== "self") {
                                $('#res_name').val(data.module_name);
                                $('#res_url').val(data.module_url);
                                $.ajax({
                                    url: base_path + "/app/module/resource/resource-helper.php",
                                    type: "POST",
                                    data: {
                                        action: "get_resource_by_id",
                                        module_id: data.module_parent
                                    },
                                    dataType: "JSON",
                                    success: function(res) {
                                        if (res !== null || res !== undefined) {
                                            var result = res[0];
                                            if (result.module_main_parent === result.module_parent) {
                                                var sIndex = result.module_backend_code + '_' + result.module_id;
                                                $('.main-parent-resource-module').val(sIndex);
                                            }
                                            // To remove child dropdown 2nd level 
                                            if ($("#child-module-res").length > 0) {
                                                $('#child-module-res').remove();
                                            }
                                        }
                                    }
                                });
                            }


                            if (data.module_main_parent !== data.module_parent) {
                               // CHILD RESOURCE S
                                var parent = parseInt(data.module_parent, 10);
                                var main_parent = parseInt(data.module_main_parent, 10);
                       				

                       			 if ($("#child-module-res").length > 0) {
                                       $('#child-module-res').remove();
                                 }


                                $.ajax({
                                    url: base_path + "/app/module/resource/resource-helper.php",
                                    type: "POST",
                                    data: {
                                        action: "get_sub_child_module",
                                        module_parent: parent,
                                        module_main_parent: main_parent
                                    },
                                    dataType: "JSON",
                                    success: function(rp) {
                                        //console.log(rp);
                                        var childDropdown = [];
                                        if (rp !== null || rp !== undefined) {
                                            if (rp.length) {
                                                var k = 0;
                                                $.each(rp, function(i, item) {
                                                    //console.log(item);
                                                    if (item.module_parent === item.module_main_parent && item.module_main_parent === 'self') {
                                                        // to fill parent 
                                                        $('#res_name').val(data.module_name);
                                                        $('#res_url').val(data.module_url);
                                                        var selectedIndex = item.module_backend_code + '_' + item.module_id;
                                                        $('.main-parent-resource-module').val(selectedIndex);
                                                    } else if (item.module_parent === item.module_main_parent && item.module_main_parent !== 'self') {
                                                        // store sub child items.
                                                        childDropdown[k] = item;
                                                        k++;
                                                    }
                                                });
                                            }
                                            // To create dynamic child drop down 
                                            if (childDropdown.length) {
                                                $('div#main-parent-resource').append('<div id="child-module-res" class="child-module-resource"><label class="lbl-sub-module"> Select Sub Module</label><select name="resource_child" class="child-resource-module form-control"></select></div>');
                                                $('.child-resource-module').append($('<option>', {
                                                    value: "",
                                                    text: "--Select--"
                                                }));
                                                $.each(childDropdown, function(i, item) {
                                                    $('.child-resource-module').append($('<option>', {
                                                        value: item.module_backend_code + '_' + item.module_id,
                                                        text: item.module_name
                                                    }));
                                                });
                                                //console.log(chSelectedIndex);

                                                for (var i = 0, len = childDropdown.length; i < len; i++) {
                                                    if (childDropdown[i].module_id === data.module_parent) {
                                                        console.log(childDropdown[i]);
                                                        // set child selected child index value
                                                        var chSelectedIndex = childDropdown[i].module_backend_code + '_' + childDropdown[i].module_id;
                                                        $('.child-resource-module').val(chSelectedIndex);
                                                    }
                                                }
                                            } else {
                                                if ($("#child-module-res").length > 0) {
                                                    $('#child-module-res').remove();
                                                }
                                            }
                                        }
                                    }// end of sucess function
                                });
                            }// end of if (data.module_main_parent !== data.module_parent) 
                        }
                    }
                }
            });
        });
    });
</script>
