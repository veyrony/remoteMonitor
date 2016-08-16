<?php

echo "welcome to remote monitor system ";
echo "<br>";

exec("sudo python ./Sensor/getTemp.py", $temp, $res);
//exec("sudo python /usr/share/nginx/www/getTemp.py 2>&1", $temp, $res);
//$cmd = system("echo hi",$ret);
//exec("echo hi", $cmd);

//sleep(2);

//$arr = system("python ./getTemp.py", $cmd);
//passthru("sudo python ./getTemp.pdfy", $res);

print_r($temp);
print_r($res);



?>
