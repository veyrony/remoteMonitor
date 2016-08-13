<?php

require_once("constant.php");

extract($_POST);
if ( 0==count($_POST) && 0==count($_GET) )
	gourl("Access Denied!", -1);

switch(true)
{
case $action == "获取温度":
	$temp = array();
	try{
		exec("sudo python ./Sensor/getTemp.py", $temp, $ret);
		if (0 != $ret) {
	    	$temp[0] = "";
	    	gourl("温度读取错误！", "main.php?temp=".$temp[0]);
		} else {
			gourl("", "main.php?temp=".$temp[0]);
		}

	} catch (Exception $e) {
			print $e;
	}

break;

case $action == "拍照";
	$temp = (float)$temp;
	try{
		exec("sudo fswebcam -d /dev/video0 -r 640x480 --bottom-banner /usr/share/nginx/www/test.jpg ", $output, $ret);
		if (0 != $ret) {
			gourl("拍照失败！", -1);
		}
		else{
			gourl("","main.php?temp=".$temp);
		}
	} catch (Exception $e) {
			print $e;
	}
	
break;

}

?>