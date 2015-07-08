<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";
    if(isset($_SESSION['loggedin']['user']['is_admin'])){

    	$access_url = $base_path.'/app/admin/panel-access/assign-panel.php';
?>

	<style type="text/css">
	.role-container{
		 position:absolute;
	     width:475px;
	     height:110px;
	     /*z-index:15;*/
	     top:45%;
	     left:50%;
	     margin:-100px 0 0 -150px;
	     background: #ccc;
	}
	.ui-dialog .ui-dialog-content{
		font-size: 14px;
	}
	.title{
		height: 35px;
		width: 100%;
		background-color:#009ACD;
		color: #fff;
		font-weight: bold;
		text-align: center;
		padding: 5px;
	}

	.role-container #form-content{
		padding: 10px;
		margin-top: 10px;
	}
	.error{
		border: 2px solid red;
	}
	.role-list{
		position:absolute;
		top:25%;
		padding: 10px;
		/*margin:10px;*/
		width: 300px;
		left:10%;
	}
	.role-name-list{
		height: auto;
		background: #ccc;
		font-weight: bold;
	}
	ul.rlist li{
		padding: 5px;
	}
	a.role-atag {
		text-decoration: none;
		color: #272727;
		cursor: pointer;
	}
	</style>



	<div class="role-list"> 
		<div class="title">Role List</div>
		<div class="role-name-list">
			<ul class="rlist">
				<?php 
					$q = "SELECT * from pos_user_roles ORDER BY role_name ASC";
					$res = mysql_query($q);
					if(mysql_num_rows($res)){
						while( $row = mysql_fetch_assoc($res)){
							echo "<li id='".$row['role_id']."'> 
									<a id='role_id_".$row['role_id']."' href='".$access_url."?role_id=".$row['role_id']."' class='role-atag'>". $row['role_name'] ."</a> 
								</li>";
						}
					}
				?>
			<ul>
		</div>
	</div>


	<div class='role-container'>
		<div class="title">Add New Role</div>
		<div id="form-content">
			<form id="user_role">
				<label for="txt-role">Enter Role Type</label>		
				<input id="txt-role" autocomplete="off" type="text" name='role_name' onkeypress="return textonly(event);">
	            <input type="button" id="btn-add-role" value="Add Role">
			</form>	
		</div>
	</div>
	<div id="role-dialog-confirm"></div>

<?php 
   } 
?>


<script type="text/javascript">
function textonly(e){
		var code;
		if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		if (code === 45) return false;
		var character = String.fromCharCode(code);
		 	var AllowRegex  = /^[\ba-zA-Z\s-]$/;
		    if (AllowRegex.test(character)) return true;     
		    return false; 
	}

(function(){
	$('#btn-add-role').on('click',function(e){
		var role =  $('#txt-role').val();
		if(!role){
			$('#txt-role').addClass('error');
			return false;
		}else{
			if($('#txt-role').hasClass('error')){
				$('#txt-role').removeClass('error');
			}
		}

		e.preventDefault();
		var param = $( "#user_role" ).serialize();
            $.ajax({
                url: "<?php echo $base_path; ?>/app/admin/user/user_action.php",
                type: "POST",
                data: {
                    ssd: "add_user_role",
                    data: param
                },
                dataType: "JSON",
                success: function (response) {
                	$("#role-dialog-confirm").html(response.statusText);
				    $("#role-dialog-confirm").dialog({
				        resizable: false,
				        modal: true,
				        title: "<?php echo $base_path?>"+ ' Says...',
				        height: 175,
				        width: 400,
				        buttons: {
				            "Yes": function () {
				               
				                callback(true);
				                 $(this).dialog('close');
				            },
				                "No": function () {
				               
				               callback(false);
				                $(this).dialog('close');
				            }
				        }
				    });
                }
            });

            function callback(f){
            	 $('#user_role')[0].reset();
            	 $.ajax({
	                url: "<?php echo $base_path; ?>/app/admin/user/user_action.php",
	                type: "POST",
	                data: {
	                    ssd: "get_user_role",
	                },
	                dataType: "JSON",
	                success: function (response) {
	                	//console.log(response);
	                	$('ul.rlist').html('');
	                	var href =  "<?php  echo $access_url ?>";
	                	if(response !== null || response !== undefined){
	                		for(var i=0, len = response.length; i< len; i++){
	                			$('ul.rlist').append('<li id="'+ response[i].role_id +'"> <a class="role-atag" href="'+ href +'?role_id='+ response[i].role_id +'" id="'+ response[i].role_id +'">'+ response[i].role_name +'</a></li>');
	                		}
	                	}
	           
	                }
	            });
            	return true;
            }
	});

})();
</script>