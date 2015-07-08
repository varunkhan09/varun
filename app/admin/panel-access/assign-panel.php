<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/app/module/common/header.php";
include_once $_SERVER['DOCUMENT_ROOT'].'/app/module/resource/module-resource.class.php';

if (isset($_SESSION['loggedin']['user']['is_admin']) && isset($_GET['role_id']) && (int)$_GET['role_id']) {
	$modules =  new ModuleResource();
    ?>
      
       <style type="text/css">
        .resource-link{
            height:100%;
            background-color:#EEEEEE;
            padding-right:0; 
        }
        .resource-link a{
            padding:0 0 5px 10px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 0px !important;
        }
        .resource-pane{
        	padding: 0 10px 10px 0px;
            height:100%;
            background-color: #FFFFFF;
            color:#777;
            border-left: 2px dashed #ccc;
        }
        .resource-pane-head{
            margin-top:5px;
            height:auto;
            background-color: #009ACD;
            color:#fff;
        }
        .resource-pane-head > span{
          display: block;
	    padding: 15px 10px;
	    position: relative;
        }

        ul.tree, ul.tree * {
            list-style-type: none;
            margin: 0;
            padding: 0 0 5px 0;
        }
        ul.tree img.arrow {
            padding: 2px 0 0 0;
            border: 0;
            width: 20px;
        }
        ul.tree li {
            padding: 4px 0 0 0;
        }
        ul.tree li ul {
            padding: 0 0 0 20px;
            margin: 0;
        }
        ul.tree label {
		    color: #009acd;
		    cursor: pointer;
		    font-size: 16px;
		    font-weight: bold;
		    padding: 2px 0;
		}
        .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
            color: #fff;
            background-color: #009ACD;
        }
        ul {
            margin-top: 5px;
            margin-bottom: 5px;
        }
        ul.tree li .arrow {
            width: 20px;
            height: 20px;
            padding: 0;
            margin: 0;
            cursor: pointer;
            float: left;
            background: transparent no-repeat 0 4px;
        }
        ul.tree li .collapsed {
            background-image: url(images/collapsed.gif);
        }
        ul.tree li .expanded {
            background-image: url(images/expanded.gif);
        }
        ul.tree li .checkbox {
            width: 20px;
            height: 30px;
            padding: 0;
            margin: 0;
            cursor: pointer;
            float: left;
            background: url(images/check0.gif) no-repeat 0 0px;
            background-size: 100%;
        }
        ul.tree li .checked {
            background-image: url(images/check2.gif);
        }

        ul.tree li .checked1 {
            background-image: url(images/check2.gif);
        }

        ul.tree li .half_checked {
            background-image: url(images/check1.gif);
        }
        .row{
            margin:0;
        }
        .resource-div{
            position: relative;
        }
        .resource-div-pos{
            margin-top: 8%;
            height: 575px;
        }
        
        form#user_resource_allocation{
            margin: 2% 0 0 5%;
        }
        .panel-user-list{
            min-height: 35px;
        	padding: 10px;
        	margin: 5px 1px 5px 10px;
            color: #333;
        }
        .panel-user-list-heading{
        	font-weight: bold;
            color: #009ACD;

        }
        .panel-res-user{
            background: white;
            max-height: 400px;
            width:99%;
            overflow-y: scroll;
            color: #999;
        }
        .panel-user-chbox{
        	width: 5%;
        	float:left;
        	min-height: 20px;
        }
         .panel-user-rolename{
        	width: 20%;
        	float:left;
        	min-height: 20px;
        }
        .panel-user-name{
        	width: 25%;
        	float:left;
        	min-height: 20px;
        }
        .panel-user-email{
        	width: 25%;
        	float:left;
        	min-height: 20px;
        }
        .panel-user-shop{
            width: 25%;
            float:left;
            min-height: 20px;
        }
        button#btn_resource_user{
           /* margin-top: 10px;*/
            margin-left: 10px;
            position:absolute;
            bottom:0;
            margin-bottom: 100px;
            background-color: #009ACD;
            color: #fff;
         
        }
        button#btn_resource_user:hover{
            background-color: #fff;
            color: #777;
        }
        .search-pane{
            height: 60px;
            background-color: #ccc;
            padding: 10px;
            color: #777 ! important;
        }
        .filter-combo{
           width: 5%;
            float:left;
        }
        .filter-name{
            width: 25%;
            float:left;
            padding-left: 5px;
        }
        .filter-email{
            width: 24%;
            float:left;
        }

        .filter-role{
           width: 19%;
            float:left;
        }
        #role-keyword{
            width: 150px;
            float:left;
        }
        select#cmb-resource{
            height: 28px;
        }
        div#res-user-list {
        font-size: 14px;
        }
    </style>

    <?php

    $resources = array();
    $finalResource = array();
     $rootList =  $modules->listAccessModulesList();
    

     $root = $rootList[0];
     $child = $rootList[1];
     $resultantData =  array();
     foreach($child as $key => $element){
     	 $access_tag = $element['access_tag'] ;
     	 $parent_id =  $element['id'];
     	 foreach ($child as $key => $elementData) {
     	 	if($elementData['parent'] === $parent_id){
     	 		$element[$access_tag][] = $elementData;
     	 	}
     	 }
     	 $resultantData[] = $element; 
     }
    
     foreach ($root as $key => $rootElement) {
     	$keys = array_keys($rootElement);
     	$temp = $rootElement[$keys['0']];
     	$parent_id =  $temp['id'];
     	$access_tag =  $temp['access_tag'];
     	foreach ($resultantData as $key => $childElement) {
     		if($childElement['parent'] == $parent_id){
     			$rootElement[$access_tag][$access_tag][] = $childElement;
     		}
     	}
     $finalResource[] = $rootElement;
     }
     $resources = $finalResource;

     //print "<pre><br><br><br>";
   // print_r($resources);
    // print_r($_SESSION);
      $allowed_res =  $modules->get_all_allowcated_resources($_GET['role_id']);
      //print_r($allowed_res);
?>

    <div class="contianer-fluid resource-div">
        <div class="row resource-div-pos">
            <div class="col-md-3 resource-link">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a data-toggle="pill" href="#menu1">Module Resources</a></li>
                    <li><a data-toggle="pill" href="#menu2">Users</a></li>
                    <button class="btn btn-default" id="btn_resource_user"> Submit Resource </button>
                </ul>
            </div>

            <div class="col-md-9 tab-content resource-pane">

                <div id="menu1" class="tab-pane fade in active">
                    <div class='resource-pane-head'><span>Module List</span></div>
                    <form id='user_resource_allocation'>
    <?php

    function create_hirarchy($arr,$allowed_r) {
        foreach ($arr as $key => $value) {
            $access_tag = '';
            $id = $value['id'];
            
            $name = $value['name'];
            $access_tag = $value['access_tag'];
            if (in_array($id, $allowed_r)){
                echo "<li><input type='checkbox' id='id_" . $access_tag . "_" . $id . "' name='" . $access_tag . "' value='" . $id . "'><label>&nbsp;&nbsp;{$name}</label>";
            }else{
                 echo "<li> <input type='checkbox' id='id_" . $access_tag . "_" . $id . "' name='" . $access_tag . "' value='" . $id . "'><label>&nbsp;&nbsp;{$name}</label>";
            }
        
            if (isset($value[$access_tag])) {
                echo "<ul>";
                if(!empty($value[$access_tag])){
                      create_hirarchy($value[$access_tag],$allowed_r);
                }
                echo "</ul>";
            } else {
                echo "</li>";
            }
        }
    }

    echo '<ul class="tree" style="margin-left: 25px; margin-top:20px;">';
        foreach ($resources as $resource) {
            create_hirarchy($resource,$allowed_res);
        }
    $html = "</ul>";
    echo $html;
    ?>
                        <br>
                    </form>
                </div>
  
                <div id="menu2" class="tab-pane fade">
                    <div class='resource-pane-head'>
                        <span>Users</span>
                        <div class='search-pane'>
                            <div class='filter-combo'>
                                <select id="cmb-resource" name='cmb_resource' title="Select to filter user">
                                    <option value="all">All</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                            <div class='filter-name'>
                               <input type="text" name='filter_name' id='name-keyword' title='Enter name to search user' placeholder='Search by name'>
                            </div>

                            <div class='filter-email'>
                               <input type="text" name='filter_email' id='email-keyword' title='Enter email to search user' placeholder='Search by email'>
                            </div>

                            <div class='filter-role'>
                               <input type="text" name='filter_role' id='role-keyword' title='Enter role to search user' placeholder='Search by role'>
                            </div>

                             <div class='filter-shop'>
                               <input type="text" name='filter_shop' id='shop-keyword' title='Enter shop to search user' placeholder='Search by shop'>
                            </div>

                        </div>
                    </div>

    <?php
    $shop_id = 0;
	if(isset($_SESSION['loggedin']['user']['shop_id'])){
		$shop_id = (int)$_SESSION['loggedin']['user']['shop_id'];
	}

    $role_id = $_GET['role_id'];

    ?>
                    <!-- USER SECTION END HERE -->

                    <form id="resource-to-user-form" name="resource_to_user_form">
                    	<div class="panel-res-user">
                        	<div class='panel-user-list panel-user-list-heading'>
                        		<div class='panel-user-chbox'><input type="hidden"></div>
                        		<div class='panel-user-name'> Name </div>
                        		<div class='panel-user-email'> Email </div>
                        		<div class='panel-user-rolename'> Role </div>
                                <div class='panel-user-shop'> Shop </div>
                        	</div>
                            <div id="res-user-list"></div>

                    	</div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<script type="text/javascript">

$(document).ready(function(){
    var roleid = "<?php echo $role_id ?>";
    var base_path = "<?php echo $base_path ?>";

    // default set selectedIndex 0, value is all
    $('#cmb-resource option')[0].selected = true;
    var searchType =  $("#cmb-resource option:selected").val();
    if(searchType === 'all'){
        initPanelUsers(roleid);
    }

    $('#cmb-resource').change(function(){
       var searchType =  $("#cmb-resource option:selected").val();
       if(searchType === 'yes'){
          initPanelUsersYes(roleid);
       }
      if(searchType === 'no'){
            initPanelUsersNo(roleid);
       }
       if(searchType === 'all'){
            initPanelUsers(roleid);
       }
    });

    //////////////////////////////////////////

        /** AUTO SEARCH **/
        var MIN_LENGTH = 2;

        $("#name-keyword").keyup(function() {
            var keyword = $("#name-keyword").val();
            if(keyword.length < MIN_LENGTH){
                initPanelUsers(roleid);
            }

            if (keyword.length >= MIN_LENGTH) {
                $.get( base_path+"/app/admin/panel-access/auto-search.php", { term: keyword,searchkey:'name' } ).success(function( response ) {
                    var response = JSON.parse(response);
                    if(response !== null || response !== undefined){
                        var resLen = response.length;
                        $('#res-user-list').html('');
                        if(resLen){
                            $.each(response, function(index,user){
                                if(user.role_id == roleid){
                                    $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' checked='true' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                                }else{
                                    $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                                }
                            });
                        }
                    }
                });
            }
        });


    $("#role-keyword").keyup(function() {
        var keyword = $("#role-keyword").val();
        if(keyword.length < MIN_LENGTH){
            initPanelUsers(roleid);
        }
        if (keyword.length >= MIN_LENGTH) {
            $.get( base_path+"/app/admin/panel-access/auto-search.php", { term: keyword,searchkey:'role' } ).success(function( response ) {
                var response = JSON.parse(response);
                if(response !== null || response !== undefined){
                    var resLen = response.length;
                    $('#res-user-list').html('');
                    if(resLen){
                        $.each(response, function(index,user){
                            if(user.role_id == roleid){
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' checked='true' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }else{
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }
                        });
                    }
                }
            });
        }
    });


    $("#email-keyword").keyup(function() {
        var keyword = $("#email-keyword").val();
        if(keyword.length < MIN_LENGTH){
            initPanelUsers(roleid);
        }

        if (keyword.length >= MIN_LENGTH) {
            $.get( base_path+"/app/admin/panel-access/auto-search.php", { term: keyword,searchkey:'email' } ).success(function( response ) {
                var response = JSON.parse(response);
                if(response !== null || response !== undefined){
                    var resLen = response.length;
                    $('#res-user-list').html('');
                    if(resLen){
                        $.each(response, function(index,user){
                            if(user.role_id == roleid){
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' checked='true' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }else{
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }
                        });
                    }
                }
            });
        }
    });


    $("#shop-keyword").keyup(function() {
        var keyword = $("#shop-keyword").val();
        if(keyword.length < MIN_LENGTH){
            initPanelUsers(roleid);
        }
        if (keyword.length >= MIN_LENGTH) {
            $.get( base_path+"/app/admin/panel-access/auto-search.php", { term: keyword,searchkey:'shop' } ).success(function( response ) {
                var response = JSON.parse(response);
                if(response !== null || response !== undefined){
                    var resLen = response.length;
                    $('#res-user-list').html('');
                    if(resLen){
                        $.each(response, function(index,user){
                            if(user.role_id == roleid){
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' checked='true' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }else{
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }
                        });
                    }
                }
            });
        }
    });

  //////////////////////////////////////////
    function initPanelUsersYes(roleid){
        var base_path = "<?php echo $base_path ?>";
        $.ajax({
            url: base_path + "/app/admin/panel-access/panel-users-helper.php",
            type: "POST",
            data: {
                action: "get_shop_users",
                shop_id: "<?php echo $shop_id ?>"
            },
            dataType: "JSON",
            success: function(response) {
                if(response !== null || response !== undefined){
                    var i=0;
                    var resLen = response.length;
                    $('#res-user-list').html('');
                    if(resLen){
                        $.each(response, function(index,user){
                            if(user.role_id === roleid){
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' checked='true' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }
                        });
                    }
                }
            }
        });
    }


    function initPanelUsersNo(roleid){
        var base_path = "<?php echo $base_path ?>";
        $.ajax({
            url: base_path + "/app/admin/panel-access/panel-users-helper.php",
            type: "POST",
            data: {
                action: "get_shop_users",
                shop_id: "<?php echo $shop_id ?>"
            },
            dataType: "JSON",
            success: function(response) {
                if(response !== null || response !== undefined){
                    var i=0;
                    var resLen = response.length;
                    $('#res-user-list').html('');
                    if(resLen){
                        $.each(response, function(index,user){
                            if(user.role_id == roleid){
                            }else{
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }
                        });
                    }
                }
            }
        });
    }

  

	function initPanelUsers(roleid){
		var base_path = "<?php echo $base_path ?>";
        $.ajax({
            url: base_path + "/app/admin/panel-access/panel-users-helper.php",
            type: "POST",
            data: {
                action: "get_shop_users",
                shop_id: "<?php echo $shop_id ?>"
            },
            dataType: "JSON",
            success: function(response) {
            
            	if(response !== null || response !== undefined){
                    $('#res-user-list').html('');
            		var i=0;
            		var resLen = response.length;
            		if(resLen){
            			$.each(response, function(index,user){
                            if(user.role_id === roleid){
                               
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' checked='true' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }else{
                                $('#res-user-list').append("<div class='panel-user-list' id='user_id_" + user.entity_id + "'><div class='panel-user-chbox' id='user_chbox_" + user.entity_id + "'> <input type='checkbox' name='user_value_" + user.entity_id + "' value='" + user.entity_id + "' id='user_checkbox_"+user.entity_id+"' class='user-chk-box'> </div><div class='panel-user-name' id='user_name_" + user.entity_id + "'> "+ user.firstname +' '+user.lastname +" </div><div class='panel-user-email' id='user_email_" + user.entity_id + "'> " +user.email+ "</div><div class='panel-user-rolename' id='user_rolename_" + user.entity_id + "'>"+ user.role_name+"</div><div class='panel-user-shop' id='user_shopname_" + user.entity_id + "'>"+ user.shop_name+"</div></div>");
                            }
            				
            			});
            		}
            	}
            }
        });
	}
})


    $(function() {
        $('ul.tree').checkTree();
    });

    (function() {
        $('#btn_user_resource_submit').click(function(e) {
            e.preventDefault();
            var formData = $('#user_resource_allocation').serialize();
           // console.log(formData);
        })
        $('#btn_resource_user').click(function() {
            var resourceData = $('#user_resource_allocation').serialize();
            var usersData = $('#resource-to-user-form').serialize();
            /*
            if(resourceData === null || resourceData === undefined || !resourceData){
                alert('Please select resource');
                return false;
            }
            */

            if(usersData === null || usersData === undefined || !usersData){
                alert('Please select user');
                return false;
            }
           
	        var base_path = "<?php echo $base_path ?>";
	        $.ajax({
	            url: base_path + "/app/admin/panel-access/panel-users-helper.php",
	            type: "POST",
	            data: {
	                action: "save_users_assigned_resource",
	                resource_data: resourceData,
	                users_data: usersData,
                    role_id: "<?php echo $role_id ?>",
	                shop_id: "<?php echo $shop_id ?>"
	            },
	            dataType: "JSON",
	            success: function(response) {
	            	//console.log(response);
                    if(response.statusCode == "OK"){
                        alert(response.statusText);
                    }
	            }
	        });

        //console.log(resourceData);
        })
    })();
</script>