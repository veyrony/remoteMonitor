<?php
	session_start();
	unset($_SESSION['REMOTE_USER']);
	setcookie("REMOTE_USER","",0);
	unset($_SESSION['PASSWORD']);
	header("Location:login.php");	
?>