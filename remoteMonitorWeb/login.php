<?
session_start();
require("./globalFunction.php");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>

<style type="text/css">

body,td,th {
	font-size: 14px;
}
/*.btn
{ 
width:80px;
height:20px;
color:#FFFFFF;

padding:0 0 2px 0 !important;
padding:2px 0 0 0 ;
background:url(images/button.jpg);
border:0;

cursor:pointer;
}*/

</style></head>

<body>
<?

if ($_SESSION['REMOTE_USER'])
	gourl("","index.php");
if (count($_POST))
	login($_POST['csl'],$_POST['password'],'');


echo showLoginForm();

echo showCopy("2015.08  Weilong		All Rights Reserved");
echo showChkLoginForm();

?>

</body>
</html>
