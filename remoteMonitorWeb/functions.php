<?php

define("IP",$_SERVER['REMOTE_ADDR']);	//定义访问IP地址
define("NOW", date('Y-m-d H:i:s'));

function alert($s)
{
	$str = "<script type='text/javascript'>alert(\"".$s."\");</script>";	
	echo $str;
}

function gourl($s, $url)
{
	$xstr = $url == -1 ? "history.go(-1);" : "location.href='".$url."';"; 
	$xs = $s == ""?"" : "alert(\"".$s."\");";
	$str = "<script type='text/javascript'>".$xs.$xstr." </script>";	
	echo $str;
	exit();
}



//date base: MySQL
class db
{
	var $host;
	var $usrname;
	var $pwd;
	var $dbname;	
	var $db_code;
	
	function execute($sql)
	{
		
		return @mysql_query($sql);
	}
	
	function getNum($table, $act)
	{
		$sql = "select * from ".TP.$table.(trim($act)?" where ".$act:"");//select * from TP.$table where [ad4=weilongy]
		$mysql_result = $this->execute($sql);
		
		return @mysql_num_rows($mysql_result); //mysql_num_rows — 取得结果集中行的数目
	}
	/*
	description：将query到的结果保存在数组中
	param：@$sql:MySQL命令
	return：返回数组
	*/
	function query($sql)
	{
		
		$result = $this->execute($sql);	
		$oo = array();
		//if(!$result) echo $sql;
		while ($o = @mysql_fetch_object($result)) $oo[] = $o; //mysql_fetch_object
		
		return $oo;
	}
	/*
	将字段Array和字段对应的值insert到table中
	parms: 	$table 数据表
			$fieldArray 数据库中的字段数组
			$valueArray 字段对应的数据
	*/
	function insertDb($table, $fieldArray, $valueArray)
	{
		$sql = "insert into ".TP.$table." (".implode(',',$fieldArray).") values ('".implode('\',\'',$valueArray)."')";

		return $this->execute($sql);
	}

	function updateDb($table, $fieldArray, $valueArray, $action)
	{
		$sql = "update ".TP.$table." set ";
		$sqlstr = "";
		for ($i = 0; $i < count($fieldArray); $i++)
		{
			$sqlstr .= "$fieldArray[$i] ='$valueArray[$i]',";
		}
		$sql .= substr($sqlstr,0,-1); 
		$sql .= trim($action)!=""?" where ".$action:"";

		return $this->execute($sql);
	}
	
	function insertUpdate($table, $fieldArray, $valueArray, $action)
	{
		if (trim($action))
			return $this->updateDb($table, $fieldArray, $valueArray, $action);
		else
			return $this->insertDb($table, $fieldArray, $valueArray);
	}

	function deleteDb($table, $act)
	{
		$sql = "delete from ".TP.$table." where ".(trim($act)?$act:"id=0");
		
		return $this->execute($sql);
	}
	
	function getObj($table, $action, $orderby)
	{
		
		$sql = "select * from ".TP.$table.($action!=""?" where ".$action:"")." order by ".($orderby==""?"id desc":$orderby);
		$o = $this->query($sql);
		return $o;
	}

	function getOneRecord($table, $action)
	{
		$o = $this->getObj($table, $action," id desc limit 1");
		return $o[0];
	}

	function getOneField($table, $action, $field)
	{
		$o = $this->getOneRecord($table, $action);
		return $o->$field;
	}

}

?>