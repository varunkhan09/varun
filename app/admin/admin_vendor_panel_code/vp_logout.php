<?php
	session_start();
	unset($_SESSION['username']);
	header("Location: vp_login.php");
?>