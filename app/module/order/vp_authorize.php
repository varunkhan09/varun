<?php
	include "vp_dbconfig.php";

	//echo $vendor_id."<hr>".$vendor_loggedin;

	if($vendor_id == "" || $vendor_loggedin == "" || is_null($vendor_id) || is_null($vendor_loggedin))
	{
		header("Location: vp_login.php");
	}
	else
	{
		echo "<input type='hidden' name='vendor_id_hidden' id='vendor_id_hidden' value='$vendor_id'>";
	}
?>