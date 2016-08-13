<?php
require_once('constant.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=TOOLNAME;?></title>
	<link href="cssjs/css.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="cssjs/jquery-1.6.1.min.js"></script>
</head>

<body>
<table width="1024" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr height="190">
		<td colspan="3" align="right"><img src="images/header1024x200.png" align="absmiddle"/></td>
	</tr>
	<tr height="20">
		<td background="images/icon_grad2.gif"><div align="right">
			<span style="margin: 0 5px 0 10px;" class="white">
			<?php
				if($_SESSION['REMOTE_USER']) 
					echo "Welcome : ".$_SESSION['REMOTE_USER']." &nbsp;&nbsp;&nbsp;<a href=\"logout.php\" class=\"white\">Logout</a>";
				else 
					echo "<a href=\"login.php\" class=\"white\">Login</a>";
			?>
			</span></div></td>
	<tr>
		<td colspan="3">