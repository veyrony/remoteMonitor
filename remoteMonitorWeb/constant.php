<?php
session_start();

define("USER",$_SESSION['REMOTE_USER']);

define("TOOLNAME", "Welcome to Remote Monitoring System ");
define("ImageCapturePath", "images/");

require_once ("functions.php");
require_once ("dbClassConnect.php");

$a = new conn;

if (!isset($_SESSION['REMOTE_USER']) || $_SESSION['REMOTE_USER']=="") 
{

	foreach ($_GET AS $k => $v)
	{
		$_SERVER['argv'][0]    = $k . '=' . $v;
	}

	gourl("","login.php?url=".urlencode($_SERVER['PHP_SELF']."?".$_SERVER['argv'][0]));
}

extract($_GET);
?>