<?php
	include "vp_dbconfig.php";

	//echo $vendor_id."<hr>".$vendor_loggedin;

	if($user_name == "" || is_null($user_name))
	{
		header("Location: vp_login.php");
	}
	else
	{
		//echo "<input type='hidden' name='vendor_id_hidden' id='vendor_id_hidden' value='$vendor_id'>";
	}
?>