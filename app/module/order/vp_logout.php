<?php
	session_start();
	unset($_SESSION['vendor_id']);
	unset($_SESSION['vendor_loggedin']);
	header("Location: vp_login.php");
?>