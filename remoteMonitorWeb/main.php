<?php
require('header.php');
?>

<?php

if (isset($temp))
	$temp = (float)$temp;
else
	$temp = 0;
?>


<form name="form1" id="form1" action="server.php" method="post" enctype="multipart/form-data" > 
<table width="100%" border="1" cellpadding="5" cellspacing="1" bgcolor="#B0C4DE" style="margin:15px 0 10px 0;">
	<tr>
		<td width="100%" colspan="2" bgcolor="#46A3FF" style="font-size:150%;color:black" align="absmiddle">当前温度</td>
	</tr>
	<tr>
		<td width="20%" bgcolor="#FFFFFF" style="font-size:125%;color:black">
			<div> 
				<input name="action" type="submit" style="font-size:125%" value="获取温度"/>
			</div>
		</td>
		<td width="80%" bgcolor="#FFFFFF" style="font-size:125%;color:black"><?php echo $temp;?> ℃</td>
	</tr>
	<tr>
		<td width="100%" colspan="2" bgcolor="#46A3FF" style="font-size:150%;color:black" align="absmiddle">当前环境</td>
	</tr>
	<tr>
		<td width="20%" bgcolor="#FFFFFF" style="font-size:120%;color:black">
			<div> 
				<input name="action" type="submit" style="font-size:120%" value="拍照"/>
				<input name="temp" type="hidden" value=<?=$temp;?>/>
			</div>
		</td>
		<td width="80%" bgcolor="#FFFFFF" style="font-size:100%;color:black">
    	<?php
			$f = "";
			if (file_exists("test.jpg")) {
				$f = "<img src=\""."test.jpg"."\" height=480 width=640></img>";
			}
			echo $f;
		?>
		</td>
	</tr>
</table>
</form>

<?php
require("footer.php");
?>