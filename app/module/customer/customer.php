<?php

    include_once $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
    if(isset($_SESSION['loggedin']['user_id']) && isset($_SESSION['loggedin']['email'])){
		include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";
?>

<style>
    .form-item {
        margin-top: 10%;
    }
    
    .cust-list p a{
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
    }           
    .table tr td a{
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }
    
    
</style>

	
<!-- Page Content -->
<div class="container">
    <div class="panel panel-default form-item">
        <div class="panel-heading text-center">
            <strong class="headingname">Shop's Customer List</strong>
        </div>
        <div class="panel-body">
            <div class="cust-list">
                <table style="margin-left:0%;width:96%;text-align:left;" class="table table-hover">
                    <thead>
                        <tr>
                        	<th>S.No</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Telephone no</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
		            <?php
							$shop_id = null; 
						    $user_id = (int) $_SESSION['loggedin']['user_id'];
							if(isset($_SESSION['loggedin']['user']['shop_id'])){
								$shop_id = (int) $_SESSION['loggedin']['user']['shop_id'];
							}

							$query = "SELECT * FROM pos_customers_entity WHERE shop_id = $shop_id AND is_active = 1";
							$result = mysql_query($query);
							if(mysql_num_rows($result) > 0){
								$index=1;
								while ($row =  mysql_fetch_assoc($result)) {
					?>
				    <tbody>
                        <tr>
                        	<td><?php echo $index ?> </td>
                            <td><?php echo $row['firstname'] ?> </td>
                            <td><?php echo $row['lastname'] ?> </td>
                            <td><?php echo $row['email'] ?></td>
                            <td><?php echo $row['telephone'] ?></td>
                    <td>
						<a href="<?php echo $base_path."/app/module/customer/edit-customer.php?id=".base64_encode($row['customer_id'])."&sid=".base64_encode($row['shop_id']) ?>">Edit</a>
                    </td>
                            <td>
                           <!--  <a href="#" id="tr_<?php //echo $row['customer_id'] ?>" class="emp_delete">Delete</a>
 -->
								<a href="javascript:void(0);" onclick = "deleteCustomer(<?php echo $row['customer_id'] ?>)">Delete</a>
                            </td>
                        </tr>
				    </tbody>
                    <?php
			                    	$index++;
								}
							}else{
								echo "<tbody><tr><td>No Record Found.</td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody>";
							}
                    ?>

                </table>
                <?php 
                	echo '<p><a href="'.$base_path.'/app/module/customer/new-customer.php" > <strong>Add Customer</strong> </a> </p>';

                ?>
            </div>
        </div>


        <!-- confirmation dialog box -->
        <div id="dialog-confirm">

        </div>

    </div>
</div>


<?php 
	}else{
			// $referrer = $_SERVER['HTTP_REFERER'];
			// if ( !preg_match($base_path."/app/admin/user/login.php",$referrer)) {
			// 	header('Location:'.$base_path);
			// } 
		}
?>


<script type="text/javascript">

// $(document).ready(function(){
//     $(".emp_delete").click(function(){
//         var this_id = $(this).attr("id");
//         var ids =  this_id.split("_");
//         var emp_id =  parseInt(ids[1],10);
        
//         $( "#dialog-confirm" ).dialog({
//       resizable: false,
//       height:140,
//       modal: true,
//       buttons: {
//         "Delete all items": function() {
//           $( this ).dialog( "close" );
//         },
//         Cancel: function() {
//           $( this ).dialog( "close" );
//         }
//       }
//     });


//     });        
// });



function deleteCustomer(customer_id){
    console.log(customer_id);
    $("#dialog-confirm").html("Do you really want to delete ?");
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "<?php echo $base_path?>"+ ' Says...',
        height: 175,
        width: 400,
        buttons: {
            "Yes": function () {
                $(this).dialog('close');
                delete_callback(true,customer_id);
            },
                "No": function () {
                $(this).dialog('close');
                delete_callback(false,customer_id);
            }
        }
    });
}

function delete_callback(stat,customer_id) {
    if (stat) {
        var http = new XMLHttpRequest();
        var url = "<?php echo $base_path; ?>/app/module/customer/customer_action.php"
        var params = "ssd=delete_customer&customer_id="+ customer_id;
            http.open("POST", url, true);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            http.onreadystatechange = function() {
                if(http.readyState == 4 && http.status == 200) {
                        console.log(http.responseText);
                        window.location.reload();
                }
            }
        http.send(params);
    } else {
        console.log("Rejected");
    }
}
</script>